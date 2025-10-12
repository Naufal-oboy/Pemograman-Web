-- Database: nutribox_db
CREATE DATABASE IF NOT EXISTS nutribox_db;
USE nutribox_db;


-- Tabel users untuk authentication
CREATE TABLE users (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel paket_catering untuk manajemen paket
CREATE TABLE paket_catering (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    nama_paket VARCHAR(100) NOT NULL,
    kategori ENUM('medical', 'weight', 'healthy', 'kids') NOT NULL,
    deskripsi TEXT,
    harga DECIMAL(10,2) NOT NULL,
    durasi INT(11) DEFAULT 7 COMMENT 'Durasi dalam hari',
    kalori INT(11),
    status ENUM('active', 'inactive') DEFAULT 'active',
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel pesanan untuk tracking orders
CREATE TABLE pesanan (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11),
    paket_id INT(11),
    nama_pemesan VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    phone VARCHAR(20) NOT NULL,
    alamat TEXT NOT NULL,
    tanggal_mulai DATE NOT NULL,
    jumlah_hari INT(11) NOT NULL,
    total_harga DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'processing', 'delivered', 'cancelled') DEFAULT 'pending',
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (paket_id) REFERENCES paket_catering(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel menu untuk daftar menu harian
CREATE TABLE menu (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    paket_id INT(11),
    nama_menu VARCHAR(150) NOT NULL,
    jenis_makanan ENUM('breakfast', 'lunch', 'dinner', 'snack') NOT NULL,
    kalori INT(11),
    protein DECIMAL(5,2),
    karbohidrat DECIMAL(5,2),
    lemak DECIMAL(5,2),
    deskripsi TEXT,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paket_id) REFERENCES paket_catering(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabel testimoni untuk customer reviews
CREATE TABLE testimoni (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    user_id INT(11),
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    rating INT(1) CHECK (rating >= 1 AND rating <= 5),
    komentar TEXT NOT NULL,
    paket_id INT(11),
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (paket_id) REFERENCES paket_catering(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Insert default user (admin)
INSERT INTO users (username, password, email, full_name, phone) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@nutribox.com', 'Administrator', '08123456789');
-- Password: admin123

-- Insert sample paket_catering
INSERT INTO paket_catering (nama_paket, kategori, deskripsi, harga, durasi, kalori, gambar) VALUES
('Medical Package - Diabetes', 'medical', 'Paket khusus untuk penderita diabetes dengan menu rendah gula dan karbohidrat terkontrol', 850000, 14, 1500, 'project/medical.jpeg'),
('Weight Loss Premium', 'weight', 'Program diet intensif untuk menurunkan berat badan hingga 3kg dalam 2 minggu', 950000, 14, 1200, 'project/weight.jpeg'),
('Healthy Balance 7 Days', 'healthy', 'Paket makanan sehat seimbang untuk gaya hidup aktif', 450000, 7, 1800, 'project/healthy.jpeg'),
('Kids Nutrition Plan', 'kids', 'Menu bergizi untuk anak usia 1-9 tahun dengan presentasi menarik', 550000, 14, 1600, 'project/kids.jpeg');

-- Insert sample menu
INSERT INTO menu (paket_id, nama_menu, jenis_makanan, kalori, protein, karbohidrat, lemak, deskripsi) VALUES
(1, 'Oatmeal dengan Buah Segar', 'breakfast', 350, 12.5, 55.0, 8.0, 'Oatmeal hangat dengan topping strawberry, blueberry, dan kacang almond'),
(1, 'Ayam Panggang dengan Quinoa', 'lunch', 500, 35.0, 45.0, 15.0, 'Dada ayam panggang dengan quinoa dan sayuran kukus'),
(2, 'Smoothie Bowl Protein', 'breakfast', 300, 20.0, 35.0, 8.0, 'Smoothie bowl dengan greek yogurt, pisang, dan granola'),
(2, 'Salmon Teriyaki', 'dinner', 450, 38.0, 25.0, 18.0, 'Salmon panggang dengan saus teriyaki rendah sodium'),
(3, 'Nasi Merah dengan Tempe Bacem', 'lunch', 550, 22.0, 65.0, 18.0, 'Nasi merah, tempe bacem, sayur lodeh, dan lalapan'),
(4, 'Chicken Nugget Homemade', 'lunch', 400, 25.0, 35.0, 15.0, 'Nugget ayam homemade dengan kentang goreng dan wortel');

-- Insert sample pesanan
INSERT INTO pesanan (user_id, paket_id, nama_pemesan, email, phone, alamat, tanggal_mulai, jumlah_hari, total_harga, status) VALUES
(1, 1, 'John Doe', 'john@example.com', '081234567890', 'Jl. Sudirman No. 123, Jakarta', '2025-10-15', 14, 850000, 'confirmed'),
(1, 3, 'Jane Smith', 'jane@example.com', '081298765432', 'Jl. Thamrin No. 45, Jakarta', '2025-10-20', 7, 450000, 'pending');

-- Insert sample testimoni
INSERT INTO testimoni (user_id, nama, email, rating, komentar, paket_id, status) VALUES
(1, 'Sarah Johnson', 'sarah@example.com', 5, 'Sangat puas dengan pelayanan dan kualitas makanan! Berat badan turun 4kg dalam 2 minggu.', 2, 'approved'),
(NULL, 'Budi Santoso', 'budi@example.com', 4, 'Menu enak dan bervariasi. Pengiriman selalu tepat waktu.', 3, 'approved');