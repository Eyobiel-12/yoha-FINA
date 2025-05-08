<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }
            .footer {
                width: 100% !important;
            }
        }
        
        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #333333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        
        .header {
            background-color: #6baa64;
            padding: 20px;
            text-align: center;
        }
        
        .logo {
            max-width: 200px;
            height: auto;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .footer {
            background-color: #f2f9f1;
            color: #6c757d;
            padding: 15px;
            text-align: center;
            font-size: 14px;
        }
        
        .invoice-details {
            background-color: #f2f9f1;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th, .table td {
            padding: 10px;
            text-align: left;
        }
        
        .button {
            background-color: #6baa64;
            border-radius: 4px;
            color: #ffffff;
            display: inline-block;
            font-weight: bold;
            padding: 12px 20px;
            text-decoration: none;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        @if(isset($settings['logo_path']))
            <img src="{{ asset($settings['logo_path']) }}" alt="Yohannes Hoveniersbedrijf" class="logo">
        @else
            <h1 style="color: white; margin: 0;">YOHANNES HOVENIERSBEDRIJF B.V.</h1>
        @endif
    </div>
    
    <div class="container">
        <h2>Herinnering: Openstaande factuur</h2>
        
        <p>Beste {{ $invoice->client->contact_name }},</p>
        
        <p>Volgens onze administratie hebben wij nog geen betaling ontvangen voor de onderstaande factuur die inmiddels is verlopen. We vragen u vriendelijk om deze factuur zo spoedig mogelijk te voldoen.</p>
        
        <div class="invoice-details">
            <table class="table">
                <tr>
                    <th>Factuurnummer:</th>
                    <td>{{ $invoice->invoice_number }}</td>
                </tr>
                <tr>
                    <th>Factuurdatum:</th>
                    <td>{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Vervaldatum:</th>
                    <td>{{ $invoice->getDueDateAttribute()->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Dagen te laat:</th>
                    <td>{{ now()->diffInDays($invoice->getDueDateAttribute()) }}</td>
                </tr>
                <tr>
                    <th>Totaalbedrag:</th>
                    <td>€{{ number_format($invoice->total_incl_btw, 2, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        
        <p>Als u de factuur reeds heeft betaald en deze betaling recent was, vragen wij u vriendelijk om deze herinnering te negeren.</p>
        
        <p>Als u vragen heeft over deze factuur of als er een reden is waarom u de factuur niet kunt betalen, neem dan contact met ons op, zodat we samen naar een oplossing kunnen zoeken.</p>
        
        <p>U kunt betalen door het totaalbedrag over te maken naar:</p>
        <p>
            <strong>IBAN:</strong> {{ $settings['bank_iban'] ?? 'NL44ABNA0108854914' }}<br>
            <strong>T.n.v.:</strong> YOHANNES HOVENIERSBEDRIJF B.V.<br>
            <strong>Onder vermelding van:</strong> Factuurnummer {{ $invoice->invoice_number }}
        </p>
        
        <p>De factuur is ook als bijlage toegevoegd aan deze e-mail.</p>
        
        <p>Met vriendelijke groet,</p>
        <p>{{ $settings['company_contact_name'] ?? 'Het team van Yohannes Hoveniersbedrijf B.V.' }}</p>
    </div>
    
    <div class="footer">
        <p>
            YOHANNES HOVENIERSBEDRIJF B.V.<br>
            {{ $settings['company_address'] ?? 'Aristotelesstraat 993, 7323 NZ Apeldoorn' }}<br>
            Tel: {{ $settings['company_phone'] ?? '0616638510' }}<br>
            Email: {{ $settings['company_email'] ?? 'info@yohanneshoveniersbedrijf.com' }}<br>
            KVK: {{ $settings['company_kvk'] ?? '92625703' }}<br>
            BTW: {{ $settings['company_btw'] ?? 'NL866120877B01' }}
        </p>
    </div>
</body>
</html> 