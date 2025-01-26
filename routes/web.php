<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AssociateController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CourtBranchController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\LawCaseController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\OfficeExpenseController;
use App\Http\Controllers\CustomEventController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\settings\AdminFeeCategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/contact/{contactableType}/{contactableId}', [ContactController::class, 'index'])->name('contact');
    Route::post('/contact/{contactableType}/{contactableId}', [ContactController::class, 'store']);
    Route::delete('/contact/{contact}', [ContactController::class, 'delete'])->name('contact.delete');
    Route::put('/contact/{contact}', [ContactController::class, 'update'])->name('contact.update');

    Route::get('/note/{notableType}/{notableId}', [NoteController::class, 'index'])->name('note');
    Route::post('/note/{notableType}/{notableId}', [NoteController::class, 'store']);
    Route::put('/note/{note}', [NoteController::class, 'update'])->name('note.update');
    Route::delete('/note/{note}', [NoteController::class, 'delete'])->name('note.delete');


    Route::get('/', [LawCaseController::class, 'index'])->name('home');

    Route::get('/date-info/{date}', [CalendarController::class, 'dateInfo'])->name('dateInfo');
    Route::get('/month-info/{date}', [CalendarController::class, 'monthInfo'])->name('monthInfo');

    Route::get('/client', [ClientController::class, 'index'])->name('client');
    Route::get('/client/create', [ClientController::class, 'create'])->name('client.create');
    Route::post('/client', [ClientController::class, 'store']);
    Route::get('/client/{client}', [ClientController::class, 'show'])->name('client.show');
    Route::put('/client/{client}', [ClientController::class, 'update'])->name('client.update');
    Route::delete('/client/{client}', [ClientController::class, 'destroy'])->name('client.delete');
    // Route::get('/client/{client}/contacts', [ContactController::class, 'indexClient'])->name('client.contacts');
    // Route::post('/client/{client}/contacts', [ContactController::class, 'storeClient']);
    // Route::get('/client/{client}/notes', [NoteController::class, 'indexClient'])->name('client.notes');
    // Route::post('/client/{client}/notes', [NoteController::class, 'storeClient']);

    Route::get('/associate', [AssociateController::class, 'index'])->name('associate');
    Route::get('/associate/create', [AssociateController::class, 'create'])->name('associate.create');
    Route::post('/associate', [AssociateController::class, 'store']);
    Route::get('/associate/{associate}', [AssociateController::class, 'show'])->name('associate.show');
    Route::put('/associate/{associate}', [AssociateController::class, 'update'])->name('associate.update');
    Route::delete('/associate/{associate}', [AssociateController::class, 'destroy'])->name('associate.delete');
    // Route::get('/associate/{associate}/contacts', [ContactController::class, 'indexAssociate'])->name('associate.contacts');
    // Route::post('/associate/{associate}/contacts', [ContactController::class, 'storeAssociate']);

    Route::get('/court-branch', [CourtBranchController::class, 'index'])->name('courtBranch');
    Route::get('/court-branch/create', [CourtBranchController::class, 'create'])->name('courtBranch.create');
    Route::post('/court-branch', [CourtBranchController::class, 'store']);
    Route::get('/court-branch/{courtBranch}', [CourtBranchController::class, 'show'])->name('courtBranch.show');
    Route::put('/court-branch/{courtBranch}', [CourtBranchController::class, 'update'])->name('courtBranch.update');
    Route::delete('/court-branch/{courtBranch}', [CourtBranchController::class, 'destroy'])->name('courtBranch.delete');
    // Route::get('/court-branch/{courtBranch}/contacts', [ContactController::class, 'indexCourtBranch'])->name('courtBranch.contacts');
    // Route::post('/court-branch/{courtBranch}/contacts', [ContactController::class, 'storeCourtBranch']);

    Route::get('/case', [LawCaseController::class, 'index'])->name('case');
    Route::get('/case/create', [LawCaseController::class, 'create'])->name('case.create');
    Route::post('/case', [LawCaseController::class, 'store']);
    Route::get('/case/{lawCase}', [LawCaseController::class, 'show'])->name('case.show');
    Route::put('/case/{lawCase}', [LawCaseController::class, 'update'])->name('case.update');
    Route::delete('/case/{lawCase}', [LawCaseController::class, 'destroy'])->name('case.delete');

    // Route::get('/case/{lawCase}/contacts', [ContactController::class, 'indexCase'])->name('case.contacts');
    // Route::post('/case/{lawCase}/contacts', [ContactController::class, 'storeCase']);


    Route::post('/case/{lawCase}/admin-deposit', [LawCaseController::class, 'addAdminDeposit'])->name('case.addAdminDeposit');
    Route::put('/case/admin-deposit/{adminDeposit}', [LawCaseController::class, 'updateAdminDeposit'])->name('case.updateAdminDeposit');
    Route::delete('/case/admin-deposit/{adminDeposit}', [LawCaseController::class, 'deleteAdminDeposit'])->name('case.deleteAdminDeposit');

    Route::post('/case/{lawCase}/admin-fee', [LawCaseController::class, 'addAdminFee'])->name('case.addAdminFee');
    Route::put('/case/admin-fee/{adminFee}', [LawCaseController::class, 'updateAdminFee'])->name('case.updateAdminFee');
    Route::delete('/case/admin-fee/{adminFee}', [LawCaseController::class, 'deleteAdminFee'])->name('case.deleteAdminFee');

    Route::post('/case/{lawCase}/hearing', [LawCaseController::class, 'addHearing'])->name('case.addHearing');
    Route::put('/case/hearing/{hearing}', [LawCaseController::class, 'updateHearing'])->name('case.updateHearing');
    Route::delete('/case/hearing/{hearing}', [LawCaseController::class, 'deleteHearing'])->name('case.deleteHearing');

    Route::post('/case/{lawCase}/billing', [LawCaseController::class, 'addBilling'])->name('case.addBilling');
    Route::put('/case/billing/{billing}', [LawCaseController::class, 'updateBilling'])->name('case.updateBilling');
    Route::delete('/case/billing/{billing}', [LawCaseController::class, 'deleteBilling'])->name('case.deleteBilling');

    Route::put('/case/update-status/{lawCase}', [LawCaseController::class, 'updateStatus'])->name('case.updateStatus');

    Route::get('/bill-deposits', [DepositController::class, 'index'])->name('billing-deposit');

    Route::get('/bill', [BillingController::class, 'index'])->name('billing');
    Route::get('/bill/{billing}', [BillingController::class, 'show'])->name('billing.show');
    Route::post('/bill', [BillingController::class, 'store']);

    Route::post('/bill/deposit/{billing}', [BillingController::class, 'addDeposit'])->name('billing.addDeposit');
    Route::put('/bill/deposit/{deposit}', [BillingController::class, 'updateDeposit'])->name('billing.updateDeposit');
    Route::delete('/bill/deposit/{deposit}', [BillingController::class, 'deleteDeposit'])->name('billing.deleteDeposit');

    Route::get('/office-expense', [OfficeExpenseController::class, 'index'])->name('officeExpense');
    Route::get('/office-expense/create', [OfficeExpenseController::class, 'create'])->name('officeExpense.create');
    Route::get('/office-expense/{officeExpense}', [OfficeExpenseController::class, 'show'])->name('officeExpense.show');
    Route::post('/office-expense', [OfficeExpenseController::class, 'store']);
    Route::put('/office-expense/{officeExpense}', [OfficeExpenseController::class, 'update'])->name('officeExpense.update');
    Route::delete('/office-expense/{officeExpense}', [OfficeExpenseController::class, 'destroy'])->name('officeExpense.delete');

    Route::get('/custom-event', [customEventController::class, 'index'])->name('customEvent');
    Route::get('/custom-event/create', [customEventController::class, 'create'])->name('customEvent.create');
    Route::get('/custom-event/{customEvent}', [customEventController::class, 'show'])->name('customEvent.show');
    Route::post('/custom-event', [customEventController::class, 'store']);
    Route::put('/custom-event/{customEvent}', [customEventController::class, 'update'])->name('customEvent.update');
    Route::delete('/custom-event/{customEvent}', [customEventController::class, 'destroy'])->name('customEvent.delete');

    Route::get('/settings/admin-fee-category/create', [AdminFeeCategoryController::class, 'create'])->name('settings.adminFeeCategory.create');

    Route::get('/settings/admin-fee-category/', [AdminFeeCategoryController::class, 'index'])->name('settings.adminFeeCategory');
    Route::post('/settings/admin-fee-category/', [AdminFeeCategoryController::class, 'store']);

    Route::get('/ajax/clients', [AjaxController::class, 'getClients'])->name('ajax.clients');
    Route::get('/ajax/associates', [AjaxController::class, 'getAssociates'])->name('ajax.associates');
    Route::get('/ajax/court-branches', [AjaxController::class, 'getCourtBranches'])->name('ajax.court-branches');

    // Route::get('/contact/$id', [ContactController::class, 'store'])->name('contact');


    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user', [UserController::class, 'store']);
    Route::get('/user/{user}', [UserController::class, 'show'])->name('user.show');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{user}', [UserController::class, 'destroy'])->name('user.delete');
    Route::post('/user/reset-password/{user}', [UserController::class, 'resetPassword'])->name('user.resetPassword');
    // Route::get('/user/{user}/contacts', [UserController::class, 'indexAssociate'])->name('user.contacts');
    // Route::post('/user/{user}/contacts', [UserController::class, 'storeAssociate']);

    Route::get('/pdf/invoice/{billing}', [PdfController::class, 'billingInvoice'])->name('pdf.billingInvoice');
    Route::get('/pdf/acknowledge-receipt/{deposit}', [PdfController::class, 'acknowledgeReceipt'])->name('pdf.acknowledgeReceipt');
});


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});
