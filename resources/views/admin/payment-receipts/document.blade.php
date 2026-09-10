<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $receipt->receipt_number ?: 'Draft receipt' }} | Selotemna</title>
    <style>
        @page { margin: 28px; }
        * { box-sizing: border-box; }
        body { margin: 0; background: #f4f3f8; color: #171329; font-family: DejaVu Sans, Arial, sans-serif; font-size: 13px; line-height: 1.55; }
        .toolbar { max-width: 820px; margin: 24px auto 0; padding: 0 16px; display: flex; gap: 10px; justify-content: flex-end; }
        .toolbar a, .toolbar button { border: 1px solid #28166b; border-radius: 8px; background: #fff; color: #28166b; cursor: pointer; padding: 10px 16px; font: inherit; font-weight: 700; text-decoration: none; }
        .toolbar .primary { background: #28166b; color: #fff; }
        .receipt { position: relative; max-width: 820px; min-height: 1040px; margin: 18px auto 30px; overflow: hidden; background: #fff; border-top: 8px solid #28166b; padding: 42px 46px; }
        .watermark { position: absolute; top: 44%; left: 8%; right: 8%; color: rgba(166, 31, 31, .09); font-size: 92px; font-weight: 800; letter-spacing: 12px; text-align: center; transform: rotate(-24deg); }
        .header { width: 100%; border-collapse: collapse; }
        .header td { vertical-align: top; }
        .logo { width: 150px; max-height: 72px; object-fit: contain; object-position: left center; }
        .company { color: #5d5870; font-size: 11px; text-align: right; }
        .title-row { margin-top: 40px; border-bottom: 2px solid #28166b; padding-bottom: 18px; }
        .title-row table { width: 100%; border-collapse: collapse; }
        h1 { margin: 0; color: #28166b; font-size: 28px; letter-spacing: 1px; }
        .status { display: inline-block; border-radius: 999px; padding: 5px 10px; background: #ece9f6; color: #28166b; font-size: 10px; font-weight: 800; letter-spacing: 1px; text-transform: uppercase; }
        .status.voided { background: #fbe9e9; color: #a61f1f; }
        .meta { margin-top: 25px; width: 100%; border-collapse: collapse; }
        .meta td { width: 50%; padding: 9px 14px 9px 0; vertical-align: top; }
        .label { display: block; color: #706b7d; font-size: 10px; font-weight: 700; letter-spacing: .7px; text-transform: uppercase; }
        .value { display: block; margin-top: 3px; font-weight: 700; }
        .amount { margin-top: 28px; border: 1px solid #dedbea; background: #f8f7fc; padding: 22px; }
        .amount strong { display: block; margin-top: 3px; color: #28166b; font-size: 30px; }
        .amount-words { margin-top: 6px; color: #4f4961; }
        .details { margin-top: 28px; width: 100%; border-collapse: collapse; }
        .details th, .details td { border-bottom: 1px solid #e8e6ee; padding: 11px 8px; text-align: left; vertical-align: top; }
        .details th { width: 34%; color: #6d6878; font-weight: 600; }
        .notice { margin-top: 28px; border-left: 4px solid #28166b; background: #f8f7fc; padding: 13px 16px; }
        .notice.danger { border-color: #a61f1f; background: #fff3f3; color: #7e1818; }
        .signature { margin-top: 58px; width: 100%; border-collapse: collapse; }
        .signature td { width: 50%; vertical-align: bottom; }
        .signature-line { width: 210px; border-top: 1px solid #4a4556; padding-top: 8px; }
        .footer { margin-top: 54px; border-top: 1px solid #dedbea; padding-top: 14px; color: #777181; font-size: 10px; text-align: center; }
        @media print { body { background: #fff; } .toolbar { display: none; } .receipt { max-width: none; margin: 0; min-height: auto; box-shadow: none; } }
        @media screen { .receipt { box-shadow: 0 18px 50px rgba(23, 19, 41, .12); } }
    </style>
</head>
<body>
    @unless ($pdfMode)
        <div class="toolbar">
            <a href="{{ \App\Filament\Resources\PaymentReceipts\PaymentReceiptResource::getUrl('view', ['record' => $receipt]) }}">Back to admin</a>
            <button type="button" onclick="window.print()">Print</button>
            @unless ($receipt->isDraft())
                <a class="primary" href="{{ route('admin.e-receipts.download', $receipt) }}">Download PDF</a>
            @endunless
        </div>
    @endunless

    <main class="receipt">
        @if ($receipt->isDraft())
            <div class="watermark">DRAFT</div>
        @elseif ($receipt->isVoided())
            <div class="watermark">VOID</div>
        @endif

        <table class="header">
            <tr>
                <td>
                    @if ($logoDataUri)
                        <img class="logo" src="{{ $logoDataUri }}" alt="Selotemna Limited">
                    @else
                        <strong>SELOTEMNA LIMITED</strong>
                    @endif
                </td>
                <td class="company">
                    <strong>Selotemna Limited</strong><br>
                    @if ($contact['phone']){{ $contact['phone'] }}<br>@endif
                    @if ($contact['email']){{ $contact['email'] }}<br>@endif
                    @if ($contact['address']){{ $contact['address'] }}@endif
                </td>
            </tr>
        </table>

        <div class="title-row">
            <table>
                <tr>
                    <td><h1>PAYMENT RECEIPT</h1></td>
                    <td style="text-align: right"><span class="status {{ $receipt->isVoided() ? 'voided' : '' }}">{{ $receipt->status }}</span></td>
                </tr>
            </table>
        </div>

        <table class="meta">
            <tr>
                <td><span class="label">Receipt number</span><span class="value">{{ $receipt->receipt_number ?: 'Assigned when issued' }}</span></td>
                <td><span class="label">Payment date</span><span class="value">{{ $receipt->payment_date->format('j F Y') }}</span></td>
            </tr>
            <tr>
                <td><span class="label">Received from</span><span class="value">{{ $receipt->customer_name }}</span></td>
                <td><span class="label">Telephone</span><span class="value">{{ $receipt->customer_phone }}</span></td>
            </tr>
        </table>

        <section class="amount">
            <span class="label">Confirmed amount received</span>
            <strong>{{ $receipt->currency === 'NGN' ? '₦' : $receipt->currency.' ' }}{{ number_format($receipt->amount_received) }}</strong>
            <div class="amount-words">{{ $receipt->amount_in_words }}</div>
        </section>

        <table class="details">
            <tr><th>Payment purpose</th><td>{{ $receipt->payment_purpose }}</td></tr>
            <tr><th>Project</th><td>{{ $receipt->project_name }}</td></tr>
            @if ($receipt->plot_label)<tr><th>Plot details</th><td>{{ $receipt->plot_label }}</td></tr>@endif
            @if ($receipt->plot_size_sqm)<tr><th>Plot size</th><td>{{ number_format($receipt->plot_size_sqm) }} sqm</td></tr>@endif
            <tr><th>Payment method</th><td>{{ $receipt->payment_method }}</td></tr>
            <tr><th>Transaction reference</th><td>{{ $receipt->transaction_reference ?: 'Not supplied' }}</td></tr>
            <tr><th>Balance remaining</th><td>{{ $receipt->balance_remaining === null ? 'Not supplied' : (($receipt->currency === 'NGN' ? '₦' : $receipt->currency.' ').number_format($receipt->balance_remaining)) }}</td></tr>
            @if ($receipt->notes)<tr><th>Receipt note</th><td>{{ $receipt->notes }}</td></tr>@endif
        </table>

        @if ($receipt->isDraft())
            <div class="notice danger"><strong>Draft preview:</strong> This document is not proof of payment until it has been issued and assigned an official receipt number.</div>
        @elseif ($receipt->isVoided())
            <div class="notice danger"><strong>This receipt has been voided.</strong><br>{{ $receipt->void_reason }}</div>
        @else
            <div class="notice">This receipt confirms that Selotemna Limited recorded the payment described above as received.</div>
        @endif

        <table class="signature">
            <tr>
                <td><div class="signature-line"><strong>{{ $receipt->issuer_name ?: 'Pending issue' }}</strong><br><span class="label">Authorised issuer</span></div></td>
                <td style="text-align: right">@if ($receipt->issued_at)<span class="label">Issued</span><strong>{{ $receipt->issued_at->format('j F Y, g:i a') }}</strong>@endif</td>
            </tr>
        </table>

        <footer class="footer">Generated securely by Selotemna Limited. Keep the receipt number for payment enquiries.</footer>
    </main>
</body>
</html>
