<?php

namespace App\Console\Commands;

use App\Mail\InvoiceReminder;
use App\Models\Invoice;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendInvoiceReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:send-reminders {--days=7 : Number of days overdue to trigger reminder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for overdue invoices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to send overdue invoice reminders...');
        
        $days = $this->option('days');
        $this->info("Looking for invoices {$days} days overdue...");
        
        // Get settings for email
        $settings = Setting::all()->pluck('value', 'key_name')->toArray();
        
        // Get overdue invoices
        $overdueInvoices = Invoice::where('status', 'overdue')
            ->with('client', 'invoiceLines.project')
            ->get();
        
        $count = 0;
        
        foreach ($overdueInvoices as $invoice) {
            // Skip if client has no email
            if (!$invoice->client->email) {
                $this->warn("Skipping invoice #{$invoice->invoice_number} - no email for client {$invoice->client->company_name}");
                continue;
            }
            
            // Calculate days overdue
            $dueDate = $invoice->getDueDateAttribute();
            $daysOverdue = now()->diffInDays($dueDate, false); // Negative value means overdue
            
            // Only send reminders for invoices overdue by the specified number of days
            if (abs($daysOverdue) >= $days) {
                $this->line("Sending reminder for invoice #{$invoice->invoice_number} to {$invoice->client->email}");
                
                try {
                    // Send the email
                    Mail::to($invoice->client->email)
                        ->send(new InvoiceReminder($invoice));
                        
                    // Log successful send
                    Log::info("Invoice reminder sent: #{$invoice->invoice_number} to {$invoice->client->email}");
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Failed to send reminder for invoice #{$invoice->invoice_number}: {$e->getMessage()}");
                    Log::error("Failed to send invoice reminder: {$e->getMessage()}", [
                        'invoice_id' => $invoice->id,
                        'client_email' => $invoice->client->email
                    ]);
                }
            }
        }
        
        Log::info("Invoice reminder process completed. {$count} reminders sent.");
        
        $this->info("Finished sending reminders. {$count} emails sent.");
        
        return Command::SUCCESS;
    }
} 