<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use NanoDepo\Taberna\Controllers\MerchantCenterController;
use NanoDepo\Taberna\Controllers\SitemapController;

Volt::route('/', 'shop.home')->name('home');

Volt::route('/c', 'shop.category')->name('categories');
Volt::route('/c/{category:slug}', 'shop.category.show')->name('category')->missing(fn () => response()->view(view: 'taberna::not-found', status: 404));
Volt::route('/c/{category:slug}/{product:sku}', 'shop.category.product')->name('product')->missing(fn () => response()->view(view: 'taberna::not-found', status: 404));
Volt::route('/c/{category:slug}/{product:sku}/{variant:sku}', 'shop.category.variant')->name('variant')->missing(fn () => response()->view(view: 'taberna::not-found', status: 404));

Volt::route('/page/about-us', 'shop.pages.about-us')->name('about-us');
Volt::route('/page/delivery', 'shop.pages.delivery')->name('delivery');
Volt::route('/page/return-policy', 'shop.pages.return-policy')->name('return-policy');
Volt::route('/page/privacy-policy', 'shop.pages.privacy-policy')->name('privacy-policy');

Volt::route('/basket', 'shop.basket')->name('basket');
Volt::route('/basket/{order}', 'shop.thank-you')->name('thank-you')->missing(fn () => response()->view(view: 'taberna::not-found', status: 404));

Route::get('/sitemap.xml', SitemapController::class);
Route::get('/merchant-feed.xml', MerchantCenterController::class);

Volt::route('/profile', 'shop.profile.show')->name('profile.show')->middleware('auth');
Volt::route('/profile/edit', 'shop.profile.edit')->name('profile.edit')->middleware('auth');
Volt::route('/profile/order', 'shop.profile.order')->name('profile.order.index')->middleware('auth');
Volt::route('/profile/order/{order}', 'shop.profile.order.show')->name('profile.order.show')->middleware('auth');

Route::middleware(['auth', 'role:manager,admin'])->prefix('dashboard')->group(function () {
    Volt::route('/', 'shop.dashboard')->name('dashboard');

    Volt::route('/group', 'shop.dashboard.group')->name('group.index');
    Volt::route('/group/{attribute}', 'shop.dashboard.group.attribute')->name('attribute.show');

    Volt::route('/brand', 'shop.dashboard.brand')->name('brand.index');
    Volt::route('/brand/{brand}', 'shop.dashboard.brand.show')->name('brand.show');

    Volt::route('/category', 'shop.dashboard.category')->name('category.index');
    Volt::route('/category/create', 'shop.dashboard.category.create')->name('category.create');
    Volt::route('/category/{category}', 'shop.dashboard.category.show')->name('category.show');
    Volt::route('/category/{category}/create', 'shop.dashboard.category.create')->name('category.children');
    Volt::route('/category/{category}/edit', 'shop.dashboard.category.edit')->name('category.edit');
    Volt::route('/category/{category}/attribute', 'shop.dashboard.category.attribute')->name('category.attribute');

    Route::redirect('/product', '/dashboard/category');
    Volt::route('/category/{category}/create/product', 'shop.dashboard.product.create')->name('product.create');
    Volt::route('/product/{product}', 'shop.dashboard.product.show')->name('product.show');
    Volt::route('/product/{product}/edit', 'shop.dashboard.product.edit')->name('product.edit');
    Volt::route('/product/{product}/{variant}', 'shop.dashboard.product.variant')->name('product.variant');

    Volt::route('/addon', 'shop.dashboard.addon')->name('addon.index');
    Volt::route('/addon/create', 'shop.dashboard.addon.create')->name('addon.create');
    Volt::route('/addon/{addon}', 'shop.dashboard.addon.show')->name('addon.show');

    Volt::route('/order', 'shop.dashboard.order')->name('order.index');
    Volt::route('/order/{order}', 'shop.dashboard.order.show')->name('order.show');

    Volt::route('/user', 'shop.dashboard.user')->name('user.index');
});
