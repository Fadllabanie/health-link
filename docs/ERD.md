

# Medical Platform ERD — Complete Database Design

## A. Clean ERD Table Structure

### Authentication & User Management

Table: users

* id BIGINT UNSIGNED (PK, AUTO\_INCREMENT)  
* uuid CHAR(36) UNIQUE NOT NULL  
* first\_name VARCHAR(100) NOT NULL  
* last\_name VARCHAR(100) NOT NULL  
* email VARCHAR(191) UNIQUE NOT NULL  
* phone VARCHAR(20) UNIQUE NULL  
* email\_verified\_at TIMESTAMP NULL  
* phone\_verified\_at TIMESTAMP NULL  
* password VARCHAR(255) NOT NULL  
* avatar VARCHAR(255) NULL  
* date\_of\_birth DATE NULL  
* gender ENUM('male','female','other') NULL  
* national\_id VARCHAR(50) UNIQUE NULL  
* country\_id BIGINT UNSIGNED NULL (FK → countries.id)  
* city\_id BIGINT UNSIGNED NULL (FK → cities.id)  
* address TEXT NULL  
* status ENUM('active','inactive','suspended','pending') DEFAULT 'pending'  
* two\_factor\_enabled BOOLEAN DEFAULT FALSE  
* two\_factor\_secret TEXT NULL  
* last\_login\_at TIMESTAMP NULL  
* last\_login\_ip VARCHAR(45) NULL  
* remember\_token VARCHAR(100) NULL  
* created\_at TIMESTAMP NULL  
* updated\_at TIMESTAMP NULL  
* deleted\_at TIMESTAMP NULL

Indexes: email, phone, national\_id, status, (country\_id, city\_id)

---

Table: roles

* id BIGINT UNSIGNED (PK)  
* name VARCHAR(50) UNIQUE NOT NULL *(super\_admin, hospital\_admin, doctor, pharmacist, patient)*  
* guard\_name VARCHAR(50) DEFAULT 'web'  
* display\_name VARCHAR(100) NOT NULL  
* description TEXT NULL  
* created\_at, updated\_at

Table: permissions

* id BIGINT UNSIGNED (PK)  
* name VARCHAR(100) UNIQUE NOT NULL  
* guard\_name VARCHAR(50) DEFAULT 'web'  
* module VARCHAR(50) NOT NULL  
* description TEXT NULL  
* created\_at, updated\_at

Table: role\_permissions *(pivot)*

* id BIGINT UNSIGNED (PK)  
* role\_id BIGINT UNSIGNED (FK → roles.id) ON DELETE CASCADE  
* permission\_id BIGINT UNSIGNED (FK → permissions.id) ON DELETE CASCADE  
* UNIQUE (role\_id, permission\_id)

Table: user\_roles *(pivot — scoped per hospital for multi-tenancy)*

* id BIGINT UNSIGNED (PK)  
* user\_id BIGINT UNSIGNED (FK → users.id) ON DELETE CASCADE  
* role\_id BIGINT UNSIGNED (FK → roles.id) ON DELETE CASCADE  
* hospital\_id BIGINT UNSIGNED NULL (FK → hospitals.id) *(null \= global role)*  
* assigned\_by BIGINT UNSIGNED NULL (FK → users.id)  
* assigned\_at TIMESTAMP  
* UNIQUE (user\_id, role\_id, hospital\_id)

---

###  Master Data

Table: countries

* id BIGINT UNSIGNED (PK)  
* name VARCHAR(100) NOT NULL  
* code CHAR(2) UNIQUE NOT NULL *(ISO 3166-1 alpha-2)*  
* code3 CHAR(3) UNIQUE NOT NULL  
* phone\_code VARCHAR(10) NULL  
* currency\_code CHAR(3) NULL  
* is\_active BOOLEAN DEFAULT TRUE  
* created\_at, updated\_at

Table: cities

