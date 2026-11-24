# How to run Coupon Deals

This guide covers how to start the project, how the data is saved, and how to create the login
(admin) accounts. Each command is shown for **PowerShell**, **Command Prompt (cmd)** and **macOS/Linux**.

---

## 1. What you need

| Requirement | How to check |
| --- | --- |
| **Java 21** (JDK) | `java -version` should print `21.x` |
| Internet connection | Needed the first time (to download Maven and the libraries) and for page styling (Bootstrap) |

You do **not** need to install Maven, MySQL or a web server. The `mvnw` script downloads Maven
by itself, and the database is a single file that is created automatically.

If Java is missing, install it on Windows with:

```
winget install EclipseAdoptium.Temurin.21.JDK
```

Then **close and reopen the terminal** so it can find `java`.

---

## 2. Open a terminal in the project folder

```
cd d:\laragon\www\uni-p
```

(Change the path if the project is somewhere else. Every command below is run from this folder.)

---

## 3. First run: create the database, the demo data and your admin login

Choose your own email and password (the password must be **at least 8 characters**).

**PowerShell**
```powershell
.\mvnw spring-boot:run "-Dspring-boot.run.arguments=--seed --admin-email=admin@example.com --admin-password=MyPassword123"
```

**Command Prompt (cmd)**
```bat
mvnw spring-boot:run "-Dspring-boot.run.arguments=--seed --admin-email=admin@example.com --admin-password=MyPassword123"
```

**macOS / Linux**
```bash
./mvnw spring-boot:run "-Dspring-boot.run.arguments=--seed --admin-email=admin@example.com --admin-password=MyPassword123"
```

The first start takes a minute or two while the libraries download. It is ready when the log shows:

```
Admin account ready: admin@example.com
Tomcat started on port 8080 (http)
```

What the options do:

| Option | What it does |
| --- | --- |
| `--seed` | Adds the demo stores, coupons, categories and blog post. Only works when the database has no stores yet, so it never adds duplicates. |
| `--admin-email=...` | The email you will sign in with. |
| `--admin-password=...` | The password for that account (8+ characters). |

---

## 4. Open the website

| Page | Address |
| --- | --- |
| Website | http://localhost:8080 |
| Admin area | http://localhost:8080/admin (sign in with the email and password from step 3) |

To **stop** the app, go back to the terminal and press **Ctrl + C**.

---

## 5. Every run after the first

The database and your account are already saved, so just start the app:

| Terminal | Command |
| --- | --- |
| PowerShell | `.\mvnw spring-boot:run` |
| cmd | `mvnw spring-boot:run` |
| macOS / Linux | `./mvnw spring-boot:run` |

---

## 6. Login accounts (credentials)

### Create the first admin, or reset a forgotten password

Start the app with `--admin-email` and `--admin-password`. If the email already exists, its
password is **replaced** and the account becomes an admin. If it doesn't exist, a new admin is created.

**PowerShell**
```powershell
.\mvnw spring-boot:run "-Dspring-boot.run.arguments=--admin-email=admin@example.com --admin-password=NewPassword456"
```

**cmd**
```bat
mvnw spring-boot:run "-Dspring-boot.run.arguments=--admin-email=admin@example.com --admin-password=NewPassword456"
```

**macOS / Linux**
```bash
./mvnw spring-boot:run "-Dspring-boot.run.arguments=--admin-email=admin@example.com --admin-password=NewPassword456"
```

If the password is shorter than 8 characters, nothing is changed and the log shows
`Admin not created: the password must be at least 8 characters.`

### Add more users from the website

1. Sign in at http://localhost:8080/admin as an admin.
2. Open **Users** and click **Add user**.
3. Enter the name, email and password (8+ characters), and choose the role:

| Role | Can do |
| --- | --- |
| **admin** | Everything, including managing users and site settings |
| **editor** | Manage stores, coupons, categories and the blog (no users or settings) |

To change a user's password later, edit the user and type a new password. Leave the field
empty to keep the current one. You cannot delete the account you are signed in with.

Passwords are never stored as plain text: they are saved as **BCrypt** hashes.

