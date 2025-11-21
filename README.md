# Sistem Berita Acara

![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue.svg)
![License](https://img.shields.io/badge/license-MIT-green.svg)

Aplikasi web untuk mengelola berita acara verifikasi nasabah dengan sistem approval berjenjang dan tanda tangan digital.

## 📋 Deskripsi

Sistem Berita Acara adalah aplikasi berbasis Laravel yang dirancang untuk mendigitalisasi proses pembuatan, verifikasi, dan approval berita acara nasabah. Aplikasi ini mendukung multi-level approval, tanda tangan digital, notifikasi real-time, dan export ke PDF.

## ✨ Fitur Utama

### 👥 Manajemen User & Role
- **Multi-level Role System**: CS, Group Head, Direktur Utama, Direktur, Admin
- **Profile Management**: Update profil, ubah password, upload tanda tangan digital
- **User Management** (Admin): CRUD user, reset password, toggle status aktif/non-aktif

### 📝 Manajemen Nasabah
- **Import Data Nasabah**: Upload data dari file Excel/CSV
- **Download Template**: Template Excel untuk import data
- **Pencarian & Filter**: Cari nasabah berdasarkan nama, KTP, NPWP
- **Status Tracking**: Lacak nasabah yang sudah/belum punya berita acara

### 📄 Manajemen Berita Acara
- **Pembuatan BA**: Form pembuatan berita acara dengan validasi lengkap
- **Watchlist & Existing Customer Check**: Verifikasi otomatis nasabah
- **Multi-level Approval Workflow**:
  - CS membuat BA
  - Group Head/Direktur/Direktur Utama melakukan approval
- **Tanda Tangan Digital**: Upload dan validasi TTD sebelum approval
- **Auto-numbering**: Penomoran BA otomatis
- **PDF Generation**: Export BA ke PDF dengan TTD terintegrasi
- **Status Tracking**: Pending, Approved, Rejected

### 🔔 Sistem Notifikasi
- **Real-time Notifications**: Notifikasi saat ada BA baru atau status berubah
- **Unread Badge**: Indikator notifikasi belum dibaca
- **Mark as Read**: Tandai notifikasi sudah dibaca
- **Mark All Read**: Tandai semua notifikasi dibaca sekaligus

### 📊 Dashboard & Reporting
- **Statistik Real-time**: Total nasabah, BA pending, approved, rejected
- **Recent Activities**: Aktivitas terbaru per user
- **Role-based Dashboard**: Dashboard disesuaikan dengan role user

## 🛠️ Teknologi Stack

- **Framework**: Laravel 10.x
- **PHP**: 8.1 atau lebih tinggi
- **Database**: MySQL 8.0+
- **Frontend**: 
  - Blade Templates
  - Bootstrap 5
  - Alpine.js
  - Font Awesome
- **PDF Generation**: mPDF
- **Import/Export**: Maatwebsite Excel
- **Notifications**: Laravel Notifications

## 📦 Persyaratan Sistem

- PHP >= 8.1
- Composer
- MySQL >= 8.0 atau MariaDB >= 10.3
- Node.js & NPM (untuk compile assets)
- Web Server (Apache/Nginx)
- PHP Extensions:
  - BCMath
  - Ctype
  - Fileinfo
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - Tokenizer
  - XML
  - GD (untuk manipulasi gambar TTD)

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/dkrmd1/sistem-berita-acara.git
cd sistem-berita-acara
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install NPM dependencies
npm install
```

### 3. Setup Environment

```bash
# Copy file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env` dan sesuaikan dengan konfigurasi database Anda:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_berita_acara
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrasi & Seeding Database

```bash
# Jalankan migrasi
php artisan migrate

# (Optional) Seed data dummy
php artisan db:seed
```

### 6. Setup Storage & Permissions

```bash
# Create symbolic link untuk storage
php artisan storage:link

# Set permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache
```

### 7. Compile Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 8. Jalankan Server

```bash
# Development server
php artisan serve

# Akses aplikasi di: http://localhost:8000
```

## 👤 Akun Default

Setelah seeding, gunakan akun berikut untuk login:

### Admin
```
Email: admin@example.com
Password: password
```

### Customer Service
```
Email: cs@example.com
Password: password
```

### Group Head
```
Email: grouphead@example.com
Password: password
```

### Direktur Utama
```
Email: dirut@example.com
Password: password
```

**⚠️ PENTING**: Ubah password default setelah login pertama kali!

## 📁 Struktur Folder Penting

```
sistem-berita-acara/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── BeritaAcaraController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── NasabahController.php
│   │   │   ├── NotificationController.php
│   │   │   ├── ProfileController.php
│   │   │   └── UserController.php
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Nasabah.php
│   │   └── BeritaAcara.php
│   └── Notifications/
│       └── NewBeritaAcaraNotification.php
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
│   └── storage/ (symbolic link)
├── resources/
│   ├── views/
│   │   ├── auth/
│   │   ├── berita-acara/
│   │   ├── dashboard/
│   │   ├── nasabah/
│   │   ├── profile/
│   │   └── users/
│   └── css/
├── routes/
│   └── web.php
├── storage/
│   └── app/
│       ├── public/
│       │   └── ttd/ (tanda tangan digital)
│       └── berita-acara/ (PDF files)
└── README.md
```

## 🔐 Role & Permission

| Role | Akses |
|------|-------|
| **Admin** | Kelola user, lihat semua data, TIDAK bisa buat/approve BA |
| **CS** | Buat BA, kelola nasabah, import data |
| **Group Head** | Approve/reject BA yang ditugaskan |
| **Direktur Utama** | Approve/reject BA yang ditugaskan |
| **Direktur** | Approve/reject BA yang ditugaskan |

## 📖 Penggunaan

### Workflow Pembuatan Berita Acara

1. **CS Login** → Upload TTD (jika belum)
2. **Import Nasabah** (jika belum ada data)
3. **Buat Berita Acara**:
   - Pilih nasabah dari daftar
   - Isi form verifikasi (watchlist, existing customer check)
   - Pilih approver
   - Submit
4. **Approver Login** → Lihat notifikasi BA baru
5. **Approver Upload TTD** (jika belum)
6. **Approve/Reject** BA
7. **PDF Otomatis Generate** dengan TTD terintegrasi
8. **CS & Approver** dapat download/print PDF

### Import Data Nasabah

1. Login sebagai CS
2. Menu **Nasabah** → **Import Data**
3. Download template Excel
4. Isi data nasabah sesuai template
5. Upload file
6. Sistem akan validasi dan import data

## 🔧 Konfigurasi Tambahan

### PDF Generation Settings

Edit file `config/pdf.php` untuk kustomisasi PDF:

```php
'format' => 'A4',
'orientation' => 'P',
'margin_left' => 10,
'margin_right' => 10,
'margin_top' => 10,
'margin_bottom' => 10,
```

### Email Notifications (Optional)

Untuk mengaktifkan email notifications, konfigurasi SMTP di `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Queue for Notifications (Recommended)

Untuk performa lebih baik, gunakan queue:

```bash
# Setup queue di .env
QUEUE_CONNECTION=database

# Jalankan queue worker
php artisan queue:work

# Atau gunakan supervisor (production)
```

## 🐛 Troubleshooting

### Error: "Class 'Mpdf\Mpdf' not found"

```bash
composer require mpdf/mpdf
```

### Error: Upload TTD Gagal

Pastikan folder `storage/app/public/ttd` memiliki permission write:

```bash
chmod -R 775 storage/app/public/ttd
```

### PDF Tidak Tampil

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Regenerate storage link
php artisan storage:link
```

### Error Import Excel

```bash
composer require maatwebsite/excel
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"
```

## 🧪 Testing

```bash
# Jalankan semua test
php artisan test

# Test spesifik
php artisan test --filter=BeritaAcaraTest
```

## 📝 Logging

Log aplikasi disimpan di `storage/logs/laravel.log`. Monitor log untuk debugging:

```bash
tail -f storage/logs/laravel.log
```

## 🚀 Deployment (Production)

### Setup untuk Production

```bash
# Set environment ke production
APP_ENV=production
APP_DEBUG=false

# Generate key baru
php artisan key:generate

# Optimize aplikasi
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set ownership & permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Web Server Configuration

#### Apache (.htaccess)

Sudah tersedia di folder `public/.htaccess`

#### Nginx

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/sistem-berita-acara/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🤝 Kontribusi

Kontribusi sangat diterima! Untuk berkontribusi:

1. Fork repository
2. Buat branch fitur (`git checkout -b fitur-baru`)
3. Commit perubahan (`git commit -m 'Menambahkan fitur baru'`)
4. Push ke branch (`git push origin fitur-baru`)
5. Buat Pull Request

## 📄 Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## 👨‍💻 Developer

- **GitHub**: [@dkrmd1](https://github.com/dkrmd1)
- **Repository**: [sistem-berita-acara](https://github.com/dkrmd1/sistem-berita-acara)

## 📞 Support

Jika menemukan bug atau memiliki saran:
- Buat [Issue](https://github.com/dkrmd1/sistem-berita-acara/issues)
- Email: [your-email@example.com]

## 🔄 Changelog

### Version 1.0.0 (2024)

#### Added
- Sistem login & autentikasi multi-role
- CRUD Berita Acara dengan workflow approval
- Upload & validasi tanda tangan digital
- PDF generation dengan TTD terintegrasi
- Import nasabah dari Excel/CSV
- Real-time notifications
- Dashboard dengan statistik
- Profile management
- User management (Admin)
- Audit logging

#### Features
- Multi-level approval system
- Auto-numbering berita acara
- Watchlist & existing customer check
- Export PDF dengan watermark
- Search & filter advanced
- Responsive design

---

⭐ **Jika proyek ini membantu, berikan star di GitHub!**

📖 **Dokumentasi lengkap**: [Wiki](https://github.com/dkrmd1/sistem-berita-acara/wiki)

🐛 **Report Bug**: [Issues](https://github.com/dkrmd1/sistem-berita-acara/issues)