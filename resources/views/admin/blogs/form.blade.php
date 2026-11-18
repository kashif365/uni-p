@php
    // create/edit dono me safe:
    $blog = $blog ?? new \App\Models\Blog;
@endphp

<style>
.ql-snow.ql-toolbar button svg{
        fill: #433c50 !important;
        stroke: #433c50 !important;
    }
.ql-snow.ql-toolbar button svg path{
        stroke: #433c50 !important;
    }
.ql-snow.ql-toolbar button svg line, polyline{
        stroke: #433c50 !important;
    }
</style>

@csrf
<!-- Top Toggles -->
<div class="d-flex flex-wrap mb-3 gap-3">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="featured" id="featured" value="1"
            {{ old('featured', $blog->featured ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="featured">Featured</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="recommended" id="recommended" value="1"
            {{ old('recommended', $blog->recommended ?? false) ? 'checked' : '' }}>
        <label class="form-check-label" for="recommended">Recommended</label>
    </div>

    <div class="d-flex align-items-center ms-auto">
        <label>Draft</label>
        <div class="form-check form-switch mx-2">
            <input class="form-check-input" type="checkbox" name="status" value="published"
                {{ old('status', $blog->status ?? 'draft') === 'published' ? 'checked' : '' }}>
        </div>
        <label>Published</label>
    </div>
</div>

<!-- Blog Title + Author -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" name="title" placeholder="Blog Title"
                value="{{ old('title', $blog->title ?? '') }}" required>
            <label>Blog Title *</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <input type="text" class="form-control" name="author" placeholder="Author"
                value="{{ old('author', $blog->author ?? 'Campus Deals') }}">
            <label>Author</label>
        </div>
    </div>
</div>

<!-- Author Bio + Author Avatar -->
<div class="row mb-3">
    <div class="col-md-8">
        <div class="form-floating form-floating-outline">
            <textarea class="form-control h-px-150" name="author_bio" placeholder="Author Bio">{{ old('author_bio', $blog->getRawOriginal('author_bio') ?? '') }}</textarea>
            <label>Author Bio</label>
        </div>
        <small class="form-text text-muted">Shown on the article's author box and on the author's public profile page (/blog/author/{{ \Illuminate\Support\Str::slug($blog->author ?? '') }}).</small>
    </div>
    <div class="col-md-4">
        <label>Author Avatar</label>
        <div class="image-upload-box" onclick="document.getElementById('author_avatar').click()">
            <img id="author_avatar_preview"
                 src="{{ ($blog->author_avatar ?? false) ? asset($blog->author_avatar) : '' }}"
                 style="{{ ($blog->author_avatar ?? false) ? '' : 'display:none;' }}">
            <svg id="author_avatar_placeholder" style="{{ ($blog->author_avatar ?? false) ? 'display:none;' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
            </svg>
        </div>
        <div class="d-flex gap-2 mt-2 flex-wrap">
            <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('author_avatar').click()">
                <i class="ri-upload-line"></i> Upload
            </button>
            <button type="button" class="btn btn-sm btn-outline-info flex-fill" onclick="openMediaLibrary('author_avatar', 'author_avatar_preview', 'author_avatar_placeholder')">
                <i class="ri-image-line"></i> Media
            </button>
        </div>
        <input type="file" id="author_avatar" name="author_avatar" style="display:none;" accept="image/*"
               onchange="previewImage(event, 'author_avatar_preview', 'author_avatar_placeholder')">
        <input type="hidden" id="author_avatar_path" name="author_avatar_path">
    </div>
</div>

<!-- Category Selection -->
<div class="row mb-3">
    <div class="col-md-6">
        <div class="form-floating form-floating-outline">
            <select name="category_id" class="form-select">
                <option value="">Select Category</option>
                @foreach(\App\Models\BlogCategory::active()->ordered()->get() as $category)
                    <option value="{{ $category->id }}" 
                        {{ old('category_id', $blog->category_id ?? '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <label>Blog Category</label>
        </div>
    </div>
</div>

<!-- Excerpt -->
<div class="form-floating form-floating-outline mb-3">
    <textarea class="form-control h-px-100" name="excerpt" placeholder="Blog Excerpt">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
    <label>Excerpt (Brief description)</label>
</div>

<!-- Media Section -->
<div class="row mb-3">
    <div class="col-md-12">
        <label>Featured Image</label>
        <div class="image-upload-box" onclick="document.getElementById('featured_image').click()">
            <img id="featured_image_preview"
                 src="{{ ($blog->featured_image ?? false) ? asset($blog->featured_image) : '' }}"
                 style="{{ ($blog->featured_image ?? false) ? '' : 'display:none;' }}">
            <!-- ✅ fixed unique placeholder id -->
            <svg id="featured_image_placeholder" style="{{ ($blog->featured_image ?? false) ? 'display:none;' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M20,5A2,2 0 0,1 22,7V17A2,2 0 0,1 20,19H4C2.89,19 2,18.1 2,17V7C2,5.89 2.89,5 4,5H20M5,16H19L14.5,10L11,14.5L8.5,11.5L5,16Z" />
            </svg>
        </div>
        <div class="d-flex gap-2 mt-2 flex-wrap">
            <button type="button" class="btn btn-sm btn-outline-primary flex-fill" onclick="document.getElementById('featured_image').click()">
                <i class="ri-upload-line"></i> Upload
            </button>
            <button type="button" class="btn btn-sm btn-outline-info flex-fill" onclick="openMediaLibrary('featured_image', 'featured_image_preview', 'featured_image_placeholder')">
                <i class="ri-image-line"></i> Media
            </button>
        </div>
        <input type="file" id="featured_image" name="featured_image" style="display:none;" accept="image/*"
               onchange="previewImage(event, 'featured_image_preview', 'featured_image_placeholder')">
        <input type="hidden" id="featured_image_path" name="featured_image_path">
    </div>
</div>

<!-- Content -->
<div class="mb-3">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Blog Content</h5>
        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#insertProductModal">
            <i class="ri-shopping-bag-3-line"></i> Insert Product
        </button>
      </div>
      <div class="card-body">
    <!-- Quill editor: visual editing area (use theme's editor id and allow theme CSS) -->
    <div id="full-editor" class="form-control">{!! old('description', $blog->description ?? '') !!}</div>
    <!-- Hidden textarea that will be posted -->
    <textarea id="description" name="description" class="d-none">{{ old('description', $blog->description ?? '') }}</textarea>
    <small class="form-text text-muted">Enter blog content as HTML. A rich editor is enabled below.
        Products: <code>[product id="12"]</code> shows one card, <code>[products ids="12,15,18"]</code> shows a grid.</small>
</div>
</div>
</div>

<!-- Insert Product Modal -->
@php $pickerProducts = \App\Models\Product::active()->ordered()->get(); @endphp
<div class="modal fade" id="insertProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Insert Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if($pickerProducts->isEmpty())
                    <p class="text-muted mb-0">No active products yet.
                        <a href="{{ route('admin.products.create') }}" target="_blank">Add a product</a> first, then reopen this window.</p>
                @else
                    <input type="text" id="productPickerSearch" class="form-control mb-3" placeholder="Search products...">
                    <div class="list-group" id="productPickerList">
                        @foreach($pickerProducts as $p)
                            <label class="list-group-item d-flex align-items-center gap-3 product-picker-item"
                                   data-search="{{ strtolower($p->name . ' ' . $p->brand . ' ' . $p->merchant_name) }}">
                                <input type="checkbox" class="form-check-input m-0 product-picker-check" value="{{ $p->id }}">
                                @if($p->image)
                                    <img src="{{ asset($p->image) }}" alt="" style="width:40px;height:40px;object-fit:contain;" class="rounded border">
                                @endif
                                <span class="flex-grow-1">
                                    <span class="fw-medium d-block">{{ $p->name }}</span>
                                    <small class="text-muted">{{ $p->brand }} {{ $p->display_price ? '· ' . $p->display_price : '' }}</small>
                                </span>
                                <small class="text-muted">#{{ $p->id }}</small>
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>
            @if($pickerProducts->isNotEmpty())
            <div class="modal-footer justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="small text-muted" id="productPickerSummary">Select one product for a card, or several for a grid.</span>
                    <select id="productPickerColumns" class="form-select form-select-sm w-auto" style="display:none;">
                        <option value="">Auto columns</option>
                        <option value="2">2 columns</option>
                        <option value="3">3 columns</option>
                        <option value="4">4 columns</option>
                    </select>
                </div>
                <button type="button" class="btn btn-primary" id="productPickerInsert" disabled>Insert</button>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- SEO Section -->
<div class="card p-3 mb-3">
    <h5>SEO Blog SEO Section</h5>
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="form-floating form-floating-outline">
                <input type="text" class="form-control" name="meta_title" placeholder="Meta Title"
                    value="{{ old('meta_title', $blog->meta_title ?? '') }}">
                <label>Meta Title</label>
            </div>
        </div>
        <div class="col-md-4">
                <div class="form-floating form-floating-outline">
                <input type="text" id="seo_url" class="form-control" name="slug" placeholder="SEO URL" required
                    value="{{ old('slug', $blog->slug ?? '') }}">
                <label>SEO URL *</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-floating form-floating-outline">
                <input type="number" class="form-control" name="sort_order" placeholder="Sort Order"
                    value="{{ old('sort_order', $blog->sort_order ?? 0) }}">
                <label>Sort Order</label>
            </div>
        </div>
    </div>
    <div class="form-floating form-floating-outline mb-3">
        <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" placeholder="Meta Keywords (comma separated)" value="{{ old('meta_keywords', $blog->meta_keywords ?? '') }}">
        <label>Meta Keywords</label>
        <small class="form-text text-muted">Enter keywords separated by commas. Example: blog, money saving, tips</small>
    </div>
    <div class="form-floating form-floating-outline mb-3">
        <textarea class="form-control h-px-100" name="meta_description" placeholder="Meta Description">{{ old('meta_description', $blog->meta_description ?? '') }}</textarea>
        <label>Meta Description</label>
    </div>
    
    <!-- Canonical URL -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="form-floating form-floating-outline">
                <input type="url" name="canonical_url" class="form-control" placeholder="Canonical URL"
                    value="{{ old('canonical_url', $blog->canonical_url ?? '') }}">
                <label>Canonical URL</label>
            </div>
        </div>
    </div>

    <!-- Schema -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="form-floating form-floating-outline mb-6">
                <textarea class="form-control h-px-150" placeholder="Schema Markup (JSON-LD)" name="schema">{{ old('schema', $blog->schema ?? '') }}</textarea>
                <label>Schema Markup (JSON-LD)</label>
            </div>
        </div>
    </div>
    
</div>

<!-- Save Button -->
<div class="text-end">
    <button type="submit" class="btn btn-primary">
        {{ isset($blog) && $blog->exists ? '💾 Update' : '💾 Save' }}
    </button>
</div>

<script>
function previewImage(event, previewId, placeholderId) {
    const file = event.target.files && event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(){
        const img = document.getElementById(previewId);
        const ph  = document.getElementById(placeholderId);
        if (img) { img.src = reader.result; img.style.display = 'block'; }
        if (ph)  { ph.style.display = 'none'; }
    };
    reader.readAsDataURL(file);
}
</script>

<!-- TinyMCE for Description field -->
<!-- Sync existing Quill instance (initialized by theme's forms-editors.js) to hidden textarea on submit -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editorNode = document.getElementById('full-editor');
    const hidden = document.getElementById('description');
    // Bind to the form that contains the editor to avoid selecting an unrelated form
    const form = editorNode.closest ? editorNode.closest('form') : document.querySelector('form');
    if (!editorNode || !hidden || !form) return;

    // Always attach a submit handler. When Quill is available we use its API,
    // otherwise we fall back to reading the editor DOM (.ql-editor) content.
    form.addEventListener('submit', function (ev) {
        try {
            let html = '';
            // Prefer the Quill instance if we can find it
            const quill = (typeof Quill !== 'undefined' && Quill.find) ? Quill.find(editorNode) : null;
            if (quill && quill.root) {
                html = quill.root.innerHTML;
            } else {
                // Fall back to the editable container created by Quill
                const editorContent = editorNode.querySelector('.ql-editor');
                html = editorContent ? editorContent.innerHTML : editorNode.innerHTML;
            }

            // Debugging: log before and after setting hidden value so developer can inspect in DevTools Network
            console.debug('Blog editor html (preview):', html);
            hidden.value = html;
            console.debug('Hidden textarea #description value set to:', hidden.value);
        } catch (err) {
            // Best-effort: ensure we don't block submission
            console.warn('Blog editor sync failed:', err);
        }
        // allow form to continue submitting
    });
});
</script>

<script>
// Insert product shortcodes into the Quill editor at the cursor
document.addEventListener('DOMContentLoaded', function () {
    const editorNode = document.getElementById('full-editor');
    const modalEl = document.getElementById('insertProductModal');
    const insertBtn = document.getElementById('productPickerInsert');
    if (!editorNode || !modalEl || !insertBtn) return;

    const getQuill = () => (typeof Quill !== 'undefined' && Quill.find) ? Quill.find(editorNode) : null;
    const checks = () => Array.from(modalEl.querySelectorAll('.product-picker-check'));
    const columnsSelect = document.getElementById('productPickerColumns');
    const summary = document.getElementById('productPickerSummary');
    let lastIndex = null;

    // Remember the cursor position, since opening the modal takes focus away from the editor
    const quill = getQuill();
    if (quill) {
        quill.on('selection-change', function (range) {
            if (range) lastIndex = range.index;
        });
    }

    function selectedIds() {
        return checks().filter(c => c.checked).map(c => c.value);
    }

    function refreshFooter() {
        const count = selectedIds().length;
        insertBtn.disabled = count === 0;
        columnsSelect.style.display = count > 1 ? '' : 'none';
        summary.textContent = count === 0
            ? 'Select one product for a card, or several for a grid.'
            : (count === 1 ? '1 product → single card' : count + ' products → grid');
    }

    modalEl.addEventListener('change', function (e) {
        if (e.target.classList.contains('product-picker-check')) refreshFooter();
    });

    const search = document.getElementById('productPickerSearch');
    search.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        modalEl.querySelectorAll('.product-picker-item').forEach(function (item) {
            item.classList.toggle('d-none', q !== '' && !item.dataset.search.includes(q));
        });
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        checks().forEach(c => c.checked = false);
        search.value = '';
        search.dispatchEvent(new Event('input'));
        columnsSelect.value = '';
        refreshFooter();
    });

    insertBtn.addEventListener('click', function () {
        const ids = selectedIds();
        if (!ids.length) return;

        let code;
        if (ids.length === 1) {
            code = '[product id="' + ids[0] + '"]';
        } else {
            code = '[products ids="' + ids.join(',') + '"' + (columnsSelect.value ? ' columns="' + columnsSelect.value + '"' : '') + ']';
        }

        const q = getQuill();
        if (q) {
            let index = lastIndex !== null ? lastIndex : Math.max(0, q.getLength() - 1);
            // Put the shortcode on its own line so it renders as a block between paragraphs
            const before = index > 0 ? q.getText(index - 1, 1) : '\n';
            const text = (before === '\n' ? '' : '\n') + code + '\n';
            q.insertText(index, text, 'user');
            q.setSelection(index + text.length, 0, 'silent');
            lastIndex = index + text.length;
        } else {
            const hidden = document.getElementById('description');
            hidden.value += '<p>' + code + '</p>';
        }

        bootstrap.Modal.getInstance(modalEl).hide();
    });
});
</script>

<script>
// Auto-generate slug for seo_url from title input
(function(){
    function slugify(text) {
        return text.toString().toLowerCase()
            .normalize('NFKD') // split accented characters
            .replace(/\p{Diacritic}/gu, '')
            .replace(/[^a-z0-9\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-');
    }

    const nameInput = document.querySelector('input[name="title"]');
    const seoInput = document.getElementById('seo_url');
    if (!nameInput || !seoInput) return;

    // Track if user manually edited SEO field
    let seoManuallyEdited = false;
    seoInput.addEventListener('input', function () { seoManuallyEdited = true; });

    // On blur or input of title, populate seo if not manually edited
    function tryFill() {
        if (seoManuallyEdited) return;
        const val = nameInput.value || '';
        const slug = slugify(val);
        if (slug && !seoInput.value) {
            seoInput.value = slug;
        } else if (slug && seoInput.value && !seoManuallyEdited) {
            // If seo present but equals previous slug candidate, update it
            // (helps when editing existing title)
            const current = seoInput.value;
            const currentSlug = slugify(current);
            if (!current || currentSlug === currentSlug) {
                seoInput.value = slug;
            }
        }
    }

    nameInput.addEventListener('blur', tryFill);
    nameInput.addEventListener('input', function () {
        // live update only when seo is empty and not manually edited
        if (!seoManuallyEdited && !seoInput.value) {
            seoInput.value = slugify(nameInput.value || '');
        }
    });
})();
</script>
