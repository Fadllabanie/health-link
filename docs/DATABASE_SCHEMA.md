# Database Schema Reference

> Clean reference of all tables, derived from `ERD.md`. Use this when writing migrations or queries.
>
> **Conventions:**
> - PK: `id BIGINT UNSIGNED AUTO_INCREMENT`
> - Timestamps: `created_at`, `updated_at` on all tables
> - Soft delete: `deleted_at` where indicated
> - All tenant tables include `hospital_id`

---

## 1. Authentication & Users

### `users`
The central user table. All roles (super_admin, hospital_admin, doctor, pharmacist, patient) live here.

| Column | Type | Notes |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| uuid | CHAR(36) UNIQUE | for external refs |
| first_name | VARCHAR(100) | |
| last_name | VARCHAR(100) | |
| email | VARCHAR(191) UNIQUE | |
| phone | VARCHAR(20) UNIQUE NULL | |
| email_verified_at | TIMESTAMP NULL | |
| phone_verified_at | TIMESTAMP NULL | |
| password | VARCHAR(255) | bcrypt |
| avatar | VARCHAR(255) NULL | |
| date_of_birth | DATE NULL | |
| gender | ENUM('male','female','other') NULL | |
| national_id | VARCHAR(50) UNIQUE NULL | |
| country_id | BIGINT UNSIGNED FK NULL | → countries |
| city_id | BIGINT UNSIGNED FK NULL | → cities |
| address | TEXT NULL | |
| status | ENUM('active','inactive','suspended','pending') | default 'pending' |
| two_factor_enabled | BOOLEAN | default false |
| two_factor_secret | TEXT NULL | |
| last_login_at | TIMESTAMP NULL | |
| last_login_ip | VARCHAR(45) NULL | |
| remember_token | VARCHAR(100) NULL | |
| deleted_at | TIMESTAMP NULL | soft delete |

**Indexes:** `email`, `phone`, `national_id`, `status`, composite `(country_id, city_id)`

---

### `roles` (Spatie + custom columns)
| Column | Type | Notes |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| name | VARCHAR(50) UNIQUE | super_admin, hospital_admin, doctor, pharmacist, patient |
| guard_name | VARCHAR(50) | default 'web' |
| display_name | VARCHAR(100) | Arabic label |
| description | TEXT NULL | |

### `permissions` (Spatie + custom columns)
| Column | Type | Notes |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| name | VARCHAR(100) UNIQUE | e.g., `hospitals.create` |
| guard_name | VARCHAR(50) | default 'web' |
| module | VARCHAR(50) | grouping: hospitals, doctors, prescriptions, etc. |
| description | TEXT NULL | |

### `role_permissions` (pivot)
- `role_id` FK → roles (cascade)
- `permission_id` FK → permissions (cascade)
- UNIQUE (role_id, permission_id)

### `user_roles` (pivot — REPLACES Spatie's `model_has_roles`)
**Critical:** this is hospital-scoped, unlike Spatie's default.

| Column | Type | Notes |
|---|---|---|
| id | BIGINT UNSIGNED PK | |
| user_id | BIGINT UNSIGNED FK | → users (cascade) |
| role_id | BIGINT UNSIGNED FK | → roles (cascade) |
| hospital_id | BIGINT UNSIGNED FK NULL | → hospitals; **NULL = global role (super_admin)** |
| assigned_by | BIGINT UNSIGNED FK NULL | → users |
| assigned_at | TIMESTAMP | |

**UNIQUE:** (user_id, role_id, hospital_id)

---

## 2. Master Data (Reference Tables)

### `countries`
- `name` VARCHAR(100), `code` CHAR(2) UNIQUE (ISO alpha-2), `code3` CHAR(3) UNIQUE
- `phone_code` VARCHAR(10) NULL, `currency_code` CHAR(3) NULL
- `is_active` BOOLEAN

### `cities`
- `country_id` FK → countries (cascade)
- `name` VARCHAR(100), `latitude` DECIMAL(10,8), `longitude` DECIMAL(11,8)
- `is_active` BOOLEAN
- Index: `country_id`, composite `(name, country_id)`

