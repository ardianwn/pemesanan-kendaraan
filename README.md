# Aplikasi Pemesanan Kendaraan

Aplikasi untuk mengelola pemesanan kendaraan dengan fitur persetujuan berjenjang dan management kendaraan.

## Teknologi yang Digunakan

- **PHP Version**: 8.2.12
- **Framework**: Laravel 12.14.1
- **Database**: MySQL 8.0
- **Frontend**: Blade Templates, TailwindCSS

## Fitur Utama

- Multi-level user management (admin, approver, user)
- Pemesanan kendaraan dengan driver
- Persetujuan berjenjang (minimal 2 level)
- Dashboard dengan grafik statistik
- Ekspor data dalam format Excel
- Sistem logging dan audit trail

## Informasi Login

### Admin
- Username: admin@example.com
- Password: password123

### Approver 1
- Username: approver1@example.com
- Password: password123

### Approver 2
- Username: approver2@example.com
- Password: password123

### User Biasa
- Username: user@example.com
- Password: password123

## Instalasi

1. Clone repositori ini
   ```bash
   git clone https://github.com/username/pemesanan-kendaraan.git
   cd pemesanan-kendaraan
   ```

2. Install dependensi
   ```bash
   composer install
   npm install
   ```

3. Salin file .env.example menjadi .env dan konfigurasi database
   ```bash
   cp .env.example .env
   ```

4. Generate application key
   ```bash
   php artisan key:generate
   ```

5. Jalankan migrasi dan seeder
   ```bash
   php artisan migrate --seed
   ```

6. Build assets
   ```bash
   npm run dev
   ```

7. Jalankan server
   ```bash
   php artisan serve
   ```

## Panduan Penggunaan

### Admin
1. Login sebagai admin
2. Pada dashboard admin, Anda dapat melihat statistik dan ringkasan pemesanan
3. Kelola kendaraan melalui menu "Kendaraan"
4. Kelola driver melalui menu "Driver"
5. Kelola pengguna melalui menu "Users"
6. Buat pemesanan baru melalui menu "Pemesanan"
7. Pada saat membuat pemesanan, Anda dapat memilih minimal 2 approver secara berurutan
8. Ekspor data pemesanan melalui menu "Export"

### Approver
1. Login sebagai approver
2. Pada dashboard approver, Anda dapat melihat pemesanan yang menunggu persetujuan
3. Klik pada pemesanan untuk melihat detailnya
4. Setujui atau tolak pemesanan dengan memberikan catatan
5. Jika Anda adalah approver tingkat pertama, persetujuan akan dilanjutkan ke approver berikutnya
6. Jika Anda adalah approver terakhir, pemesanan akan berubah status menjadi "disetujui"

### User Biasa
1. Login sebagai user biasa
2. Pada dashboard, Anda dapat melihat pemesanan Anda
3. Buat pemesanan baru melalui menu "Pemesanan"
4. Pantau status pemesanan Anda

## Struktur Database

Aplikasi ini menggunakan beberapa tabel utama:
- `users`: Menyimpan data pengguna (admin, approver, user biasa)
- `kendaraan`: Menyimpan data kendaraan yang tersedia
- `drivers`: Menyimpan data driver
- `pemesanans`: Menyimpan data pemesanan
- `persetujuans`: Menyimpan data persetujuan berjenjang
- `log_aplikasi`: Menyimpan log aktivitas

## Kontribusi

Silakan buat issue atau pull request untuk kontribusi.

## Lisensi

Copyright © 2025. Hak cipta dilindungi undang-undang.
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
