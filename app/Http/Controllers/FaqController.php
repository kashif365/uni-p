<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Store;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::with('store')->orderBy('sort_order')->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        return view('admin.faqs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'page_url' => 'required|string',
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
        ]);

        $store = $this->findStoreFromUrl($validated['page_url']);

        if (!$store) {
            return back()->withErrors(['page_url' => 'No store page was found for that URL. Make sure it points to a store page, e.g. https://example.com/stores/the-store-slug'])->withInput();
        }

        $last = Faq::where('store_id', $store->id)->max('sort_order') ?? 0;

        Faq::create([
            'store_id'   => $store->id,
            'question'   => $validated['question'],
            'answer'     => $validated['answer'],
            'status'     => $request->boolean('status', true),
            'sort_order' => $last + 1,
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.faqs.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'page_url' => 'required|string',
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
        ]);

        $store = $this->findStoreFromUrl($validated['page_url']);

        if (!$store) {
            return back()->withErrors(['page_url' => 'No store page was found for that URL. Make sure it points to a store page, e.g. https://example.com/stores/the-store-slug'])->withInput();
        }

        $faq->update([
            'store_id' => $store->id,
            'question' => $validated['question'],
            'answer'   => $validated['answer'],
            'status'   => $request->boolean('status'),
        ]);

        return redirect()->route('admin.faqs.index')->with('success', 'FAQ updated successfully.');
    }

    /**
     * Resolve a Store from a pasted page URL by matching the last URL segment
     * against stores.seo_url (e.g. https://example.com/stores/the-slug -> "the-slug").
     */
    private function findStoreFromUrl(string $url): ?Store
    {
        $path = (string) parse_url(trim($url), PHP_URL_PATH);
        $segments = array_values(array_filter(explode('/', $path)));
        $slug = end($segments);

        if (!$slug) {
            return null;
        }

        return Store::where('seo_url', $slug)->first();
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return back()->with('success', 'FAQ deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        if (!$ids || count($ids) === 0) {
            return back()->with('error', 'No FAQs selected.');
        }

        Faq::whereIn('id', $ids)->delete();

        return back()->with('success', 'Selected FAQs deleted successfully.');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'order'   => 'required|array',
            'order.*' => 'required|integer|exists:faqs,id',
        ]);

        foreach ($request->order as $index => $id) {
            Faq::where('id', $id)->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true, 'message' => 'FAQs reordered successfully.']);
    }

    public function updateStatus(Request $request, Faq $faq)
    {
        $faq->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'FAQ status updated successfully.']);
    }
}
