<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactEnquiryRequest;
use App\Mail\ContactEnquiryMail;
use App\Models\ContactEnquiry;
use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class ContactController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function create(Request $request): View
    {
        $editorialMedia = $this->content->editorialMedia();

        return view('contact', [
            'title' => 'Contact Selotemna | Property and Project Enquiries',
            'description' => 'Contact Selotemna about Omu Creek, Real Estate Development, Engineering & Construction or an inspection request.',
            'contact' => $this->content->contactDetails(),
            'contactMedia' => $editorialMedia['contact'] ?? null,
            'submissionToken' => $request->old('submission_token', (string) Str::uuid()),
            'receipt' => $request->session()->get('contact_enquiry_receipt'),
        ]);
    }

    public function store(StoreContactEnquiryRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $enquiry = ContactEnquiry::query()->firstOrCreate(
            ['submission_token' => $validated['submission_token']],
            [
                'status' => ContactEnquiry::STATUS_NEW,
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'whatsapp' => $validated['whatsapp'] ?? null,
                'preferred_contact_method' => $validated['contact_method'],
                'interest_type' => $validated['interest_type'],
                'enquiry_type' => $validated['enquiry_type'],
                'project_type' => $validated['project_type'] ?? null,
                'proposed_location' => $validated['proposed_location'] ?? null,
                'project_stage' => $validated['project_stage'] ?? null,
                'scope_summary' => $validated['scope_summary'] ?? null,
                'message' => $validated['message'],
                'consented_at' => now(),
            ],
        );

        if ($enquiry->wasRecentlyCreated) {
            $this->notifyStaff($enquiry);
        }

        $receipt = [
            'reference' => $enquiry->reference,
            'interest_type' => $enquiry->interest_type,
            'enquiry_type' => $enquiry->enquiry_type,
            'contact_method' => $enquiry->preferred_contact_method,
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Thanks! We got your message. We will reply within 24 hours.',
                'receipt' => $receipt,
            ], 201);
        }

        return redirect()
            ->route('contact')
            ->with('status', 'Thanks! We got your message. We will reply within 24 hours.')
            ->with('contact_enquiry_receipt', $receipt);
    }

    private function notifyStaff(ContactEnquiry $enquiry): void
    {
        $destination = $this->content->staffEmailDestination();

        if ($destination === null) {
            return;
        }

        try {
            Mail::to($destination)->send(new ContactEnquiryMail($enquiry));
            $enquiry->forceFill(['staff_notified_at' => now()])->save();
        } catch (Throwable $exception) {
            report($exception);
            $enquiry->forceFill(['notification_failed_at' => now()])->save();
        }
    }
}
