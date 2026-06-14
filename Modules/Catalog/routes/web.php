<?php

use Illuminate\Support\Facades\Route;
use Modules\Catalog\Livewire\ProductBrowser;

Route::get('shop', ProductBrowser::class)->name('catalog.shop');
