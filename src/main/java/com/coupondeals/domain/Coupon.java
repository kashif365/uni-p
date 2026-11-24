package com.coupondeals.domain;

import jakarta.persistence.Convert;
import jakarta.persistence.Entity;
import jakarta.persistence.JoinColumn;
import jakarta.persistence.ManyToOne;
import jakarta.persistence.Table;
import lombok.Getter;
import lombok.Setter;

import java.time.LocalDate;

/** A discount code (or a deal without a code) offered by a store. */
@Entity
@Table(name = "coupons")
@Getter
@Setter
public class Coupon extends BaseEntity {

    @ManyToOne(optional = false)
    @JoinColumn(name = "store_id")
    private Store store;

    private String title;
    private String code;
    private String description;
    private String terms;

    @Convert(converter = DateTextConverter.class)
    private LocalDate expiresOn;

    private boolean exclusive;
    private boolean verified;
    private boolean featured;
    private boolean active = true;
    private int usedCount;
    private int sortOrder;

    public boolean isExpired() {
        return expiresOn != null && expiresOn.isBefore(LocalDate.now());
    }

    public boolean hasCode() {
        return code != null && !code.isBlank();
    }
}
