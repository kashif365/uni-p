-- Coupon Deals: MySQL database (schema + data)
--
-- Tables match the Spring Boot app (src/main/resources/db/migration/V1__baseline_schema.sql).
-- Data matches the app's demo data (src/main/resources/db/seed/demo-data.sql): fictional stores and coupons.
-- No user accounts are included: create the admin with --admin-email / --admin-password.
--
-- Import:  mysql -u root -p < database/coupon_deals.sql
--     or:  phpMyAdmin / HeidiSQL -> Import this file
-- Works with MySQL 5.7+ and MariaDB 10.3+.

CREATE DATABASE IF NOT EXISTS coupon_deals CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE coupon_deals;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS contact_messages, blog_posts, blog_categories, coupons, category_store, stores, categories, settings, users;

-- ---------------------------------------------------------------- schema

CREATE TABLE users (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name       VARCHAR(255) NOT NULL,
    email      VARCHAR(255) NOT NULL,
    password   VARCHAR(255) NOT NULL COMMENT 'BCrypt hash',
    role       ENUM('admin', 'editor') NOT NULL DEFAULT 'editor',
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY users_email_unique (email)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE settings (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key`      VARCHAR(100) NOT NULL,
    value      TEXT NULL,
    label      VARCHAR(255) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY settings_key_unique (`key`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE categories (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name        VARCHAR(255) NOT NULL,
    slug        VARCHAR(255) NOT NULL,
    description TEXT NULL,
    featured    TINYINT(1) NOT NULL DEFAULT 0,
    active      TINYINT(1) NOT NULL DEFAULT 1,
    sort_order  INT NOT NULL DEFAULT 0,
    created_at  DATETIME NULL,
    updated_at  DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY categories_name_unique (name),
    UNIQUE KEY categories_slug_unique (slug)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE stores (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name        VARCHAR(255) NOT NULL,
    slug        VARCHAR(255) NOT NULL,
    website_url VARCHAR(500) NULL,
    logo        VARCHAR(255) NULL,
    description TEXT NULL,
    featured    TINYINT(1) NOT NULL DEFAULT 0,
    active      TINYINT(1) NOT NULL DEFAULT 1,
    sort_order  INT NOT NULL DEFAULT 0,
    views       INT UNSIGNED NOT NULL DEFAULT 0,
    created_at  DATETIME NULL,
    updated_at  DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY stores_slug_unique (slug)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE category_store (
    store_id    BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (store_id, category_id),
    KEY category_store_category_id_index (category_id),
    CONSTRAINT category_store_store_fk FOREIGN KEY (store_id) REFERENCES stores (id) ON DELETE CASCADE,
    CONSTRAINT category_store_category_fk FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE coupons (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    store_id    BIGINT UNSIGNED NOT NULL,
    title       VARCHAR(255) NOT NULL,
    code        VARCHAR(100) NULL,
    description TEXT NULL,
    terms       TEXT NULL,
    expires_on  DATE NULL,
    exclusive   TINYINT(1) NOT NULL DEFAULT 0,
    verified    TINYINT(1) NOT NULL DEFAULT 0,
    featured    TINYINT(1) NOT NULL DEFAULT 0,
    active      TINYINT(1) NOT NULL DEFAULT 1,
    used_count  INT UNSIGNED NOT NULL DEFAULT 0,
    sort_order  INT NOT NULL DEFAULT 0,
    created_at  DATETIME NULL,
    updated_at  DATETIME NULL,
    PRIMARY KEY (id),
    KEY coupons_store_id_index (store_id),
    CONSTRAINT coupons_store_fk FOREIGN KEY (store_id) REFERENCES stores (id) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE blog_categories (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name       VARCHAR(255) NOT NULL,
    slug       VARCHAR(255) NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY blog_categories_slug_unique (slug)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE blog_posts (
    id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    category_id BIGINT UNSIGNED NULL,
    title       VARCHAR(255) NOT NULL,
    slug        VARCHAR(255) NOT NULL,
    excerpt     TEXT NULL,
    content     LONGTEXT NOT NULL,
    image       VARCHAR(255) NULL,
    author      VARCHAR(255) NOT NULL DEFAULT 'Coupon Deals',
    published   TINYINT(1) NOT NULL DEFAULT 0,
    views       INT UNSIGNED NOT NULL DEFAULT 0,
    created_at  DATETIME NULL,
    updated_at  DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY blog_posts_slug_unique (slug),
    KEY blog_posts_category_id_index (category_id),
    CONSTRAINT blog_posts_category_fk FOREIGN KEY (category_id) REFERENCES blog_categories (id) ON DELETE SET NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

CREATE TABLE contact_messages (
    id         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name       VARCHAR(255) NOT NULL,
    email      VARCHAR(255) NOT NULL,
    subject    VARCHAR(255) NOT NULL,
    message    TEXT NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- ---------------------------------------------------------------- data

INSERT INTO settings (id, `key`, value, label, sort_order, created_at, updated_at) VALUES
(1, 'site_name', 'Coupon Deals', 'Site name', 1, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(2, 'site_tagline', 'Save More, Shop Smart', 'Tagline', 2, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(3, 'home_heading', 'Save Big with Exclusive Coupon Codes', 'Home page heading', 3, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(4, 'home_subheading', 'Discover verified coupon codes and discounts from your favourite stores.', 'Home page subheading', 4, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(5, 'primary_color', '#4a0c98', 'Primary colour', 5, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(6, 'contact_email', 'support@example.invalid', 'Contact email', 6, '2026-11-24 10:00:00', '2026-11-24 10:00:00');

INSERT INTO categories (id, name, slug, description, featured, active, sort_order, created_at, updated_at) VALUES
(1, 'Fashion', 'fashion', NULL, 1, 1, 0, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(2, 'Electronics', 'electronics', NULL, 1, 1, 1, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(3, 'Food & Drink', 'food-drink', NULL, 1, 1, 2, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(4, 'Travel', 'travel', NULL, 1, 1, 3, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(5, 'Home & Garden', 'home-garden', NULL, 1, 1, 4, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(6, 'Health & Beauty', 'health-beauty', NULL, 1, 1, 5, '2026-11-24 10:00:00', '2026-11-24 10:00:00');

INSERT INTO stores (id, name, slug, website_url, logo, description, featured, active, sort_order, views, created_at, updated_at) VALUES
(1, 'Urban Threads', 'urban-threads', NULL, NULL, 'Everyday clothing, shoes and accessories.', 1, 1, 0, 0, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(2, 'Pixel Point', 'pixel-point', NULL, NULL, 'Headphones, laptops, phones and smart-home gadgets.', 1, 1, 1, 0, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(3, 'Fresh Fork', 'fresh-fork', NULL, NULL, 'Takeaway and grocery delivery from local kitchens.', 1, 1, 2, 0, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(4, 'Wander Trips', 'wander-trips', NULL, NULL, 'Hotels, flights and city breaks.', 1, 1, 3, 0, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(5, 'Nest & Bloom', 'nest-bloom', NULL, NULL, 'Furniture, homeware and garden essentials.', 1, 1, 4, 0, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(6, 'Glow Lab', 'glow-lab', NULL, NULL, 'Skincare, haircare and wellness products.', 1, 1, 5, 0, '2026-11-24 10:00:00', '2026-11-24 10:00:00');

INSERT INTO category_store (store_id, category_id) VALUES
(1, 1), (2, 2), (3, 3), (4, 4), (5, 5), (6, 6);

INSERT INTO coupons (id, store_id, title, code, description, terms, expires_on, exclusive, verified, featured, active, used_count, sort_order, created_at, updated_at) VALUES
(1, 1, '20% off new arrivals', 'URBAN20', 'Save on the latest clothing and shoe collections.', 'Sample coupon. Minimum spend 30. One use per customer.', NULL, 0, 1, 1, 1, 0, 0, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(2, 2, '15% off headphones', 'PIXEL15', 'Discount on wired and wireless headphones.', 'Sample coupon. Excludes gift cards.', NULL, 0, 1, 1, 1, 0, 1, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(3, 3, 'Free delivery on your first order', 'FORKFREE', 'No delivery fee on your first takeaway or grocery order.', 'Sample coupon. New customers only.', NULL, 0, 1, 1, 1, 0, 2, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(4, 4, '10% off hotel bookings', 'WANDER10', 'Save on hotel stays booked at least 7 days ahead.', 'Sample coupon. Subject to availability.', NULL, 0, 1, 1, 1, 0, 3, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(5, 5, '25% off garden furniture', 'NEST25', 'Discount on outdoor tables, chairs and loungers.', 'Sample coupon. While stocks last.', NULL, 0, 1, 1, 1, 0, 4, '2026-11-24 10:00:00', '2026-11-24 10:00:00'),
(6, 6, 'Buy one, get one half price', 'GLOWBOGO', 'Second skincare item at half price.', 'Sample coupon. Cheapest item discounted.', NULL, 0, 1, 1, 1, 0, 5, '2026-11-24 10:00:00', '2026-11-24 10:00:00');

INSERT INTO blog_categories (id, name, slug, created_at, updated_at) VALUES
(1, 'Saving Tips', 'saving-tips', '2026-11-24 10:00:00', '2026-11-24 10:00:00');

INSERT INTO blog_posts (id, category_id, title, slug, excerpt, content, image, author, published, views, created_at, updated_at) VALUES
(1, 1, 'Five ways to save more when you shop online', 'five-ways-to-save-more-when-you-shop-online',
 'Simple habits that make every online order cheaper.',
 '<p>Search for a coupon code before you check out, compare prices across stores and sign up for price-drop alerts.</p><p>Wait for seasonal sales on big purchases, and combine free-delivery offers with discount codes where a store allows it.</p>',
 NULL, 'Coupon Deals', 1, 0, '2026-11-24 10:00:00', '2026-11-24 10:00:00');

SET FOREIGN_KEY_CHECKS = 1;
