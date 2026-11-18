<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Events;
use App\Models\LandingPage;
use App\Services\ActivityLogService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LandingPageCouponController extends Controller
{
    public function __construct(private readonly ImageService $imageService)
    {
    }

    public function index()
    {
        $coupons = $this->landingCouponQuery()
            ->with(['event', 'store'])
            ->orderBy('sort_order')
            ->get();

        ActivityLogService::log('view', 'Viewed landing page coupons list', null, null, null, request());

        return view('admin.landing-pages.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.landing-pages.coupons.create', $this->formData());
    }

    public function store(Request $request)
    {
        $validated = $this->validatedData($request);
        $landingPage = $this->resolveLandingPage($validated['landing_page_id'] ?? null);

        $data = $this->couponData($request, $validated, $landingPage);
        $data['status'] = true;
        $this->applyCoverLogo($request, $data);

        $coupon = Coupon::create($data);
        ActivityLogService::logCreate($coupon, $request);

        if ($landingPage && !$landingPage->featured_coupon_id) {
            $landingPage->update(['featured_coupon_id' => $coupon->id]);
        }

        $message = $landingPage
            ? "Coupon added to {$landingPage->store->store_name}'s landing page."
            : 'Coupon created. You can attach it to a landing page later by editing it.';

        return redirect()->route('admin.landing-pages.coupons.index')->with('success', $message);
    }

    public function edit(Coupon $coupon)
    {
        $this->ensureLandingCoupon($coupon);
        $data = $this->formData();
        $data['coupon'] = $coupon;
        $data['selectedLandingPageId'] = LandingPage::whereHas('store', fn ($query) => $query->where('store_name', $coupon->brand_store))->value('id');

        ActivityLogService::logView($coupon, request());

        return view('admin.landing-pages.coupons.edit', $data);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $this->ensureLandingCoupon($coupon);
        $validated = $this->validatedData($request);
        $landingPage = $this->resolveLandingPage($validated['landing_page_id'] ?? null);

        $oldValues = $coupon->toArray();
        $previousLandingPage = LandingPage::whereHas(
            'store',
            fn ($query) => $query->where('store_name', $coupon->brand_store)
        )->first();
        $data = $this->couponData($request, $validated, $landingPage);
        $this->applyCoverLogo($request, $data, $coupon);
        $coupon->update($data);

        if ($previousLandingPage && $previousLandingPage->id !== $landingPage?->id && $previousLandingPage->featured_coupon_id === $coupon->id) {
            $previousLandingPage->update(['featured_coupon_id' => null]);
        }

        ActivityLogService::logUpdate($coupon, $oldValues, $coupon->fresh()->toArray(), $request);

        if ($landingPage && !$landingPage->featured_coupon_id) {
            $landingPage->update(['featured_coupon_id' => $coupon->id]);
        }

        return redirect()->route('admin.landing-pages.coupons.index')->with('success', 'Landing-page coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $this->ensureLandingCoupon($coupon);
        $this->deleteCoverLogo($coupon);
        ActivityLogService::logDelete($coupon, request());
        $coupon->delete();

        return back()->with('success', 'Landing-page coupon deleted successfully.');
    }

    public function updateStatus(Request $request, Coupon $coupon)
    {
        $this->ensureLandingCoupon($coupon);
        $validated = $request->validate(['status' => 'required|boolean']);
        $coupon->update(['status' => $validated['status']]);

        return back()->with('success', 'Coupon status updated successfully.');
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|integer',
            'order.*.sort_order' => 'required|integer|min:0',
        ]);

        $allowedIds = $this->landingCouponQuery()->pluck('id')->all();
        foreach ($validated['order'] as $item) {
            if (in_array((int) $item['id'], $allowedIds, true)) {
                Coupon::whereKey($item['id'])->update(['sort_order' => $item['sort_order']]);
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function bulkDelete(Request $request)
    {
        $validated = $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);
        $coupons = $this->landingCouponQuery()->whereIn('id', $validated['ids'])->get();

        ActivityLogService::logBulkAction('delete', 'Coupon', $coupons->count(), $request);
        foreach ($coupons as $coupon) {
            $this->deleteCoverLogo($coupon);
            $coupon->delete();
        }

        return back()->with('success', 'Selected landing-page coupons deleted successfully.');
    }

    private function formData(): array
    {
        return [
            'landingPages' => LandingPage::with('store')->whereHas('store')->get()->sortBy('store.store_name')->values(),
            'categories' => Category::where('status', 1)->orderBy('category_name')->get(),
            'events' => Events::orderBy('event_name')->get(),
        ];
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'landing_page_id' => 'nullable|exists:landing_pages,id',
            'coupon_title' => 'required|string|max:255',
            'coupon_code' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'event_id' => 'nullable|exists:events,id',
            'submitted_by' => 'nullable|string|max:255',
            'affiliate_url' => 'nullable|string|max:255',
            'date_available' => 'nullable|date',
            'date_expiry' => 'nullable|date|after_or_equal:date_available',
            'description' => 'nullable|string',
            'terms' => 'nullable|string',
            'cover_logo' => 'nullable|image|max:2048',
        ]);
    }

    private function couponData(Request $request, array $validated, ?LandingPage $landingPage): array
    {
        $data = collect($validated)->except(['landing_page_id', 'cover_logo'])->all();
        $data['brand_store'] = $landingPage?->store?->store_name ?? '';

        foreach (['exclusive', 'featured', 'recommended', 'verified', 'hot_deals', 'student_offer', 'expiry_soon'] as $flag) {
            $data[$flag] = $request->boolean($flag);
        }

        return $data;
    }

    private function applyCoverLogo(Request $request, array &$data, ?Coupon $coupon = null): void
    {
        if ($request->hasFile('cover_logo')) {
            if ($coupon) {
                $this->deleteCoverLogo($coupon);
            }
            $data['cover_logo'] = $this->imageService->uploadAndConvert(
                $request->file('cover_logo'),
                'uploads',
                ['quality' => 100, 'preserve_original' => true]
            );
        } elseif ($request->filled('cover_logo_path')) {
            $data['cover_logo'] = $request->input('cover_logo_path');
        }
    }

    private function deleteCoverLogo(Coupon $coupon): void
    {
        if ($coupon->cover_logo && File::exists(public_path($coupon->cover_logo))) {
            File::delete(public_path($coupon->cover_logo));
        }
    }

    private function landingCouponQuery()
    {
        $storeNames = LandingPage::whereHas('store')->with('store:id,store_name')->get()->pluck('store.store_name');

        return Coupon::query()->where(function ($query) use ($storeNames) {
            $query->whereIn('brand_store', $storeNames)->orWhere('brand_store', '');
        });
    }

    private function resolveLandingPage(?int $landingPageId): ?LandingPage
    {
        if (!$landingPageId) {
            return null;
        }

        $landingPage = LandingPage::with('store')->findOrFail($landingPageId);
        abort_unless($landingPage->store, 422, 'The selected landing page has no store.');

        return $landingPage;
    }

    private function ensureLandingCoupon(Coupon $coupon): void
    {
        abort_unless($this->landingCouponQuery()->whereKey($coupon->id)->exists(), 404);
    }
}
