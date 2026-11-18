<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminRobotsController extends Controller
{
    protected string $path;

    public function __construct()
    {
        $this->path = public_path('robots.txt');
    }

    public function index()
    {
        clearstatcache(true, $this->path);
        $exists = file_exists($this->path);

        $default = "User-agent: *\nDisallow: /admin/\nDisallow: /login\nDisallow: /password/\n\nSitemap: " . url('/sitemap.xml') . "\n";

        return view('admin.robots.index', [
            'robotsExists'       => $exists,
            'robotsPath'         => $exists ? $this->path : null,
            'robotsSize'         => $exists ? filesize($this->path) : 0,
            'robotsLastModified' => $exists ? filemtime($this->path) : null,
            'currentContent'     => $exists ? (string) file_get_contents($this->path) : $default,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'robots_content' => ['nullable', 'string', 'max:102400'],
            'robots_file'    => ['nullable', 'file', 'max:5120'],
        ]);

        // File upload takes priority over textarea
        if ($request->hasFile('robots_file')) {
            /** @var \Illuminate\Http\UploadedFile $file */
            $file = $request->file('robots_file');

            if (!$file instanceof \Illuminate\Http\UploadedFile) {
                return redirect()->route('admin.robots.index')
                    ->with('error', 'Invalid file upload.');
            }

            if (strtolower($file->getClientOriginalExtension()) !== 'txt') {
                return redirect()->route('admin.robots.index')
                    ->with('error', 'Only .txt files are allowed.');
            }

            $content = (string) file_get_contents($file->getRealPath());
        } else {
            $content = (string) $request->input('robots_content', '');
        }

        if (trim($content) === '') {
            return redirect()->route('admin.robots.index')
                ->with('error', 'Content cannot be empty.');
        }

        // Replace the file — overwrites whatever was there
        if (file_put_contents($this->path, $content) === false) {
            return redirect()->route('admin.robots.index')
                ->with('error', 'Could not write to ' . $this->path . '. Check folder permissions.');
        }

        clearstatcache(true, $this->path);

        return redirect()->route('admin.robots.index')
            ->with('success', 'robots.txt replaced successfully.');
    }

    public function download()
    {
        if (!file_exists($this->path)) {
            return redirect()->route('admin.robots.index')
                ->with('error', 'No robots.txt found.');
        }

        return response()->download($this->path, 'robots.txt', ['Content-Type' => 'text/plain']);
    }
}
