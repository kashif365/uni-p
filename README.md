<img src="src/main/resources/static/img/logo.svg" alt="Coupon Deals" height="48">

# Coupon Deals

A coupon code and discount website built with **Spring Boot**.
Visitors browse stores, categories and coupon codes, search, read the blog and send a contact message.
Staff sign in to an admin area to manage the content.

The stores and coupon codes in the demo data are fictional.

## Features

| Public site | Admin area (`/admin`) |
| --- | --- |
| Home page with featured coupons, stores and categories | Dashboard with counts, latest coupons and contact messages |
| Coupons list (reveal and copy a code) | Stores: create, edit, delete, logo upload, categories |
| Stores list and store pages | Coupons: create, edit, delete, expiry dates, flags |
| Categories list and category pages | Categories |
| Search across stores and coupons | Blog posts (with image upload) and blog categories |
| Blog with categories | Users with two roles: **admin** and **editor** |
| Contact form (with validation), About and Privacy pages | Site settings (name, tagline, colour, home page text) |

Editors can manage stores, coupons, categories and the blog. Only admins can manage users and settings.

## Technology

- Java 21, Spring Boot 4 (Spring MVC, Spring Data JPA, Spring Security, Bean Validation)
- Thymeleaf templates with the Layout Dialect, Bootstrap 5
- SQLite database, schema managed by Flyway
- JUnit 5 + MockMvc integration tests

## Running it

You need Java 21. Maven does not need to be installed: the `mvnw` wrapper downloads it.

```powershell
# first run: create the database, load the demo data and create an admin account
.\mvnw spring-boot:run "-Dspring-boot.run.arguments=--seed --admin-email=you@example.com --admin-password=choose-a-password"

# later runs
.\mvnw spring-boot:run
```

Open http://localhost:8080 for the site and http://localhost:8080/admin for the admin area
(sign in with the email and password you chose). On macOS/Linux use `./mvnw`.

Run the tests with `.\mvnw test`.

See **[HOW-TO-RUN.md](HOW-TO-RUN.md)** for the full step-by-step guide: commands for PowerShell, cmd and
macOS/Linux, creating and resetting logins, how data is saved and backed up, and importing the MySQL file.

### Configuration

| Environment variable | Default | Purpose |
| --- | --- | --- |
| `PORT` | `8080` | HTTP port |
| `DB_PATH` | `database/coupon-deals.sqlite` | SQLite database file |
| `UPLOAD_DIR` | `uploads` | Folder for uploaded images |

## Project structure

```
src/main/java/com/coupondeals/
  domain/        JPA entities: Store, Coupon, Category, BlogPost, BlogCategory, User, Setting, ContactMessage
  repository/    Spring Data repositories (queries)
  service/       Settings, file uploads, slugs
  config/        Security, upload folder mapping, start-up tasks (--seed, --admin-email)
  web/           Public controllers and shared model attributes
  web/admin/     Admin controllers and their validated form objects
src/main/resources/
  db/migration/  Flyway schema (V1__baseline_schema.sql)
  db/seed/       Demo data
  templates/     Thymeleaf pages (layout.html, fragments.html, admin/...)
  static/        site.css, site.js, img/ (SVG logo and favicon)
src/test/java/   Integration tests
```

## Database

Nine tables: `stores`, `coupons`, `categories`, `category_store` (many-to-many link), `blog_posts`,
`blog_categories`, `users`, `settings` and `contact_messages`. Each coupon belongs to one store; a store can be in
several categories. Passwords are stored as BCrypt hashes.
