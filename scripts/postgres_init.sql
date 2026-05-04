DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS product;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS account;

CREATE TABLE account (
  id SERIAL PRIMARY KEY,
  name varchar(45) DEFAULT NULL,
  email varchar(45) DEFAULT NULL,
  password varchar(45) DEFAULT NULL,
  profile varchar(10) NOT NULL
);

INSERT INTO account (id, name, email, password, profile) VALUES
(1, 'admin', 'admin@test.com', '21232f297a57a5a743894a0e4a801fc3', 'admin'),
(2, 'user', 'user@test.com', 'ee11cbb19052e40b07aac0ca060c23ee', 'user');
SELECT setval('account_id_seq', (SELECT MAX(id) FROM account));

CREATE TABLE cart (
  id SERIAL PRIMARY KEY,
  user_id int NOT NULL,
  product_id int NOT NULL,
  quantity int DEFAULT 1,
  added_at timestamp DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO cart (id, user_id, product_id, quantity, added_at) VALUES
(10, 2, 2, 1, '2026-02-23 17:03:48'),
(11, 2, 3, 2, '2026-02-23 17:03:49'),
(12, 2, 4, 1, '2026-02-23 17:03:50');
SELECT setval('cart_id_seq', (SELECT MAX(id) FROM cart));

CREATE TABLE category (
  id SERIAL PRIMARY KEY,
  label varchar(10) NOT NULL
);

INSERT INTO category (id, label) VALUES
(1, 'category 1'),
(2, 'category 2');
SELECT setval('category_id_seq', (SELECT MAX(id) FROM category));

CREATE TABLE product (
  id SERIAL PRIMARY KEY,
  label varchar(200) NOT NULL,
  price decimal(10,2) NOT NULL DEFAULT 0.00,
  id_category int NOT NULL,
  description text
);

INSERT INTO product (id, label, price, id_category, description) VALUES
(1, 'product 1', 10.00, 1, NULL),
(2, 'product 2', 20.00, 1, NULL),
(3, 'product 3', 15.00, 2, NULL),
(4, 'product 4', 5.50, 2, NULL),
(5, 'product 5', 2.30, 2, NULL);
SELECT setval('product_id_seq', (SELECT MAX(id) FROM product));