---

## 7. How the data is saved

### Where it goes

| What | Saved in |
| --- | --- |
| Stores, coupons, categories, blog posts, users, settings, contact-form messages | The database file `database/coupon-deals.sqlite` |
| Uploaded images (store logos, blog images) | The `uploads/` folder |

The database file and all its tables are **created automatically** the first time the app starts
(the table definitions are in `src/main/resources/db/migration/V1__baseline_schema.sql`).

### Adding and changing data

There is nothing to "save" by hand. Every time you click **Save** in the admin area, or a visitor
sends the contact form, the change is written to the database straight away and is still there
after you restart the app.

| To add... | Go to |
| --- | --- |
| A store (with logo and categories) | Admin → **Stores** → Add store |
| A coupon | Admin → **Coupons** → Add coupon (pick its store) |
| A category | Admin → **Categories** → Add category |
| A blog post | Admin → **Blog posts** → Write a post |
| Site name, tagline, colour, home page text | Admin → **Settings** (admins only) |

### Looking at the data directly

Open `database/coupon-deals.sqlite` with **HeidiSQL** (included with Laragon: choose network type
*SQLite* and select the file) or with **DB Browser for SQLite**. Stop the app before editing the file
by hand.

### Back up and restore

1. Stop the app (Ctrl + C).
2. Copy `database/coupon-deals.sqlite` and the `uploads/` folder somewhere safe.
3. To restore, copy them back and start the app again.

### Start again with an empty database

1. Stop the app.
2. Delete `database/coupon-deals.sqlite` (and the images in `uploads/` if you want).
3. Run the **first-run command** from step 3 again.

---

## 8. MySQL copy of the database (optional)

`database/coupon_deals.sql` is a MySQL version of the same tables and demo data, for viewing in
phpMyAdmin or HeidiSQL or for including in a report. **The app itself uses the SQLite file**
above, so you don't need this to run the project.

To import it with Laragon:

- **phpMyAdmin / HeidiSQL:** start MySQL in Laragon, then use *Import* and choose `database/coupon_deals.sql`.
- **Command line** (Laragon's MySQL `root` user has no password by default):

  ```
  mysql -u root < database\coupon_deals.sql
  ```

  Use `mysql -u root -p < database\coupon_deals.sql` if your root user has a password.

This creates a database called `coupon_deals`. Importing again wipes those tables and recreates them.

---

## 9. Other useful commands

| Task | PowerShell | cmd |
| --- | --- | --- |
| Run the automated tests | `.\mvnw test` | `mvnw test` |
| Build a single runnable file | `.\mvnw package` | `mvnw package` |
| Run that file | `java -jar target\coupon-deals-1.0.0.jar` | `java -jar target\coupon-deals-1.0.0.jar` |
| Run that file and create an admin | `java -jar target\coupon-deals-1.0.0.jar --admin-email=admin@example.com --admin-password=MyPassword123` | (same) |

Run the `.jar` from the project folder so it uses the same `database/` and `uploads/` folders.

### Changing the port or file locations

| Setting | Default | PowerShell | cmd |
| --- | --- | --- | --- |
| Port | `8080` | `$env:PORT="9090"` | `set PORT=9090` |
| Database file | `database/coupon-deals.sqlite` | `$env:DB_PATH="D:\data\deals.sqlite"` | `set DB_PATH=D:\data\deals.sqlite` |
| Upload folder | `uploads` | `$env:UPLOAD_DIR="D:\data\uploads"` | `set UPLOAD_DIR=D:\data\uploads` |

Set these in the same terminal, then start the app.

---

## 10. Problems

| Problem | Fix |
| --- | --- |
| `'java' is not recognized` or `JAVA_HOME is not set` | Install Java 21 (step 1), then close and reopen the terminal. |
| `Port 8080 was already in use` | Another program is using the port. Stop it, or use another port (step 9). |
| The login says the credentials don't match | Reset the password with the command in step 6. |
| No stores appear after first start | You started without `--seed`. Stop the app and start it once with `--seed`. |
| The pages look unstyled | Bootstrap is loaded from the internet, so check your connection. |
