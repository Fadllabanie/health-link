# Medical Platform — Build Tasks

> **Read `PROJECT_OVERVIEW.md` first.** Then work through the phases below in order. Each task must be completed and verified before moving to the next.
>
> **Tracking:** Mark `[x]` when done. Add notes inline if you deviate.

---

## Phase 0 — Project Bootstrap

### [x] T0.1 — Verify Laravel installation
- **Goal:** confirm fresh Laravel project is ready
- **Steps:**
  - Run `php artisan --version` (must be Laravel 11+)
  - Run `php -v` (must be PHP 8.2+)
  - Confirm `.env` exists and database credentials are set for MySQL 8
  - Create the database: `mysql -u root -p -e "CREATE DATABASE medical_platform CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"`
- **Verify:** `php artisan migrate` runs (against empty DB, creates default Laravel tables)

### [x] T0.2 — Install required packages
- **Goal:** install all third-party dependencies
- **Steps:**
  ```bash
  composer require spatie/laravel-permission
  composer require simplesoftwareio/simple-qrcode
  composer require owen-it/laravel-auditing
  composer require laravel/breeze --dev
  php artisan breeze:install blade
  ```
- **Verify:** `composer.json` includes all packages; `vendor/` populated

### [x] T0.3 — Publish package configs
- **Goal:** publish migration files and configs we need
- **Steps:**
  - `php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"`
  - `php artisan vendor:publish --provider="OwenIt\Auditing\AuditingServiceProvider"`
  - **Important:** review Spatie's `model_has_roles` migration — we will replace it with our `user_roles` table that has `hospital_id`. Either delete that migration or rename Spatie's `model_has_roles` table reference to point at `user_roles` via `config/permission.php`.
- **Verify:** `config/permission.php` and `config/audit.php` exist

### [x] T0.4 — Set up Materio template

- path /Users/fadl/Desktop/www/health-links/materio-bootstrap-html-admin-template-free-v3.0.0
- **Goal:** integrate Materio Bootstrap admin template (RTL)
- **Steps:**
  - Copy Materio assets (`assets/css`, `assets/js`, `assets/img`, `assets/vendor`) into `public/assets/`
  - **Use the RTL CSS variant** — Materio ships RTL stylesheets; load `core.css` from the RTL build
  - Create `resources/views/layouts/app.blade.php` master layout with:
    - `<html dir="rtl" lang="ar">`
    - Materio's CSS in `<head>`, JS before `</body>`
    - `@yield('content')` and Blade sections for title, page-specific scripts
  - Create partials: `resources/views/layouts/partials/{sidebar,navbar,footer}.blade.php`
- **Verify:** load any route, page renders with Materio styling and RTL layout

### [x] T0.5 — Configure Arabic localization
- **Goal:** set Arabic as default app language
- **Steps:**
  - In `config/app.php`: `'locale' => 'ar'`, `'fallback_locale' => 'en'`
  - Create `resources/lang/ar/` with files: `auth.php`, `validation.php`, `pagination.php`, `passwords.php`, `app.php` (custom strings)
  - Translate validation messages into Arabic in `resources/lang/ar/validation.php`
- **Verify:** `__('validation.required')` returns Arabic text

### [x] T0.6 — Set up storage symlink
- **Goal:** make uploads publicly accessible
- **Steps:**
  - `php artisan storage:link`
  - Update `filesystems.php` default disk to `public`
- **Verify:** `public/storage` symlink exists pointing to `storage/app/public`

---

## Phase 1 — Database Foundation (Master Data)

> Build migrations bottom-up: tables with no FK dependencies first, then dependents.

### [x] T1.1 — Migration: countries
- Fields per ERD §Master Data → countries
- Add seeder with at least Saudi Arabia, UAE, Egypt, Jordan, Kuwait
- **Verify:** `php artisan migrate` + `php artisan db:seed --class=CountrySeeder`

### [x] T1.2 — Migration: cities
- Fields per ERD; FK to countries with `onDelete('cascade')`
- Composite index `(name, country_id)`
- Seed major cities for the seeded countries
- **Verify:** countries.id ↔ cities.country_id relationship works in tinker

### [x] T1.3 — Migration: specialties
- Fields per ERD; soft deletes
- Seed common specialties (Cardiology, Dermatology, Pediatrics, etc.) in Arabic + English names
- **Verify:** unique slug constraint enforced

### [x] T1.4 — Migration: medicine_categories
- Fields per ERD; self-referencing `parent_id` FK
- Soft deletes
- Seed top-level categories (Antibiotics, Painkillers, Vitamins, etc.)
- **Verify:** parent/child relation works

---

## Phase 2 — Auth & Users Foundation

### [x] T2.1 — Migration: users (extend Laravel default)
- **Important:** modify Laravel's default `users` migration to add ALL columns from ERD §users (uuid, first_name, last_name, phone, national_id, country_id, city_id, address, status, two_factor_*, last_login_*, gender, dob, etc.)
- Add FK to countries, cities (nullable, set null on delete)
- Add all indexes per ERD
- Soft deletes
- **Verify:** migration runs cleanly

### [x] T2.2 — Migration: roles, permissions, role_permissions
- Use Spatie's published migrations as base
- Add our extra columns: `display_name`, `description`, `module` (on permissions)
- **Verify:** Spatie tables exist with our additions

### [x] T2.3 — Migration: user_roles (replaces Spatie's model_has_roles)
- Per ERD: `user_id`, `role_id`, `hospital_id` (nullable), `assigned_by`, `assigned_at`
- Unique key `(user_id, role_id, hospital_id)`
- Update `config/permission.php` → `'model_has_roles' => 'user_roles'` and add the custom column mapping
- **Verify:** Spatie's role assignment writes to `user_roles`

