<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminSitemapController extends Controller
{
    protected string $path;

    public function __construct()
    {
        $this->path = public_path('sitemap.xml');
    }

    public function index()
    {
        clearstatcache(true, $this->path);
        $exists = file_exists($this->path);

        return view('admin.sitemap.index', [
            'sitemapExists'         => $exists,
            'sitemapPath'           => $exists ? $this->path : null,
            'sitemapSize'           => $exists ? filesize($this->path) : 0,
            'sitemapLastModified'   => $exists ? filemtime($this->path) : null,
            'currentSitemapContent' => $exists ? (string) file_get_contents($this->path) : '',
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'sitemap_file' => ['required', 'file', 'max:51200'],
        ]);

        $file = $request->file('sitemap_file');

        if (strtolower($file->getClientOriginalExtension()) !== 'xml') {
            return redirect()->route('admin.sitemap.index')
                ->with('error', 'Only .xml files are allowed.');
        }

        $content = file_get_contents($file->getRealPath());

        if (empty(trim($content))) {
            return redirect()->route('admin.sitemap.index')
                ->with('error', 'The uploaded file is empty.');
        }

        // Only block completely unparseable XML — namespace warnings are normal in sitemaps
        libxml_use_internal_errors(true);
        $parsed = simplexml_load_string($content);
        libxml_clear_errors();
        libxml_use_internal_errors(false);

        if ($parsed === false) {
            return redirect()->route('admin.sitemap.index')
                ->with('error', 'The uploaded file is not valid XML.');
        }

        // Replace the file — overwrites whatever was there
        if (file_put_contents($this->path, $content) === false) {
            return redirect()->route('admin.sitemap.index')
                ->with('error', 'Could not write to ' . $this->path . '. Check folder permissions.');
        }

        clearstatcache(true, $this->path);

        return redirect()->route('admin.sitemap.index')
            ->with('success', 'sitemap.xml replaced successfully (' . number_format(strlen($content) / 1024, 2) . ' KB).');
    }

    public function download()
    {
        if (!file_exists($this->path)) {
            return redirect()->route('admin.sitemap.index')
                ->with('error', 'No sitemap.xml found.');
        }

        return response()->download($this->path, 'sitemap.xml', ['Content-Type' => 'application/xml']);
    }
}
