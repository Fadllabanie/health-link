# CLAUDE.md

> **This file is read automatically by Claude in every session.** It contains the immutable rules and context for this project. Do not skip these instructions.

---

## Project Identity

**Name:** Medical Platform (المنصة الطبية الإلكترونية)
**Type:** Multi-tenant Hospital Management System
**Stack:** Laravel 11 + Blade + MySQL 8 + Bootstrap 5 (Materio template, RTL)
**Language:** Arabic-only UI, RTL layout
**Solo developer:** working with Claude in VS Code

---

## Read These Before Any Task

1. `PROJECT_OVERVIEW.md` — architecture decisions, conventions, business rules
2. `TASKS.md` — ordered task list (work sequentially)
3. `DATABASE_SCHEMA.md` — clean ERD reference
4. `GLOSSARY.md` — Arabic ↔ English medical terminology

---

## Hard Rules (Never Break)

### Schema & Data
- ❌ **NEVER hard-delete** any record. Use `SoftDeletes` everywhere.
- ❌ **NEVER modify the ERD schema** without asking the user first.
- ❌ **NEVER skip `hospital_id`** on tenant-scoped tables.
- ✅ Always add the indexes specified in the ERD — they are not optional.
- ✅ Always generate UUID in model `boot()` for entities that have a `uuid` column.

### Multi-Tenancy
- ✅ Every tenant model **must** use the `BelongsToHospital` trait + `HospitalScope`.
- ✅ Super Admin bypasses `HospitalScope`; all other roles are scoped.
- ❌ **NEVER write a query on tenant data without hospital scoping** (unless inside a Super Admin context).

### Roles & Permissions
- ✅ Use Spatie `laravel-permission` package.
- ✅ Replace Spatie's default `model_has_roles` with our `user_roles` table (which has `hospital_id`).
- ✅ Always check permissions with `$user->hasPermissionInHospital($permission, $hospitalId)` for tenant-scoped roles.

### Audit & Security
- ✅ Every Create/Update/Delete on Hospital, Doctor, Patient, Prescription, MedicalRecord, StockMovement, Pharmacy, MedicineInventory **must write to `audit_logs`**.
- ✅ Every form must have `@csrf`.
- ✅ Every write endpoint must use a FormRequest with `authorize()` returning `true` only for permitted roles.
- ✅ Every resource action must have a Policy check.

### Localization
- ✅ All UI strings come from `resources/lang/ar/*.php`.
- ❌ **NEVER hardcode Arabic or English text in Blade views.** Use `__('key')` or `@lang('key')`.
- ✅ Master layout is `<html dir="rtl" lang="ar">`.
- ✅ Use Materio's RTL CSS variant (not the LTR one).

### Code Quality
- ✅ Controllers stay thin — push logic to `app/Services/*Service.php`.
- ✅ Use DB transactions (`DB::transaction()`) for any multi-step write.
- ✅ Eager load relations (`->with(['relation'])`) on every index query to prevent N+1.
- ✅ Use FormRequest for validation, never validate in controller.
- ✅ Use Eloquent enum casts (PHP 8.1+ enums) for all enum columns.

### Critical Business Rules (From SRS)
1. Inactive hospital → users cannot log in.
2. Every hospital must have ≥1 Hospital Admin.
3. Doctor must have ≥1 specialty AND ≥1 department.
4. Prescription requires patient + ≥1 medicine.
5. Cannot dispense prescription without inventory stock.
6. Every dispense decrements stock + writes a `stock_movements` row.
7. Cannot edit dispensed prescription.
8. Patient cannot access another patient's data.
9. Pharmacy admin works in one hospital only.

---

## When Asked to Code

### The Decision Tree

**Before writing any code, ask yourself:**

