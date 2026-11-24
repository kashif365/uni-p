package com.coupondeals.web.admin;

import com.coupondeals.domain.Store;
import com.coupondeals.repository.CategoryRepository;
import com.coupondeals.repository.StoreRepository;
import com.coupondeals.service.FileStorageService;
import com.coupondeals.service.Slugs;
import com.coupondeals.web.NotFoundException;
import com.coupondeals.web.admin.Forms.StoreForm;
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

@Controller
@RequestMapping("/admin/stores")
public class AdminStoreController {

    private final StoreRepository stores;
    private final CategoryRepository categories;
    private final FileStorageService files;

    public AdminStoreController(StoreRepository stores, CategoryRepository categories, FileStorageService files) {
        this.stores = stores;
        this.categories = categories;
        this.files = files;
    }

    @GetMapping
    public String list(Model model) {
        model.addAttribute("stores", stores.findAllByOrderBySortOrderAscNameAsc());
        return "admin/stores/list";
    }

    @GetMapping("/new")
    public String create(Model model) {
        return form(model, new StoreForm(), new Store());
    }

    @GetMapping("/{id}/edit")
    public String edit(@PathVariable Long id, Model model) {
        Store store = find(id);
        return form(model, StoreForm.of(store), store);
    }

    @PostMapping({"", "/{id}"})
    public String save(@PathVariable(required = false) Long id, @Valid @ModelAttribute("form") StoreForm form,
                       BindingResult errors, @RequestParam(required = false) MultipartFile logoFile,
                       Model model, RedirectAttributes flash) {
        Store store = id == null ? new Store() : find(id);
        String slug = form.getSlug() == null || form.getSlug().isBlank() ? Slugs.of(form.getName()) : form.getSlug();
        if (id == null ? stores.existsBySlug(slug) : stores.existsBySlugAndIdNot(slug, id)) {
            errors.rejectValue("slug", "taken", "This slug is already used by another store.");
        }
        boolean hasLogo = logoFile != null && !logoFile.isEmpty();
        if (hasLogo && !FileStorageService.isAllowedImage(logoFile)) {
            errors.reject("logo", "The logo must be a JPG, PNG, GIF, WebP or SVG image.");
        }
        if (errors.hasErrors()) {
            return form(model, form, store);
        }
        store.setName(form.getName().trim());
        store.setSlug(slug);
        store.setWebsiteUrl(blankToNull(form.getWebsiteUrl()));
        store.setDescription(blankToNull(form.getDescription()));
        store.setCategories(categories.findAllById(form.getCategoryIds()));
        store.setFeatured(form.isFeatured());
        store.setActive(form.isActive());
        store.setSortOrder(form.getSortOrder());
        if (hasLogo) {
            files.delete(store.getLogo());
            store.setLogo(files.store(logoFile));
        }
        stores.save(store);
        flash.addFlashAttribute("success", "Store saved.");
        return "redirect:/admin/stores";
    }

    @PostMapping("/{id}/delete")
    public String delete(@PathVariable Long id, RedirectAttributes flash) {
        Store store = find(id);
        files.delete(store.getLogo());
        stores.delete(store);
        flash.addFlashAttribute("success", "Store deleted, together with its coupons.");
        return "redirect:/admin/stores";
    }

    private String form(Model model, StoreForm form, Store store) {
        model.addAttribute("form", form);
        model.addAttribute("store", store);
        model.addAttribute("allCategories", categories.findAllByOrderBySortOrderAscNameAsc());
        return "admin/stores/form";
    }

    private Store find(Long id) {
        return stores.findById(id).orElseThrow(NotFoundException::new);
    }

    static String blankToNull(String s) {
        return s == null || s.isBlank() ? null : s.trim();
    }
}
