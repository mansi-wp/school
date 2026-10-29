# 🎓 Apex Coaching Management System — Laravel 11 MVC

A production-grade, admin-only, role-based **Coaching & Class Management System** built with **Laravel 11.x (PHP 8.3+)**, MySQL 8, and AdminLTE 3 / Bootstrap 5.

This system is completely behind authentication with **no public landing page**. Root URL `/` redirects to `/admin/dashboard` or `/login`.

---

## 🚀 Quick Setup & Installation (Under 15 Minutes)

### 1. Prerequisites
- **PHP 8.3+** with extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `curl`
- **Composer 2.x**
- **Node.js 20+** & **npm**
- **MySQL 8.0+**
- **Redis 6+** (for Horizon and cached dashboard aggregates)

---

### 2. Initialization Commands

```bash
# Clone or navigate to the project directory
cd coaching-admin

# Install PHP dependencies
composer install

# Install frontend asset dependencies
npm install

# Compile production assets
npm run build

# Generate application encryption key
php artisan key:generate

# Symlink private/public storage
php artisan storage:link
```

---

### 3. Database Setup & Seeding

Configure your MySQL credentials in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=coaching_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### Option A: Run via Laravel Migrations & Seeders
```bash
php artisan migrate:fresh --seed
```

#### Option B: Direct MySQL 8 Schema Script
You can execute the source-of-truth SQL script directly into MySQL 8:
```bash
mysql -u root -p < database/sql/full_schema_and_queries.sql
```

---

### 4. Default Login Credentials

| Role | Email | Password | Access Guard |
|---|---|---|---|
| **Super Admin** | `admin@apexcoaching.edu` | `Password@123` | `web` |
| **Faculty / Teacher** | `vikram.sharma@apexcoaching.edu` | `Password@123` | `web` / `teacher` |
| **Chief Accountant** | `priya.mehta@apexcoaching.edu` | `Password@123` | `web` |
| **Receptionist** | `rohit.verma@apexcoaching.edu` | `Password@123` | `web` |
| **Student** | `aarav.patel@student.apex.edu` | `Password@123` | `web` / `student` |

> ⚠️ **Security Notice:** Change default credentials immediately upon first login.

---

### 5. Start Application Server & Background Queues

```bash
# Run local HTTP dev server
php artisan serve

# In a separate terminal, run Horizon queue worker for receipt generation & SMS notifications
php artisan horizon
```

The application will be accessible at: `http://localhost:8000` (redirects directly to `/login`).

---

## 🧱 Architectural Highlights

### 1. Dynamic Database-Driven Sidebar (`system_menus`)
- Sidebar is **never hardcoded**. It is compiled dynamically in `app/Http/View/Composers/SidebarComposer.php` from the `system_menus` table.
- Filtered per user using Spatie permissions and cached in Redis.
- Administrable via `/admin/menus` with drag-and-drop sort order.
- Invalidate cache automatically via `MenuService::clearCache()`.

### 2. Role-Based Access Control (Spatie Permission)
- 40+ granular permissions seeded across 15 modules: `dashboard.view`, `staff.*`, `course.*`, `batch.*`, `batch.transfer`, `student.*`, `fee.*`, `attendance.*`, `test.*`, `expense.*`, `report.*`, `notification.*`, `role.*`, `menu.*`, `setting.*`, `audit.*`.
- Protected in controllers via `$this->middleware('permission:code')` and in Blade templates via `@can('permission.code')`.

### 3. Student Admission & Auto Fee Schedule
- Admission validates batch seat availability against `batches.max_capacity`.
- Generates official Roll Number (`APEX-YYYY-XXXX`).
- Generates installment schedules in `fee_plans` with late fee calculation.

### 4. Financial Transactions & PDF Receipts
- Atomic DB transactions for fee collection, late fee waivers, and refunds.
- Printable PDF receipts generated via `barryvdh/laravel-dompdf`.

### 5. Append-Only Security Audit Logging
- `AuditLogger` middleware records every mutation into the `audit_logs` table.
- Immutable at model level (updates and deletes prevented).