* id BIGINT UNSIGNED (PK)  
* country\_id BIGINT UNSIGNED (FK → countries.id) ON DELETE CASCADE  
* name VARCHAR(100) NOT NULL  
* latitude DECIMAL(10,8) NULL  
* longitude DECIMAL(11,8) NULL  
* is\_active BOOLEAN DEFAULT TRUE  
* created\_at, updated\_at  
* Index: country\_id, (name, country\_id)

Table: specialties

* id BIGINT UNSIGNED (PK)  
* name VARCHAR(100) UNIQUE NOT NULL  
* slug VARCHAR(120) UNIQUE NOT NULL  
* icon VARCHAR(255) NULL  
* description TEXT NULL  
* is\_active BOOLEAN DEFAULT TRUE  
* created\_at, updated\_at, deleted\_at

Table: departments

* id BIGINT UNSIGNED (PK)  
* hospital\_id BIGINT UNSIGNED (FK → hospitals.id) ON DELETE CASCADE  
* name VARCHAR(100) NOT NULL  
* code VARCHAR(20) NULL  
* description TEXT NULL  
* head\_doctor\_id BIGINT UNSIGNED NULL (FK → doctors.id)  
* is\_active BOOLEAN DEFAULT TRUE  
* created\_at, updated\_at, deleted\_at  
* UNIQUE (hospital\_id, name)

---

###  Hospitals

Table: hospitals

* id BIGINT UNSIGNED (PK)  
* uuid CHAR(36) UNIQUE NOT NULL  
* name VARCHAR(191) NOT NULL  
* slug VARCHAR(191) UNIQUE NOT NULL  
* license\_number VARCHAR(100) UNIQUE NOT NULL  
* email VARCHAR(191) UNIQUE NOT NULL  
* phone VARCHAR(20) NOT NULL  
* alternate\_phone VARCHAR(20) NULL  
* country\_id BIGINT UNSIGNED (FK → countries.id)  
* city\_id BIGINT UNSIGNED (FK → cities.id)  
* address TEXT NOT NULL  
* latitude DECIMAL(10,8) NULL  
* longitude DECIMAL(11,8) NULL  
* logo VARCHAR(255) NULL  
* website VARCHAR(255) NULL  
* description TEXT NULL  
* established\_date DATE NULL  
* bed\_capacity INT UNSIGNED NULL  
* subscription\_plan ENUM('free','basic','premium','enterprise') DEFAULT 'basic'  
* subscription\_expires\_at TIMESTAMP NULL  
* status ENUM('active','inactive','suspended') DEFAULT 'active'  
* created\_at, updated\_at, deleted\_at  
* Indexes: status, city\_id, subscription\_plan

Table: hospital\_specialties *(pivot)*

* id BIGINT UNSIGNED (PK)  
* hospital\_id BIGINT UNSIGNED (FK → hospitals.id) ON DELETE CASCADE  
* specialty\_id BIGINT UNSIGNED (FK → specialties.id) ON DELETE CASCADE  
* UNIQUE (hospital\_id, specialty\_id)

---

###  Doctors

Table: doctors

* id BIGINT UNSIGNED (PK)  
* user\_id BIGINT UNSIGNED UNIQUE (FK → users.id) ON DELETE CASCADE  
* hospital\_id BIGINT UNSIGNED (FK → hospitals.id) ON DELETE RESTRICT  
* department\_id BIGINT UNSIGNED NULL (FK → departments.id) ON DELETE SET NULL  
* primary\_specialty\_id BIGINT UNSIGNED (FK → specialties.id)  
* license\_number VARCHAR(100) UNIQUE NOT NULL  
* license\_expires\_at DATE NULL  
* qualifications TEXT NULL  
* years\_of\_experience TINYINT UNSIGNED NULL  
* bio TEXT NULL  
* consultation\_fee DECIMAL(10,2) NULL  
* signature VARCHAR(255) NULL *(path to signature image for prescriptions)*  
* is\_available BOOLEAN DEFAULT TRUE  
* rating DECIMAL(3,2) DEFAULT 0.00  
* total\_reviews INT UNSIGNED DEFAULT 0  
* status ENUM('active','inactive','on\_leave') DEFAULT 'active'  
* joined\_at DATE NULL  
* created\_at, updated\_at, deleted\_at  
* Indexes: hospital\_id, department\_id, primary\_specialty\_id, status

