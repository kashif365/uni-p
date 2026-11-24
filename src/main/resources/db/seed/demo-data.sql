-- Demo content: fictional stores and coupon codes (sample data, not real offers).
-- Loaded when the app starts with --seed and the stores table is empty.

INSERT INTO settings (key, value, label, sort_order, created_at, updated_at) VALUES
('site_name', 'Coupon Deals', 'Site name', 1, datetime('now'), datetime('now')),
('site_tagline', 'Save More, Shop Smart', 'Tagline', 2, datetime('now'), datetime('now')),
('home_heading', 'Save Big with Exclusive Coupon Codes', 'Home page heading', 3, datetime('now'), datetime('now')),
('home_subheading', 'Discover verified coupon codes and discounts from your favourite stores.', 'Home page subheading', 4, datetime('now'), datetime('now')),
('primary_color', '#4a0c98', 'Primary colour', 5, datetime('now'), datetime('now')),
('contact_email', 'support@example.invalid', 'Contact email', 6, datetime('now'), datetime('now'));

INSERT INTO categories (id, name, slug, featured, sort_order, created_at, updated_at) VALUES
(1, 'Fashion', 'fashion', 1, 0, datetime('now'), datetime('now')),
(2, 'Electronics', 'electronics', 1, 1, datetime('now'), datetime('now')),
(3, 'Food & Drink', 'food-drink', 1, 2, datetime('now'), datetime('now')),
(4, 'Travel', 'travel', 1, 3, datetime('now'), datetime('now')),
(5, 'Home & Garden', 'home-garden', 1, 4, datetime('now'), datetime('now')),
(6, 'Health & Beauty', 'health-beauty', 1, 5, datetime('now'), datetime('now'));

INSERT INTO stores (id, name, slug, description, featured, sort_order, created_at, updated_at) VALUES
(1, 'Urban Threads', 'urban-threads', 'Everyday clothing, shoes and accessories.', 1, 0, datetime('now'), datetime('now')),
(2, 'Pixel Point', 'pixel-point', 'Headphones, laptops, phones and smart-home gadgets.', 1, 1, datetime('now'), datetime('now')),
(3, 'Fresh Fork', 'fresh-fork', 'Takeaway and grocery delivery from local kitchens.', 1, 2, datetime('now'), datetime('now')),
(4, 'Wander Trips', 'wander-trips', 'Hotels, flights and city breaks.', 1, 3, datetime('now'), datetime('now')),
(5, 'Nest & Bloom', 'nest-bloom', 'Furniture, homeware and garden essentials.', 1, 4, datetime('now'), datetime('now')),
(6, 'Glow Lab', 'glow-lab', 'Skincare, haircare and wellness products.', 1, 5, datetime('now'), datetime('now'));

INSERT INTO category_store (store_id, category_id) VALUES (1, 1), (2, 2), (3, 3), (4, 4), (5, 5), (6, 6);

INSERT INTO coupons (store_id, title, code, description, terms, featured, verified, sort_order, created_at, updated_at) VALUES
(1, '20% off new arrivals', 'URBAN20', 'Save on the latest clothing and shoe collections.', 'Sample coupon. Minimum spend 30. One use per customer.', 1, 1, 0, datetime('now'), datetime('now')),
(2, '15% off headphones', 'PIXEL15', 'Discount on wired and wireless headphones.', 'Sample coupon. Excludes gift cards.', 1, 1, 1, datetime('now'), datetime('now')),
(3, 'Free delivery on your first order', 'FORKFREE', 'No delivery fee on your first takeaway or grocery order.', 'Sample coupon. New customers only.', 1, 1, 2, datetime('now'), datetime('now')),
(4, '10% off hotel bookings', 'WANDER10', 'Save on hotel stays booked at least 7 days ahead.', 'Sample coupon. Subject to availability.', 1, 1, 3, datetime('now'), datetime('now')),
(5, '25% off garden furniture', 'NEST25', 'Discount on outdoor tables, chairs and loungers.', 'Sample coupon. While stocks last.', 1, 1, 4, datetime('now'), datetime('now')),
(6, 'Buy one, get one half price', 'GLOWBOGO', 'Second skincare item at half price.', 'Sample coupon. Cheapest item discounted.', 1, 1, 5, datetime('now'), datetime('now'));

INSERT INTO blog_categories (id, name, slug, created_at, updated_at) VALUES
(1, 'Saving Tips', 'saving-tips', datetime('now'), datetime('now'));

INSERT INTO blog_posts (category_id, title, slug, excerpt, content, published, created_at, updated_at) VALUES
(1, 'Five ways to save more when you shop online', 'five-ways-to-save-more-when-you-shop-online',
 'Simple habits that make every online order cheaper.',
 '<p>Search for a coupon code before you check out, compare prices across stores and sign up for price-drop alerts.</p><p>Wait for seasonal sales on big purchases, and combine free-delivery offers with discount codes where a store allows it.</p>',
 1, datetime('now'), datetime('now'));
