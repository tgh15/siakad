<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind">
</p>

<h1 align="center">🎓 SIAKAD</h1>
<h3 align="center">Sistem Informasi Akademik Modern</h3>

<p align="center">
  <strong>Production-grade academic information system built with Laravel 12</strong>
</p>

<p align="center">
  <a href="#-features">Features</a> •
  <a href="#-tech-stack">Tech Stack</a> •
  <a href="#-installation">Installation</a> •
  <a href="#-screenshots">Screenshots</a> •
  <a href="#-architecture">Architecture</a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Production_Ready-95%25-success?style=flat-square" alt="Production Ready">
  <img src="https://img.shields.io/badge/Tests-30+-blue?style=flat-square" alt="Tests">
  <img src="https://img.shields.io/badge/License-MIT-green?style=flat-square" alt="License">
</p>

---

## 🚀 Overview

**SIAKAD** adalah sistem informasi akademik lengkap yang dirancang untuk mengelola seluruh proses akademik universitas. Dibangun dengan arsitektur **production-grade**, sistem ini siap digunakan untuk ratusan pengguna secara bersamaan.

### ✨ Why SIAKAD?

-   🔐 **Enterprise Security** - Rate limiting, security headers, CSRF protection
-   ⚡ **High Performance** - Query caching, eager loading, optimized queries
-   🧪 **Fully Tested** - 30+ automated tests with CI/CD pipeline
-   📱 **Responsive Design** - Beautiful UI dengan dark mode support
-   🏗️ **Clean Architecture** - Service layer, proper separation of concerns

---

## 🎯 Features

### 👨‍💼 Admin Panel

| Feature            | Description                                  |
| ------------------ | -------------------------------------------- |
| 📊 Dashboard       | Overview statistik akademik                  |
| 🏫 Master Data     | Fakultas, Prodi, Mata Kuliah, Kelas, Ruangan |
| 👥 User Management | Kelola Mahasiswa & Dosen                     |
| ✅ KRS Approval    | Approve/reject pengisian KRS                 |
| 📚 Skripsi & KP    | Monitoring tugas akhir                       |

### 👨‍🏫 Dosen Portal

| Feature                 | Description                            |
| ----------------------- | -------------------------------------- |
| 📈 Dashboard            | Statistik bimbingan & mengajar         |
| ✏️ Input Nilai          | Penilaian dengan auto grade conversion |
| 📋 Presensi             | Rekap kehadiran per pertemuan          |
| 👨‍🎓 Bimbingan PA         | Kelola mahasiswa perwalian             |
| 📖 Bimbingan Skripsi/KP | Logbook & progress tracking            |

### 👨‍🎓 Mahasiswa Portal

| Feature            | Description                       |
| ------------------ | --------------------------------- |
| 🏠 Dashboard       | Overview akademik pribadi         |
| 📝 KRS             | Pengisian KRS dengan validasi SKS |
| 📅 Jadwal          | Jadwal kuliah mingguan            |
| ✅ Presensi        | Lihat rekap kehadiran             |
| 📊 KHS & Transkrip | Nilai & IPK                       |
| 📚 Skripsi & KP    | Pengajuan & progress              |

---

## 🛠️ Tech Stack

<table>
<tr>
<td>

**Backend**

-   Laravel 12
-   PHP 8.2
-   MySQL 8.0
-   Pest PHP

</td>
<td>

**Frontend**

-   Blade Templates
-   Alpine.js
-   Tailwind CSS
-   Vite 7

</td>
<td>

**DevOps**

-   GitHub Actions
-   Health Monitoring
-   Daily Logs
-   Rate Limiting

</td>
</tr>
</table>

---

## 🔒 Security Features

```
✅ Role-based Access Control (RBAC)
✅ CSRF Protection (50+ forms)
✅ Rate Limiting (10-30 req/min)
✅ Security Headers (XSS, Clickjacking, HSTS)
✅ SQL Injection Prevention (Eloquent ORM)
✅ Database Transactions (Atomic operations)
✅ Request Logging & Monitoring
```

---

## 📊 Architecture

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # 15+ controllers
│   │   ├── Dosen/          # 8 controllers
│   │   └── Mahasiswa/      # 10 controllers
│   └── Middleware/
│       ├── RoleMiddleware
│       ├── SecurityHeadersMiddleware
│       └── RequestLoggingMiddleware
├── Models/                  # 22 Eloquent models
├── Services/                # 9 service classes
└── ...

tests/Feature/               # 30+ feature tests
database/
├── migrations/              # 21 migration files
└── factories/               # 6 model factories
```

---

## ⚡ Quick Start

### Prerequisites

-   PHP 8.2+
-   Composer
-   Node.js 18+
-   MySQL 8.0+

### Installation

```bash
# Clone repository
git clone https://github.com/ryandaaa/siakad.git
cd siakad

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env
# DB_CONNECTION=mysql
# DB_DATABASE=siakad
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations & seeders
php artisan migrate --seed

# Build assets
npm run build

# Start server
php artisan serve
```

### Default Accounts

| Role      | Email                 | Password |
| --------- | --------------------- | -------- |
| Admin     | admin@siakad.test     | password |
| Dosen     | dosen@siakad.test     | password |
| Mahasiswa | mahasiswa@siakad.test | password |

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test tests/Feature/Krs
php artisan test tests/Feature/Dosen

# Run with coverage
php artisan test --coverage
```

---

## 🔍 Health Check

```bash
# Basic health check
curl http://localhost:8000/health

# Detailed health check (DB, Cache, Storage)
curl http://localhost:8000/health/detailed
```

---

## 📈 Production Readiness

| Category     | Score         |
| ------------ | ------------- |
| Architecture | ⭐⭐⭐⭐      |
| Security     | ⭐⭐⭐⭐⭐    |
| Testing      | ⭐⭐⭐⭐      |
| Performance  | ⭐⭐⭐⭐      |
| DevOps       | ⭐⭐⭐⭐⭐    |
| **Overall**  | **95/100** ✅ |

---

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

---

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

<p align="center">
  <strong>Built with ❤️ using Laravel 12</strong>
</p>
