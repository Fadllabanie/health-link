# Quick Reference — Code Templates & Common Patterns

> Copy-paste templates for common code patterns in this project. Saves time and keeps code consistent.

---

## Artisan Commands Cheat Sheet

```bash
# Generate full CRUD scaffolding
php artisan make:model Hospital -mfsc
# -m: migration  -f: factory  -s: seeder  -c: controller

# Generate FormRequest in role-specific folder
php artisan make:request SuperAdmin/StoreHospitalRequest

# Generate Policy
php artisan make:policy HospitalPolicy --model=Hospital

# Generate Service (no built-in command — create manually)
mkdir -p app/Services
touch app/Services/HospitalOnboardingService.php

# Generate Middleware
php artisan make:middleware EnsureHospitalContext

# Generate Trait (manual)
mkdir -p app/Traits
touch app/Traits/BelongsToHospital.php

# Generate Notification
php artisan make:notification PrescriptionDispensed

# Generate Event + Listener
php artisan make:event PrescriptionCreated
php artisan make:listener NotifyPharmacyOnPrescription --event=PrescriptionCreated

# Reset DB & seed (use frequently during dev)
php artisan migrate:fresh --seed

# Create symlink for storage (run once)
php artisan storage:link

# Clear all caches
php artisan optimize:clear

# Run tests
php artisan test
php artisan test --filter=HospitalTest
php artisan test --coverage
```

---

## Migration Template

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hospitals', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name', 191);
            $table->string('slug', 191)->unique();
            $table->string('license_number', 100)->unique();
            $table->string('email', 191)->unique();
            $table->string('phone', 20);
            $table->string('alternate_phone', 20)->nullable();
            $table->foreignId('country_id')->constrained()->restrictOnDelete();
            $table->foreignId('city_id')->constrained()->restrictOnDelete();
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('logo')->nullable();
            $table->enum('subscription_plan', ['free','basic','premium','enterprise'])->default('basic');
            $table->timestamp('subscription_expires_at')->nullable();
            $table->enum('status', ['active','inactive','suspended'])->default('active');
            $table->timestamps();
            $table->softDeletes();

            // Indexes from ERD
            $table->index('status');
            $table->index('city_id');
            $table->index('subscription_plan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hospitals');
    }
};
```

---

## Model Template

```php
<?php

namespace App\Models;

use App\Traits\BelongsToHospital;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Illuminate\Support\Str;

class Hospital extends Model implements AuditableContract
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'name', 'slug', 'license_number', 'email', 'phone',
        'country_id', 'city_id', 'address', 'subscription_plan', 'status',
        // ... other fillable fields
    ];

    protected $casts = [
        'subscription_expires_at' => 'datetime',
        'established_date' => 'date',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Auto-generate UUID on create
     */
    protected static function booted(): void
    {
        static::creating(function ($hospital) {
            if (empty($hospital->uuid)) {
                $hospital->uuid = (string) Str::uuid();
            }
            if (empty($hospital->slug)) {
                $hospital->slug = Str::slug($hospital->name);
            }
        });
    }

    // Relationships
    public function country()    { return $this->belongsTo(Country::class); }
    public function city()       { return $this->belongsTo(City::class); }
    public function specialties(){ return $this->belongsToMany(Specialty::class, 'hospital_specialties'); }
    public function departments(){ return $this->hasMany(Department::class); }
    public function doctors()    { return $this->hasMany(Doctor::class); }
    public function pharmacies() { return $this->hasMany(Pharmacy::class); }

    // Scopes
    public function scopeActive($query)    { return $query->where('status', 'active'); }
    public function scopeSuspended($query) { return $query->where('status', 'suspended'); }
}
```

---

## FormRequest Template

```php
<?php

namespace App\Http\Requests\SuperAdmin;

use Illuminate\Foundation\Http\FormRequest;

class StoreHospitalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('super_admin');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:191'],
            'license_number' => ['required', 'string', 'max:100', 'unique:hospitals,license_number'],
            'email' => ['required', 'email', 'max:191', 'unique:hospitals,email'],
            'phone' => ['required', 'string', 'max:20'],
            'country_id' => ['required', 'exists:countries,id'],
            'city_id' => ['required', 'exists:cities,id'],
            'address' => ['required', 'string'],
            'subscription_plan' => ['required', 'in:free,basic,premium,enterprise'],

            // Hospital admin fields (created together)
            'admin_first_name' => ['required', 'string', 'max:100'],
            'admin_last_name' => ['required', 'string', 'max:100'],
            'admin_email' => ['required', 'email', 'unique:users,email'],
            'admin_password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            // Use translation keys for Arabic messages
            'name.required' => __('validation.custom.hospital.name.required'),
        ];
    }
}
```

---

## Controller Template (Resource Controller)

```php
<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\StoreHospitalRequest;
use App\Http\Requests\SuperAdmin\UpdateHospitalRequest;
use App\Models\Hospital;
use App\Services\HospitalOnboardingService;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    public function __construct(
        private HospitalOnboardingService $onboardingService
    ) {}

    public function index(Request $request)
    {
        $hospitals = Hospital::query()
            ->with(['city', 'country'])
            ->when($request->search, fn($q, $s) =>
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
            )
            ->when($request->status, fn($q, $s) => $q->where('status', $s))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('super-admin.hospitals.index', compact('hospitals'));
    }

    public function create()
    {
        $countries = \App\Models\Country::active()->orderBy('name')->get();
        return view('super-admin.hospitals.create', compact('countries'));
    }

    public function store(StoreHospitalRequest $request)
    {
        $hospital = $this->onboardingService->create($request->validated());

        return redirect()
            ->route('super-admin.hospitals.show', $hospital)
            ->with('success', __('hospitals.created_successfully'));
    }

    public function show(Hospital $hospital)
    {
        $hospital->load(['departments', 'specialties', 'doctors.user']);
        return view('super-admin.hospitals.show', compact('hospital'));
    }

    public function edit(Hospital $hospital)
    {
        $countries = \App\Models\Country::active()->orderBy('name')->get();
        return view('super-admin.hospitals.edit', compact('hospital', 'countries'));
    }

    public function update(UpdateHospitalRequest $request, Hospital $hospital)
    {
        $hospital->update($request->validated());
        return redirect()
            ->route('super-admin.hospitals.show', $hospital)
            ->with('success', __('hospitals.updated_successfully'));
    }

    public function destroy(Hospital $hospital)
    {
        $hospital->delete(); // soft delete
        return redirect()
            ->route('super-admin.hospitals.index')
            ->with('success', __('hospitals.archived_successfully'));
    }
}
```

---

## Service Template

```php
<?php

namespace App\Services;

