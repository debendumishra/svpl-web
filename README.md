# Surya Vistaara Pvt. Ltd. (SVPL)
## PM Surya Ghar Network & Lead Management Platform

**Surya Vistaara Pvt. Ltd. (SVPL)** is an enterprise web platform designed for the **PM Surya Ghar: Muft Bijli Yojana** rooftop solar ecosystem in Odisha, India. SVPL acts as the authorized corporate promoter for **Dhwajja Solar India Pvt. Ltd.**

---

## 🚀 Key Modules & Architecture

1. **Corporate Public Website & Solar Hub**:
   - Modern, high-trust corporate portal (`/`, `/about`, `/pm-surya-ghar`, `/how-it-works`, `/faq`, `/contact`).
   - Dynamic **PM Surya Ghar Solar Subsidy Calculator** (instant calculation of ₹30,000–₹78,000 subsidies, payback period, and monthly bill savings).
   - Official disclaimers, Terms & Conditions, Privacy Policy, and QR Code verification portal (`/verify`).

2. **9-Level MLM / Network Genealogy Engine**:
   - High-performance **Closure Table** database design (`advisor_genealogy`) enabling instant $O(1)$ downline/upline lookups up to 9 levels deep.
   - **3-Customer Qualification Detector**: Automatically detects when an Advisor joins 3 eligible solar customers and upgrades status from `NEW` to `QUALIFIED`.
   - **Interactive Visual Network Tree**: Color-coded levels ($1 \dots 9$), zoomable desktop tree, and mobile-friendly responsive cards.
   - **Automated Referral Engine**: Unique Advisor IDs (`SVPL-ADV-xxxxxx`), Referral Codes (`SVPL000001`), and genuine scannable QR verification URLs.

3. **10-Stage Solar Lead & Project Lifecycle Pipeline**:
   - Tracks solar installations across:
     `REGISTRATION` → `DOCUMENTS` → `GOVT_PORTAL` → `LOAN_APPLIED` → `LOAN_SANCTIONED` → `INSTALLATION_COMMENCED` → `INSTALLATION_COMPLETED` → `JE_REPORT` → `SUBSIDY_APPLIED` → `SUBSIDY_RECEIVED (DBT)`.
   - Complete stage transition history, action remarks, and audit trail.

4. **1-Click Recursive Customer-to-Advisor Conversion**:
   - Solar customers who register or complete installation can convert into an Advisor with 1 click.
   - Preserves original customer records and sponsor genealogy without data duplication.

5. **Configurable Commission Engine & Immutable Wallet Ledger**:
   - Multi-tier level 1–9 commissions configurable from Admin Settings (Flat ₹ or % project cost).
   - Event-driven payouts (e.g., triggered on `INSTALLATION_COMPLETED` or `SUBSIDY_RECEIVED`).
   - Double-entry **Advisor Wallet** with Available Balance, Pending Commission, Total Earned, and immutable transaction ledger (`wallet_transactions`).

6. **Junior Engineer (JE) Inspection & Net Metering Module**:
   - Records official DISCOM (TPCODL, TPNODL, TPSODL, TPWODL) Junior Engineer inspections, anti-islanding tests, earthing checks, and net meter synchronization.
   - Generates official printable **JE Inspection Certificates**.

7. **Zero-Exposure Document Vault & Auto-Generation**:
   - Non-public secure storage (`/storage/documents/`) with tokenized/authenticated downloads.
   - PII privacy masking for Aadhaar (`XXXX XXXX 1234`), PAN (`XXXXX1234X`), and Bank Accounts (`XXXXXX1234`).
   - Auto-generates printable/PDF-ready corporate documents:
     - **Advisor Digital Identity Card** (with photo, verified status badge, and referral QR)
     - **Formal Appointment Letter** (with terms, letterhead, and authorized signatory)
     - **Official Money Receipts** (`SVPL-RCP-2026-xxxxxx`)
     - **Solar Rooftop Quotations / Proposals** (with subsidy deductions and equipment BOM)
     - **JE Inspection Certificates**

8. **Odisha Administrative Hierarchy**:
   - Cascading dynamic dropdowns: State (Odisha) → District (All 30 Districts) → Subdivision → Block → Gram Panchayat → Village → PIN Code.

9. **Logistics & Welcome Kit Management**:
   - Tracks dispatch of Advisor Welcome Kits (Digital ID Card, Appointment Letter, SVPL T-Shirt, 25 Solar Info Brochures) with courier partner and tracking numbers.

---

## 🛠 Tech Stack

- **Backend**: PHP 8.x (Modern Clean MVC architecture, PDO Prepared Statements, Regex Router, Role-based Middleware, CSRF Protection)
- **Database**: MySQL 8.x (InnoDB, strict mode, indexed closure tables) with automatic SQLite fallback support for instant zero-configuration testing.
- **Frontend**: Bootstrap 5.3, jQuery 3.7, AJAX, Bootstrap Icons, HTML5, CSS3 with custom Solar Theme (Deep Solar Blue `#0B2545`, Solar Green `#10B981`, Sun Gold `#F59E0B`).

---

## ⚡ Quick Start & Installation

### Option 1: Using PHP Built-in Server (Instant Demo)
```bash
# Navigate to the project root
cd d:/DKM/SVPL-Web

# Start PHP server pointing to public/ directory
php -S 127.0.0.1:8000 -t public
```
Visit in browser: **`http://127.0.0.1:8000`**

