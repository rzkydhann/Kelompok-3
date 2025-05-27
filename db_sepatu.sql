CREATE DATABASE db_sepatu;
USE db_sepatu_;

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    layanan VARCHAR(100),
    alamat TEXT,
    wa VARCHAR(20),
    jenis_sepatu VARCHAR(100),
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