### `specialties`
- `name` VARCHAR(100) UNIQUE, `slug` VARCHAR(120) UNIQUE
- `icon` VARCHAR(255) NULL, `description` TEXT NULL
- `is_active` BOOLEAN, soft delete

### `medicine_categories`
- `name` VARCHAR(100) UNIQUE, `slug` VARCHAR(120) UNIQUE
- `parent_id` FK → medicine_categories (self-ref)
- `description` TEXT NULL, soft delete

---

## 3. Hospitals & Departments

### `hospitals`
| Column | Type |
|---|---|
| id, uuid (UNIQUE) | |
| name, slug (UNIQUE), license_number (UNIQUE) | |
| email (UNIQUE), phone, alternate_phone | |
| country_id, city_id | FKs |
| address, latitude, longitude | |
| logo, website, description | |
| established_date, bed_capacity | |
| subscription_plan | ENUM('free','basic','premium','enterprise') |
| subscription_expires_at | TIMESTAMP NULL |
| status | ENUM('active','inactive','suspended') |
| deleted_at | soft delete |

**Indexes:** status, city_id, subscription_plan

### `hospital_specialties` (pivot)
- `hospital_id` FK → hospitals (cascade)
- `specialty_id` FK → specialties (cascade)
- UNIQUE (hospital_id, specialty_id)

### `departments`
- `hospital_id` FK → hospitals (cascade) — **scoped per hospital**
- `name` VARCHAR(100), `code` VARCHAR(20)
- `head_doctor_id` FK → doctors NULL (set null on delete)
- `is_active` BOOLEAN, soft delete
- UNIQUE (hospital_id, name)

---

## 4. Doctors

### `doctors`
| Column | Type | Notes |
|---|---|---|
| user_id | BIGINT UNSIGNED UNIQUE FK | → users (cascade) — **1:1 with user** |
| hospital_id | FK → hospitals (RESTRICT) | |
| department_id | FK → departments NULL (set null) | |
| primary_specialty_id | FK → specialties (RESTRICT) | |
| license_number | VARCHAR(100) UNIQUE | |
| license_expires_at | DATE NULL | |
| qualifications | TEXT | |
| years_of_experience | TINYINT UNSIGNED | |
| bio | TEXT | |
| consultation_fee | DECIMAL(10,2) | |
| signature | VARCHAR(255) | path to image |
| is_available | BOOLEAN | |
| rating | DECIMAL(3,2) | default 0.00 |
| total_reviews | INT UNSIGNED | default 0 |
| status | ENUM('active','inactive','on_leave') | |
| joined_at | DATE | |
| deleted_at | soft delete |

**Indexes:** hospital_id, department_id, primary_specialty_id, status

### `doctor_specialties` (pivot — secondary specialties)
- `doctor_id` FK → doctors (cascade)
- `specialty_id` FK → specialties (cascade)
- UNIQUE (doctor_id, specialty_id)

### `doctor_schedules`
- `doctor_id` FK → doctors (cascade)
- `day_of_week` TINYINT UNSIGNED (0=Sun..6=Sat)
- `start_time`, `end_time` TIME
- `slot_duration_minutes` SMALLINT UNSIGNED (default 30)
- `is_active` BOOLEAN
- Index: composite `(doctor_id, day_of_week)`

---

## 5. Patients

### `patients`
| Column | Type | Notes |
|---|---|---|
| user_id | BIGINT UNSIGNED UNIQUE FK | 1:1 with user |
| hospital_id | FK NULL (set null) | primary hospital |
| qr_code_id | FK → qr_codes NULL (set null) | |
| city_id | FK → cities | |
| medical_record_number | VARCHAR(50) UNIQUE | MRN |
| blood_type | ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') | |
| height_cm, weight_kg | DECIMAL(5,2) | |
| allergies, chronic_conditions, current_medications | TEXT | |
| emergency_contact_name | VARCHAR(150) | |
| emergency_contact_phone | VARCHAR(20) | |
| emergency_contact_relation | VARCHAR(50) | |
| insurance_provider, insurance_policy_number | | |
| marital_status | ENUM('single','married','divorced','widowed') | |
| occupation | VARCHAR(100) | |
| deleted_at | soft delete |

