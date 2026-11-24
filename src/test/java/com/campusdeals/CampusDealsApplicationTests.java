package com.campusdeals;

import com.campusdeals.repository.StoreRepository;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.boot.webmvc.test.autoconfigure.AutoConfigureMockMvc;
import org.springframework.security.test.context.support.WithMockUser;
import org.springframework.test.web.servlet.MockMvc;

import static org.assertj.core.api.Assertions.assertThat;
import static org.hamcrest.Matchers.containsString;
import static org.springframework.security.test.web.servlet.request.SecurityMockMvcRequestPostProcessors.csrf;
import static org.springframework.security.test.web.servlet.request.SecurityMockMvcRequestPostProcessors.user;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.get;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.post;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.content;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.model;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.redirectedUrl;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.status;

/** Runs the whole app against a throw-away SQLite file loaded with the demo data. */
@SpringBootTest(args = {"--seed", "--admin-email=admin@test.local", "--admin-password=secret-password"},
        properties = {"spring.datasource.url=jdbc:sqlite:target/test-db.sqlite", "app.upload-dir=target/test-uploads"})
@AutoConfigureMockMvc
class CampusDealsApplicationTests {

    @Autowired
    MockMvc mvc;

    @Autowired
    StoreRepository stores;

    static {
        new java.io.File("target/test-db.sqlite").delete();
    }

    @Test
    void publicPagesShowTheDemoContent() throws Exception {
        mvc.perform(get("/")).andExpect(status().isOk())
                .andExpect(content().string(containsString("Campus Deals")))
                .andExpect(content().string(containsString("Offers and coupon codes are for demonstration.")))
                .andExpect(content().string(containsString("15% off notebooks")));
        mvc.perform(get("/coupons")).andExpect(status().isOk()).andExpect(content().string(containsString("DEMOBYTE20")));
        mvc.perform(get("/stores")).andExpect(status().isOk());
        mvc.perform(get("/stores/campus-book-nook")).andExpect(status().isOk())
                .andExpect(content().string(containsString("Campus Book Nook discount codes")));
        mvc.perform(get("/categories")).andExpect(status().isOk());
        mvc.perform(get("/categories/technology")).andExpect(status().isOk())
                .andExpect(content().string(containsString("Study Byte")));
        mvc.perform(get("/blog")).andExpect(status().isOk());
        mvc.perform(get("/blog/five-ways-to-save-money-as-a-student")).andExpect(status().isOk());
        mvc.perform(get("/about")).andExpect(status().isOk());
        mvc.perform(get("/privacy-policy")).andExpect(status().isOk());
        mvc.perform(get("/contact")).andExpect(status().isOk());
    }

    @Test
    void searchFindsStoresAndCoupons() throws Exception {
        mvc.perform(get("/search").param("q", "Campus Book Nook")).andExpect(status().isOk())
                .andExpect(content().string(containsString("15% off notebooks")));
        mvc.perform(get("/search").param("q", "")).andExpect(redirectedUrl("/"));
    }

    @Test
    void unknownPagesReturn404() throws Exception {
        mvc.perform(get("/stores/no-such-store")).andExpect(status().isNotFound())
                .andExpect(content().string(containsString("Sorry, that page doesn't exist.")));
    }

    @Test
    void contactFormValidatesAndSaves() throws Exception {
        mvc.perform(post("/contact").with(csrf()).param("name", "").param("email", "not-an-email"))
                .andExpect(status().isOk())
                .andExpect(model().attributeHasFieldErrors("contact", "name", "email", "subject", "message"));
        mvc.perform(post("/contact").with(csrf()).param("name", "Sam").param("email", "sam@example.com")
                        .param("subject", "Hello").param("message", "Great site"))
                .andExpect(redirectedUrl("/contact"));
    }

    @Test
    void adminRequiresLogin() throws Exception {
        mvc.perform(get("/admin")).andExpect(status().is3xxRedirection()).andExpect(redirectedUrl("/login"));
    }

    @Test
    void adminCanSignInWithTheAccountFromTheCommandLine() throws Exception {
        mvc.perform(post("/login").with(csrf()).param("email", "admin@test.local").param("password", "secret-password"))
                .andExpect(redirectedUrl("/admin"));
        mvc.perform(post("/login").with(csrf()).param("email", "admin@test.local").param("password", "wrong"))
                .andExpect(redirectedUrl("/login?error"));
    }

    @Test
    @WithMockUser(roles = "EDITOR")
    void editorsCanManageStoresButNotUsers() throws Exception {
        mvc.perform(get("/admin")).andExpect(status().isOk());
        mvc.perform(get("/admin/stores")).andExpect(status().isOk());
        mvc.perform(get("/admin/stores/new")).andExpect(status().isOk());
        mvc.perform(get("/admin/coupons/new")).andExpect(status().isOk());
        mvc.perform(get("/admin/blog/new")).andExpect(status().isOk());
        mvc.perform(get("/admin/blog/categories")).andExpect(status().isOk());
        mvc.perform(get("/admin/users")).andExpect(status().isForbidden());
        mvc.perform(get("/admin/settings")).andExpect(status().isForbidden());
    }

    @Test
    void creatingAStoreValidatesAndGeneratesTheSlug() throws Exception {
        var editor = user("editor@test.local").roles("EDITOR");
        mvc.perform(post("/admin/stores").with(csrf()).with(editor).param("name", ""))
                .andExpect(status().isOk())
                .andExpect(model().attributeHasFieldErrors("form", "name"));
        mvc.perform(post("/admin/stores").with(csrf()).with(editor).param("name", "Lecture Hall Lunch")
                        .param("categoryIds", "3").param("active", "true"))
                .andExpect(redirectedUrl("/admin/stores"));
        assertThat(stores.findBySlugAndActiveTrue("lecture-hall-lunch")).isPresent();
        mvc.perform(get("/stores/lecture-hall-lunch")).andExpect(status().isOk());
    }

    @Test
    @WithMockUser(roles = "ADMIN")
    void adminsCanOpenUsersAndSettings() throws Exception {
        mvc.perform(get("/admin/users")).andExpect(status().isOk())
                .andExpect(content().string(containsString("admin@test.local")));
        mvc.perform(get("/admin/settings")).andExpect(status().isOk());
        mvc.perform(post("/admin/settings").with(csrf()).param("site_name", "Uni Deals"))
                .andExpect(redirectedUrl("/admin/settings"));
        mvc.perform(get("/")).andExpect(content().string(containsString("Uni Deals")));
        mvc.perform(post("/admin/settings").with(csrf()).param("site_name", "Campus Deals"));
    }
}
