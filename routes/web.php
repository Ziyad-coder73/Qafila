<?php

use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\LoyaltyCardController;
use App\Http\Controllers\Admin\LoyaltyPackageController;
use App\Http\Controllers\Admin\ManualLoyaltyCardController;
use App\Http\Controllers\Admin\ManualNotificationController;
use App\Http\Controllers\Admin\NotificationLogController;
use App\Http\Controllers\Admin\NotificationSettingController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\PolicyController;
use App\Http\Controllers\Admin\PolicyPaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\VoucherPackageController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InsuranceRequestController;
use App\Http\Controllers\LoyaltyCardPublicController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\Partner\AuthController as PartnerAuthController;
use App\Http\Controllers\Partner\PortalController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/insurance-requests', [InsuranceRequestController::class, 'store'])->name('insurance-requests.store');

Route::get('/loyalty', [LoyaltyController::class, 'index'])->name('loyalty.index');
Route::get('/loyalty/card/{token}', [LoyaltyCardPublicController::class, 'show'])->name('loyalty-card.public');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::resource('brands', BrandController::class)->except(['show']);
        Route::post('brands/{brand}/vouchers', [VoucherPackageController::class, 'store'])->name('brands.vouchers.store');
        Route::put('brands/{brand}/vouchers/{voucher}', [VoucherPackageController::class, 'update'])->name('brands.vouchers.update');
        Route::delete('brands/{brand}/vouchers/{voucher}', [VoucherPackageController::class, 'destroy'])->name('brands.vouchers.destroy');

        Route::resource('partners', PartnerController::class)->except(['show']);
        Route::post('partners/{partner}/regenerate-link', [PartnerController::class, 'regenerateLink'])->name('partners.regenerate-link');

        Route::resource('agents', AgentController::class)->except(['show']);

        Route::get('policies/{policy}/loyalty-card', [LoyaltyCardController::class, 'create'])->name('loyalty-cards.create');
        Route::post('policies/{policy}/loyalty-card', [LoyaltyCardController::class, 'store'])->name('loyalty-cards.store');
        Route::get('loyalty-cards/{loyaltyMember}', [LoyaltyCardController::class, 'show'])->name('loyalty-cards.show');
        Route::put('loyalty-cards/{loyaltyMember}/delivery', [LoyaltyCardController::class, 'updateDelivery'])->name('loyalty-cards.delivery');

        Route::get('loyalty-cards-manual', [ManualLoyaltyCardController::class, 'index'])->name('loyalty-cards.manual.index');
        Route::get('loyalty-cards-manual/create', [ManualLoyaltyCardController::class, 'create'])->name('loyalty-cards.manual.create');
        Route::post('loyalty-cards-manual', [ManualLoyaltyCardController::class, 'store'])->name('loyalty-cards.manual.store');

        Route::get('loyalty-packages', [LoyaltyPackageController::class, 'index'])->name('loyalty-packages.index');
        Route::put('loyalty-packages/{loyaltyPackage}', [LoyaltyPackageController::class, 'update'])->name('loyalty-packages.update');

        Route::get('notifications', [NotificationSettingController::class, 'index'])->name('notifications.index');
        Route::put('notifications/birthday', [NotificationSettingController::class, 'updateBirthday'])->name('notifications.birthday.update');
        Route::put('notifications/renewal', [NotificationSettingController::class, 'updateRenewal'])->name('notifications.renewal.update');
        Route::post('notifications/run-now', [NotificationSettingController::class, 'runNow'])->name('notifications.run-now');

        Route::get('notifications/log', [NotificationLogController::class, 'index'])->name('notifications.log.index');

        Route::get('notifications/manual', [ManualNotificationController::class, 'create'])->name('notifications.manual.create');
        Route::post('notifications/manual', [ManualNotificationController::class, 'store'])->name('notifications.manual.store');
    });

    Route::middleware(['auth', 'agent_or_admin'])->group(function () {
        Route::resource('policies', PolicyController::class);
        Route::post('policies/{policy}/payments', [PolicyPaymentController::class, 'store'])->name('policies.payments.store');
        Route::delete('policies/{policy}/payments/{payment}', [PolicyPaymentController::class, 'destroy'])->name('policies.payments.destroy');

        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export/csv', [ReportController::class, 'exportCsv'])->name('reports.export.csv');
        Route::get('reports/export/pdf', [ReportController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::post('reports/email', [ReportController::class, 'email'])->name('reports.email');
    });
});

Route::prefix('partner')->name('partner.')->group(function () {
    Route::get('/login', [PartnerAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [PartnerAuthController::class, 'login'])->name('login.attempt');
    Route::post('/logout', [PartnerAuthController::class, 'logout'])->name('logout');
    Route::get('/login-link/{token}', [PartnerAuthController::class, 'magicLogin'])->name('login.magic');

    Route::middleware(['auth', 'partner_or_admin'])->group(function () {
        Route::get('/', [PortalController::class, 'index'])->name('portal');
        Route::post('/verify', [PortalController::class, 'verify'])->name('verify');
        Route::get('/members/{loyaltyMember}', [PortalController::class, 'show'])->name('members.show');
        Route::post('/members/{loyaltyMember}/redeem', [PortalController::class, 'redeem'])->name('members.redeem');
    });
});
