# Sistem PPD - Dokumentasi Projek

## 1. Overview Projek

**Sistem PPD** (Sistem Pejabat Pendidikan Daerah) adalah aplikasi web berasaskan PHP dan MySQL yang direka untuk menguruskan:

- **Pengurusan Pengguna** - Pendaftaran dan pengesahan staf (guru, murid, kakitangan PPD)
- **Perolehan Buku** - Rekod pembelian dan management buku
- **Bacaan Buku** - Tracking pembacaan buku oleh pengguna
- **Analisis Data** - Analisis perolehan dan bacaan

---

## 2. Senarai Fail & Fungsi

| Fail | Fungsi |
|------|--------|
| `loginppd.php` | Halaman login pengguna (username + IC number + password) |
| `register.php` | Pendaftaran akaun baharu (guru/murid/kakitangan) |
| `dashboard.php` | Menu utama selepas login (berbeza mengikut role) |
| `sahkan_staf.php` | Pengesahan akaun oleh admin |
| `perolehan.php` | Masukkan rekod perolehan buku (admin only) |
| `senarai_perolehan.php` | Senarai buku yang diperolehi |
| `baca_buku.php` | Baca buku (viewer halaman) |
| `rekod_bacaan.php` | Rekod bacaan pengguna |
| `rujukan_buku.php` | Rujukan/katalog buku |
| `analisis.php` | Analisis data perolehan & bacaan |
| `config.php` | Sambungan database MySQL |
| `logout.php` | Logout dan destroy session |

---

## 3. Peranan Pengguna (Role)

| Peranan | Akses |
|---------|-------|
| **admin** | Semua fungsi + pengesahan akaun |
| **guru** | Dashboard, baca buku, lihat rekod |
| **murid** | Dashboard, baca buku, lihat rekod |
| **kakitangan ppd** | Dashboard, perolehan, analisis |

---

## 4. Aliran Sistem (System Flow)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           SYSTEM FLOW - SISTEM PPD                         │
└─────────────────────────────────────────────────────────────────────────────┘

    ┌──────────┐     ┌──────────────┐     ┌─────────────┐
    │  Visitor │────▶│ register.php │────▶│   Database  │
    └──────────┘     └──────────────┘     └─────────────┘
         │                                        │
         │           ┌──────────────┐             │
         └──────────▶│ loginppd.php │◀────────────┘
                     └──────────────┘
                          │
                          ▼
                ┌─────────────────┐
                │  Status Akaun   │
                └─────────────────┘
                      │         │
                 [aktif]     [menunggu]
                      │         │
                      ▼         ▼
              ┌───────────┐   ┌──────────────┐
              │Dashboard  │   │  sahkan_staf │
              │  (menu)   │   │   (admin)    │
              └───────────┘   └──────────────┘
                   │
     ┌─────────────┼─────────────┬──────────────┐
     ▼             ▼             ▼              ▼
┌─────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐
│Perolehan│  │Baca Buku │  │ Rekod    │  │ Analisis │
│  (add)  │  │ (read)   │  │Bacaan    │  │  (data)  │
└─────────┘  └──────────┘  └──────────┘  └──────────┘
     │             │             │              │
     └─────────────┴─────────────┴──────────────┘
                          │
                          ▼
                   ┌────────────┐
                   │  Logout    │
                   └────────────┘


┌─────────────────────────────────────────────────────────────────────────────┐
│                           DETAIL FLOW (STEP BY STEP)                       │
└─────────────────────────────────────────────────────────────────────────────┘

STEP 1: PENDAFTARAN
──────────────────────────────────────────
[Visitor] → [register.php]
  │- Isi: username, nama, IC number, password, role
  │- Validate: semua medan wajib, password min 6 aksara
  │- Simpan ke table 'users' dengan status_akaun = 'menunggu'
  ▼
[Database: users table]


STEP 2: LOGIN
──────────────────────────────────────────
[User] → [loginppd.php]
  │- Masukkan: username, IC number, password
  │- Validate: padanan username + IC + password (bcrypt)
  │- Semak status_akaun: must be 'aktif'
  ▼
[Session: username, role] → [dashboard.php]


STEP 3: PENGESAHAN (ADMIN SAHAJA)
──────────────────────────────────────────
[Admin] → [sahkan_staf.php]
  │- Lihat senarai users dengan status 'menunggu'
  │- Tukar status kepada 'aktif' atau 'tolak'
  ▼
[Update: users table status_akaun]


STEP 4: PEROLEHAN BUKU (ADMIN/KAKITANGAN)
──────────────────────────────────────────
[Admin/Kakitangan] → [perolehan.php]
  │- Isi: tajuk, ISBN, jenis, pengarang, penerbit, 
  │        muka surat, harga, no pesanan, tarikh
  │- Simpan ke table 'buku'
  ▼
[Database: buku table]


STEP 5: BACA BUKU
──────────────────────────────────────────
[User] → [baca_buku.php?id=X]
  │- Pilih buku dari senarai
  │- Baca halaman demi halaman
  │- Sistem rekodkan ke table 'buku_halaman'
  ▼
[Database: buku_halaman table]


STEP 6: REKOD BACAAN
──────────────────────────────────────────
[User] → [rekod_bacaan.php]
  │- Lihat sejarah bacaan sendiri
  │- Paparkan: tajuk, tarikh, halaman dibaca
  ▼
[Query: buku_halaman + buku]


STEP 7: ANALISIS
──────────────────────────────────────────
[Admin] → [analisis.php]
  │- Paparkan statistik:
  │  - Bilangan buku diperolehi
  │  - Bilangan pengguna
  │  - Analisis bacaan
  ▼
[Aggregate queries from database]
```

---

## 5. Struktur Database (Ringkas)

### Table: `users`
| Column | Type | Description |
|--------|------|-------------|
| id | INT | Primary key |
| username | VARCHAR | Unique username |
| nama | VARCHAR | Full name |
| ic_number | VARCHAR | IC number (unique) |
| password | VARCHAR | Bcrypt hashed |
| role | ENUM | guru/murid/kakitangan ppd/admin |
| status_akaun | ENUM | aktif/menunggu |

### Table: `buku`
| Column | Type |
|--------|------|
| id | INT |
| tajuk | VARCHAR |
| isbn | VARCHAR |
| jenis_bahan | VARCHAR |
| pengarang | VARCHAR |
| penerbit | VARCHAR |
| muka_surat | INT |
| harga | DECIMAL |
| no_pesanan | VARCHAR |
| tarikh | DATE |

### Table: `buku_halaman`
| Column | Type |
|--------|------|
| id | INT |
| id_buku | INT |
| no_halaman | INT |
| username | VARCHAR |
| tarikh_baca | DATETIME |

---

## 6. Teknologi Used

- **Backend**: PHP (native)
- **Database**: MySQL (via phpMyAdmin/XAMPP)
- **Frontend**: HTML, CSS (custom styling)
- **Security**: password_hash/password_verify, mysqli_prepare
- **Server**: Apache (XAMPP)

---

## 7. Nota

- Sistem menggunakan session untuk authentication
- Password disimpan dalam format bcrypt
- Login memerlukan username + IC number + password
- Admin perlu manually activate akaun baharu
- Setiap halaman ada protection: `if (!isset($_SESSION['username']))`