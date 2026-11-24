package com.campusdeals.web.admin;

import com.campusdeals.domain.BlogCategory;
import com.campusdeals.domain.BlogPost;
import com.campusdeals.domain.Category;
import com.campusdeals.domain.Coupon;
import com.campusdeals.domain.Store;
import com.campusdeals.domain.User;
import jakarta.validation.constraints.Email;
import jakarta.validation.constraints.NotBlank;
import jakarta.validation.constraints.NotNull;
import jakarta.validation.constraints.Pattern;
import jakarta.validation.constraints.Size;
import lombok.Data;
import org.hibernate.validator.constraints.URL;
import org.springframework.format.annotation.DateTimeFormat;

import java.time.LocalDate;
import java.util.ArrayList;
import java.util.List;

/** Form objects for the admin screens: what the user can edit, with validation rules. */
public final class Forms {

    private Forms() {
    }

    /** Slugs: lower-case letters, digits and dashes. Left empty, they are generated from the name. */
    static final String SLUG = "^$|^[a-z0-9]+(-[a-z0-9]+)*$";
    static final String SLUG_MESSAGE = "Use lower-case letters, numbers and dashes only.";

    @Data
    public static class StoreForm {
        @NotBlank @Size(max = 255)
        private String name;
        @Pattern(regexp = SLUG, message = SLUG_MESSAGE)
        private String slug;
        @URL @Size(max = 255)
        private String websiteUrl;
        private String description;
        private List<Long> categoryIds = new ArrayList<>();
        private boolean featured;
        private boolean active = true;
        private int sortOrder;

        static StoreForm of(Store s) {
            StoreForm f = new StoreForm();
            f.name = s.getName();
            f.slug = s.getSlug();
            f.websiteUrl = s.getWebsiteUrl();
            f.description = s.getDescription();
            f.categoryIds = s.getCategories().stream().map(Category::getId).toList();
            f.featured = s.isFeatured();
            f.active = s.isActive();
            f.sortOrder = s.getSortOrder();
            return f;
        }
    }

    @Data
    public static class CouponForm {
        @NotNull(message = "Choose a store.")
        private Long storeId;
        @NotBlank @Size(max = 255)
        private String title;
        @Size(max = 100)
        private String code;
        private String description;
        private String terms;
        @DateTimeFormat(iso = DateTimeFormat.ISO.DATE)
        private LocalDate expiresOn;
        private boolean exclusive;
        private boolean verified;
        private boolean featured;
        private boolean active = true;
        private int sortOrder;

        static CouponForm of(Coupon c) {
            CouponForm f = new CouponForm();
            f.storeId = c.getStore().getId();
            f.title = c.getTitle();
            f.code = c.getCode();
            f.description = c.getDescription();
            f.terms = c.getTerms();
            f.expiresOn = c.getExpiresOn();
            f.exclusive = c.isExclusive();
            f.verified = c.isVerified();
            f.featured = c.isFeatured();
            f.active = c.isActive();
            f.sortOrder = c.getSortOrder();
            return f;
        }
    }

    @Data
    public static class CategoryForm {
        @NotBlank @Size(max = 255)
        private String name;
        @Pattern(regexp = SLUG, message = SLUG_MESSAGE)
        private String slug;
        private String description;
        private boolean featured;
        private boolean active = true;
        private int sortOrder;

        static CategoryForm of(Category c) {
            CategoryForm f = new CategoryForm();
            f.name = c.getName();
            f.slug = c.getSlug();
            f.description = c.getDescription();
            f.featured = c.isFeatured();
            f.active = c.isActive();
            f.sortOrder = c.getSortOrder();
            return f;
        }
    }

    @Data
    public static class BlogPostForm {
        @NotBlank @Size(max = 255)
        private String title;
        @Pattern(regexp = SLUG, message = SLUG_MESSAGE)
        private String slug;
        private Long categoryId;
        @Size(max = 500)
        private String excerpt;
        @NotBlank
        private String content;
        @Size(max = 255)
        private String author = "Campus Deals";
        private boolean published;

        static BlogPostForm of(BlogPost p) {
            BlogPostForm f = new BlogPostForm();
            f.title = p.getTitle();
            f.slug = p.getSlug();
            f.categoryId = p.getCategory() == null ? null : p.getCategory().getId();
            f.excerpt = p.getExcerpt();
            f.content = p.getContent();
            f.author = p.getAuthor();
            f.published = p.isPublished();
            return f;
        }
    }

    @Data
    public static class BlogCategoryForm {
        @NotBlank @Size(max = 255)
        private String name;
        @Pattern(regexp = SLUG, message = SLUG_MESSAGE)
        private String slug;

        static BlogCategoryForm of(BlogCategory c) {
            BlogCategoryForm f = new BlogCategoryForm();
            f.name = c.getName();
            f.slug = c.getSlug();
            return f;
        }
    }

    @Data
    public static class UserForm {
        @NotBlank @Size(max = 255)
        private String name;
        @NotBlank @Email @Size(max = 255)
        private String email;
        /** Required for new users; left empty on edit it keeps the current password. */
        @Size(max = 72)
        private String password;
        @Pattern(regexp = "admin|editor")
        private String role = "editor";

        static UserForm of(User u) {
            UserForm f = new UserForm();
            f.name = u.getName();
            f.email = u.getEmail();
            f.role = u.getRole();
            return f;
        }
    }
}
