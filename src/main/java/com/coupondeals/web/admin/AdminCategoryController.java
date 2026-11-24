package com.coupondeals.web.admin;

import com.coupondeals.domain.Category;
import com.coupondeals.repository.CategoryRepository;
import com.coupondeals.service.Slugs;
import com.coupondeals.web.NotFoundException;
import com.coupondeals.web.admin.Forms.CategoryForm;
import jakarta.validation.Valid;
import org.springframework.dao.DataIntegrityViolationException;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import static com.coupondeals.web.admin.AdminStoreController.blankToNull;

@Controller
@RequestMapping("/admin/categories")
public class AdminCategoryController {

    private final CategoryRepository categories;

    public AdminCategoryController(CategoryRepository categories) {
        this.categories = categories;
    }

    @GetMapping
    public String list(Model model) {
        model.addAttribute("categories", categories.findAllByOrderBySortOrderAscNameAsc());
        return "admin/categories/list";
    }

    @GetMapping("/new")
    public String create(Model model) {
        return form(model, new CategoryForm(), new Category());
    }

    @GetMapping("/{id}/edit")
    public String edit(@PathVariable Long id, Model model) {
        Category category = find(id);
        return form(model, CategoryForm.of(category), category);
    }

    @PostMapping({"", "/{id}"})
    public String save(@PathVariable(required = false) Long id, @Valid @ModelAttribute("form") CategoryForm form,
                       BindingResult errors, Model model, RedirectAttributes flash) {
        Category category = id == null ? new Category() : find(id);
        String slug = form.getSlug() == null || form.getSlug().isBlank() ? Slugs.of(form.getName()) : form.getSlug();
        if (id == null ? categories.existsBySlug(slug) : categories.existsBySlugAndIdNot(slug, id)) {
            errors.rejectValue("slug", "taken", "This slug is already used by another category.");
        }
        if (errors.hasErrors()) {
            return form(model, form, category);
        }
        category.setName(form.getName().trim());
        category.setSlug(slug);
        category.setDescription(blankToNull(form.getDescription()));
        category.setFeatured(form.isFeatured());
        category.setActive(form.isActive());
        category.setSortOrder(form.getSortOrder());
        try {
            categories.saveAndFlush(category);
        } catch (DataIntegrityViolationException ex) {
            errors.rejectValue("name", "taken", "A category with this name already exists.");
            return form(model, form, category);
        }
        flash.addFlashAttribute("success", "Category saved.");
        return "redirect:/admin/categories";
    }

    @PostMapping("/{id}/delete")
    public String delete(@PathVariable Long id, RedirectAttributes flash) {
        categories.delete(find(id));
        flash.addFlashAttribute("success", "Category deleted.");
        return "redirect:/admin/categories";
    }

    private String form(Model model, CategoryForm form, Category category) {
        model.addAttribute("form", form);
        model.addAttribute("category", category);
        return "admin/categories/form";
    }

    private Category find(Long id) {
        return categories.findById(id).orElseThrow(NotFoundException::new);
    }
}
