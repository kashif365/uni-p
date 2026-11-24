package com.coupondeals.domain;

import jakarta.persistence.Entity;
import jakarta.persistence.JoinColumn;
import jakarta.persistence.JoinTable;
import jakarta.persistence.ManyToMany;
import jakarta.persistence.OneToMany;
import jakarta.persistence.OrderBy;
import jakarta.persistence.Table;
import lombok.Getter;
import lombok.Setter;

import java.util.ArrayList;
import java.util.List;

/** A brand/shop that offers coupons. */
@Entity
@Table(name = "stores")
@Getter
@Setter
public class Store extends BaseEntity {

    private String name;
    private String slug;
    private String websiteUrl;
    private String logo;
    private String description;
    private boolean featured;
    private boolean active = true;
    private int sortOrder;
    private int views;

    @ManyToMany
    @JoinTable(name = "category_store", joinColumns = @JoinColumn(name = "store_id"),
            inverseJoinColumns = @JoinColumn(name = "category_id"))
    private List<Category> categories = new ArrayList<>();

    @OneToMany(mappedBy = "store")
    @OrderBy("sortOrder")
    private List<Coupon> coupons = new ArrayList<>();

    public List<Coupon> getActiveCoupons() {
        return coupons.stream().filter(Coupon::isActive).toList();
    }
}