use App\Models\Hospital;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HospitalOnboardingService
{
    /**
     * Create hospital + first hospital admin in one transaction.
     */
    public function create(array $data): Hospital
    {
        return DB::transaction(function () use ($data) {
            // 1. Create hospital
            $hospital = Hospital::create([
                'name' => $data['name'],
                'license_number' => $data['license_number'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'country_id' => $data['country_id'],
                'city_id' => $data['city_id'],
                'address' => $data['address'],
                'subscription_plan' => $data['subscription_plan'],
                'status' => 'active',
            ]);

            // 2. Create the hospital admin user
            $admin = User::create([
                'first_name' => $data['admin_first_name'],
                'last_name' => $data['admin_last_name'],
                'email' => $data['admin_email'],
                'password' => Hash::make($data['admin_password']),
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            // 3. Assign hospital_admin role scoped to this hospital
            $admin->assignRoleInHospital('hospital_admin', $hospital->id);

            return $hospital->fresh();
        });
    }
}
```

---

## Routes Template

`routes/super-admin.php`:
```php
<?php

use App\Http\Controllers\SuperAdmin;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:super_admin'])
    ->prefix('super-admin')
    ->name('super-admin.')
    ->group(function () {
        Route::get('/dashboard', [SuperAdmin\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('hospitals', SuperAdmin\HospitalController::class);
        Route::patch('hospitals/{hospital}/status', [SuperAdmin\HospitalController::class, 'updateStatus'])
            ->name('hospitals.update-status');

        // Master data
        Route::resource('master-data/countries', SuperAdmin\CountryController::class);
        Route::resource('master-data/cities', SuperAdmin\CityController::class);
        Route::resource('master-data/specialties', SuperAdmin\SpecialtyController::class);

        // Audit logs
        Route::get('audit-logs', [SuperAdmin\AuditLogController::class, 'index'])->name('audit-logs.index');
    });
```

Then register in `bootstrap/app.php` (Laravel 11):
```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
    then: function () {
        Route::middleware('web')->group(base_path('routes/super-admin.php'));
        Route::middleware('web')->group(base_path('routes/hospital-admin.php'));
        Route::middleware('web')->group(base_path('routes/doctor.php'));
        Route::middleware('web')->group(base_path('routes/pharmacy.php'));
        Route::middleware('web')->group(base_path('routes/patient.php'));
    },
)
```

---

## Trait: BelongsToHospital

```php
<?php

namespace App\Traits;

use App\Models\Hospital;
use App\Scopes\HospitalScope;

trait BelongsToHospital
{
    protected static function bootBelongsToHospital(): void
    {
        static::addGlobalScope(new HospitalScope);

        static::creating(function ($model) {
            if (empty($model->hospital_id) && auth()->check() && session('current_hospital_id')) {
                $model->hospital_id = session('current_hospital_id');
            }
        });
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
```

---

## Scope: HospitalScope

```php
<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class HospitalScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();

        // Super admin sees all
        if ($user->hasRole('super_admin')) {
            return;
        }

        $hospitalId = session('current_hospital_id');
        if ($hospitalId) {
            $builder->where($model->getTable() . '.hospital_id', $hospitalId);
        }
    }
}
```

---

## Blade Layout (Materio RTL)

`resources/views/layouts/app.blade.php`:
```blade
<!DOCTYPE html>
<html lang="ar" dir="rtl" class="layout-menu-fixed layout-compact">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', __('app.name'))</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Materio RTL CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    @stack('styles')
</head>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @include('layouts.partials.sidebar')

            <div class="layout-page">
                @include('layouts.partials.navbar')

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @yield('content')
                    </div>

                    @include('layouts.partials.footer')
                </div>
            </div>
        </div>
    </div>

    {{-- Materio JS --}}
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    @stack('scripts')
</body>
</html>
```

---

## Translation File Template

`resources/lang/ar/hospitals.php`:
```php
<?php

return [
    'title' => 'المستشفيات',
    'list' => 'قائمة المستشفيات',
    'add' => 'إضافة مستشفى',
    'edit' => 'تعديل مستشفى',
    'show' => 'تفاصيل المستشفى',
    'name' => 'اسم المستشفى',
    'license_number' => 'رقم الترخيص',
    'email' => 'البريد الإلكتروني',
    'phone' => 'رقم الهاتف',
    'address' => 'العنوان',
    'status' => 'الحالة',
    'created_successfully' => 'تم إنشاء المستشفى بنجاح',
    'updated_successfully' => 'تم تحديث المستشفى بنجاح',
    'archived_successfully' => 'تم أرشفة المستشفى بنجاح',
    'statuses' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'suspended' => 'موقوف',
    ],
];
```

---

## Feature Test Template

```php
<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HospitalManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    }

    public function test_super_admin_can_create_hospital_with_admin(): void
    {
        $superAdmin = User::factory()->create();
        $superAdmin->assignRole('super_admin');

        $country = \App\Models\Country::factory()->create();
        $city = \App\Models\City::factory()->create(['country_id' => $country->id]);

        $response = $this->actingAs($superAdmin)
            ->post(route('super-admin.hospitals.store'), [
                'name' => 'مستشفى الملك فهد',
                'license_number' => 'LIC-12345',
                'email' => 'info@hospital.test',
                'phone' => '0501234567',
                'country_id' => $country->id,
                'city_id' => $city->id,
                'address' => 'شارع الملك فهد، الرياض',
                'subscription_plan' => 'basic',
                'admin_first_name' => 'أحمد',
                'admin_last_name' => 'محمد',
                'admin_email' => 'admin@hospital.test',
                'admin_password' => 'password123',
                'admin_password_confirmation' => 'password123',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('hospitals', ['email' => 'info@hospital.test']);
        $this->assertDatabaseHas('users', ['email' => 'admin@hospital.test']);
    }

    public function test_non_super_admin_cannot_access_hospitals(): void
    {
        $user = User::factory()->create();
        $user->assignRole('doctor');

        $this->actingAs($user)
            ->get(route('super-admin.hospitals.index'))
            ->assertForbidden();
    }
}
```

---

## Common Materio Components

### Data Table Card
```blade
<div class="card">
    <h5 class="card-header">{{ __('hospitals.list') }}</h5>
    <div class="card-body">
        <form method="GET" class="row g-3 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-control" placeholder="{{ __('app.search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">{{ __('app.all_statuses') }}</option>
                    <option value="active" @selected(request('status')==='active')>{{ __('hospitals.statuses.active') }}</option>
                    <option value="suspended" @selected(request('status')==='suspended')>{{ __('hospitals.statuses.suspended') }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary">{{ __('app.filter') }}</button>
            </div>
        </form>
    </div>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('hospitals.name') }}</th>
                    <th>{{ __('hospitals.email') }}</th>
                    <th>{{ __('hospitals.status') }}</th>
                    <th>{{ __('app.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($hospitals as $hospital)
                    <tr>
                        <td>{{ $hospital->name }}</td>
                        <td>{{ $hospital->email }}</td>
                        <td>
                            <span class="badge bg-label-{{ $hospital->status === 'active' ? 'success' : 'warning' }}">
                                {{ __('hospitals.statuses.' . $hospital->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('super-admin.hospitals.show', $hospital) }}" class="btn btn-sm btn-icon">
                                <i class="bx bx-show"></i>
                            </a>
                            <a href="{{ route('super-admin.hospitals.edit', $hospital) }}" class="btn btn-sm btn-icon">
                                <i class="bx bx-edit"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">{{ __('app.no_data') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $hospitals->links() }}
</div>
```

---

## Helpful Tinker Snippets

```php
// Quick test of a model
php artisan tinker

// Create a hospital
$h = \App\Models\Hospital::factory()->create();

// Test relationships
$h->city;
$h->doctors;

// Test policies
auth()->loginUsingId(1);
auth()->user()->can('create', \App\Models\Hospital::class);

// Test scopes
\App\Models\Hospital::active()->count();

// Inspect query
\App\Models\Hospital::with('city')->where('status', 'active')->toRawSql();
```

---

**Use these templates as starting points. Adapt to fit each specific case, but keep the structure consistent.**