### [x] T2.4 — Seeder: roles & permissions
- Seed 5 roles: `super_admin`, `hospital_admin`, `doctor`, `pharmacist`, `patient`
- Seed permissions grouped by module:
  - hospitals.{view,create,edit,delete,toggle-status}
  - doctors.{view,create,edit,disable}
  - patients.{view,view-details}
  - prescriptions.{view,create,edit,cancel,dispense}
  - medicines.{view,create,edit}
  - inventory.{view,manage}
  - master-data.{view,manage}
  - audit-logs.view
- Assign permissions to roles (super_admin gets all)
- **Verify:** `php artisan db:seed --class=RolePermissionSeeder` succeeds

### [x] T2.5 — Model: User
- Apply traits: `HasFactory`, `Notifiable`, `SoftDeletes`, Spatie's `HasRoles`, custom `HasHospitalScopedRoles`
- Casts for enum fields (gender, status), datetimes
- Relationships: `country()`, `city()`, `doctor()`, `patient()`, `pharmacist()`, `auditLogs()`
- Accessor `getFullNameAttribute()`
- **Verify:** factory creates user; `$user->assignRole('doctor')` works

### [x] T2.6 — Trait: HasHospitalScopedRoles
- File: `app/Traits/HasHospitalScopedRoles.php`
- Methods:
  - `hasRoleInHospital(string $role, int $hospitalId): bool`
  - `hasPermissionInHospital(string $permission, int $hospitalId): bool`
  - `assignRoleInHospital(string $role, ?int $hospitalId, ?int $assignedBy = null)`
  - `currentHospital()` — returns the active hospital from session/user context
- **Verify:** unit test for each method

### [x] T2.7 — Auth scaffolding (Breeze, Arabic, RTL)
- Customize Breeze views with Materio layout
- Translate all auth pages to Arabic
- Login redirect logic: route user to their role-specific dashboard
- Add `last_login_at`, `last_login_ip` update on successful login (event listener)
- **Verify:** can log in, log out, password reset flow works in Arabic

### [x] T2.8 — Middleware: EnsureHospitalContext
- File: `app/Http/Middleware/EnsureHospitalContext.php`
- For non-super-admin users: ensure session has `current_hospital_id` set; if user has multiple hospitals, redirect to a hospital picker
- Register in `app/Http/Kernel.php` as `hospital.context`
- **Verify:** routes protected by middleware redirect correctly

### [x] T2.9 — Global scope: HospitalScope
- File: `app/Scopes/HospitalScope.php`
- Filters by `auth()->user()->current_hospital_id` unless user is super_admin
- Trait `BelongsToHospital` applies the scope and provides `hospital()` relation
- **Verify:** unit test confirms tenant isolation

---

## Phase 3 — Hospitals Module

### [x] T3.1 — Migration: hospitals
- All fields per ERD §Hospitals
- FKs to countries, cities
- Indexes: status, city_id, subscription_plan
- Soft deletes
- **Verify:** migration runs

### [x] T3.2 — Migration: hospital_specialties (pivot)
- Per ERD; cascade deletes both ways
- Unique `(hospital_id, specialty_id)`

### [x] T3.3 — Migration: departments
- Per ERD; FK to hospitals (cascade), nullable FK to doctors (head_doctor_id, set null)
- Unique `(hospital_id, name)`
- Soft deletes

### [x] T3.4 — Models: Hospital, Department
- `Hospital` model:
  - SoftDeletes, Auditable, generates UUID on create
  - Relationships: `country()`, `city()`, `specialties()` (belongsToMany), `departments()`, `doctors()`, `pharmacies()`, `admins()` (users with hospital_admin role for this hospital)
  - Scopes: `scopeActive()`, `scopeBySubscription()`
- `Department` model:
  - SoftDeletes, BelongsToHospital trait
  - Relationships: `hospital()`, `headDoctor()`, `doctors()`
- **Verify:** factory + tinker can create hospital with departments

### [x] T3.5 — Super Admin: Hospitals CRUD (FR-SA-002 to FR-SA-007, FR-SA-012, FR-SA-013)
- Routes group: `routes/super-admin.php`, prefix `/super-admin`, middleware `auth`, `role:super_admin`
- Controller: `App\Http\Controllers\SuperAdmin\HospitalController` (resource controller)
- FormRequests: `StoreHospitalRequest`, `UpdateHospitalRequest`
- Validation per FR-SA-003: name required, email unique, phone, address, hospital_admin email + password
- Views: `resources/views/super-admin/hospitals/{index,create,edit,show}.blade.php`
- Index page: search by name/email, filter by status, paginate (15 per page)
- Show page: full details, list of departments, list of admins
- **Special:** "Add Hospital" form also creates the first Hospital Admin user atomically (DB transaction). Wrap creation logic in `HospitalOnboardingService`.
- All actions write to `audit_logs`
- **Verify:** manual test all CRUD flows; feature test for store + admin creation

### [x] T3.6 — Super Admin: Hospital status & archive (FR-SA-006, FR-SA-007)
- Endpoints: `PATCH /super-admin/hospitals/{hospital}/status`, `DELETE /super-admin/hospitals/{hospital}` (soft delete = archive)
- Business rule: when status set to `inactive` or `suspended`, log out all users of that hospital and prevent login (check in `EnsureHospitalContext` middleware)
- **Verify:** test that suspended hospital users cannot log in

