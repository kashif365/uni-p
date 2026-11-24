package com.coupondeals.web.admin;

import com.coupondeals.service.SettingsService;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import java.util.Map;

/** Site settings (admin role only). */
@Controller
@RequestMapping("/admin/settings")
public class AdminSettingsController {

    private final SettingsService settings;

    public AdminSettingsController(SettingsService settings) {
        this.settings = settings;
    }

    @GetMapping
    public String edit(Model model) {
        model.addAttribute("settings", settings.editable());
        return "admin/settings";
    }

    @PostMapping
    public String save(@RequestParam Map<String, String> values, RedirectAttributes flash) {
        values.remove("_csrf");
        settings.update(values);
        flash.addFlashAttribute("success", "Settings saved.");
        return "redirect:/admin/settings";
    }
}
