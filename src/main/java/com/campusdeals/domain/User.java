package com.campusdeals.domain;

import jakarta.persistence.Entity;
import jakarta.persistence.Table;
import lombok.Getter;
import lombok.Setter;

/** Admin panel account. Role "admin" can also manage users and settings; "editor" manages content only. */
@Entity
@Table(name = "users")
@Getter
@Setter
public class User extends BaseEntity {

    private String name;
    private String email;
    private String password;
    private String role = "editor";

    public boolean isAdmin() {
        return "admin".equals(role);
    }
}
