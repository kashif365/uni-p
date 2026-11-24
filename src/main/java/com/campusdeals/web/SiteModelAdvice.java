package com.campusdeals.web;

import com.campusdeals.domain.Category;
import com.campusdeals.repository.CategoryRepository;
import com.campusdeals.service.SettingsService;
import jakarta.servlet.http.HttpServletRequest;
import org.springframework.http.HttpStatus;
import org.springframework.security.core.Authentication;
import org.springframework.web.bind.annotation.ControllerAdvice;
import org.springframework.web.bind.annotation.ExceptionHandler;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.ResponseStatus;
import org.springframework.web.servlet.ModelAndView;

import java.util.List;
import java.util.Map;

/** Values every page uses (site settings, navigation categories) and the 404 page. */
@ControllerAdvice
public class SiteModelAdvice {

    private final SettingsService settings;
    private final CategoryRepository categories;

    public SiteModelAdvice(SettingsService settings, CategoryRepository categories) {
        this.settings = settings;
        this.categories = categories;
    }

    @ModelAttribute("site")
    public Map<String, String> site() {
        return settings.all();
    }

    @ModelAttribute("navCategories")
    public List<Category> navCategories() {
        return categories.findByActiveTrueOrderBySortOrderAscNameAsc();
    }

    /** Which admin menu item to highlight: "stores", "coupons", "blog", "blog-categories"... */
    @ModelAttribute("adminSection")
    public String adminSection(HttpServletRequest request) {
        String path = request.getRequestURI().substring(request.getContextPath().length());
        if (!path.startsWith("/admin")) {
            return null;
        }
        if (path.startsWith("/admin/blog/categories")) {
            return "blog-categories";
        }
        String[] parts = path.split("/");
        return parts.length > 2 ? parts[2] : "dashboard";
    }

    @ModelAttribute("isAdmin")
    public boolean isAdmin(Authentication auth) {
        return auth != null && auth.getAuthorities().stream().anyMatch(a -> a.getAuthority().equals("ROLE_ADMIN"));
    }

    @ModelAttribute("userEmail")
    public String userEmail(Authentication auth) {
        return auth == null ? null : auth.getName();
    }

    /** Exception handlers don't receive the @ModelAttribute values, so the 404 page gets them here. */
    @ExceptionHandler(NotFoundException.class)
    @ResponseStatus(HttpStatus.NOT_FOUND)
    public ModelAndView notFound() {
        ModelAndView page = new ModelAndView("error/404");
        page.addObject("site", site());
        page.addObject("navCategories", navCategories());
        return page;
    }
}