**Indexes:** hospital_id, medical_record_number

### `patient_hospitals` (pivot — patients can visit multiple hospitals)
- `patient_id` FK (cascade), `hospital_id` FK (cascade)
- `registered_at` TIMESTAMP
- UNIQUE (patient_id, hospital_id)

---

## 6. QR Codes (Polymorphic)

### `qr_codes`
| Column | Type |
|---|---|
| code | VARCHAR(100) UNIQUE — encrypted token |
| qrable_type | VARCHAR(100) — 'App\Models\Patient' or 'App\Models\Prescription' |
| qrable_id | BIGINT UNSIGNED |
| image_path | VARCHAR(255) |
| scan_count | INT UNSIGNED default 0 |
| last_scanned_at | TIMESTAMP NULL |
| expires_at | TIMESTAMP NULL |
| is_active | BOOLEAN |
| deleted_at | soft delete |

**Indexes:** code, composite `(qrable_type, qrable_id)`

---

## 7. Medical Records

### `medical_records`
| Column | Type |
|---|---|
| uuid | UNIQUE |
| patient_id | FK (cascade) |
| doctor_id | FK (RESTRICT) |
| hospital_id | FK (RESTRICT) |
| visit_date | DATETIME |
| visit_type | ENUM('consultation','follow_up','emergency','surgery','checkup') |
| notes | TEXT |
| status | ENUM('draft','finalized','amended') |
| deleted_at | soft delete |

**Indexes:** patient_id, doctor_id, hospital_id, visit_date, **composite `(patient_id, visit_date)`**

### `medical_record_attachments`
- `medical_record_id` FK (cascade)
- `file_path`, `file_name`, `file_type`, `file_size`
- `description`, `uploaded_by` FK → users

---

## 8. Medicines

### `medicines` (global, no hospital_id)
| Column | Type |
|---|---|
| name, generic_name, brand_name | |
| barcode | UNIQUE NULL |
| category_id | FK → medicine_categories NULL |
| manufacturer | |
| form | ENUM('tablet','capsule','syrup','injection','cream','drops','inhaler','other') |
| strength | VARCHAR(50) — e.g. "500mg" |
| unit | VARCHAR(20) — mg, ml, g |
| description, side_effects, contraindications, dosage_instructions | TEXT |
| requires_prescription | BOOLEAN default true |
| is_controlled | BOOLEAN default false |
| image | VARCHAR(255) |
| is_active | BOOLEAN |
| deleted_at | soft delete |

**Indexes:** name, generic_name, barcode, category_id

---

## 9. Prescriptions

### `prescriptions`
| Column | Type | Notes |
|---|---|---|
| uuid | UNIQUE | |
| prescription_number | VARCHAR(50) UNIQUE | auto-generated |
| medical_record_id | FK NULL (set null) | optional link |
| patient_id | FK (RESTRICT) | |
| doctor_id | FK (RESTRICT) | |
| hospital_id | FK (RESTRICT) | |
| pharmacy_id | FK → pharmacies NULL (set null) | dispensing pharmacy |
| issued_at | DATETIME | |
| valid_until | DATE | |
| notes, diagnosis_summary | TEXT | |
| status | ENUM('pending','partially_dispensed','dispensed','cancelled','expired') | default 'pending' |
| dispensed_at | DATETIME NULL | |
| dispensed_by | FK → users NULL | pharmacist who dispensed |
| total_amount | DECIMAL(10,2) | |
| deleted_at | soft delete |

**Indexes:** patient_id, doctor_id, hospital_id, pharmacy_id, status, issued_at, prescription_number

