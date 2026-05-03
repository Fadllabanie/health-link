# Medical Platform — Project Overview

> **Read this file first.** It is the source of truth for project context, stack, conventions, and architecture decisions. Always re-read this before starting a new task in `TASKS.md`.

---

## 1. What We Are Building

An electronic medical platform (المنصة الطبية الإلكترونية) — a multi-tenant hospital management system that connects **Hospitals, Doctors, Pharmacies, and Patients** under one platform.

**Five user roles:**
1. **Super Admin** — manages the whole platform, hospitals, master data
2. **Hospital Admin** — manages one hospital's doctors, pharmacy, patients
3. **Doctor** — manages patient medical records and prescriptions
4. **Pharmacy Admin** — manages medicine inventory and dispenses prescriptions
5. **Patient** — views own profile, prescriptions, medical history, QR code

**Core capabilities:**
- Hospital management with multi-tenant isolation (`hospital_id` scoping)
- Doctor scheduling, specialties, departments
- Patient medical records with attachments
- Prescriptions with multiple medicine items
- Pharmacy inventory with batches, expiry, stock movements
- Unique QR code per patient that links to their latest prescription
- Audit logging on every critical action
- Soft deletes everywhere clinically significant (HIPAA-style)

---

## 2. Tech Stack (Locked Decisions)

| Layer | Choice |
|---|---|
| Framework | **Laravel 11+** (traditional MVC with Blade) |
| PHP | 8.2+ |
| Database | **MySQL 8** |
| Frontend templates | Blade + **Materio Bootstrap HTML Admin Template (free v3.0.0)** |
| CSS framework | Bootstrap 5 (comes with Materio) |
| Roles & Permissions | **Spatie laravel-permission** (with custom `hospital_id` scoping layer) |
| QR codes | `simple-qrcode` (or `endroid/qr-code`) |
| File storage | **Local** — `storage/app/public` (linked via `php artisan storage:link`) |
| Language | **Arabic-only, RTL-only** for v1 |
| Authentication | Laravel Breeze (Blade) or hand-rolled — email + password only |
| Testing | PHPUnit + factories + seeders |

---

## 3. Architectural Decisions (Read Before Coding)

### 3.1 Multi-Tenancy Model
- **Tenancy is per-hospital**, enforced via `hospital_id` column on every tenant-scoped table.
- A user has roles **scoped per hospital** through the `user_roles` pivot (`user_id`, `role_id`, `hospital_id`).
- A `null` `hospital_id` in `user_roles` = global role (Super Admin).
- Every tenant model **must** apply a global Eloquent scope `HospitalScope` that filters by `auth()->user()->current_hospital_id`.
- Super Admin bypasses `HospitalScope`.

### 3.2 Spatie Integration with Hospital Scoping
Spatie's default `model_has_roles` table is **not enough** because we need hospital-scoped roles. Strategy:
- Use Spatie's `roles` and `permissions` tables as-is.
- Replace/extend `model_has_roles` with our `user_roles` table that includes `hospital_id`.
- Create a `HasHospitalScopedRoles` trait on the `User` model that wraps Spatie's role checks with hospital context.
- Permission checks become: `$user->hasPermissionInHospital('manage-doctors', $hospitalId)`.

### 3.3 Polymorphic Relationships
These are polymorphic per the ERD — implement using Laravel's `morphTo` / `morphMany`:
- `audit_logs` → any model (`auditable_type`, `auditable_id`)
- `qr_codes` → Patient or Prescription (`qrable_type`, `qrable_id`)
- `stock_movements` → Prescription or Purchase (`reference_type`, `reference_id`)
- `notifications` → Users (Laravel default)

### 3.4 Soft Deletes
All clinically significant tables use Laravel's `SoftDeletes` trait (per ERD `deleted_at` columns). Never hard-delete medical data — use archive/soft-delete only.

