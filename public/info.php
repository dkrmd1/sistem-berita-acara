<?php phpinfo(); ?>
```

Akses: `http://localhost/berita-acara/public/info.php`

Cari "gd" di halaman tersebut. Jika **TIDAK ADA**, berarti GD memang tidak aktif di web server.

### 2️⃣ **Enable GD di php.ini yang Benar**

Di halaman phpinfo tadi, lihat baris:
```
Loaded Configuration File    C:\xampp\php\php.ini