### `prescription_items`
| Column | Type |
|---|---|
| prescription_id | FK (cascade) |
| medicine_id | FK (RESTRICT) |
| dosage | VARCHAR(100) — "1 tablet" |
| frequency | VARCHAR(100) — "twice daily" |
| duration_days | SMALLINT UNSIGNED |
| quantity | INT UNSIGNED — ordered |
| quantity_dispensed | INT UNSIGNED default 0 |
| route | VARCHAR(50) — oral, IV, topical |
| instructions | TEXT |
| unit_price, total_price | DECIMAL(10,2) |
| is_dispensed | BOOLEAN |

**Indexes:** prescription_id, medicine_id

---

## 10. Pharmacies & Inventory

### `pharmacies`
| Column | Type | Notes |
|---|---|---|
| uuid | UNIQUE | |
| hospital_id | FK NULL (cascade) | **NULL = independent external pharmacy** |
| name, slug (UNIQUE), license_number (UNIQUE), email (UNIQUE) | | |
| phone, country_id, city_id, address, lat/lng | | |
| logo | | |
| type | ENUM('in_hospital','external','chain') | |
| is_24_hours | BOOLEAN | |
| opening_time, closing_time | TIME | |
| status | ENUM('active','inactive','suspended') | |
| deleted_at | soft delete |

**Indexes:** hospital_id, city_id, status

### `pharmacists`
- `user_id` FK UNIQUE (cascade) — 1:1 with user
- `pharmacy_id` FK (cascade)
- `license_number` UNIQUE, `license_expires_at` DATE
- `position` VARCHAR(100), `is_active` BOOLEAN
- soft delete

### `pharmacy_inventories`
| Column | Type |
|---|---|
| pharmacy_id | FK (cascade) |
| medicine_id | FK (RESTRICT) |
| batch_number | VARCHAR(100) |
| quantity_in_stock | INT default 0 |
| reorder_level | INT UNSIGNED default 10 |
| unit_cost, selling_price | DECIMAL(10,2) |
| manufacturing_date | DATE |
| expiry_date | DATE |
| supplier | VARCHAR(191) |
| location | VARCHAR(100) — shelf/rack |
| status | ENUM('available','low_stock','out_of_stock','expired') |
| deleted_at | soft delete |

**UNIQUE:** (pharmacy_id, medicine_id, batch_number)
**Indexes:** pharmacy_id, medicine_id, expiry_date, status

### `stock_movements`
| Column | Type | Notes |
|---|---|---|
| pharmacy_inventory_id | FK (cascade) | |
| type | ENUM('purchase','sale','return','adjustment','expired','transfer') | |
| quantity | INT | positive or negative |
| reference_type | VARCHAR(100) NULL | polymorphic: Prescription, Purchase |
| reference_id | BIGINT UNSIGNED NULL | |
| unit_price | DECIMAL(10,2) | |
| notes | TEXT | |
| performed_by | FK → users | |

**Indexes:** pharmacy_inventory_id, composite `(reference_type, reference_id)`, type

---

## 11. Appointments

### `appointments`
| Column | Type |
|---|---|
| uuid (UNIQUE), appointment_number (UNIQUE) | |
| patient_id (cascade), doctor_id (RESTRICT), hospital_id (RESTRICT) | |
| department_id NULL (set null) | |
| scheduled_at | DATETIME |
| duration_minutes | SMALLINT default 30 |
| type | ENUM('in_person','video','phone') |
| reason | TEXT |
| status | ENUM('scheduled','confirmed','checked_in','completed','cancelled','no_show') |
| cancellation_reason | TEXT |
| fee | DECIMAL(10,2) |
| deleted_at | soft delete |

**Indexes:** patient_id, doctor_id, hospital_id, scheduled_at, status

---

## 12. Audit & Notifications

### `audit_logs` (polymorphic)
| Column | Type |
|---|---|
| user_id | FK NULL (set null) |
| hospital_id | FK NULL (set null) |
| action | VARCHAR(100) — created, updated, deleted, viewed, login, logout |
| auditable_type | VARCHAR(100) |
| auditable_id | BIGINT UNSIGNED |
| old_values, new_values | JSON NULL |
| ip_address | VARCHAR(45) |
| user_agent | VARCHAR(500) |
| url | VARCHAR(500) |
| method | VARCHAR(10) |

