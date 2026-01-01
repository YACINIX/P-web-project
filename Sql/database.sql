CREATE DATABASE IF NOT EXISTS ecommerce_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE ecommerce_db;

DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  category_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

INSERT INTO categories (name) VALUES
('Téléphones'),
('PC Portables'),
('Accessoires');

INSERT INTO products (name, description, price, category_id) VALUES
('Smartphone A', 'Bon rapport qualité/prix', 599.00, 1),
('Smartphone B', 'Très bon appareil photo', 799.00, 1),
('Laptop X', 'Ultrabook léger', 1200.00, 2),
('Laptop Y', 'Gamer performant', 1500.00, 2),
('Casque Audio', 'Réduction de bruit', 99.90, 3),
('Souris', 'Souris ergonomique', 25.00, 3);