Table: doctor\_specialties *(pivot — secondary specialties)*

* id BIGINT UNSIGNED (PK)  
* doctor\_id BIGINT UNSIGNED (FK → doctors.id) ON DELETE CASCADE  
* specialty\_id BIGINT UNSIGNED (FK → specialties.id) ON DELETE CASCADE  
* UNIQUE (doctor\_id, specialty\_id)

Table: doctor\_schedules

* id BIGINT UNSIGNED (PK)  
* doctor\_id BIGINT UNSIGNED (FK → doctors.id) ON DELETE CASCADE  
* day\_of\_week TINYINT UNSIGNED NOT NULL *(0=Sunday..6=Saturday)*  
* start\_time TIME NOT NULL  
* end\_time TIME NOT NULL  
* slot\_duration\_minutes SMALLINT UNSIGNED DEFAULT 30  
* is\_active BOOLEAN DEFAULT TRUE  
* created\_at, updated\_at  
* Index: (doctor\_id, day\_of\_week)

---

###  Patients

Table: patients

* id BIGINT UNSIGNED (PK)  
* user\_id BIGINT UNSIGNED UNIQUE (FK → users.id) ON DELETE CASCADE  
* hospital\_id BIGINT UNSIGNED NULL (FK → hospitals.id) ON DELETE SET NULL *(primary hospital)*  
* qr\_code\_id BIGINT UNSIGNED NULL (FK → qr\_codes.id) ON DELETE SET NULL  
* city\_id BIGINT UNSIGNED NULL (FK → cities.id)  
* medical\_record\_number VARCHAR(50) UNIQUE NOT NULL *(MRN)*  
* blood\_type ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NULL  
* height\_cm DECIMAL(5,2) NULL  
* weight\_kg DECIMAL(5,2) NULL  
* allergies TEXT NULL  
* chronic\_conditions TEXT NULL  
* current\_medications TEXT NULL  
* emergency\_contact\_name VARCHAR(150) NULL  
* emergency\_contact\_phone VARCHAR(20) NULL  
* emergency\_contact\_relation VARCHAR(50) NULL  
* insurance\_provider VARCHAR(150) NULL  
* insurance\_policy\_number VARCHAR(100) NULL  
* marital\_status ENUM('single','married','divorced','widowed') NULL  
* occupation VARCHAR(100) NULL  
* created\_at, updated\_at, deleted\_at  
* Indexes: hospital\_id, medical\_record\_number

Table: patient\_hospitals *(pivot — patients can visit multiple hospitals)*

* id BIGINT UNSIGNED (PK)  
* patient\_id BIGINT UNSIGNED (FK → patients.id) ON DELETE CASCADE  
* hospital\_id BIGINT UNSIGNED (FK → hospitals.id) ON DELETE CASCADE  
* registered\_at TIMESTAMP  
* UNIQUE (patient\_id, hospital\_id)

---

###  QR Codes

Table: qr\_codes

* id BIGINT UNSIGNED (PK)  
* code VARCHAR(100) UNIQUE NOT NULL *(encrypted token)*  
* qrable\_type VARCHAR(100) NOT NULL *(polymorphic: Patient, Prescription)*  
* qrable\_id BIGINT UNSIGNED NOT NULL  
* image\_path VARCHAR(255) NULL  
* scan\_count INT UNSIGNED DEFAULT 0  
* last\_scanned\_at TIMESTAMP NULL  
* expires\_at TIMESTAMP NULL  
* is\_active BOOLEAN DEFAULT TRUE  
* created\_at, updated\_at, deleted\_at  
* Indexes: code, (qrable\_type, qrable\_id)

---

###  Medical Records

Table: medical\_records

