package com.coupondeals.web.admin;

import com.coupondeals.domain.User;
import com.coupondeals.repository.UserRepository;
import com.coupondeals.web.NotFoundException;
import com.coupondeals.web.admin.Forms.UserForm;
import jakarta.validation.Valid;
import org.springframework.security.core.annotation.AuthenticationPrincipal;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

/** Admin accounts (admin role only). */
@Controller
@RequestMapping("/admin/users")
public class AdminUserController {

    private final UserRepository users;
    private final PasswordEncoder encoder;

    public AdminUserController(UserRepository users, PasswordEncoder encoder) {
        this.users = users;
        this.encoder = encoder;
    }

    @GetMapping
    public String list(Model model) {
        model.addAttribute("users", users.findAllByOrderByNameAsc());
        return "admin/users/list";
    }

    @GetMapping("/new")
    public String create(Model model) {
        return form(model, new UserForm(), new User());
    }

    @GetMapping("/{id}/edit")
    public String edit(@PathVariable Long id, Model model) {
        User user = find(id);
        return form(model, UserForm.of(user), user);
    }

    @PostMapping({"", "/{id}"})
    public String save(@PathVariable(required = false) Long id, @Valid @ModelAttribute("form") UserForm form,
                       BindingResult errors, Model model, RedirectAttributes flash) {
        User user = id == null ? new User() : find(id);
        String email = form.getEmail() == null ? "" : form.getEmail().trim().toLowerCase();
        if (id == null ? users.existsByEmailIgnoreCase(email) : users.existsByEmailIgnoreCaseAndIdNot(email, id)) {
            errors.rejectValue("email", "taken", "This email is already used by another account.");
        }
        String password = form.getPassword() == null ? "" : form.getPassword();
        if ((id == null || !password.isEmpty()) && password.length() < 8) {
            errors.rejectValue("password", "short", "The password must be at least 8 characters.");
        }
        if (errors.hasErrors()) {
            return form(model, form, user);
        }
        user.setName(form.getName().trim());
        user.setEmail(email);
        user.setRole(form.getRole());
        if (!password.isEmpty()) {
            user.setPassword(encoder.encode(password));
        }
        users.save(user);
        flash.addFlashAttribute("success", "User saved.");
        return "redirect:/admin/users";
    }

    @PostMapping("/{id}/delete")
    public String delete(@PathVariable Long id, @AuthenticationPrincipal UserDetails me, RedirectAttributes flash) {
        User user = find(id);
        if (user.getEmail().equalsIgnoreCase(me.getUsername())) {
            flash.addFlashAttribute("error", "You cannot delete your own account.");
        } else {
            users.delete(user);
            flash.addFlashAttribute("success", "User deleted.");
        }
        return "redirect:/admin/users";
    }

    private String form(Model model, UserForm form, User user) {
        model.addAttribute("form", form);
        model.addAttribute("user", user);
        return "admin/users/form";
    }

    private User find(Long id) {
        return users.findById(id).orElseThrow(NotFoundException::new);
    }
}
