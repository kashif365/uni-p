package com.campusdeals.web.admin;

import com.campusdeals.domain.Coupon;
import com.campusdeals.repository.CouponRepository;
import com.campusdeals.repository.StoreRepository;
import com.campusdeals.web.NotFoundException;
import com.campusdeals.web.admin.Forms.CouponForm;
import jakarta.validation.Valid;
import org.springframework.data.domain.PageRequest;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import static com.campusdeals.web.admin.AdminStoreController.blankToNull;

@Controller
@RequestMapping("/admin/coupons")
public class AdminCouponController {

    private final CouponRepository coupons;
    private final StoreRepository stores;

    public AdminCouponController(CouponRepository coupons, StoreRepository stores) {
        this.coupons = coupons;
        this.stores = stores;
    }

    @GetMapping
    public String list(@RequestParam(defaultValue = "0") int page, Model model) {
        model.addAttribute("coupons", coupons.findAllByOrderBySortOrderAscIdAsc(PageRequest.of(page, 20)));
        return "admin/coupons/list";
    }

    @GetMapping("/new")
    public String create(@RequestParam(required = false) Long storeId, Model model) {
        CouponForm form = new CouponForm();
        form.setStoreId(storeId);
        return form(model, form, new Coupon());
    }

    @GetMapping("/{id}/edit")
    public String edit(@PathVariable Long id, Model model) {
        Coupon coupon = find(id);
        return form(model, CouponForm.of(coupon), coupon);
    }

    @PostMapping({"", "/{id}"})
    public String save(@PathVariable(required = false) Long id, @Valid @ModelAttribute("form") CouponForm form,
                       BindingResult errors, Model model, RedirectAttributes flash) {
        Coupon coupon = id == null ? new Coupon() : find(id);
        if (errors.hasErrors()) {
            return form(model, form, coupon);
        }
        coupon.setStore(stores.findById(form.getStoreId()).orElseThrow(NotFoundException::new));
        coupon.setTitle(form.getTitle().trim());
        coupon.setCode(blankToNull(form.getCode()));
        coupon.setDescription(blankToNull(form.getDescription()));
        coupon.setTerms(blankToNull(form.getTerms()));
        coupon.setExpiresOn(form.getExpiresOn());
        coupon.setExclusive(form.isExclusive());
        coupon.setVerified(form.isVerified());
        coupon.setFeatured(form.isFeatured());
        coupon.setActive(form.isActive());
        coupon.setSortOrder(form.getSortOrder());
        coupons.save(coupon);
        flash.addFlashAttribute("success", "Coupon saved.");
        return "redirect:/admin/coupons";
    }

    @PostMapping("/{id}/delete")
    public String delete(@PathVariable Long id, RedirectAttributes flash) {
        coupons.delete(find(id));
        flash.addFlashAttribute("success", "Coupon deleted.");
        return "redirect:/admin/coupons";
    }

    private String form(Model model, CouponForm form, Coupon coupon) {
        model.addAttribute("form", form);
        model.addAttribute("coupon", coupon);
        model.addAttribute("allStores", stores.findAllByOrderBySortOrderAscNameAsc());
        return "admin/coupons/form";
    }

    private Coupon find(Long id) {
        return coupons.findById(id).orElseThrow(NotFoundException::new);
    }
}
