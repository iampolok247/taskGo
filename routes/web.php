<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\AgentAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\TaskController as UserTaskController;
use App\Http\Controllers\User\WalletController;
use App\Http\Controllers\User\DepositController as UserDepositController;
use App\Http\Controllers\User\WithdrawalController as UserWithdrawalController;
use App\Http\Controllers\User\ReferralController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\Agent\DashboardController as AgentDashboardController;
use App\Http\Controllers\Agent\UserController as AgentUserController;
use App\Http\Controllers\Agent\CommissionController;
use App\Http\Controllers\Agent\ProfileController as AgentProfileController;
use App\Http\Controllers\Agent\DepositController as AgentDepositController;
use App\Http\Controllers\Agent\WithdrawalController as AgentWithdrawalController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\TaskController as AdminTaskController;
use App\Http\Controllers\Admin\TaskSubmissionController;
use App\Http\Controllers\Admin\DepositController as AdminDepositController;
use App\Http\Controllers\Admin\WithdrawalController as AdminWithdrawalController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\PaymentMethodController as AdminPaymentMethodController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// APK Download Route
Route::get('/download-app', function () {
    $apkPath = public_path('taskgo.apk');
    
    if (!file_exists($apkPath)) {
        abort(404, 'APK file not found');
    }
    
    return response()->download($apkPath, 'TaskGo.apk', [
        'Content-Type' => 'application/vnd.android.package-archive',
    ]);
})->name('download.app');

// Storage file serving (for cPanel where symlinks don't work)
Route::get('/storage/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    
    if (!file_exists($fullPath)) {
        abort(404);
    }
    
    $mimeType = mime_content_type($fullPath);
    
    return response()->file($fullPath, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=604800',
    ]);
})->where('path', '.*')->name('storage.serve');

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ==================== USER AUTHENTICATION ====================
// Login form - accessible always (for role tab switching)
Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');

