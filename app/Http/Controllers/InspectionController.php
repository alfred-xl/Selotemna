<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInspectionRequest;
use App\Mail\InspectionRequestMail;
use App\Models\InspectionRequest;
use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class InspectionController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function create(Request $request): View
    {
        $editorialMedia = $this->content->editorialMedia();

        return view('inspections.create', [
            'title' => 'Request a Property Inspection | Selotemna',
            'description' => 'Request an Omu Creek inspection and receive follow-up from a Selotemna representative. Submission does not confirm an appointment.',
            'contact' => $this->content->contactDetails(),
            'inspectionMedia' => $editorialMedia['inspection'] ?? null,
            'submissionToken' => $request->old('submission_token', (string) Str::uuid()),
            'receipt' => $request->session()->get('inspection_receipt'),
        ]);
    }

    public function store(StoreInspectionRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $inspection = InspectionRequest::query()->firstOrCreate(
            ['submission_token' => $validated['submission_token']],
            [
                'status' => InspectionRequest::STATUS_NEW,
                'project_slug' => 'omu-creek',
                'project_name' => 'Omu Creek',
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'whatsapp' => $validated['whatsapp'] ?? null,
                'email' => $validated['email'] ?? null,
                'preferred_contact_method' => $validated['contact_method'],
                'preferred_date' => $validated['preferred_date'],
                'preferred_time' => $validated['preferred_time'] ?? null,
                'message' => $validated['message'] ?? null,
                'consented_at' => now(),
            ],
        );

        if ($inspection->wasRecentlyCreated) {
            $this->notifyStaff($inspection);
        }

        return redirect()
            ->route('inspections.create')
            ->with('status', 'Your inspection request has been received and saved. A Selotemna representative will follow up; this does not confirm an appointment.')
            ->with('inspection_receipt', [
                'reference' => $inspection->reference,
                'project' => $inspection->project_name,
                'preferred_date' => $inspection->preferred_date->format('j F Y'),
                'preferred_time' => $inspection->preferred_time ?: 'No preference',
            ]);
    }

    private function notifyStaff(InspectionRequest $inspection): void
    {
        $destination = $this->content->inspectionEmailDestination();

        if ($destination === null) {
            return;
        }

        try {
            Mail::to($destination)->send(new InspectionRequestMail($inspection));
            $inspection->forceFill(['staff_notified_at' => now()])->save();
        } catch (Throwable $exception) {
            report($exception);
            $inspection->forceFill(['notification_failed_at' => now()])->save();
        }
    }
}
