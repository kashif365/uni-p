package com.campusdeals.domain;

import jakarta.persistence.Entity;
import jakarta.persistence.JoinColumn;
import jakarta.persistence.ManyToOne;
import jakarta.persistence.Table;
import lombok.Getter;
import lombok.Setter;

@Entity
@Table(name = "blog_posts")
@Getter
@Setter
public class BlogPost extends BaseEntity {

    @ManyToOne
    @JoinColumn(name = "category_id")
    private BlogCategory category;

    private String title;
    private String slug;
    private String excerpt;
    /** HTML body written by admins. */
    private String content;
    private String image;
    private String author = "Campus Deals";
    private boolean published;
    private int views;
}
