<?php

use App\Livewire\Pages\Users\Create;
use App\Livewire\Pages\Users\Edit;
use App\Livewire\Pages\Users\Index;
use Illuminate\Support\Facades\Route;

//User Livewire 
Route::get('/users', Index::class)->name('users.index');
Route::get('/users/create', Create::class)->name('users.create');
Route::get('/users/{userId}/edit', Edit::class)->name('users.edit');

//Roles Livwire
Route::get('/roles', \App\Livewire\Pages\Roles\Index::class)->name('roles.index');
Route::get('/roles/create', \App\Livewire\Pages\Roles\Create::class)->name('roles.create');
Route::get('/roles/{roleId}/edit', \App\Livewire\Pages\Roles\Edit::class)->name('roles.edit');

//SubUnit Livewire
Route::get('/sub-units', \App\Livewire\Pages\SubUnit\Index::class)->name('sub-units.index');
Route::get('/sub-units/create', \App\Livewire\Pages\SubUnit\Create::class)->name('sub-units.create');
Route::get('/sub-units/{subUnitId}/edit', \App\Livewire\Pages\SubUnit\Edit::class)->name('sub-units.edit');

//Transactions Request
Route::get('/transactions/admin', \App\Livewire\Pages\Request\admin\Index::class)->name('transactions.index');
Route::get('/transactions/request/admin/{transactionId}/view', \App\Livewire\Pages\Request\admin\View::class)->name('transactions.view');
Route::get('/transactions/request/requestor/{transactionId}/view', \App\Livewire\Pages\Request\requestor\View::class)->name('transactions.requestor.view');
Route::get('/transactions/request/manager/{transactionId}/view', \App\Livewire\Pages\Request\manager\View::class)->name('transactions.manager.view');
Route::get('/transactions/requestor', \App\Livewire\Pages\Return\requestor\Index::class)->name('transactions.requestor.index');
Route::get('/transactions/requestor/request/create', \App\Livewire\Pages\Request\requestor\Create::class)->name('transactions.requestor.create');
Route::get('/transactions/requestor/request/{transactionId}/edit', \App\Livewire\Pages\Request\requestor\Edit::class)->name('transactions.asman.edit');
Route::get('/transactions/manager', \App\Livewire\Pages\Request\manager\Index::class)->name('transactions.manager.index');



//Transactions Return
Route::get('/transactions/requestor/return/create', \App\Livewire\Pages\Return\requestor\Create::class)->name('transactions.asman.return.create');
Route::get('/transactions/requestor/return/{transactionId}/view', \App\Livewire\Pages\Return\requestor\View::class)->name('transactions.requestor.return.view');
Route::get('/transactions/admin/return/{transactionId}/view', \App\Livewire\Pages\Return\admin\View::class)->name('transactions.admin.return.view');
Route::get('/transactions/manager/return/{transactionId}/view', \App\Livewire\Pages\Return\manager\View::class)->name('transactions.manager.return.view');
Route::get('/transactions/requestor/return/{transactionId}/edit', \App\Livewire\Pages\Return\requestor\Edit::class)->name('transactions.asman.return.edit');
Route::get('/transactions/senior-manager', \App\Livewire\Pages\Return\SeniorManager\Index::class)->name('transactions.senior.manager.index');
Route::get('/transactions/vice-president', \App\Livewire\Pages\Return\VicePresident\Index::class)->name('transactions.vice.president.index');
Route::get('/transactions/finance', \App\Livewire\Pages\Return\Finance\Index::class)->name('transactions.finance.index');
Route::get('/transactions/senior-manager/request/{transactionId}/view', \App\Livewire\Pages\Request\SeniorManager\View::class)->name('transactions.senior.manager.request.view');
Route::get('/transactions/vice-president/request/{transactionId}/view', \App\Livewire\Pages\Request\VicePresident\View::class)->name('transactions.vice.president.request.view');
Route::get('/transactions/finance/request/{transactionId}/view', \App\Livewire\Pages\Request\Finance\View::class)->name('transactions.finance.request.view');
Route::get('/transactions/senior-manager/return/{transactionId}/view', \App\Livewire\Pages\Return\SeniorManager\View::class)->name('transactions.senior.manager.return.view');
Route::get('/transactions/vice-president/return/{transactionId}/view', \App\Livewire\Pages\Return\VicePresident\View::class)->name('transactions.vice.president.return.view');
Route::get('/transactions/finance/{transactionId}/view', \App\Livewire\Pages\Return\Finance\View::class)->name('transactions.finance.return.view');

Route::get('/loginAsAdmin', [
    \App\Http\Controllers\AdminLoginController::class,
    'logginAdmin'
])->name('loginAsAdmin');
