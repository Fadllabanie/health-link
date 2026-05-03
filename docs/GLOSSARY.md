# Glossary — قاموس المصطلحات

> **Critical:** SRS is in Arabic, code is in English. Use this glossary to map between them. Do not translate creatively.

---

## Core Entities — الكيانات الأساسية

| العربية | English | Code (model/table) |
|---|---|---|
| المنصة الطبية | Medical Platform | (project name) |
| المستشفى | Hospital | `Hospital` / `hospitals` |
| القسم | Department | `Department` / `departments` |
| الاختصاص / التخصص | Specialty | `Specialty` / `specialties` |
| الطبيب | Doctor | `Doctor` / `doctors` |
| المريض | Patient | `Patient` / `patients` |
| الصيدلية | Pharmacy | `Pharmacy` / `pharmacies` |
| الصيدلي / مدير الصيدلية | Pharmacist / Pharmacy Admin | `Pharmacist` / `pharmacists` |
| الدواء | Medicine | `Medicine` / `medicines` |
| فئة الأدوية | Medicine Category | `MedicineCategory` / `medicine_categories` |
| المخزون | Inventory / Stock | `PharmacyInventory` / `pharmacy_inventories` |
| الوصفة الطبية | Prescription | `Prescription` / `prescriptions` |
| بنود الوصفة / الأدوية في الوصفة | Prescription Items | `PrescriptionItem` / `prescription_items` |
| السجل الطبي / الملف الطبي | Medical Record | `MedicalRecord` / `medical_records` |
| التاريخ الطبي / التاريخ المرضي | Medical History | (collection of medical records) |
| المرفقات الطبية | Medical Record Attachments | `MedicalRecordAttachment` |
| الموعد / حجز موعد | Appointment | `Appointment` / `appointments` |
| الجدول الزمني للطبيب | Doctor Schedule | `DoctorSchedule` / `doctor_schedules` |
| رمز QR | QR Code | `QrCode` / `qr_codes` |
| المستخدم | User | `User` / `users` |
| الدور / الصلاحية | Role | `Role` / `roles` |
| الصلاحيات / الأذونات | Permission | `Permission` / `permissions` |
| سجل العمليات / سجل التدقيق | Audit Log | `AuditLog` / `audit_logs` |
| الإشعارات | Notifications | `Notification` / `notifications` |
| حركة المخزون | Stock Movement | `StockMovement` / `stock_movements` |

---

## User Roles — الأدوار

| العربية | English | Role name (Spatie) |
|---|---|---|
| المشرف العام | Super Admin | `super_admin` |
| مدير المستشفى | Hospital Admin | `hospital_admin` |
| الطبيب | Doctor | `doctor` |
| مدير الصيدلية / الصيدلي | Pharmacy Admin / Pharmacist | `pharmacist` |
| المريض | Patient | `patient` |

---

## Actions / Verbs — الأفعال والإجراءات

| العربية | English | Code term |
|---|---|---|
| تسجيل الدخول | Log in | `login` |
| تسجيل الخروج | Log out | `logout` |
| إضافة | Add / Create | `create` / `store` |
| تعديل | Edit / Update | `edit` / `update` |
| حذف | Delete | `destroy` (soft delete only) |
| أرشفة | Archive | (soft delete) |
| عرض | View / Show | `show` / `index` |
| البحث | Search | `search` |
| تصفية / فلترة | Filter | `filter` |
| تفعيل | Activate / Enable | `activate` / `enable` |
| تعطيل | Deactivate / Disable | `deactivate` / `disable` |
| إيقاف / تعليق | Suspend | `suspend` |
| إعادة تعيين | Reset | `reset` |
| إعادة تعيين كلمة المرور | Reset password | `reset-password` |
| تأكيد | Confirm | `confirm` |
| إلغاء | Cancel | `cancel` |
| رفض | Reject | `reject` |
| اعتماد | Approve / Finalize | `approve` / `finalize` |
| صرف (دواء/وصفة) | Dispense | `dispense` |
| إصدار (وصفة) | Issue | `issue` |
| الموافقة | Approve | `approve` |
| التحقق | Verify | `verify` |
| تنفيذ | Execute / Process | `process` |
| متابعة | Track / Monitor | `track` |
| إنشاء | Create / Generate | `create` / `generate` |
| ربط | Link / Associate | `attach` / `link` |
| فصل | Detach | `detach` |

