<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInspectionRequest;
use App\Mail\InspectionRequestMail;
use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class InspectionController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function create(): View
    {
        $contact = $this->content->contactDetails();
        $destination = $this->content->inspectionEmailDestination();

        return view('inspections.create', [
            'title' => 'Request a Property Inspection | Selotemna',
            'description' => 'Request an inspection for Omu Creek or another Selotemna opportunity and receive follow-up from a representative.',
            'contact' => $contact,
            'canSubmit' => $destination !== null,
        ]);
    }

    public function store(StoreInspectionRequest $request): RedirectResponse
    {
        $destination = $this->content->inspectionEmailDestination();

        abort_unless($destination, 404);

        Mail::to($destination)->send(new InspectionRequestMail($request->validated()));

        return redirect()
            ->route('inspections.create')
            ->with('status', 'Your inspection request has been received. A Selotemna representative will follow up; this does not confirm an appointment.');
    }
}
