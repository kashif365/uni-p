package com.coupondeals.web;

import com.coupondeals.domain.BlogPost;
import com.coupondeals.repository.BlogCategoryRepository;
import com.coupondeals.repository.BlogPostRepository;
import jakarta.servlet.http.HttpSession;
import org.springframework.data.domain.PageRequest;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;

@Controller
@RequestMapping("/blog")
public class BlogController {

    private static final int PAGE_SIZE = 6;

    private final BlogPostRepository posts;
    private final BlogCategoryRepository categories;

    public BlogController(BlogPostRepository posts, BlogCategoryRepository categories) {
        this.posts = posts;
        this.categories = categories;
    }

    @GetMapping
    public String index(@RequestParam(defaultValue = "0") int page, Model model) {
        model.addAttribute("posts", posts.findByPublishedTrueOrderByCreatedAtDesc(PageRequest.of(page, PAGE_SIZE)));
        model.addAttribute("blogCategories", categories.findAllByOrderByNameAsc());
        return "blog/index";
    }

    @GetMapping("/category/{slug}")
    public String category(@PathVariable String slug, @RequestParam(defaultValue = "0") int page, Model model) {
        model.addAttribute("category", categories.findBySlug(slug).orElseThrow(NotFoundException::new));
        model.addAttribute("posts", posts.findByPublishedTrueAndCategorySlugOrderByCreatedAtDesc(slug, PageRequest.of(page, PAGE_SIZE)));
        model.addAttribute("blogCategories", categories.findAllByOrderByNameAsc());
        return "blog/index";
    }

    @GetMapping("/{slug}")
    public String show(@PathVariable String slug, Model model, HttpSession session) {
        BlogPost post = posts.findBySlugAndPublishedTrue(slug).orElseThrow(NotFoundException::new);
        if (session.getAttribute("viewed-post-" + post.getId()) == null) {
            session.setAttribute("viewed-post-" + post.getId(), true);
            posts.incrementViews(post.getId());
        }
        model.addAttribute("post", post);
        model.addAttribute("morePosts", posts.findTop3ByPublishedTrueAndIdNotOrderByCreatedAtDesc(post.getId()));
        return "blog/show";
    }
}