---

## Status Values — قيم الحالات

### User status — حالة المستخدم
| العربية | English | DB value |
|---|---|---|
| نشط / فعّال | Active | `active` |
| غير نشط / غير فعّال | Inactive | `inactive` |
| موقوف / معلّق | Suspended | `suspended` |
| قيد الانتظار | Pending | `pending` |

### Hospital status — حالة المستشفى
- نشط → `active`
- غير نشط → `inactive`
- موقوف → `suspended`

### Prescription status — حالة الوصفة
| العربية | English | DB value |
|---|---|---|
| جديدة / قيد الانتظار | Pending | `pending` |
| قيد التنفيذ | Partially dispensed | `partially_dispensed` |
| مصروفة | Dispensed | `dispensed` |
| ملغاة | Cancelled | `cancelled` |
| منتهية الصلاحية | Expired | `expired` |
| مرفوضة | Rejected | (use `cancelled` with reason) |

### Medical Record status — حالة السجل الطبي
- مسودة → `draft`
- معتمد / نهائي → `finalized`
- معدّل → `amended`

### Visit type — نوع الزيارة
| العربية | English | DB value |
|---|---|---|
| استشارة | Consultation | `consultation` |
| متابعة | Follow-up | `follow_up` |
| طوارئ | Emergency | `emergency` |
| جراحة | Surgery | `surgery` |
| فحص دوري | Checkup | `checkup` |

### Appointment status
- مجدول → `scheduled`
- مؤكد → `confirmed`
- تم الحضور → `checked_in`
- مكتمل → `completed`
- ملغى → `cancelled`
- لم يحضر → `no_show`

### Inventory status — حالة المخزون
- متوفر → `available`
- مخزون منخفض → `low_stock`
- نفذ → `out_of_stock`
- منتهي الصلاحية → `expired`

### Stock movement type — نوع حركة المخزون
| العربية | English | DB value |
|---|---|---|
| شراء | Purchase | `purchase` |
| بيع / صرف | Sale | `sale` |
| إرجاع | Return | `return` |
| تعديل | Adjustment | `adjustment` |
| منتهي الصلاحية | Expired | `expired` |
| نقل | Transfer | `transfer` |

---

## Patient & Medical Fields — حقول المريض والطب

| العربية | English | Field |
|---|---|---|
| رقم المعرف الطبي / رقم الملف | Medical Record Number (MRN) | `medical_record_number` |
| فصيلة الدم | Blood type | `blood_type` |
| الطول | Height | `height_cm` |
| الوزن | Weight | `weight_kg` |
| الحساسية | Allergies | `allergies` |
| الأمراض المزمنة | Chronic conditions | `chronic_conditions` |
| الأدوية الحالية | Current medications | `current_medications` |
| جهة الاتصال للطوارئ | Emergency contact | `emergency_contact_*` |
| التأمين الصحي | Health insurance | `insurance_*` |
| الحالة الاجتماعية | Marital status | `marital_status` |
| المهنة | Occupation | `occupation` |
| تاريخ الميلاد | Date of birth | `date_of_birth` |
| الجنس | Gender | `gender` |
| الجنسية / الرقم القومي | National ID | `national_id` |

---

## Doctor Fields — حقول الطبيب

| العربية | English | Field |
|---|---|---|
| رقم الترخيص | License number | `license_number` |
| تاريخ انتهاء الترخيص | License expiry | `license_expires_at` |
| المؤهلات | Qualifications | `qualifications` |
| سنوات الخبرة | Years of experience | `years_of_experience` |
| نبذة | Bio | `bio` |
| رسوم الاستشارة | Consultation fee | `consultation_fee` |
| التوقيع | Signature | `signature` |
| الاختصاص الأساسي | Primary specialty | `primary_specialty_id` |
| الاختصاصات الفرعية | Secondary specialties | `doctor_specialties` |
| متاح | Available | `is_available` |
| التقييم | Rating | `rating` |

---

## Prescription Fields — حقول الوصفة

