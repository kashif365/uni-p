package com.coupondeals.domain;

import jakarta.persistence.Column;
import jakarta.persistence.Entity;
import jakarta.persistence.Table;
import lombok.Getter;
import lombok.Setter;

/** Editable site setting (site name, colours, home page text...). */
@Entity
@Table(name = "settings")
@Getter
@Setter
public class Setting extends BaseEntity {

    @Column(name = "\"key\"")
    private String key;
    private String value;
    private String label;
    private int sortOrder;
}
