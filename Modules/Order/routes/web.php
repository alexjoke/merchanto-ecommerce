<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Livewire\CreateOrder;
use Modules\Order\Livewire\ViewOrder;

Route::get('orders/create', CreateOrder::class)->name('order.create');
Route::get('orders/{reference}', ViewOrder::class)->name('order.view');
