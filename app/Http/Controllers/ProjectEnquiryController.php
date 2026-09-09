<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectEnquiryRequest;
use App\Mail\ProjectEnquiryMail;
use App\Models\Project;
use App\Models\ProjectEnquiry;
use App\Support\SelotemnaContent;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

class ProjectEnquiryController extends Controller
{
    public function __construct(private readonly SelotemnaContent $content) {}

    public function create(Request $request): View
    {
        $property = $this->content->project('omu-creek');
        abort_if($property === [], 404);

        return view('project-enquiries.create', [
            'title' => 'Omu Creek Plot Enquiry | Selotemna',
            'description' => 'Select an Omu Creek plot size and send your purchase enquiry to Selotemna.',
            'contact' => $this->content->contactDetails(),
            'property' => $property,
            'submissionToken' => $request->old('submission_token', (string) Str::uuid()),
            'receipt' => $request->session()->get('project_enquiry_receipt'),
        ]);
    }

    public function store(StoreProjectEnquiryRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $property = $this->content->project('omu-creek');
        abort_if($property === [], 404);

        $option = collect($property['options'] ?? [])->first(
            fn (array $item): bool => (string) (int) $item['size_sqm'] === $validated['plot_option'],
        );

        $project = Project::query()->published()->where('slug', 'omu-creek')->first();
        $enquiry = ProjectEnquiry::query()->firstOrCreate(
            ['submission_token' => $validated['submission_token']],
            [
                'project_id' => $project?->getKey(),
                'status' => ProjectEnquiry::STATUS_NEW,
                'project_slug' => 'omu-creek',
                'project_name' => $property['name'],
                'plot_size_sqm' => $option ? (int) $option['size_sqm'] : null,
                'plot_label' => $option ? number_format($option['size_sqm']).' sqm — '.$option['label'] : 'Not sure yet',
                'price_snapshot' => $option['price'] ?? null,
                'currency' => $option['currency'] ?? $property['currency'] ?? 'NGN',
                'payment_preference' => $validated['payment_preference'],
                'purchase_timeline' => $validated['purchase_timeline'],
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'whatsapp' => $validated['whatsapp'] ?? null,
                'preferred_contact_method' => $validated['contact_method'],
                'message' => $validated['message'] ?? null,
                'consented_at' => now(),
            ],
        );

        if ($enquiry->wasRecentlyCreated) {
            $this->notifyStaff($enquiry);
        }

        $receipt = [
            'reference' => $enquiry->reference,
            'project' => $enquiry->project_name,
            'plot' => $enquiry->plot_label,
            'timeline' => $enquiry->purchase_timeline,
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Thanks! We received your Omu Creek enquiry. Our team will contact you within 24 hours to confirm availability and discuss the next step.',
                'receipt' => $receipt,
            ], 201);
        }

        return redirect()
            ->route('project-enquiries.create')
            ->with('status', 'Thanks! We received your Omu Creek enquiry. Our team will contact you within 24 hours.')
            ->with('project_enquiry_receipt', $receipt);
    }

    private function notifyStaff(ProjectEnquiry $enquiry): void
    {
        $destination = $this->content->staffEmailDestination();

        if ($destination === null) {
            return;
        }

        try {
            Mail::to($destination)->send(new ProjectEnquiryMail($enquiry));
            $enquiry->forceFill(['staff_notified_at' => now()])->save();
        } catch (Throwable $exception) {
            report($exception);
            $enquiry->forceFill(['notification_failed_at' => now()])->save();
        }
    }
}