| العربية | English | Field |
|---|---|---|
| رقم الوصفة | Prescription number | `prescription_number` |
| الجرعة | Dosage | `dosage` |
| عدد المرات / التكرار | Frequency | `frequency` |
| مدة الاستخدام | Duration | `duration_days` |
| الكمية | Quantity | `quantity` |
| الكمية المصروفة | Quantity dispensed | `quantity_dispensed` |
| طريقة الاستخدام | Route of administration | `route` |
| التعليمات | Instructions | `instructions` |
| التشخيص | Diagnosis | `diagnosis_summary` |
| سبب الإلغاء | Cancellation reason | `cancellation_reason` |
| تاريخ الإصدار | Issued date | `issued_at` |
| صالحة حتى | Valid until | `valid_until` |
| تاريخ الصرف | Dispensed at | `dispensed_at` |

---

## Medicine Fields — حقول الدواء

| العربية | English | Field |
|---|---|---|
| الاسم التجاري | Brand name | `brand_name` |
| الاسم العلمي | Generic name | `generic_name` |
| الباركود | Barcode | `barcode` |
| الشركة المصنعة | Manufacturer | `manufacturer` |
| الشكل الدوائي | Form (tablet, capsule, etc.) | `form` |
| التركيز / الجرعة | Strength | `strength` |
| الوحدة | Unit (mg, ml, g) | `unit` |
| الآثار الجانبية | Side effects | `side_effects` |
| موانع الاستعمال | Contraindications | `contraindications` |
| تعليمات الجرعة | Dosage instructions | `dosage_instructions` |
| يتطلب وصفة | Requires prescription | `requires_prescription` |
| مادة مراقبة | Controlled substance | `is_controlled` |
| رقم التشغيلة / الدفعة | Batch number | `batch_number` |
| تاريخ الإنتاج | Manufacturing date | `manufacturing_date` |
| تاريخ الانتهاء | Expiry date | `expiry_date` |
| المورّد | Supplier | `supplier` |
| سعر الوحدة (تكلفة) | Unit cost | `unit_cost` |
| سعر البيع | Selling price | `selling_price` |
| حد إعادة الطلب | Reorder level | `reorder_level` |

---

## Common Phrases in SRS — عبارات شائعة في الـ SRS

| العربية | English meaning | Action in code |
|---|---|---|
| قواعد التحقق | Validation rules | FormRequest `rules()` |
| المتطلبات الوظيفية | Functional requirements | The FR-codes (FR-SA-001, etc.) |
| المتطلبات التقنية | Technical requirements | Architecture / non-functional |
| قواعد العمل | Business rules | Service layer enforcement |
| قصص المستخدم | User stories | Used to derive routes/controllers |
| لوحة التحكم | Dashboard | `/dashboard` route per role |
| المدخلات | Inputs | Request fields |
| المخرجات | Outputs | Response / result |
| حسب الصلاحية | According to permission | Spatie permission check |
| حسب سياسة النظام | Per system policy | **Ask user — ambiguous!** |
| فريد | Unique | DB unique constraint |
| مطلوب | Required | `required` validation rule |
| اختياري | Optional | `nullable` validation rule |
| لا يوجد | None / N/A | (no input needed) |

---

## Special Notes / Gotchas

1. **"حسب سياسة النظام"** ("per system policy") in the SRS = ambiguous. **Always stop and ask the user** what the policy should be.

2. **"وصفة طبية" = Prescription**, NOT "recipe". Common mistranslation.

3. **"السجل الطبي" vs "التاريخ الطبي":**
   - السجل الطبي = single Medical Record (one visit)
   - التاريخ الطبي = Medical History (collection of records)

4. **"صرف الوصفة"** = Dispense the prescription (pharmacist hands over medicine), NOT "spend".

5. **"معرف"** typically means ID/identifier in this SRS, not "introducer".

6. **"رقم المعرف للمريض"** = Patient's MRN (Medical Record Number), unique per patient.

7. **"رمز QR"** = QR code. Always use `QrCode` (PascalCase, no underscore).

8. **"مدير المستشفى"** vs **"المشرف العام"**:
   - مدير المستشفى = Hospital Admin (one hospital)
   - المشرف العام = Super Admin (whole platform)

9. **"اختصاص"** and **"تخصص"** are used interchangeably in the SRS — both mean Specialty.

10. **"قسم"** = Department (within a hospital), not "section" of a system.

---

**Use this glossary as the single source of truth for translations. If a term is missing, add it here before using it in code.**
