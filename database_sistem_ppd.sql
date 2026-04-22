-- =====================================================
-- DATABASE: sistem_ppd
-- Created: April 2026
-- =====================================================

CREATE DATABASE IF NOT EXISTS sistem_ppd;
USE sistem_ppd;

-- =====================================================
-- TABLE: users (Pengguna)
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    nama VARCHAR(150) NOT NULL,
    ic_number VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'guru', 'murid', 'kakitangan ppd') NOT NULL,
    status_akaun ENUM('menunggu', 'aktif', 'tidak_aktif') DEFAULT 'menunggu',
    tarikh_daftar DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_ic (ic_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLE: buku (Buku/ Bahan)
-- =====================================================
CREATE TABLE IF NOT EXISTS buku (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tajuk VARCHAR(255) NOT NULL,
    isbn VARCHAR(20),
    jenis_bahan VARCHAR(50),
    pengarang VARCHAR(150),
    penerbit VARCHAR(100),
    muka_surat INT,
    harga DECIMAL(10,2),
    no_pesanan VARCHAR(50),
    tarikh DATE,
    status ENUM('aktif', 'dipadam') DEFAULT 'aktif',
    tarikh_cipta DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_tajuk (tajuk),
    INDEX idx_isbn (isbn)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABLE: rekod_bacaan (Rekod Bacaan)
-- =====================================================
CREATE TABLE IF NOT EXISTS rekod_bacaan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_buku INT NOT NULL,
    nama_staf VARCHAR(150) NOT NULL,
    tarikh_baca DATE NOT NULL,
    bil_muka_surat INT NOT NULL,
    status ENUM('sedang_dibaca', 'selesai', 'belum_selesai') DEFAULT 'sedang_dibaca',
    tarikh_cipta DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_buku) REFERENCES buku(id) ON DELETE CASCADE,
    INDEX idx_id_buku (id_buku),
    INDEX idx_tarikh (tarikh_baca)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- DEFAULT ADMIN ACCOUNT
-- Username: admin
-- Password: admin123
-- =====================================================
INSERT INTO users (username, nama, ic_number, password, role, status_akaun)
VALUES ('admin', 'Admin PPD', '123456789012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'aktif');