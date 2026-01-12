CREATE DATABASE IF NOT EXISTS ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE ecommerce_db;

DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  image VARCHAR(255) DEFAULT NULL,
  category_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(60) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categories (name) VALUES
('Téléphones'),
('PC Portables'),
('Accessoires');

INSERT INTO products (name, description, price, image, category_id) VALUES
('Smartphone A', 'Bon rapport qualité/prix', 599.00, 'placeholder.png', 1),
('Smartphone B', 'Très bon appareil photo', 799.00, 'placeholder.png', 1),
('Laptop X', 'Ultrabook léger', 1200.00, 'placeholder.png', 2),
('Laptop Y', 'Gamer performant', 1500.00, 'placeholder.png', 2),
('Casque Audio', 'Réduction de bruit', 99.90, 'placeholder.png', 3),
('Souris', 'Souris ergonomique', 25.00, 'placeholder.png', 3);
