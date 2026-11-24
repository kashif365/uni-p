package com.campusdeals.web;

import com.campusdeals.domain.Category;
import com.campusdeals.domain.ContactMessage;
import com.campusdeals.domain.Store;
import com.campusdeals.repository.BlogPostRepository;
import com.campusdeals.repository.CategoryRepository;
import com.campusdeals.repository.ContactMessageRepository;
import com.campusdeals.repository.CouponRepository;
import com.campusdeals.repository.StoreRepository;
import jakarta.servlet.http.HttpSession;
import jakarta.validation.Valid;
import org.springframework.data.domain.PageRequest;
import org.springframework.http.ResponseEntity;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.util.Map;

/** Public pages: home, coupons, stores, categories, search, contact and the static pages. */
@Controller
public class SiteController {

    private static final int PAGE_SIZE = 12;

    private final StoreRepository stores;
    private final CouponRepository coupons;
    private final CategoryRepository categories;
    private final BlogPostRepository posts;
    private final ContactMessageRepository messages;

    public SiteController(StoreRepository stores, CouponRepository coupons, CategoryRepository categories,
                          BlogPostRepository posts, ContactMessageRepository messages) {
        this.stores = stores;
        this.coupons = coupons;
        this.categories = categories;
        this.posts = posts;
        this.messages = messages;
    }

    @GetMapping("/")
    public String home(Model model) {
        model.addAttribute("featuredCoupons", coupons.findFeatured());
        model.addAttribute("featuredStores", stores.findTop8ByActiveTrueAndFeaturedTrueOrderBySortOrderAsc());
        model.addAttribute("featuredCategories", categories.findByActiveTrueAndFeaturedTrueOrderBySortOrderAsc());
        model.addAttribute("latestPosts", posts.findTop3ByPublishedTrueOrderByCreatedAtDesc());
        model.addAttribute("stats", Map.of("coupons", coupons.countActive(), "stores", stores.countByActiveTrue(),
                "categories", categories.countByActiveTrue()));
        return "home";
    }

    @GetMapping("/coupons")
    public String coupons(@RequestParam(defaultValue = "0") int page, Model model) {
        model.addAttribute("coupons", coupons.findActive(PageRequest.of(page, PAGE_SIZE)));
        return "coupons";
    }

    /** Called by site.js when a visitor reveals a code; counts the use once per session. */
    @PostMapping("/coupons/{id}/use")
    public ResponseEntity<Void> useCoupon(@PathVariable Long id, HttpSession session) {
        if (session.getAttribute("used-coupon-" + id) == null && coupons.existsById(id)) {
            session.setAttribute("used-coupon-" + id, true);
            coupons.incrementUsed(id);
        }
        return ResponseEntity.noContent().build();
    }

    @GetMapping("/stores")
    public String stores(Model model) {
        model.addAttribute("stores", stores.findByActiveTrueOrderByNameAsc());
        return "stores/index";
    }

    @GetMapping("/stores/{slug}")
    public String store(@PathVariable String slug, Model model, HttpSession session) {
        Store store = stores.findBySlugAndActiveTrue(slug).orElseThrow(NotFoundException::new);
        if (session.getAttribute("viewed-store-" + store.getId()) == null) {
            session.setAttribute("viewed-store-" + store.getId(), true);
            stores.incrementViews(store.getId());
        }
        model.addAttribute("store", store);
        return "stores/show";
    }

    @GetMapping("/categories")
    public String categories() {
        return "categories/index";
    }

    @GetMapping("/categories/{slug}")
    public String category(@PathVariable String slug, Model model) {
        Category category = categories.findBySlugAndActiveTrue(slug).orElseThrow(NotFoundException::new);
        model.addAttribute("category", category);
        model.addAttribute("stores", stores.findActiveInCategory(category.getId()));
        model.addAttribute("coupons", coupons.findActiveInCategory(category.getId()));
        return "categories/show";
    }

    @GetMapping("/search")
    public String search(@RequestParam(defaultValue = "") String q, Model model) {
        String query = q.trim();
        if (query.isEmpty()) {
            return "redirect:/";
        }
        model.addAttribute("q", query);
        model.addAttribute("stores", stores.search(query, PageRequest.of(0, 24)).getContent());
        model.addAttribute("coupons", coupons.search(query, PageRequest.of(0, 24)).getContent());
        return "search";
    }

    @GetMapping("/about")
    public String about() {
        return "about";
    }

    @GetMapping("/privacy-policy")
    public String privacy() {
        return "privacy";
    }

    @GetMapping("/contact")
    public String contact(Model model) {
        model.addAttribute("contact", new ContactMessage());
        return "contact";
    }

    @PostMapping("/contact")
    public String sendContact(@Valid @ModelAttribute("contact") ContactMessage contact, BindingResult errors,
                              RedirectAttributes flash) {
        if (errors.hasErrors()) {
            return "contact";
        }
        messages.save(contact);
        flash.addFlashAttribute("success", "Thank you for your message! We will get back to you soon.");
        return "redirect:/contact";
    }

    @GetMapping("/login")
    public String login() {
        return "login";
    }
}