// Login/Register POST - only for guests
Route::middleware('guest:web')->group(function () {
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::post('/register', [UserAuthController::class, 'register']);
});

Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==================== USER ROUTES ====================
Route::middleware(['auth', 'user'])->prefix('user')->name('user.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Tasks
    Route::get('/tasks', [UserTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/{task}', [UserTaskController::class, 'show'])->name('tasks.show');
    Route::post('/tasks/{task}/submit', [UserTaskController::class, 'submit'])->name('tasks.submit');
    Route::get('/my-submissions', [UserTaskController::class, 'submissions'])->name('tasks.submissions');

    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('/wallet/transactions', [WalletController::class, 'transactions'])->name('wallet.transactions');

    // Deposits
    Route::get('/deposits', [UserDepositController::class, 'index'])->name('deposits.index');
    Route::get('/deposits/create', [UserDepositController::class, 'create'])->name('deposits.create');
    Route::post('/deposits', [UserDepositController::class, 'store'])->name('deposits.store');
    Route::get('/deposits/{deposit}', [UserDepositController::class, 'show'])->name('deposits.show');

    // Withdrawals
    Route::get('/withdrawals', [UserWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/create', [UserWithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals', [UserWithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::get('/withdrawals/{withdrawal}', [UserWithdrawalController::class, 'show'])->name('withdrawals.show');

    // Referrals
    Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals.index');

    // Profile
    Route::get('/profile', [UserProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [UserProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/profile/notifications', [UserProfileController::class, 'notifications'])->name('profile.notifications');
    Route::post('/notifications/{notification}/read', [UserProfileController::class, 'markNotificationRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [UserProfileController::class, 'markAllNotificationsRead'])->name('notifications.read-all');
});

// ==================== AGENT AUTHENTICATION ====================
Route::prefix('agent')->name('agent.')->group(function () {
    // Login form - accessible always (for role tab switching)
    Route::get('/login', [AgentAuthController::class, 'showLoginForm'])->name('login');

    // Login POST - only for guests
    Route::middleware('guest:agent')->group(function () {
        Route::post('/login', [AgentAuthController::class, 'login']);
    });

    Route::post('/logout', [AgentAuthController::class, 'logout'])->name('logout')->middleware('auth:agent');
});

// ==================== AGENT ROUTES ====================
Route::middleware(['auth:agent', 'agent'])->prefix('agent')->name('agent.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [AgentUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [AgentUserController::class, 'create'])->name('users.create');
    Route::post('/users', [AgentUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [AgentUserController::class, 'show'])->name('users.show');

    // Commissions
    Route::get('/commissions', [CommissionController::class, 'index'])->name('commissions.index');

    // Deposits
    Route::get('/deposits', [AgentDepositController::class, 'index'])->name('deposits.index');
    Route::get('/deposits/create', [AgentDepositController::class, 'create'])->name('deposits.create');
    Route::post('/deposits', [AgentDepositController::class, 'store'])->name('deposits.store');
    Route::get('/deposits/{deposit}', [AgentDepositController::class, 'show'])->name('deposits.show');

    // Withdrawals
    Route::get('/withdrawals', [AgentWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/create', [AgentWithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals', [AgentWithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::get('/withdrawals/{withdrawal}', [AgentWithdrawalController::class, 'show'])->name('withdrawals.show');

    // Profile
    Route::get('/profile', [AgentProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [AgentProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [AgentProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password', [AgentProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/profile/notifications', [AgentProfileController::class, 'notifications'])->name('profile.notifications');
});

// ==================== ADMIN AUTHENTICATION ====================
Route::prefix('admin')->name('admin.')->group(function () {
    // Login form - accessible always (for role tab switching)
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');

    // Login POST - only for guests
    Route::middleware('guest:admin')->group(function () {
        Route::post('/login', [AdminAuthController::class, 'login']);
    });

    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('auth:admin');
});

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth:admin', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::post('/users/{user}/adjust-balance', [AdminUserController::class, 'adjustBalance'])->name('users.adjust-balance');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Agents
    Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
    Route::get('/agents/create', [AgentController::class, 'create'])->name('agents.create');
    Route::post('/agents', [AgentController::class, 'store'])->name('agents.store');
    Route::get('/agents/{agent}', [AgentController::class, 'show'])->name('agents.show');
    Route::get('/agents/{agent}/edit', [AgentController::class, 'edit'])->name('agents.edit');
    Route::put('/agents/{agent}', [AgentController::class, 'update'])->name('agents.update');
    Route::post('/agents/{agent}/toggle-status', [AgentController::class, 'toggleStatus'])->name('agents.toggle-status');
    Route::post('/agents/{agent}/reset-password', [AgentController::class, 'resetPassword'])->name('agents.reset-password');
    Route::delete('/agents/{agent}', [AgentController::class, 'destroy'])->name('agents.destroy');

    // Tasks
    Route::get('/tasks', [AdminTaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [AdminTaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks', [AdminTaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{task}', [AdminTaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{task}/edit', [AdminTaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{task}', [AdminTaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [AdminTaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/tasks/{task}/toggle-status', [AdminTaskController::class, 'toggleStatus'])->name('tasks.toggle-status');
    Route::post('/tasks/reset-daily', [AdminTaskController::class, 'resetDailyCount'])->name('tasks.reset-daily');

    // Task Submissions
    Route::get('/submissions', [TaskSubmissionController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/{submission}', [TaskSubmissionController::class, 'show'])->name('submissions.show');
    Route::post('/submissions/{submission}/approve', [TaskSubmissionController::class, 'approve'])->name('submissions.approve');
    Route::post('/submissions/{submission}/reject', [TaskSubmissionController::class, 'reject'])->name('submissions.reject');
    Route::post('/submissions/bulk-approve', [TaskSubmissionController::class, 'bulkApprove'])->name('submissions.bulk-approve');
    Route::post('/submissions/bulk-reject', [TaskSubmissionController::class, 'bulkReject'])->name('submissions.bulk-reject');

    // Deposits
    Route::get('/deposits', [AdminDepositController::class, 'index'])->name('deposits.index');
    Route::get('/deposits/{deposit}', [AdminDepositController::class, 'show'])->name('deposits.show');
    Route::post('/deposits/{deposit}/approve', [AdminDepositController::class, 'approve'])->name('deposits.approve');
    Route::post('/deposits/{deposit}/reject', [AdminDepositController::class, 'reject'])->name('deposits.reject');

    // Withdrawals
    Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/{withdrawal}', [AdminWithdrawalController::class, 'show'])->name('withdrawals.show');
    Route::post('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/complete', [AdminWithdrawalController::class, 'complete'])->name('withdrawals.complete');
    Route::post('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');

    // Announcements
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    Route::post('/announcements/{announcement}/toggle', [AnnouncementController::class, 'toggleStatus'])->name('announcements.toggle');


    // Payment Methods
    Route::get("/payment-methods", [AdminPaymentMethodController::class, "index"])->name("payment-methods.index");
    Route::get("/payment-methods/create", [AdminPaymentMethodController::class, "create"])->name("payment-methods.create");
    Route::post("/payment-methods", [AdminPaymentMethodController::class, "store"])->name("payment-methods.store");
    Route::get("/payment-methods/{paymentMethod}/edit", [AdminPaymentMethodController::class, "edit"])->name("payment-methods.edit");
    Route::put("/payment-methods/{paymentMethod}", [AdminPaymentMethodController::class, "update"])->name("payment-methods.update");
    Route::delete("/payment-methods/{paymentMethod}", [AdminPaymentMethodController::class, "destroy"])->name("payment-methods.destroy");
    Route::patch("/payment-methods/{paymentMethod}/toggle-status", [AdminPaymentMethodController::class, "toggleStatus"])->name("payment-methods.toggle-status");

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/currencies', [SettingsController::class, 'currencies'])->name('settings.currencies');
    Route::put('/settings/currencies/{currency}', [SettingsController::class, 'updateCurrency'])->name('settings.currencies.update');
    Route::get('/settings/payment-methods', [SettingsController::class, 'paymentMethods'])->name('settings.payment-methods');
    Route::post('/settings/payment-methods', [SettingsController::class, 'storePaymentMethod'])->name('settings.payment-methods.store');
    Route::put('/settings/payment-methods/{method}', [SettingsController::class, 'updatePaymentMethod'])->name('settings.payment-methods.update');
});
