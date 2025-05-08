<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Factuur {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: A4 portrait;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: DejaVu Sans, sans-serif;
        }
        body {
            color: #333;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            font-size: 9pt;
            line-height: 1.3;
        }
        
        /* Header and footer bars */
        .header-bar {
            background-color: #6baa64;
            height: 1.2cm;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }
        
        .footer-bar {
            background-color: #6baa64;
            height: 1.2cm;
            width: 100%;
            position: absolute;
            bottom: 0;
            left: 0;
        }
        
        .footer-line {
            border-top: 1px solid #6baa64;
            position: absolute;
            bottom: 1.5cm;
            left: 10%;
            width: 80%;
        }
        
        /* Main content */
        .page-content {
            padding: 2cm 1.5cm 2.5cm 1.5cm;
        }
        
        /* Logo section */
        .logo-container {
            margin-bottom: 0.4cm;
        }
        
        .logo {
            max-height: 1.5cm;
        }
        
        /* Header section */
        .company-info {
            margin-bottom: 0.7cm;
        }
        
        .company-name {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 0.1cm;
        }
        
        .company-details {
            font-size: 9pt;
            line-height: 1.3;
        }
        
        .invoice-title {
            position: absolute;
            top: 2.5cm;
            right: 1.5cm;
            font-size: 18pt;
            font-weight: bold;
        }
        
        /* Columns layout */
        .columns-container {
            width: 100%;
            margin-bottom: 1cm;
        }
        
        .left-column {
            width: 60%;
            float: left;
        }
        
        .right-column {
            width: 40%;
            float: right;
        }
        
        /* Sections */
        .section-title {
            color: #6baa64;
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 0.2cm;
            border-bottom: 1px solid #6baa64;
            padding-bottom: 0.1cm;
            text-transform: uppercase;
        }
        
        /* Client info */
        .client-info {
            background-color: #f2f9f1;
            padding: 0.3cm;
            margin-bottom: 1cm;
            border-radius: 3px;
            width: 95%;
        }
        
        /* Invoice details */
        .invoice-details {
            background-color: #f2f9f1;
            padding: 0.3cm;
            border-radius: 3px;
            width: 100%;
        }
        
        .invoice-details-table {
            width: 100%;
        }
        
        .invoice-details-table td {
            padding: 0.1cm 0;
        }
        
        .invoice-details-table td:first-child {
            font-weight: bold;
            width: 45%;
        }
        
        /* Invoice items */
        .items-section {
            margin-bottom: 0.7cm;
            clear: both;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.2cm;
        }
        
        .items-table th {
            background-color: #6baa64;
            color: white;
            text-align: left;
            padding: 0.2cm;
            font-weight: bold;
            border: 1px solid #6baa64;
        }
        
        .items-table td {
            padding: 0.2cm;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        .text-right {
            text-align: right;
        }
        
        /* Totals */
        .totals-section {
            float: right;
            width: 40%;
            margin-bottom: 1cm;
            clear: right;
        }
        
        .totals {
            background-color: #f2f9f1;
            padding: 0.3cm;
            border-radius: 3px;
        }
        
        .totals-table {
            width: 100%;
        }
        
        .totals-table td {
            padding: 0.1cm;
        }
        
        .totals-table td:first-child {
            text-align: left;
        }
        
        .totals-table td:last-child {
            text-align: right;
        }
        
        .total-row {
            font-weight: bold;
            color: #6baa64;
        }
        
        .total-row td {
            border-top: 1px solid #6baa64;
            padding-top: 0.2cm;
        }
        
        /* Notes */
        .notes-section {
            clear: both;
            margin-bottom: 1cm;
        }
        
        .notes-content {
            background-color: #f2f9f1;
            padding: 0.3cm;
            border-radius: 3px;
        }
        
        /* Payment info */
        .payment-section {
            background-color: #6baa64;
            color: white;
            padding: 0.4cm;
            border-radius: 3px;
            clear: both;
        }
        
        .payment-title {
            font-weight: bold;
            margin-bottom: 0.1cm;
            text-transform: uppercase;
        }
        
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header-bar"></div>
    
    <div class="page-content">
        <!-- Logo -->
        <div class="logo-container">
            @if(isset($settings['logo_path']) && $settings['logo_path'])
                <img src="{{ public_path('storage/' . $settings['logo_path']) }}" alt="Yohannes Hoveniersbedrijf" class="logo">
            @else
                <div style="color: #6baa64; font-weight: bold; font-size: 14pt;">{{ $settings['company_name'] ?? 'Yohannes Hoveniersbedrijf' }}</div>
            @endif
        </div>
        
        <!-- Company info and invoice title -->
        <div class="company-info">
            <div class="company-name">{{ $settings['company_name'] ?? 'YOHANNES HOVENIERSBEDRIJF B.V.' }}</div>
            <div class="company-details">
                {{ $settings['company_address'] ?? 'Aristotelesstraat 993' }}<br>
                {{ $settings['company_postal_city'] ?? '7323 NZ Apeldoorn' }}<br>
                Tel: {{ $settings['company_phone'] ?? '0616638510' }} | Email: {{ $settings['company_email'] ?? 'info@yohanneshoveniersbedrijf.com' }}<br>
                KVK: {{ $settings['company_kvk'] ?? '92625703' }} | BTW: {{ $settings['company_btw'] ?? 'NL866120877B01' }}
            </div>
        </div>
        
        <div class="invoice-title">FACTUUR</div>
        
        <!-- Two columns layout for client and invoice details -->
        <div class="columns-container clearfix">
            <div class="left-column">
                <!-- Client Info -->
                <div class="section-title">KLANTGEGEVENS</div>
                <div class="client-info">
                    <strong>{{ $invoice->client->company_name }}</strong><br>
                    {{ $invoice->client->address }}<br>
                    @if($invoice->client->kvk_number)
                        KVK: {{ $invoice->client->kvk_number }}<br>
                    @endif
                    @if($invoice->client->btw_number)
                        BTW: {{ $invoice->client->btw_number }}
                    @endif
                </div>
            </div>
            
            <div class="right-column">
                <!-- Invoice Details -->
                <div class="section-title">FACTUURGEGEVENS</div>
                <div class="invoice-details">
                    <table class="invoice-details-table">
                        <tr>
                            <td>Factuurnummer:</td>
                            <td>{{ $invoice->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td>Factuurdatum:</td>
                            <td>{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td>Vervaldatum:</td>
                            <td>{{ $invoice->getDueDateAttribute()->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <td>Status:</td>
                            <td>{{ ucfirst($invoice->status) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Invoice Items -->
        <div class="items-section">
            <div class="section-title">FACTUURITEMS</div>
            <table class="items-table">
                <thead>
                    <tr>
                        <th width="20%">Project</th>
                        <th width="40%">Omschrijving</th>
                        <th width="12%" class="text-right">Uren</th>
                        <th width="12%" class="text-right">Tarief</th>
                        <th width="16%" class="text-right">Bedrag</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->invoiceLines as $line)
                        <tr>
                            <td>{{ $line->project->name }}</td>
                            <td>{{ $line->description }}</td>
                            <td class="text-right">{{ number_format($line->hours, 2) }}</td>
                            <td class="text-right">€{{ number_format($line->rate, 2) }}</td>
                            <td class="text-right">€{{ number_format($line->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Totals -->
        <div class="totals-section">
            <div class="totals">
                <table class="totals-table">
                    <tr>
                        <td>Subtotaal:</td>
                        <td>€{{ number_format($invoice->total_excl_btw, 2) }}</td>
                    </tr>
                    <tr>
                        <td>BTW ({{ $invoice->btw_percentage }}%):</td>
                        <td>€{{ number_format($invoice->btw_amount, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td>Totaal:</td>
                        <td>€{{ number_format($invoice->total_incl_btw, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Notes Section if needed -->
        @if(isset($invoice->notes) && $invoice->notes)
        <div class="notes-section">
            <div class="section-title">NOTITIES</div>
            <div class="notes-content">
                {{ $invoice->notes }}
            </div>
        </div>
        @endif
        
        <!-- Payment Information -->
        <div class="payment-section">
            <div class="payment-title">BETALINGSINFORMATIE</div>
            Gelieve binnen {{ $invoice->payment_due_days }} dagen te voldoen op rekeningnummer {{ $settings['bank_iban'] ?? 'NL44ABNA0108854914' }} onder vermelding van het factuurnummer ten name van {{ $settings['company_name'] ?? 'YOHANNES HOVENIERSBEDRIJF B.V.' }}
        </div>
    </div>
    
    <div class="footer-line"></div>
    <div class="footer-bar"></div>
</body>
</html> 