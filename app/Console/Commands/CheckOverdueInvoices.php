<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckOverdueInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for unpaid invoices that are past their due date and mark them as overdue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue invoices...');
        
        // Get current date
        $today = Carbon::today();
        
        // Find all unpaid invoices
        $unpaidInvoices = Invoice::where('status', 'unpaid')->get();
        
        // Filter to only those that are overdue
        $overdueInvoices = $unpaidInvoices->filter(function ($invoice) use ($today) {
            // Calculate due date using invoice date and payment terms
            $dueDate = Carbon::parse($invoice->invoice_date)->addDays($invoice->payment_due_days);
            
            // Invoice is overdue if due date is in the past
            return $dueDate->lt($today);
        });
        
        $count = $overdueInvoices->count();
        $this->info("Found {$count} overdue invoices.");
        
        // Update status to overdue
        foreach ($overdueInvoices as $invoice) {
            $invoice->update(['status' => 'overdue']);
            $this->line("Invoice #{$invoice->invoice_number} marked as overdue.");
            
            // Could also send notification emails here if needed
        }
        
        Log::info("Overdue invoice check completed. {$count} invoices marked as overdue.");
        
        $this->info('Finished checking overdue invoices.');
        
        return Command::SUCCESS;
    }
} 