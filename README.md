# 🎓 Certificate Verification Portal

A professional, production-ready Laravel application for managing graduate certificate records and providing a public, high-trust verification portal via QR codes.

## 🌟 Core Purpose
This system acts as the digital bridge between a physically issued certificate and the institution's official records. It manages graduate data and generates unique QR codes that, when scanned, lead to an official verification page.

**Note:** This is NOT a certificate designer. It does not design or generate the physical certificate PDF.

## 🚀 Quick Start

### Prerequisites
- PHP 8.3+
- MySQL / MariaDB
- Composer
- Node.js & NPM (for frontend assets)

### Installation
1. **Clone the repository**
2. **Install Dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```
3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Configure your `.env` with database credentials and `APP_URL`.

4. **Database Setup**
   ```bash
   php artisan migrate
   ```

5. **Admin Setup**
   The system comes with a default admin account for initial setup:
   - **Email:** `admin@example.com`
   - **Password:** `password123`
   *(Please change this immediately after first login)*

6. **Storage Link**
   ```bash
   php artisan storage:link
   ```

## 🛠 Administrative Workflow

### 1. Institutional Branding
Navigate to **Admin $\to$ Institution Settings**. Upload your logo and favicon, and configure your official name, address, and verification footer. This branding will appear on all public verification pages.

### 2. Managing Certificates
- **Manual Entry:** Use the "Add Graduate" form to enter student details and upload a passport photo.
- **Bulk Import:** Upload an Excel/CSV file. The system will:
    - Preview the data.
    - Validate for duplicates.
    - Bulk-create records and generate QR codes.

### 3. QR Code Distribution
- **Individual Download:** Download a specific student's QR code as a high-res PNG.
- **Bulk Download:** Filter graduates by department or session and download a ZIP package of all matching QR codes.
- **Placement:** Manually place the downloaded PNG QR code onto your existing certificate design artwork.

### 4. Certificate Lifecycle
- **Revocation:** If a certificate is withdrawn, mark it as **REVOKED**. The public verification page will immediately reflect this change, showing a warning banner.
- **Restoration:** Revoked certificates can be restored to **VALID** status if authorized.

## 🌐 Public Verification
The public verifies a certificate by scanning the QR code, which opens:
`https://your-domain.com/verify/{secure_token}`

The page displays:
- Institution branding.
- Verification status (Verified/Revoked/Not Found).
- Graduate's passport photo.
- Official academic details.
- Verification timestamp.

## 🔒 Security & Architecture
- **Secure Tokens:** Uses cryptographically strong random tokens for verification URLs.
- **Audit Logging:** Every admin action (creation, revocation, bulk download) is logged in the `audit_logs` table.
- **Rate Limiting:** Public verification endpoints are rate-limited to prevent brute-force attacks.
- **File Safety:** Strict MIME-type validation and sanitized filenames prevent malicious uploads and directory traversal.
- **Service Layer:** Business logic is decoupled into `QrCodeService`, `VerificationService`, `ImportService`, and `AuditService`.

## 📊 Database Schema
- `users`: Admin accounts.
- `certificates`: Graduate records and verification tokens.
- `institution_settings`: Branding and contact info.
- `verification_logs`: Audit trail of public scans.
- `audit_logs`: Audit trail of administrative actions.
