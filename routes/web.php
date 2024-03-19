<?php

declare(strict_types=1);

/*
 * Copyright © 2024 - Garfaludica APS - MIT License
 */

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', static function() {
	return Inertia::render('Home', [
		'tdgLogo' => asset('storage/images/tdg-logo.png'),
	]);
})->name('home');

Route::get('/en', static function() {
	\App::setLocale('en');
	return Inertia::render('Home', [
		'tdgLogo' => asset('storage/images/tdg-logo.png'),
	]);
})->name('en.home');

Route::get('/about', static fn() => Inertia::render('About'))->name('about');

Route::get('/en/about', static function() {
	\App::setLocale('en');
	return Inertia::render('About');
})->name('en.about');

Route::get('/tables', static fn() => Inertia::render('About'))->name('tables');

Route::get('/en/tables', static function() {
	\App::setLocale('en');
	return Inertia::render('Tables');
})->name('en.tables');

Route::get('/hotels', static fn() => Inertia::render('Hotels'))->name('hotels');

Route::get('/en/hotels', static function() {
	\App::setLocale('en');
	return Inertia::render('Hotels');
})->name('en.hotels');

Route::get('/venue', static fn() => Inertia::render('Venue'))->name('venue');

Route::get('/en/venue', static function() {
	\App::setLocale('en');
	return Inertia::render('Venue');
})->name('en.venue');

Route::get('/organization', static fn() => Inertia::render('Organization'))->name('organization');

Route::get('/en/organization', static function() {
	\App::setLocale('en');
	return Inertia::render('Organization');
})->name('en.organization');

Route::get('/contact', static fn() => Inertia::render('Contact'))->name('contact');

Route::get('/en/contact', static function() {
	\App::setLocale('en');
	return Inertia::render('Contact');
})->name('en.contact');

Route::get('/book', static fn() => Inertia::render('Book'))->name('book');

Route::get('/en/book', static function() {
	\App::setLocale('en');
	return Inertia::render('Book');
})->name('en.book');

Route::get('/license', static fn() => Inertia::render('License'))->name('license');

Route::prefix('admin')->group(static function(): void {
	Route::get('/', static fn() => Inertia::render('Admin/Dashboard'))->name('admin');
	Route::get('/admins', static fn() => Inertia::render('Admin/Admins'))->name('admin.admins');
});
