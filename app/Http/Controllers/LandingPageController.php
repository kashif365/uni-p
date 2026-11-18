<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\LandingPage;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LandingPageController extends Controller
{
    public function index()
    {
        $landingPages = LandingPage::with(['store', 'featuredCoupon'])
            ->latest()
            ->get();

        return view('admin.landing-pages.index', compact('landingPages'));
    }

    public function create()
    {
        $landingPage = new LandingPage(['status' => true]);
        $stores = Store::whereDoesntHave('landingPage')->orderBy('store_name')->get();
        $coupons = collect();

        return view('admin.landing-pages.create', compact('landingPage', 'stores', 'coupons'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        LandingPage::create($data);

        return redirect()->route('admin.landing-pages.index')
            ->with('success', 'Landing page created successfully.');
    }

    public function show(LandingPage $landing_page)
    {
        return redirect()->route('landing.show', $landing_page->slug);
    }

    public function edit(LandingPage $landing_page)
    {
        $landingPage = $landing_page;
        $stores = Store::where(function ($query) use ($landingPage) {
            $query->whereDoesntHave('landingPage')
                ->orWhere('id', $landingPage->store_id);
        })->orderBy('store_name')->get();
        $coupons = $landingPage->store
            ? $landingPage->store->coupons()->where('status', 1)->orderBy('sort_order')->get()
            : collect();

        return view('admin.landing-pages.edit', compact('landingPage', 'stores', 'coupons'));
    }

    public function update(Request $request, LandingPage $landing_page)
    {
        $landing_page->update($this->validatedData($request, $landing_page));

        return redirect()->route('admin.landing-pages.index')
            ->with('success', 'Landing page updated successfully.');
    }

    public function destroy(LandingPage $landing_page)
    {
        $landing_page->delete();

        return back()->with('success', 'Landing page deleted successfully.');
    }

    public function publicShow(string $slug)
    {
        $landingPage = LandingPage::with(['store.categories', 'featuredCoupon'])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        abort_unless($landingPage->store && $landingPage->store->status, 404);

        $store = $landingPage->store;
        $coupons = Coupon::where('brand_store', $store->store_name)
            ->where('status', 1)
            ->orderByRaw('id = ? DESC', [$landingPage->featured_coupon_id ?: 0])
            ->orderBy('sort_order')
            ->withCount(['usages as today_usage_count' => fn ($query) => $query->whereDate('usage_date', today())])
            ->get();

        return view('frontend.landing.store', compact('landingPage', 'store', 'coupons'));
    }

    public function couponsForStore(Store $store)
    {
        return response()->json(
            $store->coupons()->where('status', 1)->orderBy('sort_order')->get(['id', 'coupon_title'])
        );
    }

    private function validatedData(Request $request, ?LandingPage $landingPage = null): array
    {
        $store = Store::find($request->input('store_id'));
        $request->merge([
            'slug' => Str::slug($request->input('slug') ?: $store?->seo_url ?: $store?->store_name),
        ]);

        $validated = $request->validate([
            'store_id' => [
                'required',
                'exists:stores,id',
                Rule::unique('landing_pages', 'store_id')->ignore($landingPage?->id),
            ],
            'featured_coupon_id' => [
                'nullable',
                Rule::exists('coupons', 'id')->where(
                    fn ($query) => $store ? $query->where('brand_store', $store->store_name) : $query
                ),
            ],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('landing_pages', 'slug')->ignore($landingPage?->id)],
            'heading' => 'nullable|string|max:255',
            'subheading' => 'nullable|string|max:255',
            'disclosure' => 'nullable|string|max:1000',
            'intro_content' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:1000',
        ]);

        $validated['status'] = $request->boolean('status');
        $validated['no_index'] = $request->boolean('no_index');

        return $validated;
    }
}