* id BIGINT UNSIGNED (PK)  
* uuid CHAR(36) UNIQUE NOT NULL  
* patient\_id BIGINT UNSIGNED (FK → patients.id) ON DELETE CASCADE  
* doctor\_id BIGINT UNSIGNED (FK → doctors.id) ON DELETE RESTRICT  
* hospital\_id BIGINT UNSIGNED (FK → hospitals.id) ON DELETE RESTRICT  
* visit\_date DATETIME NOT NULL  
* visit\_type ENUM('consultation','follow\_up','emergency','surgery','checkup') DEFAULT 'consultation'  
* notes TEXT NULL  
* status ENUM('draft','finalized','amended') DEFAULT 'draft'  
* created\_at, updated\_at, deleted\_at  
* Indexes: patient\_id, doctor\_id, hospital\_id, visit\_date, (patient\_id, visit\_date)

Table: medical\_record\_attachments

* id BIGINT UNSIGNED (PK)  
* medical\_record\_id BIGINT UNSIGNED (FK → medical\_records.id) ON DELETE CASCADE  
* file\_path VARCHAR(255) NOT NULL  
* file\_name VARCHAR(255) NOT NULL  
* file\_type VARCHAR(50) NOT NULL  
* file\_size INT UNSIGNED NOT NULL  
* description VARCHAR(255) NULL  
* uploaded\_by BIGINT UNSIGNED (FK → users.id)  
* created\_at, updated\_at

---

###  Medicines

Table: medicines

* id BIGINT UNSIGNED (PK)  
* name VARCHAR(191) NOT NULL  
* generic\_name VARCHAR(191) NULL  
* brand\_name VARCHAR(191) NULL  
* barcode VARCHAR(100) UNIQUE NULL  
* category\_id BIGINT UNSIGNED NULL (FK → medicine\_categories.id)  
* manufacturer VARCHAR(191) NULL  
* form ENUM('tablet','capsule','syrup','injection','cream','drops','inhaler','other') NOT NULL  
* strength VARCHAR(50) NULL *(e.g., 500mg)*  
* unit VARCHAR(20) NULL *(mg, ml, g)*  
* description TEXT NULL  
* side\_effects TEXT NULL  
* contraindications TEXT NULL  
* dosage\_instructions TEXT NULL  
* requires\_prescription BOOLEAN DEFAULT TRUE  
* is\_controlled BOOLEAN DEFAULT FALSE  
* image VARCHAR(255) NULL  
* is\_active BOOLEAN DEFAULT TRUE  
* created\_at, updated\_at, deleted\_at  
* Indexes: name, generic\_name, barcode, category\_id

Table: medicine\_categories

* id BIGINT UNSIGNED (PK)  
* name VARCHAR(100) UNIQUE NOT NULL  
* slug VARCHAR(120) UNIQUE NOT NULL  
* parent\_id BIGINT UNSIGNED NULL (FK → medicine\_categories.id)  
* description TEXT NULL  
* is\_active BOOLEAN DEFAULT TRUE  
* created\_at, updated\_at, deleted\_at

---

###  Prescriptions

Table: prescriptions

* id BIGINT UNSIGNED (PK)  
* uuid CHAR(36) UNIQUE NOT NULL  
* prescription\_number VARCHAR(50) UNIQUE NOT NULL  
* medical\_record\_id BIGINT UNSIGNED NULL (FK → medical\_records.id) ON DELETE SET NULL  
* patient\_id BIGINT UNSIGNED (FK → patients.id) ON DELETE RESTRICT  
* doctor\_id BIGINT UNSIGNED (FK → doctors.id) ON DELETE RESTRICT  
* hospital\_id BIGINT UNSIGNED (FK → hospitals.id) ON DELETE RESTRICT  
* pharmacy\_id BIGINT UNSIGNED NULL (FK → pharmacies.id) ON DELETE SET NULL  
* issued\_at DATETIME NOT NULL  
* valid\_until DATE NULL  
* notes TEXT NULL  
* diagnosis\_summary TEXT NULL  
* status ENUM('pending','partially\_dispensed','dispensed','cancelled','expired') DEFAULT 'pending'  
* dispensed\_at DATETIME NULL  
* dispensed\_by BIGINT UNSIGNED NULL (FK → users.id)  
* total\_amount DECIMAL(10,2) NULL  
* created\_at, updated\_at, deleted\_at  
* Indexes: patient\_id, doctor\_id, hospital\_id, pharmacy\_id, status, issued\_at, prescription\_number

