<?php

namespace App\Actions\Projects;

use App\Enums\ProjectDivision;
use App\Enums\ProjectMediaKind;
use App\Enums\ProjectMediaRole;
use App\Enums\ProjectPublicationStatus;
use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

final class ImportOmuCreekProject
{
    public function handle(bool $force = false): ProjectImportResult
    {
        return DB::transaction(function () use ($force): ProjectImportResult {
            $project = Project::withTrashed()->where('slug', 'omu-creek')->first();

            if ($project && ! $force) {
                return new ProjectImportResult($project, false);
            }

            $property = config('selotemna.featured_property', []);
            $projectSummary = collect(config('selotemna.projects', []))
                ->flatMap(fn (array $group): array => $group['items'] ?? [])
                ->firstWhere('slug', 'omu-creek')['summary'] ?? $property['overview'];
            $locations = array_values($property['locations'] ?? []);

            $project ??= new Project;
            $project->fill([
                'name' => $property['name'],
                'slug' => 'omu-creek',
                'division' => ProjectDivision::RealEstateDevelopment,
                'project_type' => $property['type'],
                'status' => ProjectStatus::Upcoming,
                'publication_status' => ProjectPublicationStatus::Published,
                'is_featured' => true,
                'is_publication_verified' => true,
                'summary' => $projectSummary,
                'overview' => $property['overview'],
                'marketing_summary' => $property['marketing'],
                'location_summary' => collect($locations)->join(', ', ' and '),
                'land_title' => $property['title'],
                'title_information' => $property['title_information'],
                'price_per_sqm' => $property['price_per_sqm'],
                'currency' => 'NGN',
                'allocation_details' => $property['allocation'],
                'construction_details' => $property['construction'],
                'disclaimer' => $property['disclaimer'],
                'canonical_path' => '/real-estate-development/omu-creek',
                'seo_title' => 'Omu Creek Upcoming Project | Selotemna',
                'seo_description' => 'Review the Omu Creek Upcoming Project, including its land title, plot sizes, current prices, charges, allocation terms and inspection options.',
                'published_at' => $project->published_at ?? now(),
                'sort_order' => 0,
            ]);

            if ($project->trashed()) {
                $project->restore();
            }

            $project->save();
            $this->clearRelatedContent($project);
            $this->importLocations($project, $locations);
            $this->importMedia($project, $property);
            $this->importPlotOptions($project, $property);
            $this->importPaymentPlan($project, $property);
            $this->importCharges($project, $property);
            $this->importDocuments($project, $property);
            $this->importInfrastructure($project, $property);
            $this->importPolicies($project, $property);
            $this->importFaqs($project);

            return new ProjectImportResult($project->fresh(), true);
        });
    }

    private function clearRelatedContent(Project $project): void
    {
        $project->faqGroups()->delete();
        $project->documentStages()->delete();
        $project->locations()->delete();
        $project->media()->delete();
        $project->plotOptions()->delete();
        $project->paymentPlan()->delete();
        $project->charges()->delete();
        $project->infrastructure()->delete();
        $project->policies()->delete();
    }

    private function importLocations(Project $project, array $locations): void
    {
        foreach ($locations as $sortOrder => $location) {
            $project->locations()->create([
                'name' => $location,
                'region' => 'Lagos State',
                'country' => 'Nigeria',
                'sort_order' => $sortOrder,
            ]);
        }
    }

    private function importMedia(Project $project, array $property): void
    {
        $this->createMedia($project, ProjectMediaKind::Image, ProjectMediaRole::HeroImage, $property['hero_image'] ?? null, 'image/png', $property['hero_image_alt'] ?? null, 0);
        $this->createMedia($project, ProjectMediaKind::Image, ProjectMediaRole::GalleryImage, $property['gallery_image'] ?? null, 'image/png', $property['gallery_image_alt'] ?? null, 1);
        $this->createMedia($project, ProjectMediaKind::Video, ProjectMediaRole::DetailVideo, $property['video_url'] ?? null, 'video/mp4', null, 2);
        $this->createMedia($project, ProjectMediaKind::Image, ProjectMediaRole::DetailVideoPoster, $property['video_poster'] ?? null, null, 'Omu Creek detailed video poster', 3);
        $this->createMedia($project, ProjectMediaKind::Video, ProjectMediaRole::PreviewVideo, $property['short_video_url'] ?? null, 'video/mp4', null, 4);
        $this->createMedia($project, ProjectMediaKind::Image, ProjectMediaRole::PreviewVideoPoster, $property['short_video_poster'] ?? null, null, 'Omu Creek preview video poster', 5);
        $this->createMedia($project, ProjectMediaKind::Document, ProjectMediaRole::Brochure, $property['brochure_url'] ?? null, 'application/pdf', null, 6);
    }

