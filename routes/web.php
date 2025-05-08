<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectEntryController;
use App\Http\Controllers\SettingController;
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

// Redirect root to dashboard
Route::redirect('/', '/dashboard');

// Protected routes that require authentication
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Client management
    Route::resource('clients', ClientController::class);
    
    // Project management
    Route::resource('projects', ProjectController::class);
    
    // Project entries (hours)
    Route::resource('project-entries', ProjectEntryController::class);
    Route::get('projects/{project}/entries/create', [ProjectEntryController::class, 'createForProject'])->name('projects.entries.create');
    
    // Invoice management
    Route::resource('invoices', InvoiceController::class);
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoices.pdf');
    Route::post('invoices/{invoice}/send', [InvoiceController::class, 'sendInvoice'])->name('invoices.send');
    Route::patch('invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.status');
    
    // Settings
    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::patch('settings', [SettingController::class, 'update'])->name('settings.update');
});

// Profile routes provided by Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Test route for PDF viewing
Route::get('/test-invoice-pdf/{invoice}', function(App\Models\Invoice $invoice) {
    // Load relationships
    $invoice->load(['client', 'invoiceLines.project']);
    
    // Get all settings
    $settings = App\Models\Setting::all()->pluck('value', 'key_name')->toArray();
    
    // Generate PDF
    $pdf = Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.pdf', [
        'invoice' => $invoice,
        'settings' => $settings
    ]);
    
    // Enhanced PDF settings for A4 paper
    $pdf->setPaper('a4', 'portrait');
    $pdf->setOptions([
        'isHtml5ParserEnabled' => true,
        'isRemoteEnabled' => true,
        'defaultFont' => 'sans-serif',
        'dpi' => 150,
        'debugCss' => false,
        'isFontSubsettingEnabled' => true,
        'isPhpEnabled' => true,
        'isJavascriptEnabled' => true,
        'margin_top' => 0,
        'margin_right' => 0,
        'margin_bottom' => 0,
        'margin_left' => 0
    ]);
    
    // Show in browser
    return $pdf->stream('test.pdf');
});

require __DIR__.'/auth.php';
