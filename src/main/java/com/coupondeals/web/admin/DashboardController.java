package com.coupondeals.web.admin;

import com.coupondeals.repository.BlogPostRepository;
import com.coupondeals.repository.CategoryRepository;
import com.coupondeals.repository.ContactMessageRepository;
import com.coupondeals.repository.CouponRepository;
import com.coupondeals.repository.StoreRepository;
import org.springframework.data.domain.Sort;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;

import java.util.LinkedHashMap;
import java.util.Map;

@Controller
public class DashboardController {

    private final StoreRepository stores;
    private final CouponRepository coupons;
    private final CategoryRepository categories;
    private final BlogPostRepository posts;
    private final ContactMessageRepository messages;

    public DashboardController(StoreRepository stores, CouponRepository coupons, CategoryRepository categories,
                               BlogPostRepository posts, ContactMessageRepository messages) {
        this.stores = stores;
        this.coupons = coupons;
        this.categories = categories;
        this.posts = posts;
        this.messages = messages;
    }

    @GetMapping("/admin")
    public String dashboard(Model model) {
        Map<String, Long> counts = new LinkedHashMap<>();
        counts.put("Stores", stores.count());
        counts.put("Coupons", coupons.count());
        counts.put("Categories", categories.count());
        counts.put("Blog posts", posts.count());
        model.addAttribute("counts", counts);
        model.addAttribute("latestCoupons", coupons.findTop5ByOrderByCreatedAtDesc());
        model.addAttribute("messages", messages.findAll(Sort.by(Sort.Direction.DESC, "createdAt")).stream().limit(5).toList());
        return "admin/dashboard";
    }
}
