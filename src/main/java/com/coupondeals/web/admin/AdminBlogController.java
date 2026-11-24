package com.coupondeals.web.admin;

import com.coupondeals.domain.BlogCategory;
import com.coupondeals.domain.BlogPost;
import com.coupondeals.repository.BlogCategoryRepository;
import com.coupondeals.repository.BlogPostRepository;
import com.coupondeals.service.FileStorageService;
import com.coupondeals.service.Slugs;
import com.coupondeals.web.NotFoundException;
import com.coupondeals.web.admin.Forms.BlogCategoryForm;
import com.coupondeals.web.admin.Forms.BlogPostForm;
import jakarta.validation.Valid;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.multipart.MultipartFile;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import static com.coupondeals.web.admin.AdminStoreController.blankToNull;

/** Blog posts (/admin/blog) and blog categories (/admin/blog/categories). */
@Controller
@RequestMapping("/admin/blog")
public class AdminBlogController {

    private final BlogPostRepository posts;
    private final BlogCategoryRepository categories;
    private final FileStorageService files;

    public AdminBlogController(BlogPostRepository posts, BlogCategoryRepository categories, FileStorageService files) {
        this.posts = posts;
        this.categories = categories;
        this.files = files;
    }

    // ---------------------------------------------------------------- posts

    @GetMapping
    public String list(Model model) {
        model.addAttribute("posts", posts.findAllByOrderByCreatedAtDesc());
        return "admin/blog/list";
    }

    @GetMapping("/new")
    public String create(Model model) {
        return postForm(model, new BlogPostForm(), new BlogPost());
    }

    @GetMapping("/{id}/edit")
    public String edit(@PathVariable Long id, Model model) {
        BlogPost post = findPost(id);
        return postForm(model, BlogPostForm.of(post), post);
    }

    @PostMapping({"", "/{id}"})
    public String save(@PathVariable(required = false) Long id, @Valid @ModelAttribute("form") BlogPostForm form,
                       BindingResult errors, @RequestParam(required = false) MultipartFile imageFile,
                       Model model, RedirectAttributes flash) {
        BlogPost post = id == null ? new BlogPost() : findPost(id);
        String slug = form.getSlug() == null || form.getSlug().isBlank() ? Slugs.of(form.getTitle()) : form.getSlug();
        if (id == null ? posts.existsBySlug(slug) : posts.existsBySlugAndIdNot(slug, id)) {
            errors.rejectValue("slug", "taken", "This slug is already used by another post.");
        }
        boolean hasImage = imageFile != null && !imageFile.isEmpty();
        if (hasImage && !FileStorageService.isAllowedImage(imageFile)) {
            errors.reject("image", "The image must be a JPG, PNG, GIF, WebP or SVG file.");
        }
        if (errors.hasErrors()) {
            return postForm(model, form, post);
        }
        post.setTitle(form.getTitle().trim());
        post.setSlug(slug);
        post.setCategory(form.getCategoryId() == null ? null : categories.findById(form.getCategoryId()).orElse(null));
        post.setExcerpt(blankToNull(form.getExcerpt()));
        post.setContent(form.getContent());
        post.setAuthor(form.getAuthor() == null || form.getAuthor().isBlank() ? "Coupon Deals" : form.getAuthor().trim());
        post.setPublished(form.isPublished());
        if (hasImage) {
            files.delete(post.getImage());
            post.setImage(files.store(imageFile));
        }
        posts.save(post);
        flash.addFlashAttribute("success", "Post saved.");
        return "redirect:/admin/blog";
    }

    @PostMapping("/{id}/delete")
    public String delete(@PathVariable Long id, RedirectAttributes flash) {
        BlogPost post = findPost(id);
        files.delete(post.getImage());
        posts.delete(post);
        flash.addFlashAttribute("success", "Post deleted.");
        return "redirect:/admin/blog";
    }

    private String postForm(Model model, BlogPostForm form, BlogPost post) {
        model.addAttribute("form", form);
        model.addAttribute("post", post);
        model.addAttribute("allCategories", categories.findAllByOrderByNameAsc());
        return "admin/blog/form";
    }

    private BlogPost findPost(Long id) {
        return posts.findById(id).orElseThrow(NotFoundException::new);
    }

    // ---------------------------------------------------------------- categories

    @GetMapping("/categories")
    public String categories(Model model) {
        model.addAttribute("categories", categories.findAllByOrderByNameAsc());
        if (!model.containsAttribute("form")) {
            model.addAttribute("form", new BlogCategoryForm());
        }
        return "admin/blog/categories";
    }

    @GetMapping("/categories/{id}/edit")
    public String editCategory(@PathVariable Long id, Model model) {
        BlogCategory category = findCategory(id);
        model.addAttribute("editing", category);
        model.addAttribute("form", BlogCategoryForm.of(category));
        return categories(model);
    }

    @PostMapping({"/categories", "/categories/{id}"})
    public String saveCategory(@PathVariable(required = false) Long id, @Valid @ModelAttribute("form") BlogCategoryForm form,
                               BindingResult errors, Model model, RedirectAttributes flash) {
        BlogCategory category = id == null ? new BlogCategory() : findCategory(id);
        String slug = form.getSlug() == null || form.getSlug().isBlank() ? Slugs.of(form.getName()) : form.getSlug();
        if (id == null ? categories.existsBySlug(slug) : categories.existsBySlugAndIdNot(slug, id)) {
            errors.rejectValue("slug", "taken", "This slug is already used.");
        }
        if (errors.hasErrors()) {
            model.addAttribute("editing", id == null ? null : category);
            return categories(model);
        }
        category.setName(form.getName().trim());
        category.setSlug(slug);
        categories.save(category);
        flash.addFlashAttribute("success", "Blog category saved.");
        return "redirect:/admin/blog/categories";
    }

    @PostMapping("/categories/{id}/delete")
    public String deleteCategory(@PathVariable Long id, RedirectAttributes flash) {
        categories.delete(findCategory(id));
        flash.addFlashAttribute("success", "Blog category deleted. Its posts are now uncategorised.");
        return "redirect:/admin/blog/categories";
    }

    private BlogCategory findCategory(Long id) {
        return categories.findById(id).orElseThrow(NotFoundException::new);
    }
}
