<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ActivityLogService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::ordered()->get();

        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $this->validated($request);

        $data['image'] = $this->resolveImage($request);
        $data['sort_order'] = (Product::max('sort_order') ?? 0) + 1;

        $product = Product::create($data);

        ActivityLogService::logCreate($product, $request);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully. Shortcode: ' . $product->shortcode);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request);

        $data['image'] = $this->resolveImage($request, $product->image);

        $oldValues = $product->toArray();
        $product->update($data);

        ActivityLogService::logUpdate($product, $oldValues, $data, $request);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * Images are left on disk because they may be shared through the media library.
     */
    public function destroy(Product $product)
    {
        ActivityLogService::logDelete($product, request());

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    /**
     * Bulk delete products
     */
    public function bulkDelete(Request $request)
    {
        if (!$request->has('ids') || count($request->ids) === 0) {
            return back()->with('error', 'No products selected.');
        }

        $count = Product::whereIn('id', $request->ids)->delete();

        ActivityLogService::logBulkAction('delete', 'Product', $count, $request);

        return back()->with('success', 'Selected products deleted successfully.');
    }

    /**
     * Reorder products (AJAX)
     */
    public function reorder(Request $request)
    {
        $data = $request->validate([
            'order'              => 'required|array',
            'order.*.id'         => 'required|integer|exists:products,id',
            'order.*.sort_order' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($data) {
            foreach ($data['order'] as $item) {
                Product::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
            }
        });

        return response()->json(['status' => 'success']);
    }

    /**
     * Public redirect used by product buttons: counts the click and sends the visitor to the affiliate URL.
     */
    public function go(Product $product)
    {
        abort_unless($product->status, 404);

        Product::where('id', $product->id)->increment('clicks');

        return redirect()->away($product->affiliate_url, 302)
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'brand'         => 'nullable|string|max:255',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'description'   => 'nullable|string',
            'price'         => 'nullable|numeric|min:0',
            'sale_price'    => 'nullable|numeric|min:0',
            'currency'      => 'nullable|string|max:5',
            'affiliate_url' => 'required|url',
            'merchant_name' => 'nullable|string|max:255',
            'button_text'   => 'nullable|string|max:255',
            'badge'         => 'nullable|string|max:255',
            'rating'        => 'nullable|numeric|min:0|max:5',
            'pros'          => 'nullable|string',
            'cons'          => 'nullable|string',
        ]);

        unset($data['image']);
        $data['currency'] = $data['currency'] ?: '$';
        $data['status'] = $request->boolean('status');

        return $data;
    }

    // Uploaded file wins, then a media-library pick, otherwise keep the current image
    private function resolveImage(Request $request, ?string $current = null): ?string
    {
        if ($request->hasFile('image')) {
            return $this->imageService->uploadAndConvert(
                $request->file('image'),
                'uploads',
                ['quality' => 100, 'preserve_original' => true]
            );
        }

        if ($request->filled('image_path')) {
            return $request->input('image_path');
        }

        return $current;
    }
}
