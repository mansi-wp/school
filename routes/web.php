<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\FeeController;
use App\Http\Controllers\Admin\FeePlanController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\MarksController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\NotificationTemplateController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AuditLogController;

use App\Http\Controllers\Teacher\DashboardController as TeacherDashboardController;
use App\Http\Controllers\Teacher\AttendanceController as TeacherAttendanceController;
use App\Http\Controllers\Teacher\MarksController as TeacherMarksController;

use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ResultController as StudentResultController;
use App\Http\Controllers\Student\FeeController as StudentFeeController;

use App\Http\Controllers\Parent\DashboardController as ParentDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes — Coaching Management System (Admin-Only, Role-Based)
|--------------------------------------------------------------------------
| Root URL strictly redirects to /admin/dashboard or /login.
| Public self-registration is explicitly disabled.
*/

Route::get('/', function () {
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();
    if ($user->user_type === 'teacher' || $user->hasRole('teacher')) return redirect()->route('teacher.dashboard');
    if ($user->user_type === 'student' || $user->hasRole('student')) return redirect()->route('student.dashboard');
    if ($user->user_type === 'parent' || $user->hasRole('parent')) return redirect()->route('parent.dashboard');

    return redirect()->route('admin.dashboard');
});

// Authentication Routes (Breeze Blade - Public registration disabled)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:5,1');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Admin & Back-Office Routes (Guarded with Spatie Permissions)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'audit'])->prefix('admin')->name('admin.')->group(function () {
    // Executive Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->middleware('permission:dashboard.view')
        ->name('dashboard');

    // Staff & Faculty Management
    Route::resource('staff', StaffController::class);

    // Course Catalog & Pricing
    Route::resource('courses', CourseController::class);

    // Batches & Batch Transfer
    Route::resource('batches', BatchController::class);
    Route::get('batches/transfer/form', [BatchController::class, 'transferForm'])->middleware('permission:batch.transfer')->name('batches.transfer.form');
    Route::post('batches/transfer', [BatchController::class, 'transfer'])->middleware('permission:batch.transfer')->name('batches.transfer');

    // Students Admissions & Profiles
    Route::resource('students', StudentController::class);

    // Fee Collection, Receipts, Defaulters, Refunds
    Route::get('fees', [FeeController::class, 'index'])->middleware('permission:fee.view')->name('fees.index');
    Route::get('fees/collect/{student?}', [FeeController::class, 'collectForm'])->middleware('permission:fee.collect')->name('fees.collect');
    Route::post('fees/collect', [FeeController::class, 'collect'])->middleware('permission:fee.collect')->name('fees.store');
    Route::get('fees/receipt/{payment}', [FeeController::class, 'receipt'])->middleware('permission:fee.view')->name('fees.receipt');
    Route::get('fees/defaulters', [FeeController::class, 'defaulters'])->middleware('permission:fee.defaulters')->name('fees.defaulters');
    Route::post('fees/refund', [FeeController::class, 'refund'])->middleware('permission:fee.refund')->name('fees.refund');
    Route::resource('fee-plans', FeePlanController::class);

    // Attendance & Leaves
    Route::get('attendance', [AdminAttendanceController::class, 'index'])->middleware('permission:attendance.view')->name('attendance.index');
    Route::get('attendance/mark', [AdminAttendanceController::class, 'markForm'])->middleware('permission:attendance.mark')->name('attendance.mark');
    Route::post('attendance/mark', [AdminAttendanceController::class, 'store'])->middleware('permission:attendance.mark')->name('attendance.store');
    Route::get('attendance/report', [AdminAttendanceController::class, 'report'])->middleware('permission:attendance.report')->name('attendance.report');
    Route::post('attendance/leaves/{leave}/approve', [AdminAttendanceController::class, 'approveLeave'])->middleware('permission:attendance.leave.approve')->name('attendance.leave.approve');

    // Tests & Marks Entry
    Route::resource('tests', TestController::class);
    Route::get('tests/{test}/marks', [MarksController::class, 'entryForm'])->middleware('permission:test.marks.entry')->name('tests.marks.form');
    Route::post('tests/{test}/marks', [MarksController::class, 'store'])->middleware('permission:test.marks.entry')->name('tests.marks.store');
    Route::post('tests/{test}/publish', [MarksController::class, 'publish'])->middleware('permission:test.publish')->name('tests.publish');

    // Expenses
    Route::resource('expenses', ExpenseController::class)->middleware('permission:expense.view');

    // Reports & Analytics Export
    Route::get('reports', [ReportController::class, 'index'])->middleware('permission:report.view')->name('reports.index');
    Route::get('reports/export/{type}', [ReportController::class, 'export'])->middleware('permission:report.export')->name('reports.export');

    // Notifications & Announcements
    Route::get('notifications', [NotificationTemplateController::class, 'index'])->middleware('permission:notification.view')->name('notifications.index');
    Route::post('notifications/templates', [NotificationTemplateController::class, 'store'])->middleware('permission:notification.template.manage')->name('notifications.templates.store');
    Route::post('notifications/announce', [AnnouncementController::class, 'send'])->middleware('permission:notification.send')->name('notifications.announce');

    // Roles & Dynamic System Menus
    Route::resource('roles', RoleController::class);
    Route::resource('menus', MenuController::class);
    Route::post('menus/reorder', [MenuController::class, 'reorder'])->middleware('permission:menu.edit')->name('menus.reorder');

    // Settings & Audit Trail
    Route::get('settings', [SettingController::class, 'index'])->middleware('permission:setting.view')->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->middleware('permission:setting.edit')->name('settings.update');
    Route::get('audit', [AuditLogController::class, 'index'])->middleware('permission:audit.view')->name('audit.index');
});

/*
|--------------------------------------------------------------------------
| Dedicated Guard Dashboards (Teacher, Student, Parent)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::get('/attendance/mark', [TeacherAttendanceController::class, 'mark'])->name('attendance.mark');
    Route::get('/marks/entry', [TeacherMarksController::class, 'entry'])->name('marks.entry');
});

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/results', [StudentResultController::class, 'index'])->name('results');
    Route::get('/fees', [StudentFeeController::class, 'index'])->name('fees');
});

Route::middleware(['auth', 'role:parent'])->prefix('parent')->name('parent.')->group(function () {
    Route::get('/dashboard', [ParentDashboardController::class, 'index'])->name('dashboard');
});



Route::get('/test-dompdf-loader', function () {
    abort_unless(
        auth()->check()
        && auth()->user()->hasRole('super-admin'),
        403
    );

    require_once base_path('vendor/autoload.php');

    $classes = [
        'Barryvdh\\DomPDF\\PDF',
        'Barryvdh\\DomPDF\\Facade\\Pdf',
        'Dompdf\\Dompdf',
    ];

    $result = [];

    foreach ($classes as $class) {
        $result[$class] = [
            'exists' => class_exists($class),
            'file' => class_exists($class)
                ? (new ReflectionClass($class))->getFileName()
                : null,
        ];
    }

    return response()->json($result);
});



Route::get('/test-cpdf-loader', function () {
    abort_unless(
        auth()->check()
        && auth()->user()->hasRole('super-admin'),
        403
    );

    require_once base_path('vendor/autoload.php');

   return response()->json([
    'html5_file_exists' => file_exists(
        base_path('vendor/masterminds/html5/src/HTML5.php')
    ),
    'html5_class_exists' => class_exists(
        \Masterminds\HTML5::class
    ),
    'cpdf_class_exists' => class_exists(
        \Dompdf\Cpdf::class
    ),
]);
});

