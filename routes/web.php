<?php

use App\Http\Controllers\EmailExportController;
use App\Livewire\Public\Kuhusu;
use App\Livewire\Public\TafutaKazi;
use App\Livewire\Public\TafutaWafanyakazi;
use Illuminate\Support\Facades\Route;

// Laravel Boost only registers POST /_boost/browser-logs; prefetch/GET hits would 405 otherwise.
if (app()->environment('local') || config('app.debug')) {
    Route::get('/_boost/browser-logs', fn () => response()->noContent(204));
}

// ========================================
// Public Routes
// ========================================
Route::get('/', function () {
    $featuredWingas = \App\Models\User::where('role', 'winga')
        ->where('onboarding_completed', true)
        ->whereHas('subscriptions', fn ($q) => $q->where('status', 'active')->where('expires_at', '>', now()))
        ->with(['skills:id,name'])
        ->inRandomOrder()
        ->take(8)
        ->get();

    return view('welcome', compact('featuredWingas'));
})->name('home');
Route::get('/tafuta-kazi', TafutaKazi::class)->name('tafuta-kazi');
Route::get('/tafuta-wafanyakazi', TafutaWafanyakazi::class)->name('tafuta-wafanyakazi');
Route::get('/kazi/{slug}', \App\Livewire\Public\KaziDetail::class)->name('kazi.show');
Route::get('/wafanyakazi/{id}', \App\Livewire\Public\WafanyakaziProfile::class)->name('wafanyakazi.show')->whereNumber('id');
Route::get('/kategoria', \App\Livewire\Public\KaziByCategory::class)->name('kazi-by-category');
Route::get('/kuhusu', Kuhusu::class)->name('kuhusu');
Route::get('/bei', function () {
    $plans = \App\Models\SubscriptionPlan::active()->get();

    return view('pages.bei', compact('plans'));
})->name('bei');
Route::get('/wasifu', function () {
    return view('pages.wasifu');
})->name('wasifu');

Route::view('/account-suspended', 'pages.account-suspended')->name('account-suspended');

// ========================================
// Auth & OTP Routes
// ========================================
Route::middleware('guest')->group(function () {
    Route::get('/register', \App\Livewire\Auth\Register::class)->name('register');
    Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
    Route::get('/verify-otp', \App\Livewire\Auth\VerifyOtp::class)->name('verify-otp');
    Route::get('/forgot-password', \App\Livewire\Auth\ForgotPassword::class)->name('password.request');
});