### 3.5 Audit Logging
Every Create/Update/Delete on critical models (hospitals, doctors, patients, prescriptions, medical_records, stock_movements, etc.) writes a row to `audit_logs`. Implementation options:
- **Recommended:** use `owen-it/laravel-auditing` package, OR
- Custom `Auditable` trait on the relevant models that hooks into `created`, `updated`, `deleted` Eloquent events.

### 3.6 RTL Support
- Set `<html dir="rtl" lang="ar">` in the master Blade layout.
- Use Materio's RTL CSS bundle (the template ships RTL-ready — use the RTL stylesheet variant).
- All UI strings come from `resources/lang/ar/*.php` translation files.
- Number formatting and date formatting must use Arabic locale where appropriate (but DB stores in standard ISO formats).

### 3.7 Validation Strategy
- One **FormRequest class per action** (e.g., `StoreHospitalRequest`, `UpdateHospitalRequest`).
- Validation rules mirror the SRS "قواعد التحقق" (validation rules) sections.
- Authorization via FormRequest `authorize()` method using Spatie + hospital scope.

### 3.8 Routing & Controllers
- Group routes by role: `routes/web.php` includes `super-admin.php`, `hospital-admin.php`, `doctor.php`, `pharmacy.php`, `patient.php`.
- Each role has its own controller namespace: `App\Http\Controllers\SuperAdmin\`, `App\Http\Controllers\HospitalAdmin\`, etc.
- Resource controllers where CRUD applies; explicit invokable controllers for one-off actions.

### 3.9 Service Layer
For complex logic (prescription dispensing, stock movement, hospital onboarding, QR generation), use **service classes** in `app/Services/` — keep controllers thin.

---

## 4. Folder & Naming Conventions

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── SuperAdmin/
│   │   ├── HospitalAdmin/
│   │   ├── Doctor/
│   │   ├── Pharmacy/
│   │   └── Patient/
│   ├── Requests/
│   │   ├── SuperAdmin/
│   │   ├── HospitalAdmin/
│   │   └── ...
│   └── Middleware/
│       ├── EnsureHospitalContext.php
│       └── RoleMiddleware.php (Spatie)
├── Models/                  # All Eloquent models
├── Scopes/
│   └── HospitalScope.php
├── Services/
│   ├── PrescriptionService.php
│   ├── InventoryService.php
│   ├── QrCodeService.php
│   └── AuditLogService.php
├── Traits/
│   ├── HasHospitalScopedRoles.php
│   ├── BelongsToHospital.php
│   └── Auditable.php
└── Policies/

resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php             # Materio master (RTL)
│   │   ├── auth.blade.php
│   │   └── partials/                  # sidebar, navbar, footer
│   ├── super-admin/
│   ├── hospital-admin/
│   ├── doctor/
│   ├── pharmacy/
│   └── patient/
├── lang/ar/                  # Arabic translations
└── sass/                     # if customizing Materio

database/
├── migrations/               # Order matters — see TASKS.md
├── seeders/
└── factories/
```

**Naming:**
- Models: singular PascalCase (`Hospital`, `Doctor`, `Prescription`)
- Tables: plural snake_case (per ERD)
- Pivot tables: alphabetical singular_singular (`hospital_specialty`) — **but the ERD uses plural pivots like `hospital_specialties`. Follow the ERD names exactly** to avoid confusion.
- Controllers: `{Resource}Controller` (e.g., `HospitalController`)
- Form Requests: `{Action}{Resource}Request` (e.g., `StoreHospitalRequest`)

---

## 5. Database Conventions

- Primary keys: `BIGINT UNSIGNED AUTO_INCREMENT`
- All user-facing entities also have a `uuid CHAR(36) UNIQUE` for external references (URLs, QR codes)
- Timestamps: `created_at`, `updated_at` everywhere
- Soft deletes: `deleted_at` on clinical tables
- Enums: use Laravel's enum casts where possible (PHP 8.1+ enums)
- Decimals for money: `DECIMAL(10,2)`
- Coordinates: `DECIMAL(10,8)` for latitude, `DECIMAL(11,8)` for longitude
- Always add the indexes specified in the ERD — they are not optional