### [x] T3.7 — Super Admin: Hospital Admin management (FR-SA-008 to FR-SA-011)
- Sub-controller or nested routes under hospitals: manage users with `hospital_admin` role for a given hospital
- CRUD on hospital admins; password reset endpoint (`POST /super-admin/hospitals/{hospital}/admins/{user}/reset-password`)
- Disable admin = `users.status = 'inactive'`
- **Verify:** all four actions (create/edit/disable/reset-password) work and audit-log

### [x] T3.8 — Super Admin Dashboard (FR-SA-013)
- Route: `GET /super-admin/dashboard`
- Cards: total hospitals, active count, suspended count, total users by role
- Recent activity from audit_logs (last 10)
- **Verify:** numbers match DB

---

## Phase 4 — Master Data Admin (Super Admin manages reference data)

### [x] T4.1 — Countries CRUD (FR-MD-001 to FR-MD-005)
- Controller: `App\Http\Controllers\SuperAdmin\CountryController`
- Routes under `/super-admin/master-data/countries`
- FormRequests with validation per SRS
- Active/inactive toggle, archive (soft delete)
- Views with Materio data tables
- **Verify:** all CRUD actions, soft delete works

### [x] T4.2 — Cities CRUD (FR-MD-006 to FR-MD-010)
- Controller: `App\Http\Controllers\SuperAdmin\CityController`
- Country dropdown on create/edit (cascading select if needed)
- Routes under `/super-admin/master-data/cities`
- **Verify:** city must belong to a country, validation enforces this

### [x] T4.3 — Specialties CRUD (FR-MD-011 to FR-MD-015)
- Controller: `App\Http\Controllers\SuperAdmin\SpecialtyController`
- Auto-generate slug from name (use `Str::slug`)
- Icon upload (optional, store in `storage/app/public/specialties/`)
- **Verify:** slug uniqueness, icon upload works

### [x] T4.4 — Departments CRUD (FR-MD-016 to FR-MD-020)
- **Note:** Departments are hospital-scoped per ERD. The SRS §2 places them under master data managed by Super Admin, but the ERD column `hospital_id` makes them per-hospital.
- **Decision:** Super Admin can view/manage departments across all hospitals; Hospital Admin manages only their hospital's departments. Implement in **both** Super Admin and Hospital Admin sections later.
- For this task: build the Super Admin global view first (`/super-admin/master-data/departments`)
- Filter by hospital dropdown
- **Verify:** can create department under any hospital from super admin

### [x] T4.5 — Master Data seeders
- Verify all master data seeders work end-to-end with `php artisan migrate:fresh --seed`
- **Verify:** clean DB → seeded DB has countries, cities, specialties, medicine_categories, roles, permissions, super admin user

---

## Phase 5 — Doctors Module

### [x] T5.1 — Migration: doctors
- All fields per ERD §Doctors
- FKs: user_id (cascade), hospital_id (restrict), department_id (set null), primary_specialty_id (restrict)
- Soft deletes; indexes per ERD
- **Verify:** migration runs

### [x] T5.2 — Migration: doctor_specialties (pivot for secondary specialties)
- Per ERD; cascade both ways

### [x] T5.3 — Migration: doctor_schedules
- Per ERD; FK to doctors (cascade)
- Index `(doctor_id, day_of_week)`

### [x] T5.4 — Model: Doctor
- SoftDeletes, BelongsToHospital, Auditable
- Relationships: `user()`, `hospital()`, `department()`, `primarySpecialty()`, `specialties()` (belongsToMany via doctor_specialties), `schedules()`, `prescriptions()`, `medicalRecords()`, `appointments()`
- Computed: `getNameAttribute()` (from user), `getIsAvailableAttribute()`
- **Verify:** factory creates doctor with linked user

### [x] T5.5 — Hospital Admin: Doctors CRUD (FR-HA-002, FR-HA-002b, FR-HA-002c, FR-HA-003)
- Routes group: `routes/hospital-admin.php`, prefix `/hospital-admin`, middleware `auth`, `role:hospital_admin`, `hospital.context`
- Controller: `App\Http\Controllers\HospitalAdmin\DoctorController`
- Create form: name, email, phone, license_number, primary_specialty (required), department (required), secondary specialties (multi-select), consultation_fee, qualifications, bio
- **Important:** creating a doctor creates a User + Doctor record + assigns `doctor` role scoped to current hospital, all in one transaction. Use `DoctorOnboardingService`.
- Validation: email unique within hospital, license_number globally unique
- Disable doctor = `doctors.status = 'inactive'` AND `users.status = 'inactive'`
- All actions audit-logged
- Views show specialties + department in list; filter by specialty/department/status
- **Verify:** doctor creation atomic; doctor cannot log in if disabled

### [x] T5.6 — Doctor Schedules management
- Controller: `App\Http\Controllers\HospitalAdmin\DoctorScheduleController`
- Each doctor can have one schedule entry per day_of_week
- Form: weekly grid (Sun-Sat) with start/end time and slot duration
- **Verify:** only one active schedule per (doctor, day_of_week)

### [x] T5.7 — Hospital Admin Dashboard
- Route: `GET /hospital-admin/dashboard`
- Cards: doctors count, patients count, today's appointments, pending prescriptions
- Recent activity for this hospital
- **Verify:** numbers scoped to current hospital only

---

## Phase 6 — Patients Module

### [ ] T6.1 — Migration: patients
- All fields per ERD §Patients
- FKs: user_id (cascade), hospital_id (set null), qr_code_id (set null), city_id
- Soft deletes
- Auto-generate MRN (medical_record_number) on create — format: `MRN-{hospital_id}-{padded_sequence}` or UUID-based
- **Verify:** migration runs; MRN unique