// ========================================
// Authenticated Routes
// ========================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Default dashboard redirect based on role (mteja/winga lazima wakamilishe onboarding kwanza)
    Route::get('dashboard', function () {
        $user = auth()->user();
        if (! $user->onboarding_completed && ! $user->isAdmin()) {
            if ($user->isMteja()) {
                return redirect()->route('onboarding.mteja');
            }
            if ($user->isWinga()) {
                return redirect()->route('onboarding.winga');
            }
        }
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isMteja()) {
            return redirect()->route('mteja.dashboard');
        }

        return redirect()->route('winga.dashboard');
    })->name('dashboard');

    // ========================================
    // Onboarding
    // ========================================
    Route::get('/onboarding/mteja', \App\Livewire\Onboarding\MtejaOnboarding::class)->name('onboarding.mteja');
    Route::get('/onboarding/winga', \App\Livewire\Onboarding\WingaOnboarding::class)->name('onboarding.winga');

    // ========================================
    // Mteja (Employer) Routes
    // ========================================
    Route::prefix('mteja')->name('mteja.')->group(function () {
        Route::get('/dashboard', \App\Livewire\Mteja\Dashboard::class)->name('dashboard');
        Route::get('/post-kazi', \App\Livewire\Mteja\PostKazi::class)->name('post-kazi');
        Route::get('/kazi-zangu', \App\Livewire\Mteja\KaziZangu::class)->name('kazi-zangu');
        Route::get('/codes', \App\Livewire\Mteja\Codes::class)->name('codes');
        Route::get('/kazi/{id}', \App\Livewire\Mteja\KaziDetail::class)->name('kazi-detail');
        Route::get('/maombi', \App\Livewire\Mteja\Maombi::class)->name('maombi');
        Route::get('/mawinga', \App\Livewire\Mteja\Mawinga::class)->name('mawinga');
        Route::get('/huduma', \App\Livewire\Mteja\HudumaMarketplace::class)->name('huduma');
        Route::get('/huduma-malipo', \App\Livewire\Mteja\HudumaMalipo::class)->name('huduma-malipo');
        Route::get('/winga/{id}', \App\Livewire\Mteja\WingaProfile::class)->name('winga-profile')->whereNumber('id');
        Route::get('/wallet', \App\Livewire\Mteja\Wallet::class)->name('wallet');
        Route::get('/analytics', \App\Livewire\Mteja\Analytics::class)->name('analytics');
        Route::get('/smart-match', \App\Livewire\Mteja\SmartMatch::class)->name('smart-match');
    });

    // ========================================
    // Winga (Worker) Routes
    // ========================================
    Route::prefix('winga')->name('winga.')->group(function () {
        Route::get('/dashboard', \App\Livewire\Winga\Dashboard::class)->name('dashboard');
        Route::get('/kazi-karibu', \App\Livewire\Winga\KaziKaribu::class)->name('kazi-karibu');
        Route::get('/maombi-yangu', \App\Livewire\Winga\MaombiYangu::class)->name('maombi-yangu');
        Route::get('/portfolio', \App\Livewire\Winga\Portfolio::class)->name('portfolio');
        Route::get('/mapato', \App\Livewire\Winga\Mapato::class)->name('mapato');
        Route::get('/tomba-ombi', \App\Livewire\Winga\TombaOmbi::class)->name('tomba-ombi');
        Route::get('/post-huduma', \App\Livewire\Winga\PostHuduma::class)->name('post-huduma');
        Route::get('/huduma/{service}/hariri', \App\Livewire\Winga\PostHuduma::class)->name('edit-huduma');
        Route::get('/huduma-zangu', \App\Livewire\Winga\HudumaZangu::class)->name('huduma-zangu');
        Route::get('/huduma-maombi', \App\Livewire\Winga\HudumaMaombi::class)->name('huduma-maombi');
        Route::get('/weka-code', \App\Livewire\Winga\WekaCode::class)->name('weka-code');
        Route::get('/ramani', \App\Livewire\Winga\Ramani::class)->name('ramani');
        Route::get('/subscription', \App\Livewire\Winga\Subscription::class)->name('subscription');
        Route::get('/kazi/{slug}', \App\Livewire\Winga\JobDetail::class)->name('kazi-detail');
    });

    // ========================================
    // Admin Routes
    // ========================================
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
        Route::get('/watumiaji', \App\Livewire\Admin\Watumiaji::class)->name('watumiaji');
        Route::get('/watumiaji/{id}', \App\Livewire\Admin\UserDetail::class)->name('watumiaji.detail')->whereNumber('id');
        Route::get('/kazi', \App\Livewire\Admin\Kazi::class)->name('kazi');
        Route::get('/kazi/pending', \App\Livewire\Admin\KaziPending::class)->name('kazi.pending');
        Route::get('/kazi/{id}', \App\Livewire\Admin\KaziDetail::class)->name('kazi.detail');
        Route::get('/malipo', \App\Livewire\Admin\Malipo::class)->name('malipo');
        Route::get('/migogoro', \App\Livewire\Admin\Migogoro::class)->name('migogoro');
        Route::get('/migogoro/{id}', \App\Livewire\Admin\MigogoroDetail::class)->name('migogoro.detail');
        Route::get('/kategoria', \App\Livewire\Admin\Kategoria::class)->name('kategoria');
        Route::get('/mazungumzo', \App\Livewire\Admin\Mazungumzo::class)->name('mazungumzo');
        Route::get('/maombi-kutoa', \App\Livewire\Admin\MaombiKutoa::class)->name('maombi-kutoa');
        Route::get('/subscriptions', \App\Livewire\Admin\Subscriptions::class)->name('subscriptions');
        Route::get('/subscription-plans', \App\Livewire\Admin\SubscriptionPlans::class)->name('subscription-plans');
        Route::get('/matangazo', \App\Livewire\Admin\Matangazo::class)->name('matangazo');
        Route::get('/settings', \App\Livewire\Admin\Settings::class)->name('settings');
        Route::get('/audit-logs', \App\Livewire\Admin\AuditLogs::class)->name('audit-logs');

        // Email Export Routes
        Route::get('/export-emails', [EmailExportController::class, 'export'])->name('export-emails');
        Route::get('/export-emails-details', [EmailExportController::class, 'exportWithDetails'])->name('export-emails-details');
    });
    // Shared: Messages + Notifications
    Route::get('/messages', \App\Livewire\Shared\Messages::class)->name('messages');
    Route::get('/messages/{conversationId}', \App\Livewire\Shared\Messages::class)->name('messages.conversation');
    Route::get('/notifications', \App\Livewire\Shared\Notifications::class)->name('notifications');
});

require __DIR__.'/settings.php';
