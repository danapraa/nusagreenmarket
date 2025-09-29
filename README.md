<p align="center"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></p>

<p align="center">
<a href="https://github.com/danapraa/nusagreenmarket/actions"><img src="https://github.com/danapraa/nusagreenmarket/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Laravel Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# NusaGreenMarket 🌿

NusaGreenMarket adalah platform penjualan sayur-sayuran dan buah-buahan yang dipetik langsung dari kebun untuk menjamin kesegaran maksimal.

## Installation

### Prerequisites

Pastikan Anda memiliki requirement berikut sebelum memulai instalasi:

- PHP 8.1 or later
- Composer
- Node.js 18.x or later (recommended to use Node.js 20.x or later)
- MySQL 5.7+ or PostgreSQL
- Git

### Cloning the Repository

Clone repository menggunakan command berikut:

```bash
git clone https://github.com/danapraa/nusagreenmarket.git
```

> **Windows Users:** letakkan repository dekat dengan root drive Anda jika mengalami masalah saat cloning.

1. Masuk ke direktori project:

```bash
cd nusagreenmarket
```

2. Install PHP dependencies:

```bash
composer install
```

> Gunakan flag `--ignore-platform-reqs` jika mengalami error platform requirement saat instalasi.

3. Install Node.js dependencies:

```bash
npm install
# atau
yarn install
```

> Gunakan flag `--legacy-peer-deps` jika mengalami error peer-dependency saat instalasi.

4. Buat file environment:

```bash
cp .env.example .env
```

5. Generate application key:

```bash
php artisan key:generate
```

6. Konfigurasi database di file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nusagreenmarket
DB_USERNAME=root
DB_PASSWORD=
```

7. Jalankan database migrations:

```bash
php artisan migrate
```

8. Seed database dengan data sample (optional):

```bash
php artisan db:seed
```

9. Buat symbolic link untuk storage:

```bash
php artisan storage:link
```

10. Build frontend assets:

```bash
npm run build
# atau untuk development
npm run dev
```

11. Jalankan development server:

```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## Running in Development Mode

Untuk menjalankan Laravel dan Vite development server secara bersamaan:

```bash
# Terminal 1 - Laravel server
php artisan serve

# Terminal 2 - Vite dev server
npm run dev
```

## Default Login Credentials

Setelah seeding database, Anda dapat login dengan kredensial berikut:

**Admin:**
- Email: admin@nusagreenmarket.com
- Password: password

**Seller:**
- Email: seller@nusagreenmarket.com
- Password: password

**Customer:**
- Email: customer@nusagreenmarket.com
- Password: password

## License

Project ini adalah open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Credits

- [Laravel](https://laravel.com)
- [Tailwind CSS](https://tailwindcss.com)
- [All Contributors](../../contributors)


Made with 💚 for a greener Indonesia