**Indexes:** user_id, hospital_id, composite `(auditable_type, auditable_id)`, action, created_at

### `notifications` (Laravel default)
| Column | Type |
|---|---|
| id | CHAR(36) PK |
| type | VARCHAR(191) |
| notifiable_type, notifiable_id | morphTo |
| data | JSON |
| read_at | TIMESTAMP NULL |

---

## Relationship Map (Quick Reference)

### One-to-One
- User ↔ Doctor
- User ↔ Patient
- User ↔ Pharmacist
- Patient ↔ QrCode

### One-to-Many
- Country → Cities, Hospitals
- City → Hospitals, Pharmacies, Users
- Hospital → Doctors, Departments, Pharmacies, MedicalRecords, Prescriptions, Appointments
- Department → Doctors
- Specialty → Doctors (primary)
- Doctor → Prescriptions, MedicalRecords, Appointments, DoctorSchedules
- Patient → MedicalRecords, Prescriptions, Appointments
- MedicalRecord → MedicalRecordAttachments, Prescriptions
- Prescription → PrescriptionItems
- Medicine → PrescriptionItems, PharmacyInventories
- MedicineCategory → Medicines, MedicineCategories (self-ref)
- Pharmacy → Pharmacists, PharmacyInventories, Prescriptions (dispensed)
- PharmacyInventory → StockMovements
- User → AuditLogs

### Many-to-Many
- Users ↔ Roles (via `user_roles`, **scoped per hospital**)
- Roles ↔ Permissions (via `role_permissions`)
- Hospitals ↔ Specialties (via `hospital_specialties`)
- Doctors ↔ Specialties (via `doctor_specialties` — secondary)
- Patients ↔ Hospitals (via `patient_hospitals`)

### Polymorphic
- AuditLogs → any model (`auditable`)
- StockMovements → Prescription | Purchase (`reference`)
- Notifications → User (`notifiable`)
- QrCodes → Patient | Prescription (`qrable`)

---

## Migration Order (Critical — Follow This Sequence)

```
1.  countries
2.  cities                    (FK → countries)
3.  specialties
4.  medicine_categories       (self-ref)
5.  users                     (FK → countries, cities)
6.  roles, permissions, role_permissions  (Spatie)
7.  hospitals                 (FK → countries, cities)
8.  user_roles                (FK → users, roles, hospitals)
9.  hospital_specialties      (FK → hospitals, specialties)
10. departments               (FK → hospitals; head_doctor_id added later)
11. doctors                   (FK → users, hospitals, departments, specialties)
12. ALTER departments ADD head_doctor_id FK → doctors
13. doctor_specialties        (FK → doctors, specialties)
14. doctor_schedules          (FK → doctors)
15. qr_codes                  (polymorphic, no FK at table level)
16. patients                  (FK → users, hospitals, qr_codes, cities)
17. patient_hospitals         (FK → patients, hospitals)
18. medical_records           (FK → patients, doctors, hospitals)
19. medical_record_attachments (FK → medical_records, users)
20. medicines                 (FK → medicine_categories)
21. pharmacies                (FK → hospitals, countries, cities)
22. pharmacists               (FK → users, pharmacies)
23. pharmacy_inventories      (FK → pharmacies, medicines)
24. prescriptions             (FK → medical_records, patients, doctors, hospitals, pharmacies, users)
25. prescription_items        (FK → prescriptions, medicines)
26. stock_movements           (FK → pharmacy_inventories, users; polymorphic reference)
27. appointments              (FK → patients, doctors, hospitals, departments)
28. audit_logs                (FK → users, hospitals; polymorphic auditable)
29. notifications             (Laravel default)
```

**Note on departments ↔ doctors circular FK:** create `departments` first without `head_doctor_id`, then create `doctors`, then ALTER `departments` to add `head_doctor_id` FK. Or create `head_doctor_id` as a nullable column without FK first, then add the FK constraint later.

---

**End of Schema Reference.**
