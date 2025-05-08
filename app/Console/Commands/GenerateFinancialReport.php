<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateFinancialReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:financial 
                            {period=month : The period to report on (month/quarter/year)}
                            {--from= : Start date in Y-m-d format} 
                            {--to= : End date in Y-m-d format}
                            {--export : Export to CSV}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a financial summary report for a given period';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Determine date range based on period
        $period = $this->argument('period');
        $fromDate = $this->option('from') ? Carbon::parse($this->option('from')) : null;
        $toDate = $this->option('to') ? Carbon::parse($this->option('to')) : null;
        
        if (!$fromDate || !$toDate) {
            switch ($period) {
                case 'month':
                    $fromDate = $fromDate ?: Carbon::now()->startOfMonth();
                    $toDate = $toDate ?: Carbon::now()->endOfMonth();
                    break;
                case 'quarter':
                    $fromDate = $fromDate ?: Carbon::now()->startOfQuarter();
                    $toDate = $toDate ?: Carbon::now()->endOfQuarter();
                    break;
                case 'year':
                    $fromDate = $fromDate ?: Carbon::now()->startOfYear();
                    $toDate = $toDate ?: Carbon::now()->endOfYear();
                    break;
                default:
                    $this->error('Invalid period specified. Use month, quarter, or year.');
                    return Command::FAILURE;
            }
        }
        
        $this->info("Generating financial report from {$fromDate->format('Y-m-d')} to {$toDate->format('Y-m-d')}");
        
        // Get invoices within date range
        $invoices = Invoice::whereBetween('invoice_date', [$fromDate, $toDate])->get();
        
        // Calculate summary statistics
        $totalInvoiced = $invoices->sum('total_incl_btw');
        $totalPaid = $invoices->where('status', 'paid')->sum('total_incl_btw');
        $totalUnpaid = $invoices->where('status', 'unpaid')->sum('total_incl_btw');
        $totalOverdue = $invoices->where('status', 'overdue')->sum('total_incl_btw');
        
        // Client summary
        $clientSummary = [];
        foreach (Client::has('invoices')->get() as $client) {
            $clientInvoices = $invoices->where('client_id', $client->id);
            if ($clientInvoices->isNotEmpty()) {
                $clientSummary[] = [
                    'name' => $client->company_name,
                    'total_invoiced' => $clientInvoices->sum('total_incl_btw'),
                    'total_paid' => $clientInvoices->where('status', 'paid')->sum('total_incl_btw'),
                    'total_outstanding' => $clientInvoices->whereIn('status', ['unpaid', 'overdue'])->sum('total_incl_btw'),
                ];
            }
        }
        
        // Project summary
        $projectSummary = [];
        foreach (Project::all() as $project) {
            $projectRevenue = 0;
            
            // Calculate revenue from invoice lines linked to this project
            foreach ($invoices as $invoice) {
                foreach ($invoice->invoiceLines as $line) {
                    if ($line->project_id === $project->id) {
                        $projectRevenue += $line->line_total;
                    }
                }
            }
            
            if ($projectRevenue > 0) {
                $projectSummary[] = [
                    'name' => $project->name,
                    'client' => $project->client->company_name,
                    'revenue' => $projectRevenue,
                ];
            }
        }
        
        // Display report in console
        $this->info("\n=== FINANCIAL SUMMARY ===");
        $this->info("Period: {$period}");
        $this->info("From: {$fromDate->format('Y-m-d')} To: {$toDate->format('Y-m-d')}");
        $this->info("Total Invoiced: €" . number_format($totalInvoiced, 2));
        $this->info("Total Paid: €" . number_format($totalPaid, 2));
        $this->info("Total Unpaid: €" . number_format($totalUnpaid, 2));
        $this->info("Total Overdue: €" . number_format($totalOverdue, 2));
        
        $this->info("\n=== CLIENT SUMMARY ===");
        $this->table(
            ['Client', 'Total Invoiced', 'Total Paid', 'Outstanding'],
            collect($clientSummary)->map(function ($item) {
                return [
                    $item['name'],
                    '€' . number_format($item['total_invoiced'], 2),
                    '€' . number_format($item['total_paid'], 2),
                    '€' . number_format($item['total_outstanding'], 2),
                ];
            })
        );
        
        $this->info("\n=== PROJECT SUMMARY ===");
        $this->table(
            ['Project', 'Client', 'Revenue'],
            collect($projectSummary)->map(function ($item) {
                return [
                    $item['name'],
                    $item['client'],
                    '€' . number_format($item['revenue'], 2),
                ];
            })
        );
        
        // Export to CSV if requested
        if ($this->option('export')) {
            $this->exportToCsv($fromDate, $toDate, $totalInvoiced, $totalPaid, $totalUnpaid, $totalOverdue, $clientSummary, $projectSummary);
        }
        
        return Command::SUCCESS;
    }
    
    /**
     * Export report data to CSV files
     */
    private function exportToCsv($fromDate, $toDate, $totalInvoiced, $totalPaid, $totalUnpaid, $totalOverdue, $clientSummary, $projectSummary)
    {
        $dateRange = $fromDate->format('Ymd') . '-' . $toDate->format('Ymd');
        
        // Summary CSV
        $summaryPath = "reports/financial_summary_{$dateRange}.csv";
        $summaryContent = "Period,From,To,Total Invoiced,Total Paid,Total Unpaid,Total Overdue\n";
        $summaryContent .= "Financial Summary,{$fromDate->format('Y-m-d')},{$toDate->format('Y-m-d')},";
        $summaryContent .= number_format($totalInvoiced, 2) . ',';
        $summaryContent .= number_format($totalPaid, 2) . ',';
        $summaryContent .= number_format($totalUnpaid, 2) . ',';
        $summaryContent .= number_format($totalOverdue, 2) . "\n";
        
        Storage::put($summaryPath, $summaryContent);
        
        // Client CSV
        $clientPath = "reports/client_summary_{$dateRange}.csv";
        $clientContent = "Client,Total Invoiced,Total Paid,Outstanding\n";
        foreach ($clientSummary as $client) {
            $clientContent .= "\"{$client['name']}\",";
            $clientContent .= number_format($client['total_invoiced'], 2) . ',';
            $clientContent .= number_format($client['total_paid'], 2) . ',';
            $clientContent .= number_format($client['total_outstanding'], 2) . "\n";
        }
        
        Storage::put($clientPath, $clientContent);
        
        // Project CSV
        $projectPath = "reports/project_summary_{$dateRange}.csv";
        $projectContent = "Project,Client,Revenue\n";
        foreach ($projectSummary as $project) {
            $projectContent .= "\"{$project['name']}\",";
            $projectContent .= "\"{$project['client']}\",";
            $projectContent .= number_format($project['revenue'], 2) . "\n";
        }
        
        Storage::put($projectPath, $projectContent);
        
        $this->info("\nReports exported to:");
        $this->line("- {$summaryPath}");
        $this->line("- {$clientPath}");
        $this->line("- {$projectPath}");
    }
} 