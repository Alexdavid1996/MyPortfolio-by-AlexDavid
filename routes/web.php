<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\AdminBlogPostController;
use App\Http\Controllers\AdminBlogCategoryController;
use App\Http\Controllers\AdminPortfolioController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\GscController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\AdminContactController;
use App\Http\Controllers\AdminMessageController;
use App\Http\Controllers\AdminServiceController;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [HomeController::class, 'index'])->name('home');

// Portfolio
Route::prefix('portfolio')->group(function () {
    Route::get('/', [PortfolioController::class, 'index'])->name('portfolio.index');
    Route::get('/{slug}', [PortfolioController::class, 'show'])->name('portfolio.show');
});

// Blog
Route::prefix('blog')->group(function () {
    Route::get('/', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
    Route::get('/{slug}', [BlogController::class, 'show'])->name('blog.show');
});

// Contact
Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Services
Route::get('/services', [ServicesController::class, 'index'])->name('services.index');

// Robots
Route::get('/robots.txt', function () {
    $content = "User-agent: *\nDisallow: /" . config('app.admin_prefix', 'admin');
    return response($content, 200)->header('Content-Type', 'text/plain');
});

// Admin
Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');

$adminPrefix = config('app.admin_prefix', 'admin');
Route::prefix($adminPrefix)->name('admin.')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])
        ->name('login.submit')
        ->middleware('throttle:5,1');

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    });

    Route::middleware(['auth', 'admin', 'verified'])->group(function () {
        Route::get('/', fn () => redirect()->route('admin.cv'));

        Route::get('/cv', [ProfileController::class, 'index'])->name('cv');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::resource('experiences', ExperienceController::class)->only(['store', 'update', 'destroy']);
        Route::resource('skills', SkillController::class)->only(['store', 'update', 'destroy']);
        Route::resource('languages', LanguageController::class)->only(['store', 'update', 'destroy']);

        Route::post('settings/contact-email', [SettingsController::class, 'updateContactEmail'])->name('settings.contact-email');

        Route::post('portfolio/generate-slug', [AdminPortfolioController::class, 'generateSlug'])->name('portfolio.generate-slug');
        Route::post('portfolio/upload-image', [AdminPortfolioController::class, 'uploadImage'])->name('portfolio.upload-image');
        Route::resource('portfolio', AdminPortfolioController::class)->except(['show']);
        Route::post('categories/generate-slug', [AdminBlogCategoryController::class, 'generateSlug'])->name('categories.generate-slug');
        Route::post('categories/upload-image', [AdminBlogCategoryController::class, 'uploadImage'])->name('categories.upload-image');
        Route::resource('categories', AdminBlogCategoryController::class)->except(['show']);
        Route::post('blog/generate-slug', [AdminBlogPostController::class, 'generateSlug'])->name('blog.generate-slug');
        Route::post('blog/upload-image', [AdminBlogPostController::class, 'uploadImage'])->name('blog.upload-image');
        Route::resource('blog', AdminBlogPostController::class)->except(['show']);
        Route::resource('services', AdminServiceController::class)->except(['show', 'create', 'edit']);
        Route::post('services/page', [AdminServiceController::class, 'updatePage'])->name('services.page');
        Route::get('/gsc', [GscController::class, 'index'])->name('gsc');
        Route::post('/gsc', [GscController::class, 'store'])->name('gsc.store');
        Route::resource('messages', AdminMessageController::class)->only(['index', 'destroy']);
        Route::get('messages/sidebar', [AdminMessageController::class, 'sidebar'])->name('messages.sidebar');
        Route::get('/contact', [AdminContactController::class, 'edit'])->name('contact');
        Route::post('/contact', [AdminContactController::class, 'update'])->name('contact.update');
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
        Route::post('/settings/general', [SettingsController::class, 'updateGeneral'])->name('settings.general');
        Route::post('/settings/account', [SettingsController::class, 'updateAccount'])->name('settings.account');
    });
});