### Option 2: Using Apache / XAMPP / Laragon / WAMP
1. Place the project inside your web server directory (`htdocs` or `www`).
2. Ensure `mod_rewrite` is enabled.
3. Configure MySQL database credentials in `config/database.php` (or use the automatic database installer).
4. Run migrations via CLI: `php database/setup.php` or visit `http://localhost/SVPL-Web/public/install`.

---

## 🔑 Demo & Test Credentials

All demo user passwords are: **`Password@123`**

| Role | Login Identifier (Mobile/Email/Code) | Password | Description |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@suryavistaara.com` or `9999999999` | `Password@123` | Full system access, settings, approvals |
| **Accounts** | `accounts@suryavistaara.com` or `9888888881` | `Password@123` | Financial ledger, payments, commissions |
| **Operations** | `operations@suryavistaara.com` or `9888888882` | `Password@123` | Lead management, installations, JE reports |
| **Top Advisor (L1)** | `9437011111` or `SVPL-ADV-000001` or `SVPL000001` | `Password@123` | Manoj Kumar Dash (Qualified Advisor, 3 Customers) |
| **Advisor (L2)** | `9437022222` or `SVPL-ADV-000002` or `SVPL000002` | `Password@123` | Priya Ranjan Nayak (Cuttack) |
| **Customer** | `9124011111` | `Password@123` | Bijay Kumar Patra (Customer Portal) |

---

## 🧪 Automated Test Suite

Run the built-in automated test scripts from command line:
```bash
# 1. Test 9-Level Genealogy & 3-Customer Qualification Engine
php tests/test_genealogy.php

# 2. Test Multi-Level Commission Calculation & Immutable Ledger
php tests/test_commission.php

# 3. Test PII Masking, Security & CSRF
php tests/test_security.php
```

---

## 📁 Directory Layout

```
SVPL-Web/
├── app/
│   ├── Controllers/     # Public, Auth, Admin, Advisor, Customer, Lead, Document, Report
│   ├── Helpers/         # Database, Router, Response, Formatter, Csrf
│   ├── Middleware/      # AuthMiddleware, RoleMiddleware, CsrfMiddleware
│   ├── Models/          # User, Advisor, Customer, Lead, Genealogy, Commission, Wallet, etc.
│   ├── Services/        # AuthService, GenealogyService, CommissionEngine, QualificationService, etc.
│   └── Views/           # Responsive Bootstrap 5 + jQuery UI templates
├── config/              # app.php, database.php, constants.php
├── database/            # schema.sql, seeders.sql, setup.php
├── public/              # index.php, assets/ (CSS, JS, theme)
├── storage/             # documents/, generated/, logs/
├── routes/              # web.php
└── tests/               # test_genealogy.php, test_commission.php, test_security.php
```

---

## 📜 Compliance & Statutory Notice
Surya Vistaara Pvt. Ltd. (SVPL) is an independent corporate promoter for Dhwajja Solar India Pvt. Ltd. and is not a government agency. Subsidies and loan terms are subject to Government of India and DISCOM policies.

---

## 🛠️ Configuration Guide: Running on XAMPP (Default Port 80) & Shared Hosting

#### 1. Standard XAMPP (Port 80 / Default Localhost)
To run the web app on standard XAMPP (`http://localhost/svpl-web` without specifying a port number):

1. **Folder Location:**
   Place the project folder inside your standard XAMPP `htdocs` directory:
   `C:\xampp\htdocs\svpl-web`

2. **Environment File (`.env`):**
   Edit or create the `.env` file in the root folder (`svpl-web/.env`):
   ```ini
   APP_NAME="Surya Vistaara"
   APP_ENV=local
   APP_URL=http://localhost/svpl-web

   # Database Configuration
   DB_DRIVER=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=svpl_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

3. **Apache Server Check:**
   - Ensure Apache module `mod_rewrite` is enabled in `C:\xampp\apache\conf\httpd.conf` (`LoadModule rewrite_module modules/mod_rewrite.so`).
   - Open browser: `http://localhost/svpl-web`

---

#### 2. Deploying to Shared Web Hosting (cPanel / DirectAdmin / Hostinger / GoDaddy)

To deploy the portal to a shared Linux hosting server with a live domain name (e.g. `https://suryavistaara.com` or `https://portal.suryavistaara.com`):

1. **Upload Files:**
   Upload all project files to your website's root folder (`public_html` or domain root folder).

2. **Database Setup:**
   - Go to **cPanel > MySQL® Databases**.
   - Create a database (e.g., `suryavis_db`) and database user with full privileges.
   - Open **phpMyAdmin**, select `suryavis_db`, and import the file [`database/schema.sql`](file:///c:/xampp8.2/htdocs/svpl-web/database/schema.sql).

3. **Configure Live `.env`:**
   Edit the `.env` file on the server with your domain and database credentials:
   ```ini
   APP_NAME="Surya Vistaara"
   APP_ENV=production
   APP_URL=https://suryavistaara.com

   # Production MySQL Database Credentials
   DB_DRIVER=mysql
   DB_HOST=localhost
   DB_PORT=3306
   DB_DATABASE=suryavis_db
   DB_USERNAME=suryavis_user
   DB_PASSWORD=YourSecurePasswordHere
   ```

4. **Directory Permissions & Front Controller:**
   - Ensure `public/uploads` directory permissions are set to `755` (writable for document and image uploads).
   - If hosting directly in `public_html`, ensure the root `.htaccess` points requests to `public/index.php`.