<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Mail\InvoiceReminder;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::with('client')->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        $projects = Project::all();
        return view('invoices.create', compact('clients', 'projects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInvoiceRequest $request)
    {
        $validated = $request->validated();

        // Start a database transaction
        \DB::beginTransaction();
        
        try {
            // Calculate totals for items first
            $totalExclBtw = 0;
            $invoiceLines = [];
            
            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity'] * $item['price'];
                $totalExclBtw += $lineTotal;
                
                $invoiceLines[] = [
                    'description' => $item['description'],
                    'hours' => $item['quantity'],
                    'rate' => $item['price'],
                    'line_total' => $lineTotal,
                    'project_id' => $validated['project_id'] ?? null,
                ];
            }
            
            // Calculate BTW amount and total
            $btwAmount = $totalExclBtw * ($validated['btw_percentage'] / 100);
            $totalInclBtw = $totalExclBtw + $btwAmount;
            
            // Create the invoice with all totals included from the start
            $invoice = Invoice::create([
                'client_id' => $validated['client_id'],
                'invoice_number' => $validated['invoice_number'],
                'invoice_date' => $validated['invoice_date'],
                'payment_due_days' => $validated['payment_days'],
                'btw_percentage' => $validated['btw_percentage'],
                'status' => 'unpaid',
                'total_excl_btw' => $totalExclBtw,
                'btw_amount' => $btwAmount,
                'total_incl_btw' => $totalInclBtw,
            ]);
            
            // Create invoice lines
            foreach ($invoiceLines as $line) {
                $invoice->invoiceLines()->create($line);
            }
            
            \DB::commit();
            
            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Invoice created successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'invoiceLines.project']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        $clients = Client::all();
        $projects = Project::all();
        $invoice->load(['client', 'invoiceLines.project']);
        return view('invoices.edit', compact('invoice', 'clients', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }

    /**
     * Generate PDF for the invoice
     */
    public function generatePdf(Invoice $invoice)
    {
        // Load relationships
        $invoice->load(['client', 'invoiceLines.project']);
        
        // Get all settings as key-value pairs
        $settings = Setting::all()->pluck('value', 'key_name')->toArray();
        
        // Generate PDF with specific configuration for better rendering
        $pdf = PDF::loadView('invoices.pdf', [
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
        
        // Generate filename
        $filename = 'invoice_' . $invoice->invoice_number . '.pdf';
        
        // Store PDF file
        $pdfPath = 'invoices/' . $filename;
        Storage::disk('public')->put($pdfPath, $pdf->output());
        
        // Update the invoice with the PDF path
        $invoice->update([
            'pdf_path' => 'storage/' . $pdfPath
        ]);
        
        // Return PDF for download
        return $pdf->download($filename);
    }
    
    /**
     * Send invoice via email
     */
    public function sendInvoice(Invoice $invoice)
    {
        // Make sure invoice is loaded with client
        $invoice->load('client');
        
        // Check if client has email
        if (!$invoice->client->email) {
            return redirect()->route('invoices.show', $invoice)
                ->with('error', 'Client has no email address. Please add one first.');
        }
        
        // Generate PDF if not already generated
        if (!$invoice->pdf_path) {
            // Generate the PDF
            $settings = Setting::all()->pluck('value', 'key_name')->toArray();
            
            $pdf = PDF::loadView('invoices.pdf', [
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
            
            // Generate filename
            $filename = 'invoice_' . $invoice->invoice_number . '.pdf';
            
            // Store PDF file
            $pdfPath = 'invoices/' . $filename;
            Storage::disk('public')->put($pdfPath, $pdf->output());
            
            // Update the invoice with the PDF path
            $invoice->update([
                'pdf_path' => 'storage/' . $pdfPath
            ]);
        }
        
        // Get settings for email
        $settings = Setting::all()->pluck('value', 'key_name')->toArray();
        
        // Send the email
        Mail::to($invoice->client->email)
            ->send(new InvoiceReminder($invoice));
        
        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice reminder has been sent to ' . $invoice->client->email);
    }
    
    /**
     * Update invoice status
     */
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'status' => 'required|in:unpaid,paid,overdue'
        ]);
        
        $invoice->update($validated);
        
        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice status has been updated.');
    }
}