---

## 6. Security Requirements (Per SRS)

- Passwords hashed via bcrypt (Laravel default)
- Session protection (Laravel default + `secure` cookies in production)
- CSRF on every form
- Email verification on user registration
- Two-factor auth fields exist on `users` table (`two_factor_enabled`, `two_factor_secret`) — **scaffold but don't fully wire 2FA in v1** unless time permits
- Hospital-scope middleware blocks cross-tenant access at the route level
- All sensitive operations (prescription creation, dispensing, deletion) write to `audit_logs`
- File uploads: validate MIME type + size; store outside web root; serve via signed URLs

---

## 7. Business Rules Summary (From SRS)

These are non-negotiable rules the code must enforce:

1. An inactive hospital cannot log into the system.
2. Every hospital must have at least one Hospital Admin.
3. No data is hard-deleted — use soft delete / archive.
4. Every administrative action must be logged.
5. A doctor must be linked to at least one specialty and one department.
6. A doctor can work in more than one hospital.
7. A prescription cannot be created without a patient and at least one medicine.
8. A prescription cannot be dispensed without sufficient inventory stock.
9. Every dispense action must decrement inventory stock.
10. A prescription cannot be edited after it is dispensed (unless system policy allows).
11. Every patient must have a unique Medical Record Number (MRN) and a unique QR code.
12. A patient cannot access another patient's data.
13. Pharmacy admin works within one hospital only and cannot access other hospitals' data.
14. Inventory must track expiry dates per batch.

---

## 8. SRS Reference Map (Where Each Requirement Lives)

| SRS Section | Role | Doc Reference |
|---|---|---|
| §1 | Super Admin | FR-SA-001 to FR-SA-014 |
| §2 | Master Data (Countries, Cities, Specialties, Departments) | FR-MD-001 to FR-MD-020 |
| §3 | Hospital Admin | FR-HA-001 to FR-HA-007 |
| §4 | Doctor | FR-DR-001 to FR-DR-014 |
| §5 | Pharmacy Admin | FR-PH-001 to FR-PH-013 |
| §6 | Patient | FR-PA-001 to FR-PA-010 |

When implementing a feature, reference its FR-code in commit messages and PR descriptions.

---

## 9. Definition of Done (per task)

A task is done when:
1. Migration runs cleanly (`php artisan migrate:fresh --seed`)
2. Model has correct relationships, casts, fillable/guarded, scopes
3. FormRequest validates all SRS rules
4. Controller is thin and uses service classes for complex logic
5. Routes are registered with proper middleware (`auth`, `role:`, `hospital.context`)
6. Blade views use the Materio RTL layout and Arabic translations
7. Authorization (Policy or Gate) is enforced
8. Audit log entries are written for create/update/delete
9. Factory + seeder exist (for tested entities)
10. At least one feature test covers the happy path
11. The feature passes manual smoke testing in browser

---

## 10. How Claude Should Work With `TASKS.md`

- Tasks are **strictly ordered** — do them in sequence. Earlier tasks unblock later ones.
- Each task has a **goal**, **steps**, **deliverables**, and **verification command**.
- After finishing a task, **mark it `[x]`** in `TASKS.md` and run the verification.
- If a task reveals an ambiguity, **stop and ask the user** rather than guessing.
- Never modify the ERD schema without flagging the change.
- Never skip the audit log / soft delete / hospital scope on tenant-scoped models.

---

## 11. Out of Scope for v1

- Online payment / billing module
- Telemedicine (video calls) — `appointments.type='video'` exists but no implementation
- Lab tests / radiology / insurance claims
- Mobile app (web responsive only)
- SMS gateway (we keep `phone_verified_at` field but don't send SMS yet)
- Real 2FA enforcement (column exists, full flow deferred)
- Bilingual UI — Arabic only

---

**End of overview. Begin work in `TASKS.md`.**