1. **Is this in `TASKS.md`?** If yes, follow the task's steps exactly.
2. **Does this affect the schema?** If yes, check `DATABASE_SCHEMA.md` first.
3. **Is there an existing similar pattern?** Search the codebase before creating new patterns.
4. **Am I sure about the requirement?** If ambiguous → **stop and ask the user**, do not guess on medical/security logic.
5. **Will this work for all 5 roles?** Check role-specific permissions.

### Default Patterns to Follow

**New CRUD module (e.g., Specialty):**
1. Migration with all ERD fields + indexes + soft deletes
2. Model with relationships, casts, traits, scopes
3. Factory + Seeder
4. FormRequests (Store + Update)
5. Resource Controller in role-specific namespace
6. Routes in role-specific routes file with middleware
7. Blade views in role-specific folder, using Materio components
8. Policy if cross-user access is possible
9. Feature test for happy path
10. Translation keys in `resources/lang/ar/`

**New Service (e.g., PrescriptionService):**
- Static methods or instance methods, your call — be consistent
- DB transaction wrapping all writes
- Validate business rules at the start
- Throw custom exceptions on failure
- Return the resulting model
- Write audit log entry

---

## File Naming Conventions

| Thing | Pattern | Example |
|---|---|---|
| Model | Singular PascalCase | `Hospital`, `Prescription` |
| Migration | Plural snake_case | `2024_01_01_create_hospitals_table.php` |
| Controller | `{Resource}Controller` in role namespace | `App\Http\Controllers\HospitalAdmin\DoctorController` |
| FormRequest | `{Action}{Resource}Request` | `StoreDoctorRequest`, `UpdateDoctorRequest` |
| Service | `{Domain}Service` | `PrescriptionService`, `InventoryService` |
| Policy | `{Resource}Policy` | `PrescriptionPolicy` |
| Trait | `{Capability}` | `BelongsToHospital`, `Auditable` |
| Scope | `{Description}Scope` | `HospitalScope`, `ActiveScope` |
| Blade view folder | role/resource/action | `hospital-admin/doctors/create.blade.php` |
| Translation file | snake_case | `resources/lang/ar/doctors.php` |

---

## Stack Versions (Do Not Change)

```
Laravel: 11.x
PHP: 8.2+
MySQL: 8.0
Bootstrap: 5.x (via Materio v3.0.0)
spatie/laravel-permission: ^6.x
simplesoftwareio/simple-qrcode: ^4.x
owen-it/laravel-auditing: ^13.x
```

---

## Common Commands

```bash
# Fresh install / reset
php artisan migrate:fresh --seed

# Run tests
php artisan test
php artisan test --filter=HospitalTest

# Make components
php artisan make:model Hospital -mfsc          # model + migration + factory + seeder + controller
php artisan make:request SuperAdmin/StoreHospitalRequest
php artisan make:policy HospitalPolicy --model=Hospital
php artisan make:middleware EnsureHospitalContext

# Cache (run after config changes)
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Storage
php artisan storage:link

# Inspect routes
php artisan route:list --name=super-admin
```

---

## When You're Unsure

**Stop and ask the user. Do not guess.**

Especially for:
- Medical workflow logic (prescription rules, dispensing logic)
- Security boundaries (who can see what)
- Schema changes
- Business rule interpretation
- Anything that says "حسب سياسة النظام" (per system policy) in the SRS

A wrong guess in a medical system is much worse than asking a clarifying question.

---

## How to Mark Progress

After completing a task in `TASKS.md`:
1. Change `[ ]` to `[x]`
2. Add a one-line note in the "Progress Log" section at the bottom of `TASKS.md`
3. Commit with message format: `feat(T5.5): add doctor CRUD for hospital admin`

---

## Tone & Communication

- Reply in **Arabic** when the user writes in Arabic, **English** when they write in English.
- Be concise. The user is solo, busy, building a real product.
- Show the user the file structure or output before assuming success.
- If you make a decision that wasn't explicitly approved, **flag it explicitly**.

---

**End of CLAUDE.md. Begin work after reading `PROJECT_OVERVIEW.md` and the relevant section of `TASKS.md`.**
