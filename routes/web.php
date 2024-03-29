<?php

declare(strict_types=1);

/*
 * Copyright © 2024 - Garfaludica APS - MIT License
 */

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\InvitationController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group([
	'middleware' => 'lang:pub',
], static function(): void {
	Route::get('/', static function() {
		return Inertia::render('Home', [
			'tdgLogo' => asset('storage/images/tdg-logo.png'),
		]);
	})->name('home');
	Route::get('/about', static fn() => Inertia::render('About'))->name('about');
	Route::get('/tables', static fn() => Inertia::render('About'))->name('tables');
	Route::get('/hotels', static fn() => Inertia::render('Hotels'))->name('hotels');
	Route::get('/venue', static fn() => Inertia::render('Venue'))->name('venue');
	Route::get('/organization', static fn() => Inertia::render('Organization'))->name('organization');
	Route::get('/contact', static fn() => Inertia::render('Contact'))->name('contact');
	Route::get('/book', static fn() => Inertia::render('Book'))->name('book');
	Route::get('/license', static fn() => Inertia::render('License'))->name('license');
});

Route::group([
	'middleware' => 'lang:pub',
	'prefix' => 'en',
	'as' => 'en.',
], static function(): void {
	Route::get('/', static function() {
		return Inertia::render('Home', [
			'tdgLogo' => asset('storage/images/tdg-logo.png'),
		]);
	})->name('home');
	Route::get('/about', static fn() => Inertia::render('About'))->name('about');
	Route::get('/tables', static fn() => Inertia::render('About'))->name('tables');
	Route::get('/hotels', static fn() => Inertia::render('Hotels'))->name('hotels');
	Route::get('/venue', static fn() => Inertia::render('Venue'))->name('venue');
	Route::get('/organization', static fn() => Inertia::render('Organization'))->name('organization');
	Route::get('/contact', static fn() => Inertia::render('Contact'))->name('contact');
	Route::get('/book', static fn() => Inertia::render('Book'))->name('book');
});

Route::group([
	'middleware' => ['lang:admin', 'auth:web'],
	'prefix' => 'admin',
	'as' => 'admin.',
], static function(): void {
	Route::get('/', static fn() => to_route('admin.dashboard'))->name('index');
	Route::get('/dashboard', DashboardController::class)->name('dashboard');
	Route::resource('admins', AdminController::class)->only([
		'index', 'destroy',
	]);
	Route::resource('invites', InvitationController::class)->only([
		'store', 'destroy',
	]);
})->name('admin');

Route::get('/en/admin', static fn() => to_route('admin.dashboard'))->middleware(['lang:pub', 'auth:web'])->name('en.admin.index');

Route::get('/accept-invite/{invite}/{token}', [InvitationController::class, 'acceptForm'])->middleware(['lang:admin', 'guest:web'])->name('admin.invitation.accept');
Route::post('/accept-invite/{invite}', [InvitationController::class, 'accept'])->middleware(['lang:admin', 'guest:web'])->name('admin.register');

Route::group([
	'middleware' => ['lang:pub', 'guest:web'],
	'prefix' => 'admin',
	'controller' => AuthController::class,
	'as' => 'auth.',
], static function(): void {
	Route::get('/login', 'login')->withoutMiddleware('lang:admin')->middleware('lang:pub')->name('login');
	Route::get('/forgot-password', 'forgotPassword')->name('password.forgot');
});

Route::group([
	'middleware' => ['lang:admin', 'guest:web'],
	'prefix' => 'admin',
	'controller' => AuthController::class,
	'as' => 'auth.',
], static function() : void {
	Route::post('/login', 'authenticate')->middleware('throttle:login')->name('authenticate');
	Route::post('/forgot-password', 'sendPasswordResetLink')->middleware('throttle:password-reset')->name('password.email');
	Route::post('/reset-password', 'updatePassword')->name('password.reset');
});

Route::get('/en/admin/login', [AuthController::class, 'login'])->middleware(['lang:pub', 'guest:web'])->name('en.auth.login');

Route::get('/en/forgot-password', [AuthController::class, 'forgotPassword'])->middleware(['lang:pub', 'guest:web'])->name('en.auth.password.forgot');
Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->middleware(['lang:admin', 'guest:web'])->name('password.reset');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:web')->name('auth.logout');