Table: prescription\_items

* id BIGINT UNSIGNED (PK)  
* prescription\_id BIGINT UNSIGNED (FK → prescriptions.id) ON DELETE CASCADE  
* medicine\_id BIGINT UNSIGNED (FK → medicines.id) ON DELETE RESTRICT  
* dosage VARCHAR(100) NOT NULL *(e.g., "1 tablet")*  
* frequency VARCHAR(100) NOT NULL *(e.g., "twice daily")*  
* duration\_days SMALLINT UNSIGNED NULL  
* quantity INT UNSIGNED NOT NULL  
* quantity\_dispensed INT UNSIGNED DEFAULT 0  
* route VARCHAR(50) NULL *(oral, IV, topical)*  
* instructions TEXT NULL  
* unit\_price DECIMAL(10,2) NULL  
* total\_price DECIMAL(10,2) NULL  
* is\_dispensed BOOLEAN DEFAULT FALSE  
* created\_at, updated\_at  
* Indexes: prescription\_id, medicine\_id

---

###  Pharmacies

Table: pharmacies

* id BIGINT UNSIGNED (PK)  
* uuid CHAR(36) UNIQUE NOT NULL  
* hospital\_id BIGINT UNSIGNED NULL (FK → hospitals.id) ON DELETE CASCADE *(null \= independent)*  
* name VARCHAR(191) NOT NULL  
* slug VARCHAR(191) UNIQUE NOT NULL  
* license\_number VARCHAR(100) UNIQUE NOT NULL  
* email VARCHAR(191) UNIQUE NOT NULL  
* phone VARCHAR(20) NOT NULL  
* country\_id BIGINT UNSIGNED (FK → countries.id)  
* city\_id BIGINT UNSIGNED (FK → cities.id)  
* address TEXT NOT NULL  
* latitude DECIMAL(10,8) NULL  
* longitude DECIMAL(11,8) NULL  
* logo VARCHAR(255) NULL  
* type ENUM('in\_hospital','external','chain') DEFAULT 'external'  
* is\_24\_hours BOOLEAN DEFAULT FALSE  
* opening\_time TIME NULL  
* closing\_time TIME NULL  
* status ENUM('active','inactive','suspended') DEFAULT 'active'  
* created\_at, updated\_at, deleted\_at  
* Indexes: hospital\_id, city\_id, status

Table: pharmacists

* id BIGINT UNSIGNED (PK)  
* user\_id BIGINT UNSIGNED UNIQUE (FK → users.id) ON DELETE CASCADE  
* pharmacy\_id BIGINT UNSIGNED (FK → pharmacies.id) ON DELETE CASCADE  
* license\_number VARCHAR(100) UNIQUE NOT NULL  
* license\_expires\_at DATE NULL  
* position VARCHAR(100) NULL  
* is\_active BOOLEAN DEFAULT TRUE  
* created\_at, updated\_at, deleted\_at

---

###  Inventory / Stock

Table: pharmacy\_inventories

* id BIGINT UNSIGNED (PK)  
* pharmacy\_id BIGINT UNSIGNED (FK → pharmacies.id) ON DELETE CASCADE  
* medicine\_id BIGINT UNSIGNED (FK → medicines.id) ON DELETE RESTRICT  
* batch\_number VARCHAR(100) NOT NULL  
* quantity\_in\_stock INT NOT NULL DEFAULT 0  
* reorder\_level INT UNSIGNED DEFAULT 10  
* unit\_cost DECIMAL(10,2) NOT NULL  
* selling\_price DECIMAL(10,2) NOT NULL  
* manufacturing\_date DATE NULL  
* expiry\_date DATE NOT NULL  
* supplier VARCHAR(191) NULL  
* location VARCHAR(100) NULL *(shelf/rack)*  
* status ENUM('available','low\_stock','out\_of\_stock','expired') DEFAULT 'available'  
* created\_at, updated\_at, deleted\_at  
* UNIQUE (pharmacy\_id, medicine\_id, batch\_number)  
* Indexes: pharmacy\_id, medicine\_id, expiry\_date, status