### [ ] T6.2 — Migration: patient_hospitals (pivot)
- Per ERD; tracks which hospitals a patient has registered with
- Cascade both ways; unique `(patient_id, hospital_id)`

### [ ] T6.3 — Migration: qr_codes
- Polymorphic per ERD: `qrable_type`, `qrable_id`
- `code` is the encrypted token used in QR image
- Index on `code`, `(qrable_type, qrable_id)`
- Soft deletes
- **Verify:** migration runs

### [ ] T6.4 — Model: Patient
- SoftDeletes, Auditable
- Relationships: `user()`, `hospital()` (primary), `hospitals()` (belongsToMany via patient_hospitals), `city()`, `qrCode()` (morphOne or belongsTo), `medicalRecords()`, `prescriptions()`, `appointments()`
- Boot method auto-generates MRN
- **Verify:** factory + MRN auto-generation

### [ ] T6.5 — Model: QrCode
- Polymorphic `qrable()` morphTo
- Methods: `regenerate()`, `incrementScan()`, `isExpired()`
- **Verify:** can attach QR to Patient and Prescription

### [ ] T6.6 — Service: QrCodeService
- File: `app/Services/QrCodeService.php`
- Methods:
  - `generateForPatient(Patient $patient): QrCode` — creates encrypted code, generates PNG via simple-qrcode, stores in `storage/app/public/qr-codes/`, links via polymorphic relation
  - `regenerate(QrCode $qr)` — invalidates old, creates new
  - `verifyAndResolve(string $code)` — decrypts, returns the related model or throws
- The QR points to a public URL like `/qr/{code}` that resolves to the patient's latest prescription view
- **Verify:** unit test for generate + verify roundtrip

### [ ] T6.7 — Patient registration flow
- Patient self-registration is **not** in v1 SRS — patients are created by Hospital Admin or Doctor
- Hospital Admin route: `GET/POST /hospital-admin/patients/create`
- Form: personal data + medical fields + creates User with `patient` role + auto-generates MRN + auto-generates QR code
- All in one DB transaction via `PatientRegistrationService`
- **Verify:** new patient has user, MRN, QR code

### [ ] T6.8 — Hospital Admin: Patients list (FR-HA-004a, FR-HA-004b)
- Read-only list per business rule #2 of §3 ("Hospital Admin can only view patients, not edit")
- Pagination, search by name/MRN/phone
- Detail page shows full medical record, prescriptions, appointments
- **Verify:** edit/delete buttons hidden for hospital_admin role

### [ ] T6.9 — Patient self-service portal (Phase 11) — placeholder
- Skip for now; this phase only covers patient management by admin/doctor

---

## Phase 7 — Medical Records

### [ ] T7.1 — Migration: medical_records
- Per ERD §Medical Records
- FKs: patient_id (cascade), doctor_id (restrict), hospital_id (restrict)
- Soft deletes; indexes per ERD including composite `(patient_id, visit_date)`
- **Verify:** migration runs

### [ ] T7.2 — Migration: medical_record_attachments
- Per ERD; FK to medical_records (cascade), uploaded_by → users
- **Verify:** migration runs

### [ ] T7.3 — Models: MedicalRecord, MedicalRecordAttachment
- `MedicalRecord`: SoftDeletes, BelongsToHospital, Auditable; relations to patient, doctor, hospital, attachments, prescriptions; UUID auto-generated
- `MedicalRecordAttachment`: relation to medicalRecord, uploader; accessor for full file URL
- **Verify:** factories work

### [ ] T7.4 — Doctor: View patient medical history (FR-DR-005)
- Route: `GET /doctor/patients/{patient}/medical-history`
- Authorization Policy: doctor can only view patients in their hospital(s)
- Show timeline of medical_records (most recent first)
- **Verify:** doctor in different hospital cannot access

### [ ] T7.5 — Doctor: Add medical record entry (FR-DR-006)
- Route: `POST /doctor/patients/{patient}/medical-records`
- FormRequest validates: visit_type, notes, diagnosis required
- Optional file uploads → store via attachments
- Status defaults to `draft`; doctor can finalize
- Audit logged
- **Verify:** record + attachments saved together

### [ ] T7.6 — Doctor: Edit medical record (FR-DR-007)
- Only allowed if record status is `draft` OR within edit window (per system policy — define as 24h after finalize)
- Status `amended` after edit of finalized record
- **Verify:** finalized record older than 24h cannot be edited

### [ ] T7.7 — File upload security
- Validate MIME types: pdf, jpg, png, doc, docx
- Max size 10MB
- Store under `storage/app/public/medical-records/{patient_id}/`
- Filename sanitized + UUID-prefixed
- **Verify:** malicious file types rejected

---

## Phase 8 — Medicines

### [ ] T8.1 — Migration: medicines
- Per ERD §Medicines
- FK to medicine_categories (set null)
- Soft deletes; indexes on name, generic_name, barcode, category_id
- **Verify:** migration runs

### [ ] T8.2 — Model: Medicine
- SoftDeletes, Auditable
- Relations: `category()`, `prescriptionItems()`, `inventories()`
- Casts for boolean fields
- Image accessor for full URL
- **Verify:** factory works

### [ ] T8.3 — Hospital Admin & Pharmacy Admin: Medicine catalog (FR-HA-005a/b, FR-PH-002 to FR-PH-005)
- **Decision:** Medicines are global (no `hospital_id` per ERD). Hospital Admin and Pharmacy Admin can both manage the medicine catalog. Super Admin too.
- Controllers (one per role context, sharing logic via service):
  - `App\Http\Controllers\HospitalAdmin\MedicineController`
  - `App\Http\Controllers\Pharmacy\MedicineController`
