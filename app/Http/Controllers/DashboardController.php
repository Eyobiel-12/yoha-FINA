<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\ProjectEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with financial statistics.
     */
    public function index()
    {
        // Count statistics
        $stats = [
            'clients_count' => Client::count(),
            'projects_count' => Project::count(),
            'unpaid_invoices' => Invoice::where('status', 'unpaid')->count(),
            'paid_invoices' => Invoice::where('status', 'paid')->count(),
        ];

        // Financial totals
        $financials = [
            'total_unpaid' => Invoice::where('status', 'unpaid')->sum('total_incl_btw'),
            'total_paid' => Invoice::where('status', 'paid')->sum('total_incl_btw'),
            'total_overdue' => Invoice::where('status', 'overdue')->sum('total_incl_btw'),
        ];

        // Recent invoices
        $recentInvoices = Invoice::with('client')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Monthly revenue for current year using SQLite compatible date functions
        $monthlyRevenue = Invoice::select(
                DB::raw("cast(strftime('%m', invoice_date) as integer) as month"),
                DB::raw('SUM(total_incl_btw) as total')
            )
            ->whereRaw("strftime('%Y', invoice_date) = ?", [date('Y')])
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();
            
        // Format months as 1-12 with 0 values for missing months
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[$i] = $monthlyRevenue[$i] ?? 0;
        }

        return view('dashboard', compact('stats', 'financials', 'recentInvoices', 'chartData'));
    }
}