Table: stock\_movements

* id BIGINT UNSIGNED (PK)  
* pharmacy\_inventory\_id BIGINT UNSIGNED (FK → pharmacy\_inventories.id) ON DELETE CASCADE  
* type ENUM('purchase','sale','return','adjustment','expired','transfer') NOT NULL  
* quantity INT NOT NULL *(positive or negative)*  
* reference\_type VARCHAR(100) NULL *(polymorphic: Prescription, Purchase)*  
* reference\_id BIGINT UNSIGNED NULL  
* unit\_price DECIMAL(10,2) NULL  
* notes TEXT NULL  
* performed\_by BIGINT UNSIGNED (FK → users.id)  
* created\_at, updated\_at  
* Indexes: pharmacy\_inventory\_id, (reference\_type, reference\_id), type

---

###  Appointments

Table: appointments

* id BIGINT UNSIGNED (PK)  
* uuid CHAR(36) UNIQUE NOT NULL  
* appointment\_number VARCHAR(50) UNIQUE NOT NULL  
* patient\_id BIGINT UNSIGNED (FK → patients.id) ON DELETE CASCADE  
* doctor\_id BIGINT UNSIGNED (FK → doctors.id) ON DELETE RESTRICT  
* hospital\_id BIGINT UNSIGNED (FK → hospitals.id) ON DELETE RESTRICT  
* department\_id BIGINT UNSIGNED NULL (FK → departments.id) ON DELETE SET NULL  
* scheduled\_at DATETIME NOT NULL  
* duration\_minutes SMALLINT UNSIGNED DEFAULT 30  
* type ENUM('in\_person','video','phone') DEFAULT 'in\_person'  
* reason TEXT NULL  
* status ENUM('scheduled','confirmed','checked\_in','completed','cancelled','no\_show') DEFAULT 'scheduled'  
* cancellation\_reason TEXT NULL  
* fee DECIMAL(10,2) NULL  
* created\_at, updated\_at, deleted\_at  
* Indexes: patient\_id, doctor\_id, hospital\_id, scheduled\_at, status

---

###  Audit & Notifications

Table: audit\_logs

* id BIGINT UNSIGNED (PK)  
* user\_id BIGINT UNSIGNED NULL (FK → users.id) ON DELETE SET NULL  
* hospital\_id BIGINT UNSIGNED NULL (FK → hospitals.id) ON DELETE SET NULL  
* action VARCHAR(100) NOT NULL *(created, updated, deleted, viewed, login, logout)*  
* auditable\_type VARCHAR(100) NOT NULL  
* auditable\_id BIGINT UNSIGNED NOT NULL  
* old\_values JSON NULL  
* new\_values JSON NULL  
* ip\_address VARCHAR(45) NULL  
* user\_agent VARCHAR(500) NULL  
* url VARCHAR(500) NULL  
* method VARCHAR(10) NULL  
* created\_at TIMESTAMP  
* Indexes: user\_id, hospital\_id, (auditable\_type, auditable\_id), action, created\_at

Table: notifications

* id CHAR(36) (PK) *(Laravel default)*  
* type VARCHAR(191) NOT NULL  
* notifiable\_type VARCHAR(191) NOT NULL  
* notifiable\_id BIGINT UNSIGNED NOT NULL  
* data JSON NOT NULL  
* read\_at TIMESTAMP NULL  
* created\_at, updated\_at  
* Index: (notifiable\_type, notifiable\_id)

---

##  B. Relationships List

### One-to-One (1:1)

* User (1) → (1) Doctor  
* User (1) → (1) Patient  
* User (1) → (1) Pharmacist  
* Patient (1) → (1) QRCode 

### One-to-Many (1:∞)