- CRUD: name, generic_name, brand_name, barcode, category, manufacturer, form (enum), strength, unit, description, side_effects, contraindications, dosage_instructions, requires_prescription, is_controlled, image
- Image upload to `storage/app/public/medicines/`
- Search by name, generic name, barcode
- **Verify:** all CRUD operations

### [ ] T8.4 — Medicine seeder
- Seed ~20 common medicines across different categories
- **Verify:** seeder runs cleanly

---

## Phase 9 — Pharmacies & Inventory

### [ ] T9.1 — Migration: pharmacies
- Per ERD; FK to hospitals (cascade, NULLABLE for independent pharmacies), country, city
- Soft deletes; indexes per ERD
- **Verify:** migration runs

### [ ] T9.2 — Migration: pharmacists
- Per ERD; FK to users (cascade), pharmacies (cascade)
- Soft deletes
- **Verify:** migration runs

### [ ] T9.3 — Migration: pharmacy_inventories
- Per ERD; FKs to pharmacies (cascade), medicines (restrict)
- Unique `(pharmacy_id, medicine_id, batch_number)`
- Indexes per ERD
- **Verify:** migration runs

### [ ] T9.4 — Migration: stock_movements
- Per ERD; FK to pharmacy_inventories (cascade), users (performed_by)
- Polymorphic `(reference_type, reference_id)` for Prescription/Purchase reference
- **Verify:** migration runs

### [ ] T9.5 — Models: Pharmacy, Pharmacist, PharmacyInventory, StockMovement
- All apply SoftDeletes + Auditable where ERD has `deleted_at`
- `Pharmacy`: relations to hospital, country, city, pharmacists, inventories
- `Pharmacist`: relations to user, pharmacy
- `PharmacyInventory`: relations to pharmacy, medicine, stockMovements; computed `getStatusAttribute()` based on quantity vs reorder_level vs expiry
- `StockMovement`: morphTo `reference()`
- **Verify:** factories + relationships

### [ ] T9.6 — Hospital Admin: Pharmacy management
- Routes: `/hospital-admin/pharmacies` — CRUD for in-hospital pharmacies
- Pharmacy admin (pharmacist) creation: form creates User + Pharmacist + assigns role atomically via service
- **Verify:** can add pharmacy with admin

### [ ] T9.7 — Pharmacy Admin: Inventory CRUD (FR-PH-006a, FR-PH-006b)
- Routes group: `routes/pharmacy.php`, prefix `/pharmacy`, middleware `auth`, `role:pharmacist`
- Controller: `App\Http\Controllers\Pharmacy\InventoryController`
- Create: medicine, batch_number, quantity, unit_cost, selling_price, manufacturing_date, expiry_date, supplier, location, reorder_level
- Each create writes a `stock_movements` row with `type=purchase`
- Edit: only `quantity`, `selling_price`, `reorder_level`, `location` editable; quantity changes write stock_movement with `type=adjustment`
- **Verify:** stock_movements correctly tracks every change

### [ ] T9.8 — Pharmacy Admin: Expiry tracking (FR-PH-007)
- Route: `GET /pharmacy/inventory/expiring`
- Show items expiring within 30 days, 60 days, 90 days, already expired
- Auto-update inventory `status` to `expired` when expiry_date < today (run as scheduled command daily)
- **Verify:** expired items shown with status

### [ ] T9.9 — Pharmacy Admin: Reports (FR-PH-013)
- Stock report (current quantities by medicine)
- Movement report (all movements in date range)
- Low stock report (quantity <= reorder_level)
- **Verify:** reports return correct data

---

## Phase 10 — Prescriptions

> The **most critical** module — encodes safety-critical business rules.

### [ ] T10.1 — Migration: prescriptions
- Per ERD §Prescriptions
- FKs per ERD with proper onDelete behaviors (mostly RESTRICT)
- Soft deletes; all indexes from ERD
- **Verify:** migration runs

### [ ] T10.2 — Migration: prescription_items
- Per ERD; FKs to prescriptions (cascade), medicines (restrict)
- **Verify:** migration runs

### [ ] T10.3 — Models: Prescription, PrescriptionItem
- `Prescription`: SoftDeletes, BelongsToHospital, Auditable; UUID + prescription_number auto-generated; relations to patient, doctor, hospital, pharmacy, medicalRecord, items, dispensedBy
- `PrescriptionItem`: relations to prescription, medicine; computed `total_price = quantity * unit_price`
- Status enum cast
- **Verify:** factory creates prescription with items

### [ ] T10.4 — Service: PrescriptionService
- File: `app/Services/PrescriptionService.php`
- Methods:
  - `create(Doctor $doctor, Patient $patient, array $data, array $items): Prescription` — validates, creates prescription + items in transaction, generates prescription_number, logs audit
  - `update(Prescription $rx, array $data, array $items): Prescription` — only if status=`pending`
  - `cancel(Prescription $rx, string $reason): Prescription` — sets status=`cancelled`, stores reason in notes/cancellation
  - `dispense(Prescription $rx, Pharmacy $pharmacy, User $pharmacist, array $itemQuantities): Prescription` — checks inventory, decrements stock, creates stock_movements, sets status=`dispensed` or `partially_dispensed`, sets `dispensed_at`, `dispensed_by`, `pharmacy_id`
- Each method enforces business rules from §10 of PROJECT_OVERVIEW
- **Verify:** unit tests for each method, including failure paths (insufficient stock, already dispensed, etc.)

