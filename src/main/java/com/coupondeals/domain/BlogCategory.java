package com.coupondeals.domain;

import jakarta.persistence.Entity;
import jakarta.persistence.Table;
import lombok.Getter;
import lombok.Setter;

@Entity
@Table(name = "blog_categories")
@Getter
@Setter
public class BlogCategory extends BaseEntity {

    private String name;
    private String slug;
}