* Country (1) → (∞) Cities  
* Country (1) → (∞) Hospitals  
* City (1) → (∞) Hospitals / Pharmacies / Users  
* Hospital (1) → (∞) Doctors  
* Hospital (1) → (∞) Departments  
* Hospital (1) → (∞) Pharmacies  
* Hospital (1) → (∞) MedicalRecords  
* Hospital (1) → (∞) Prescriptions  
* Hospital (1) → (∞) Appointments  
* Department (1) → (∞) Doctors  
* Specialty (1) → (∞) Doctors *(primary)*  
* Doctor (1) → (∞) Prescriptions  
* Doctor (1) → (∞) MedicalRecords  
* Doctor (1) → (∞) Appointments  
* Doctor (1) → (∞) DoctorSchedules  
* Patient (1) → (∞) MedicalRecords  
* Patient (1) → (∞) Prescriptions  
* Patient (1) → (∞) Appointments  
* MedicalRecord (1) → (∞) MedicalRecordAttachments  
* MedicalRecord (1) → (∞) Prescriptions  
* Prescription (1) → (∞) PrescriptionItems  
* Medicine (1) → (∞) PrescriptionItems  
* Medicine (1) → (∞) PharmacyInventories  
* MedicineCategory (1) → (∞) Medicines  
* MedicineCategory (1) → (∞) MedicineCategories *(self-ref, parent/child)*  
* Pharmacy (1) → (∞) Pharmacists  
* Pharmacy (1) → (∞) PharmacyInventories  
* Pharmacy (1) → (∞) Prescriptions *(dispensed)*  
* PharmacyInventory (1) → (∞) StockMovements  
* User (1) → (∞) AuditLogs

### Many-to-Many (∞:∞)

* Users ↔ Roles *(via user\_roles, scoped per hospital)*  
* Roles ↔ Permissions *(via role\_permissions)*  
* Hospitals ↔ Specialties *(via hospital\_specialties)*  
* Doctors ↔ Specialties *(via doctor\_specialties — secondary)*  
* Patients ↔ Hospitals *(via patient\_hospitals)*

### Polymorphic

* AuditLogs → any model *(auditable)*  
* StockMovements → Prescriptions | Purchases *(reference)*  
* Notifications → Users *(notifiable)*

---

\---

\#\# 🔑 Multi-Tenancy & Implementation Notes

\*\*Hospital-level isolation\*\* is enforced through the \`hospital\_id\` foreign key present on every tenant-scoped table (\`doctors\`, \`departments\`, \`patients\`, \`medical\_records\`, \`prescriptions\`, \`appointments\`, \`pharmacies\`, \`audit\_logs\`, \`user\_roles\`). Apply a Laravel global scope \`HospitalScope\` on all tenant models to filter by the authenticated user's active hospital.

\*\*Pharmacy scoping\*\*: \`pharmacies.hospital\_id\` is nullable — \`null\` represents independent external pharmacies; non-null represents in-hospital pharmacies.

\*\*Indexing strategy\*\*: Composite indexes on \`(patient\_id, visit\_date)\`, \`(hospital\_id, status)\`, and \`(auditable\_type, auditable\_id)\` optimize the most common query patterns (patient timelines, hospital dashboards, audit lookups).

\*\*Soft deletes\*\* applied on all clinically-significant tables to preserve medical history integrity (required for HIPAA/regulatory compliance).

\*\*Polymorphic relationships\*\* (\`qr\_codes\`, \`audit\_logs\`, \`stock\_movements\`, \`notifications\`) follow Laravel's \`morphTo\` convention — use \`qrable\_type \+ qrable\_id\` pattern.The complete \`.dbml\` file is ready — paste it directly into \[dbdiagram.io\](https://dbdiagram.io/d) to visualize the full ERD.

\*\*Note on the Google Docs link\*\*: I wasn't able to access the URL you shared, so I designed this ERD based on the detailed requirements in your message. If your actual SRS contains specific entities or rules I didn't cover (e.g., billing/invoicing, lab tests, radiology, telemedicine sessions, insurance claims, referrals), share the document contents as text and I'll extend the schema accordingly.