### [ ] T10.5 — Doctor: Create prescription (FR-DR-008)
- Route: `GET/POST /doctor/patients/{patient}/prescriptions/create`
- Form: optional medical_record_id, valid_until, notes, diagnosis_summary, dynamic items table
- Each item: medicine (searchable dropdown), dosage, frequency, duration_days, quantity, route, instructions
- Min 1 item required (validation)
- **Verify:** form submits, prescription created with items, prescription_number generated

### [ ] T10.6 — Doctor: Edit/cancel prescription (FR-DR-011, FR-DR-012)
- Edit only when status=`pending` (not yet dispensed)
- Cancel any time before dispense, requires reason
- Audit logged
- **Verify:** dispensed prescription cannot be edited; cancellation captures reason

### [ ] T10.7 — Doctor: View prescriptions (FR-DR-009, FR-DR-010, FR-DR-013)
- Index: list of own prescriptions, filter by patient/status/date
- Show: full details + status timeline
- **Verify:** doctor sees only own + hospital-scoped prescriptions

### [ ] T10.8 — Pharmacy Admin: View pending prescriptions (FR-PH-008, FR-PH-009)
- Route: `GET /pharmacy/prescriptions`
- List prescriptions with status=`pending` or `partially_dispensed` for this hospital
- Filter by status, date, patient name, prescription_number
- Show details: patient info, doctor info, items with required quantities, current stock per medicine in this pharmacy
- **Verify:** list scoped correctly to pharmacy's hospital

### [ ] T10.9 — Pharmacy Admin: Dispense prescription (FR-PH-010)
- Route: `POST /pharmacy/prescriptions/{prescription}/dispense`
- Form: each item has dispensed quantity field (default = ordered quantity)
- Service call: `PrescriptionService::dispense()`
- On success: update prescription status, decrement stock, create stock_movements with `type=sale` and `reference_type=Prescription`
- On insufficient stock: validation error per item
- Update QR code if prescription is the patient's latest
- **Verify:** stock decrements correctly; partial dispense allowed; cannot dispense without stock

### [ ] T10.10 — Pharmacy Admin: Reject prescription (FR-PH-011)
- Reason required
- Status set to `cancelled` (or add `rejected` to enum if needed) with note
- Notify the prescribing doctor (Phase 12)
- **Verify:** rejection captured

### [ ] T10.11 — Prescription QR code
- After prescription is created, generate a QR code linking to `/qr/prescription/{code}` (resolves to a print-friendly view)
- Used by patients/pharmacies to scan + verify
- **Verify:** scanning QR opens prescription details

---

## Phase 11 — Patient Portal

> Patient self-service — view-only access to own data.

### [ ] T11.1 — Patient routes & middleware
- Routes group: `routes/patient.php`, prefix `/patient`, middleware `auth`, `role:patient`
- Authorization Policy: every action ensures `auth()->user()->patient->id == $resource->patient_id`

### [ ] T11.2 — Patient Dashboard / Profile (FR-PA-002, FR-PA-003)
- Route: `GET /patient/dashboard`
- Shows: profile card with name, MRN, phone, email, DOB, gender, city, blood type, allergies summary
- MRN displayed prominently (FR-PA-003)
- **Verify:** patient sees own data only

### [ ] T11.3 — Patient QR code page (FR-PA-004)
- Route: `GET /patient/qr-code`
- Shows the QR image, downloadable as PNG
- "Regenerate" button (writes audit log)
- **Verify:** QR resolves to latest prescription per FR-PA-006

### [ ] T11.4 — Public QR resolver (FR-PA-006)
- Route: `GET /qr/{code}` — public but rate-limited
- Resolves QR, increments scan_count
- Returns latest prescription view (or "no prescription" page)
- Sensitive data masked unless authenticated (e.g., shows medicines but not full medical history)
- **Verify:** scanning works; expired QR shows error

### [ ] T11.5 — Patient: Latest prescription (FR-PA-005)
- Route: `GET /patient/prescriptions/latest`
- Shows most recent prescription with full details: doctor name, hospital, items, instructions, status
- **Verify:** displays correctly

### [ ] T11.6 — Patient: All prescriptions (FR-PA-007, FR-PA-008)
- Route: `GET /patient/prescriptions` — paginated list
- Route: `GET /patient/prescriptions/{prescription}` — detail view
- Status badge + history
- **Verify:** patient cannot see another patient's prescription via URL tampering (Policy enforces)

### [ ] T11.7 — Patient: Medical history (FR-PA-009)
- Route: `GET /patient/medical-history`
- Shows medical records timeline with attachments
- Read-only
- **Verify:** sorted by visit_date desc; attachments downloadable

### [ ] T11.8 — Patient: Prescription status (FR-PA-010)
- Status displayed on each prescription view (already covered by T11.6)
- Optional: WebSocket/polling for live updates (out of scope v1 — show status on page load)

---

## Phase 12 — Audit Logs & Notifications

### [ ] T12.1 — Migration: audit_logs
- Per ERD; polymorphic auditable; JSON columns for old/new values; indexes per ERD
- **Note:** if using `owen-it/laravel-auditing`, its default schema is similar — adapt or use as-is. If keeping ERD schema, write a custom Auditable trait.
- **Verify:** migration runs

### [ ] T12.2 — Migration: notifications
- Use Laravel's default `php artisan notifications:table` migration
- Confirm schema matches ERD
- **Verify:** migration runs

### [ ] T12.3 — Auditable trait / package config
- If using package: configure auditable models in their classes
- Confirm logging on every Hospital/Doctor/Patient/Prescription/MedicalRecord/StockMovement create/update/delete
- Capture user_id, hospital_id, ip_address, user_agent, url, method
- **Verify:** test trail of actions appears in audit_logs