    private function createMedia(
        Project $project,
        ProjectMediaKind $kind,
        ProjectMediaRole $role,
        mixed $source,
        ?string $mimeType,
        ?string $altText,
        int $sortOrder,
    ): void {
        $source = trim((string) $source);

        if ($source === '') {
            return;
        }

        $isExternal = filter_var($source, FILTER_VALIDATE_URL) !== false;

        $project->media()->create([
            'kind' => $kind,
            'role' => $role,
            'disk' => 'public',
            'path' => $isExternal ? null : ltrim(str_replace('\\', '/', $source), '/'),
            'external_url' => $isExternal ? $source : null,
            'mime_type' => $mimeType,
            'alt_text' => $altText,
            'sort_order' => $sortOrder,
        ]);
    }

    private function importPlotOptions(Project $project, array $property): void
    {
        foreach ($property['options'] ?? [] as $sortOrder => $option) {
            $project->plotOptions()->create([
                'label' => $option['label'],
                'size_sqm' => $option['size_sqm'],
                'price' => $option['price'],
                'currency' => 'NGN',
                'sort_order' => $sortOrder,
            ]);
        }
    }

    private function importPaymentPlan(Project $project, array $property): void
    {
        $paymentPlan = $property['payment_plan'] ?? null;

        if (! $paymentPlan) {
            return;
        }

        $project->paymentPlan()->create([
            'initial_deposit' => $paymentPlan['initial_deposit'] ?? null,
            'currency' => 'NGN',
            'balance_period' => $paymentPlan['balance_period'] ?? null,
            'note' => $paymentPlan['note'] ?? null,
        ]);
    }

    private function importCharges(Project $project, array $property): void
    {
        foreach ($property['charges'] ?? [] as $sortOrder => $charge) {
            $project->charges()->create($charge + ['sort_order' => $sortOrder]);
        }
    }

    private function importDocuments(Project $project, array $property): void
    {
        foreach ($property['documents'] ?? [] as $stageOrder => $documentStage) {
            $stage = $project->documentStages()->create([
                'stage' => $documentStage['stage'],
                'sort_order' => $stageOrder,
            ]);

            foreach ($documentStage['items'] ?? [] as $documentOrder => $document) {
                $stage->documents()->create([
                    'name' => $document,
                    'sort_order' => $documentOrder,
                ]);
            }
        }
    }

    private function importInfrastructure(Project $project, array $property): void
    {
        foreach ($property['planned_infrastructure'] ?? [] as $sortOrder => $item) {
            $project->infrastructure()->create([
                'name' => $item,
                'sort_order' => $sortOrder,
            ]);
        }
    }

    private function importPolicies(Project $project, array $property): void
    {
        $headings = [
            'Instalment-default terms',
            'Resale and change of ownership',
            'Refund terms',
        ];

        foreach ($property['policies'] ?? [] as $sortOrder => $policy) {
            $project->policies()->create([
                'heading' => $headings[$sortOrder] ?? 'Project policy',
                'body' => $policy,
                'sort_order' => $sortOrder,
            ]);
        }
    }

    private function importFaqs(Project $project): void
    {
        $featuredFaqs = config('selotemna.homepage_faq_ids', []);
        $faqGroups = config('selotemna.faq_groups', []);

        foreach ($faqGroups as $groupSlug => $faqGroup) {
            $group = $project->faqGroups()->create([
                'slug' => $groupSlug,
                'label' => $faqGroup['label'],
                'sort_order' => array_search($groupSlug, array_keys($faqGroups), true),
            ]);

            foreach ($faqGroup['items'] ?? [] as $faqOrder => $faq) {
                $group->faqs()->create([
                    'key' => $faq['id'],
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                    'points' => $faq['points'] ?? null,
                    'note' => $faq['note'] ?? null,
                    'is_featured' => in_array($faq['id'], $featuredFaqs, true),
                    'sort_order' => $faqOrder,
                ]);
            }
        }
    }
}
