package com.coupondeals.domain;

import jakarta.persistence.Entity;
import jakarta.persistence.ManyToMany;
import jakarta.persistence.Table;
import lombok.Getter;
import lombok.Setter;

import java.util.ArrayList;
import java.util.List;

/** Store category, e.g. "Technology". */
@Entity
@Table(name = "categories")
@Getter
@Setter
public class Category extends BaseEntity {

    private String name;
    private String slug;
    private String description;
    private boolean featured;
    private boolean active = true;
    private int sortOrder;

    @ManyToMany(mappedBy = "categories")
    private List<Store> stores = new ArrayList<>();
}