### [ ] T12.4 — Audit logs viewer (Super Admin: FR-SA-014, Hospital Admin scoped)
- Super Admin: `/super-admin/audit-logs` — all logs, filter by user, hospital, action, date range
- Hospital Admin: `/hospital-admin/audit-logs` — only own hospital
- Pagination + filters
- **Verify:** sensitive logs visible only to super admin

### [ ] T12.5 — Notifications system
- Use Laravel notifications (DB channel)
- Notification classes:
  - `PrescriptionCreated` — to pharmacy admins of patient's hospital
  - `PrescriptionDispensed` — to doctor
  - `PrescriptionRejected` — to doctor
  - `LowStockAlert` — to pharmacy admin (when reorder_level hit)
  - `ExpiringMedicine` — to pharmacy admin (scheduled)
  - `HospitalSuspended` — to hospital admins
- Bell icon in navbar with unread count + dropdown
- **Verify:** notifications fire and display

### [ ] T12.6 — Scheduled jobs
- File: `routes/console.php` or `app/Console/Kernel.php`
- Daily job: scan expiring inventory, send notifications, mark expired
- Daily job: clean up expired QR codes
- **Verify:** `php artisan schedule:list` shows the jobs

---

## Phase 13 — Appointments (lightweight v1)

> Appointments table exists in ERD but only doctor schedules + simple appointment booking covered in v1. Telemedicine/video out of scope.

### [ ] T13.1 — Migration: appointments
- Per ERD §Appointments
- FKs: patient (cascade), doctor (restrict), hospital (restrict), department (set null)
- Soft deletes; indexes per ERD
- **Verify:** migration runs

### [ ] T13.2 — Model: Appointment
- BelongsToHospital, SoftDeletes, Auditable
- Relations: patient, doctor, hospital, department
- UUID + appointment_number auto-generated
- Status enum cast
- **Verify:** factory works

### [ ] T13.3 — Hospital Admin: Appointments view
- Read-only list for now (scheduling can be added later)
- Filter by doctor, date, status
- **Verify:** scoped to hospital

### [ ] T13.4 — Doctor: Today's appointments on dashboard
- Query for appointments scheduled today for the logged-in doctor
- **Verify:** correct results

> **Note:** full appointment booking flow (patient picks slot from doctor schedule, conflict detection, status transitions) is **deferred** unless you explicitly want it in v1. The data model is ready for a later phase.

---

## Phase 14 — Polish, Testing, Deployment Prep

### [ ] T14.1 — Feature tests (happy paths)
- Auth flow (login, logout, password reset)
- Super Admin creates hospital + admin
- Hospital Admin creates doctor
- Doctor creates prescription for patient
- Pharmacy Admin dispenses prescription (with stock decrement assertion)
- Patient views own latest prescription
- Audit log entry exists after each major action
- Tenant isolation: doctor in hospital A cannot see patient in hospital B
- **Verify:** `php artisan test` passes

### [ ] T14.2 — Authorization audit
- Walk through every controller action; confirm Policy or Gate check
- Confirm Spatie permission middleware on every route
- Confirm `EnsureHospitalContext` on tenant routes
- **Verify:** manual penetration testing — try cross-tenant URLs

### [ ] T14.3 — UI polish
- Consistent Materio sidebar/navbar across all roles
- Role-based sidebar menu (each role sees only their items)
- Breadcrumbs on every page
- Flash messages (success/error) translated to Arabic
- Form validation errors displayed in Arabic with Materio styling
- **Verify:** visual review of every major page

### [ ] T14.4 — Performance pass
- Add eager loading to all index queries (use `with()` to avoid N+1)
- Confirm pagination on all list views (15-25 per page)
- Add DB indexes from ERD to migrations if any missed
- Use `php artisan optimize` for production
- **Verify:** Laravel Debugbar shows no N+1 on key pages

### [ ] T14.5 — Seeders for full demo
- Master DemoSeeder that creates:
  - 1 Super Admin (super@admin.test / password)
  - 3 hospitals across different cities
  - 1 Hospital Admin per hospital
  - 5 doctors per hospital (across various specialties + departments)
  - 3 pharmacies (1 in-hospital each)
  - 1 Pharmacy Admin per pharmacy
  - 30 medicines with inventory
  - 20 patients across hospitals
  - 50 sample medical records
  - 30 prescriptions in various statuses
- **Verify:** `php artisan migrate:fresh --seed` produces a fully usable demo

### [ ] T14.6 — Deployment checklist
- `.env.example` complete with all required keys
- `APP_ENV=production`, `APP_DEBUG=false` for production
- Storage symlink + permissions
- Cache: `php artisan config:cache && route:cache && view:cache`
- Queue worker for notifications (`php artisan queue:work`)
- Scheduler cron entry: `* * * * * cd /path && php artisan schedule:run >> /dev/null 2>&1`
- **Verify:** deploy-able artifact

### [ ] T14.7 — README & docs
- `README.md` with setup instructions, demo credentials, and feature overview
- Link to PROJECT_OVERVIEW.md and TASKS.md
- Screenshots of major screens
- **Verify:** a fresh dev can clone + run + log in within 15 minutes

---

## Cross-Cutting Concerns (Apply Throughout)

These are not standalone tasks but **rules for every task**:

- **Soft deletes** on every clinically significant model
- **Audit logging** on every C/U/D of tenant data
- **Hospital scope** on every tenant query
- **FormRequest validation** on every write endpoint
- **Policy authorization** on every resource action
- **Arabic translations** for every user-facing string (no hardcoded English)
- **Database transactions** for any multi-step write
- **UUID generation** on entities that have a `uuid` column (in `boot()` method)
- **Eager loading** to prevent N+1 (`->with(['relation'])`)
- **CSRF tokens** on every form (Blade `@csrf`)

---

## When You Get Stuck

- Re-read `PROJECT_OVERVIEW.md` for the relevant decision
- Check the ERD file for schema specifics
- Check the SRS for the FR-code that drives the requirement
- If still ambiguous → **stop and ask the user** — do not guess on medical/security logic

---

## Progress Log

> Append a one-liner here after each completed task, with date and any notes for the next developer.

- 2026-05-03 T0.1 done — Laravel 13.6 + PHP 8.3 + MySQL DB health_links + migrate:fresh OK (35 domain migrations pass)
- 2026-05-03 T0.2 done — spatie/laravel-permission + simplesoftwareio/simple-qrcode + owen-it/laravel-auditing + laravel/breeze (blade) installed; Node 18 warning on vite build (needs Node 20+, fix in T0.4)
- 2026-05-03 T0.3 done — config/permission.php + config/audit.php published; model_has_roles → user_roles; stub migrations deleted
- 2026-05-03 T0.4 done — Materio assets copied to public/assets/; app.blade.php RTL (dir=rtl lang=ar Cairo font); sidebar/navbar/footer partials created
- 2026-05-03 T0.5 done — APP_LOCALE=ar; resources/lang/ar/{app,auth,validation,pagination,passwords}.php created; __('validation.required') returns Arabic
- 2026-05-03 T0.6 done — storage:link created; filesystems default=public
- 2026-05-03 T1.1 done — CountrySeeder: 10 countries seeded
- 2026-05-03 T1.2 done — CitySeeder: 16 cities seeded across 6 countries
- 2026-05-03 T1.3 done — SpecialtySeeder: 20 specialties (Arabic names + English slugs)
- 2026-05-03 T1.4 done — MedicineCategorySeeder: 10 parent + 4 child categories; parent/child relation works
- 2026-05-03 T2.1 done — users migration already had all ERD fields; FK migration 000003 adds country/city FKs
- 2026-05-03 T2.2 done — roles/permissions/role_permissions custom migrations cover Spatie needs + extra columns (display_name, module)
- 2026-05-03 T2.3 done — user_roles has hospital_id; added model_type+model_id via migration 000032 for Spatie compat; model_has_permissions table added; config/permission.php maps role_has_permissions→role_permissions
- 2026-05-03 T2.4 done — 5 roles + 24 permissions seeded; super_admin gets all 24; role-specific permission sets assigned
- 2026-05-03 T2.5 done — User model: HasRoles + HasHospitalScopedRoles + all relationships + getFullNameAttribute
- 2026-05-03 T2.6 done — app/Traits/HasHospitalScopedRoles.php: hasRoleInHospital, hasPermissionInHospital, assignRoleInHospital, currentHospital, hospitalIds
- 2026-05-03 T2.7 done — Breeze auth views rewritten in Arabic/Materio RTL; login redirects by role; last_login_at+ip updated on login
- 2026-05-03 T2.8 done — EnsureHospitalContext middleware: auto-set session, hospital-picker redirect, suspended hospital block; registered as hospital.context
- 2026-05-03 T2.9 done — HospitalScope (filters by session hospital_id, bypasses super_admin) + BelongsToHospital trait (applies scope + hospital() relation)
- 2026-05-03 T3.1–T3.3 done — migrations already existed from Phase 0 scaffold; all ran OK (31 tables live)
- 2026-05-03 T3.4 done — Hospital: Auditable (OwenIt), admins() via hasManyThrough, scopeActive(), scopeBySubscription(); Department: BelongsToHospital trait added
- 2026-05-03 T3.5 done — HospitalController (resource), StoreHospitalRequest, UpdateHospitalRequest, HospitalOnboardingService (atomic create+admin, update, archive), routes/super-admin.php, 4 Blade views (index/create/edit/show)
- 2026-05-03 T5.1–T5.3 done — migrations existed from Phase 0 scaffold; doctors/doctor_specialties/doctor_schedules all ran OK
- 2026-05-03 T5.4 done — Doctor model: BelongsToHospital + Auditable added; getNameAttribute() + getIsAvailableAttribute() (checks column AND status=active)
- 2026-05-03 T5.5 done — DoctorController, DoctorOnboardingService (atomic User+Doctor+role in transaction), StoreDoctorRequest, UpdateDoctorRequest, routes/hospital-admin.php, 4 Blade views (index/create/edit/show); sidebar menu hospital-admin partial created
- 2026-05-03 T5.6 done — DoctorScheduleController: updateOrCreate per day, disable by leaving empty; weekly grid view (schedules.blade.php)
- 2026-05-03 T5.7 done — DashboardController: 4 stats cards scoped to current hospital; hospital-admin/dashboard.blade.php
- 2026-05-03 T3.6 done — PATCH hospitals/{hospital}/status in HospitalController@updateStatus; invalidates sessions of hospital users on inactive/suspended; soft-delete via destroy()
- 2026-05-03 T3.7 done — HospitalAdminController: index/create/store/edit/update/disable/resetPassword; nested routes under hospitals/{hospital}/admins; all actions audit-log
- 2026-05-03 T3.8 done — DashboardController: 4 stat cards (total/active/suspended/inactive hospitals + total users) + recent audit_logs (last 10); view: super-admin/dashboard.blade.php
