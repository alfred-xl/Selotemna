<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentReceipt;
use App\Support\SelotemnaContent;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentReceiptDocumentController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function preview(Request $request, PaymentReceipt $paymentReceipt): View
    {
        $this->authorizeAdministrator($request);

        return view('admin.payment-receipts.document', $this->viewData($paymentReceipt, pdfMode: false));
    }

    public function download(Request $request, PaymentReceipt $paymentReceipt): Response
    {
        $this->authorizeAdministrator($request);
        abort_if($paymentReceipt->isDraft(), 409, 'Issue this receipt before downloading its official PDF.');

        $filename = strtolower($paymentReceipt->receipt_number).'.pdf';

        return Pdf::loadView('admin.payment-receipts.document', $this->viewData($paymentReceipt, pdfMode: true))
            ->setPaper('a4')
            ->download($filename);
    }

    private function authorizeAdministrator(Request $request): void
    {
        abort_unless($request->user()?->is_admin, 403);
    }

    /** @return array<string, mixed> */
    private function viewData(PaymentReceipt $receipt, bool $pdfMode): array
    {
        $logoPath = public_path('assets/logo.png');

        return [
            'receipt' => $receipt->loadMissing(['creator', 'issuer', 'voidedBy']),
            'contact' => $this->content->contactDetails(),
            'logoDataUri' => is_file($logoPath) ? 'data:image/png;base64,'.base64_encode((string) file_get_contents($logoPath)) : null,
            'pdfMode' => $pdfMode,
        ];
    }
}
