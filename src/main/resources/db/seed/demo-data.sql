-- Demo content: fictional stores and coupon codes for the university project.
-- Loaded when the app starts with --seed and the stores table is empty.

INSERT INTO settings (key, value, label, sort_order, created_at, updated_at) VALUES
('site_name', 'Campus Deals', 'Site name', 1, datetime('now'), datetime('now')),
('site_tagline', 'Save More, Shop Smart', 'Tagline', 2, datetime('now'), datetime('now')),
('home_heading', 'Save Big with Exclusive Coupon Codes', 'Home page heading', 3, datetime('now'), datetime('now')),
('home_subheading', 'Discover verified student discount codes from your favourite campus brands.', 'Home page subheading', 4, datetime('now'), datetime('now')),
('primary_color', '#4a0c98', 'Primary colour', 5, datetime('now'), datetime('now')),
('contact_email', 'support@example.invalid', 'Contact email', 6, datetime('now'), datetime('now'));

INSERT INTO categories (id, name, slug, featured, sort_order, created_at, updated_at) VALUES
(1, 'Books & Stationery', 'books-stationery', 1, 0, datetime('now'), datetime('now')),
(2, 'Technology', 'technology', 1, 1, datetime('now'), datetime('now')),
(3, 'Food & Drink', 'food-drink', 1, 2, datetime('now'), datetime('now')),
(4, 'Travel', 'travel', 1, 3, datetime('now'), datetime('now')),
(5, 'Sports & Fitness', 'sports-fitness', 1, 4, datetime('now'), datetime('now')),
(6, 'Arts & Crafts', 'arts-crafts', 1, 5, datetime('now'), datetime('now'));

INSERT INTO stores (id, name, slug, description, featured, sort_order, created_at, updated_at) VALUES
(1, 'Campus Book Nook', 'campus-book-nook', 'Fictional store for the Campus Deals university demo.', 1, 0, datetime('now'), datetime('now')),
(2, 'Study Byte', 'study-byte', 'Fictional store for the Campus Deals university demo.', 1, 1, datetime('now'), datetime('now')),
(3, 'Quad Cafe', 'quad-cafe', 'Fictional store for the Campus Deals university demo.', 1, 2, datetime('now'), datetime('now')),
(4, 'Semester Shuttle', 'semester-shuttle', 'Fictional store for the Campus Deals university demo.', 1, 3, datetime('now'), datetime('now')),
(5, 'Campus Active', 'campus-active', 'Fictional store for the Campus Deals university demo.', 1, 4, datetime('now'), datetime('now')),
(6, 'Studio Corner', 'studio-corner', 'Fictional store for the Campus Deals university demo.', 1, 5, datetime('now'), datetime('now'));

INSERT INTO category_store (store_id, category_id) VALUES (1, 1), (2, 2), (3, 3), (4, 4), (5, 5), (6, 6);

INSERT INTO coupons (store_id, title, code, description, terms, featured, verified, sort_order, created_at, updated_at) VALUES
(1, '15% off notebooks', 'DEMOBOOK15', 'Sample offer for demonstrating coupon search and redemption.', 'Demonstration only. This code cannot be redeemed at a real store.', 1, 1, 0, datetime('now'), datetime('now')),
(2, '20% off laptop sleeves', 'DEMOBYTE20', 'Sample offer for demonstrating coupon search and redemption.', 'Demonstration only. This code cannot be redeemed at a real store.', 1, 1, 1, datetime('now'), datetime('now')),
(3, '10% off a study snack', 'DEMOQUAD10', 'Sample offer for demonstrating coupon search and redemption.', 'Demonstration only. This code cannot be redeemed at a real store.', 1, 1, 2, datetime('now'), datetime('now')),
(4, '25% off a day pass', 'DEMORIDE25', 'Sample offer for demonstrating coupon search and redemption.', 'Demonstration only. This code cannot be redeemed at a real store.', 1, 1, 3, datetime('now'), datetime('now')),
(5, '15% off training gear', 'DEMOMOVE15', 'Sample offer for demonstrating coupon search and redemption.', 'Demonstration only. This code cannot be redeemed at a real store.', 1, 1, 4, datetime('now'), datetime('now')),
(6, '20% off art supplies', 'DEMOART20', 'Sample offer for demonstrating coupon search and redemption.', 'Demonstration only. This code cannot be redeemed at a real store.', 1, 1, 5, datetime('now'), datetime('now'));

INSERT INTO blog_categories (id, name, slug, created_at, updated_at) VALUES
(1, 'Saving Tips', 'saving-tips', datetime('now'), datetime('now'));

INSERT INTO blog_posts (category_id, title, slug, excerpt, content, published, created_at, updated_at) VALUES
(1, 'Five ways to save money as a student', 'five-ways-to-save-money-as-a-student',
 'Simple habits that make your student budget go further.',
 '<p>Plan your weekly shop, compare prices before you buy, and always check for a student discount code before paying.</p><p>Small savings on everyday purchases add up quickly over a semester.</p>',
 1, datetime('now'), datetime('now'));
