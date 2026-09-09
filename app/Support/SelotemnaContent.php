<?php

namespace App\Support;

use App\Enums\ProjectMediaRole;
use App\Models\Project;
use App\Models\ProjectMedia;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

final class SelotemnaContent
{
    private ?bool $managedProjectsAreAuthoritative = null;

    /** @var Collection<int, Project>|null */
    private ?Collection $managedPublishedProjects = null;

    /** @return array<string, mixed> */
    public function contactDetails(): array
    {
        $phones = collect([
            config('selotemna.phone'),
            ...array_values((array) config('selotemna.phones', [])),
        ])
            ->map(fn (mixed $phone): ?array => $this->phoneContact($phone))
            ->filter()
            ->unique('url')
            ->values()
            ->map(fn (array $phone, int $index): array => $phone + [
                'label' => $index === 0 ? 'Primary telephone' : 'Secondary telephone',
            ])
            ->all();

        $emails = collect([
            config('selotemna.email'),
            ...array_values((array) config('selotemna.emails', [])),
        ])
            ->map(function (mixed $email): ?array {
                $email = trim((string) $email);

                if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return null;
                }

                return ['address' => $email, 'url' => 'mailto:'.$email];
            })
            ->filter()
            ->unique(fn (array $email): string => strtolower($email['address']))
            ->values()
            ->map(fn (array $email, int $index): array => $email + [
                'label' => $index === 0 ? 'Primary email' : 'Secondary email',
            ])
            ->all();

        $whatsApp = trim((string) config('selotemna.whatsapp'));
        $whatsAppDigits = preg_replace('/\D/', '', $whatsApp) ?: null;

        return [
            'phones' => $phones,
            'emails' => $emails,
            'phone' => $phones[0]['display'] ?? null,
            'phone_url' => $phones[0]['url'] ?? null,
            'whatsapp' => $whatsApp ?: null,
            'whatsapp_url' => $whatsAppDigits ? 'https://wa.me/'.$whatsAppDigits : null,
            'email' => $emails[0]['address'] ?? null,
            'email_url' => $emails[0]['url'] ?? null,
            'address' => $this->optionalString(config('selotemna.address')),
            'business_hours' => $this->optionalString(config('selotemna.business_hours')),
        ];
    }

    /** @return array<string, mixed> */
    public function featuredProperty(): array
    {
        if ($this->usesManagedProjects()) {
            $project = $this->publishedProjects()->firstWhere('is_featured', true);

            return $project instanceof Project ? $this->featuredPropertyArray($project) : [];
        }

        $property = config('selotemna.featured_property', []);
        $property['video_url'] = $this->optionalString($property['video_url'] ?? null);
        $property['video_poster'] = $this->optionalString($property['video_poster'] ?? null);
        $property['short_video_url'] = $this->optionalString($property['short_video_url'] ?? null);
        $property['short_video_poster'] = $this->optionalString($property['short_video_poster'] ?? null);
        $property['hero_image'] = $this->publicAsset($property['hero_image'] ?? null);
        $property['gallery_image'] = $this->publicAsset($property['gallery_image'] ?? null);
        $property['brochure_url'] = $this->pdfDocumentUrl($property['brochure_url'] ?? null);

        return $property;
    }

    /** @return array<string, mixed> */
    public function project(string $slug): array
    {
        if ($this->usesManagedProjects()) {
            $project = $this->publishedProjects()->firstWhere('slug', $slug);

            return $project instanceof Project ? $this->featuredPropertyArray($project) : [];
        }

        return $slug === 'omu-creek' ? $this->featuredProperty() : [];
    }

    /** @return array<string, mixed> */
    public function projectPreview(Project $project): array
    {
        $project->loadMissing($this->projectRelations());

        return $this->featuredPropertyArray($project);
    }

    /** @return array<string, string|null> */
    public function siteMedia(): array
    {
        return collect(config('selotemna.media', []))
            ->mapWithKeys(fn (mixed $path, string $key): array => [$key => $this->publicAsset($path)])
            ->all();
    }

    public function featuredPropertyHeroImage(): ?string
    {
        $property = $this->featuredProperty();

        return $property['hero_image']
            ?? $property['video_poster']
            ?? $property['short_video_poster']
            ?? $this->siteMedia()['development_aerial']
            ?? null;
    }

    /** @return array<string, array{url: string, sourceUrl: string|null, credit: string|null, alt: string}> */
    public function editorialMedia(): array
    {
        return collect(config('selotemna.editorial_media', []))
            ->map(function (mixed $media): ?array {
                if (! is_array($media)) {
                    return null;
                }

                $url = $this->publicAsset($media['path'] ?? null) ?? $this->optionalString($media['url'] ?? null);
                $sourceUrl = $this->optionalString($media['source_url'] ?? null);
                $credit = $this->optionalString($media['credit'] ?? null);
                $alt = $this->optionalString($media['alt'] ?? null);

                if (! $url || ! $alt) {
                    return null;
                }

                $hasAttribution = $sourceUrl !== null || $credit !== null;

                if ($hasAttribution && (! $sourceUrl || ! $credit || ! str_starts_with($sourceUrl, 'https://'))) {
                    return null;
                }

                return compact('url', 'sourceUrl', 'credit', 'alt');
            })
            ->filter()
            ->all();
    }

    public function inspectionEmailDestination(): ?string
    {
        return $this->staffEmailDestination();
    }

    public function staffEmailDestination(): ?string
    {
        $email = $this->contactDetails()['email'];
        $mailer = trim((string) config('mail.default'));

        if (! $email || $mailer === '' || in_array($mailer, ['array', 'log'], true)) {
            return null;
        }

        return $email;
    }

    /** @return array<string, array<string, mixed>> */
    public function projectGroups(?string $division = null, bool $includeEmptyGroups = false): array
    {
        if ($this->usesManagedProjects()) {
            return $this->managedProjectGroups($division, $includeEmptyGroups);
        }

        $groups = config('selotemna.projects', []);
        $featuredProperty = $this->featuredProperty();

        $groups = array_map(function (array $group) use ($division, $featuredProperty): array {
            $items = array_values($group['items'] ?? []);

            if ($division !== null) {
                $items = array_values(array_filter($items, fn (array $project): bool => ($project['division'] ?? null) === $division));
            }

            $items = array_map(function (array $project) use ($featuredProperty): array {
                $routeName = $this->optionalString($project['route'] ?? null);
                $project['href'] = $routeName ? route($routeName) : null;
                $project['media_url'] = ($project['slug'] ?? null) === 'omu-creek'
                    ? ($featuredProperty['hero_image'] ?? $featuredProperty['short_video_poster'])
                    : $this->publicAsset($project['media_path'] ?? null);

                return $project;
            }, $items);

            $group['items'] = $items;

            return $group;
        }, $groups);

        if (! $includeEmptyGroups) {
            return array_filter($groups, fn (array $group): bool => $group['items'] !== []);
        }

        $stageLabels = [
            'upcoming' => 'Upcoming',
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
        ];

        return collect($stageLabels)
            ->mapWithKeys(fn (string $label, string $stage): array => [
                $stage => $groups[$stage] ?? ['label' => $label, 'items' => []],
            ])
            ->all();
    }

    /** @return array<int, array<string, mixed>> */
    public function testimonials(?string $division = null, ?string $project = null): array
    {
        return collect(config('selotemna.testimonials', []))
            ->filter(function (array $testimonial) use ($division, $project): bool {
                if (! ($testimonial['approved'] ?? false) || ! ($testimonial['permission_confirmed'] ?? false)) {
                    return false;
                }

                if ($this->optionalString($testimonial['quote'] ?? null) === null || $this->optionalString($testimonial['name'] ?? null) === null) {
                    return false;
                }

                if ($division !== null && ($testimonial['division'] ?? null) !== $division) {
                    return false;
                }

                return $project === null || ($testimonial['project'] ?? null) === $project;
            })
            ->values()
            ->all();
    }

    /** @return array<string, array<string, mixed>> */
    public function faqGroups(string $projectSlug = 'omu-creek'): array
    {
        if ($this->usesManagedProjects()) {
            $project = $this->publishedProjects()->firstWhere('slug', $projectSlug);

            if (! $project instanceof Project) {
                return [];
            }

            return $project->faqGroups
                ->mapWithKeys(fn ($group): array => [
                    $group->slug => [
                        'label' => $group->label,
                        'items' => $group->faqs->map(fn ($faq): array => [
                            'id' => $faq->key,
                            'question' => $faq->question,
                            'answer' => $faq->answer,
                            'points' => $faq->points ?? [],
                            'note' => $faq->note,
                        ])->all(),
                    ],
                ])
                ->all();
        }

        return config('selotemna.faq_groups', []);
    }

    /** @return array<int, array<string, mixed>> */
    public function homepageFaqs(): array
    {
        if ($this->usesManagedProjects()) {
            $project = $this->publishedProjects()->firstWhere('slug', 'omu-creek');

            if (! $project instanceof Project) {
                return [];
            }

            return $project->faqGroups
                ->flatMap->faqs
                ->where('is_featured', true)
                ->map(fn ($faq): array => [
                    'id' => $faq->key,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'points' => $faq->points ?? [],
                    'note' => $faq->note,
                ])
                ->values()
                ->all();
        }

        return $this->faqsByIds(config('selotemna.homepage_faq_ids', []));
    }

    /**
     * @param  array<int, string>  $ids
     * @return array<int, array<string, mixed>>
     */
    public function faqsByIds(array $ids): array
    {
        $items = collect($this->faqGroups())->flatMap(fn (array $group): array => $group['items'] ?? [])->keyBy('id');

        return collect($ids)->map(fn (string $id): ?array => $items->get($id))->filter()->values()->all();
    }

    private function optionalString(mixed $value): ?string
    {
        $value = trim((string) $value);

        return $value !== '' ? $value : null;
    }

    /** @return array{display: string, url: string}|null */
    private function phoneContact(mixed $value): ?array
    {
        $phone = $this->optionalString($value);

        if ($phone === null) {
            return null;
        }

        $digits = preg_replace('/\D/', '', $phone) ?: '';

        if (strlen($digits) < 7 || strlen($digits) > 15) {
            return null;
        }

        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            return [
                'display' => substr($digits, 0, 4).' '.substr($digits, 4, 3).' '.substr($digits, 7, 4),
                'url' => 'tel:+234'.substr($digits, 1),
            ];
        }

        if (strlen($digits) === 13 && str_starts_with($digits, '234')) {
            $local = '0'.substr($digits, 3);

            return [
                'display' => substr($local, 0, 4).' '.substr($local, 4, 3).' '.substr($local, 7, 4),
                'url' => 'tel:+'.$digits,
            ];
        }

        return [
            'display' => $phone,
            'url' => 'tel:'.(str_starts_with($phone, '+') ? '+' : '').$digits,
        ];
    }

    private function publicAsset(mixed $path): ?string
    {
        $path = $this->optionalString($path);

        if ($path === null) {
            return null;
        }

        $relativePath = ltrim(str_replace('\\', '/', $path), '/');

        return is_file(public_path($relativePath)) ? asset($relativePath) : null;
    }

    private function pdfDocumentUrl(mixed $value): ?string
    {
        $value = $this->optionalString($value);

        if ($value === null) {
            return null;
        }

        $path = parse_url($value, PHP_URL_PATH);

        if (! is_string($path) || strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'pdf') {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return parse_url($value, PHP_URL_SCHEME) === 'https' ? $value : null;
        }

        return $this->publicAsset($value);
    }

    private function usesManagedProjects(): bool
    {
        return $this->managedProjectsAreAuthoritative ??= Schema::hasTable('projects')
            && Project::withTrashed()->exists();
    }

    /** @return Collection<int, Project> */
    private function publishedProjects(): Collection
    {
        return $this->managedPublishedProjects ??= Project::query()
            ->published()
            ->ordered()
            ->with($this->projectRelations())
            ->get();
    }

    /** @return array<string, array{label: string, items: array<int, array<string, mixed>>}> */
    private function managedProjectGroups(?string $division, bool $includeEmptyGroups): array
    {
        $stageLabels = [
            'upcoming' => 'Upcoming',
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
        ];

        $projects = $this->publishedProjects()
            ->when($division !== null, fn (Collection $items): Collection => $items->filter(
                fn (Project $project): bool => $project->division->value === $division,
            ));

        $groups = collect($stageLabels)->mapWithKeys(fn (string $label, string $stage): array => [
            $stage => [
                'label' => $label,
                'items' => $projects
                    ->filter(fn (Project $project): bool => $project->status->value === $stage)
                    ->map(fn (Project $project): array => $this->projectCardArray($project))
                    ->values()
                    ->all(),
            ],
        ]);

        if (! $includeEmptyGroups) {
            $groups = $groups->filter(fn (array $group): bool => $group['items'] !== []);
        }

        return $groups->all();
    }

    /** @return array<string, mixed> */
    private function projectCardArray(Project $project): array
    {
        $media = $this->preferredProjectImage($project);

        return [
            'slug' => $project->slug,
            'name' => $project->name,
            'status' => $project->status->label(),
            'division' => $project->division->value,
            'summary' => $project->summary,
            'href' => $project->slug === 'omu-creek' ? route('omu-creek') : route('projects.show', ['slug' => $project->slug]),
            'media_url' => $media?->source_url,
            'media_alt' => $media?->alt_text,
        ];
    }

    /** @return array<string, mixed> */
    private function featuredPropertyArray(Project $project): array
    {
        $media = $project->media->keyBy(fn (ProjectMedia $item): string => $item->role->value);

        return [
            'name' => $project->name,
            'slug' => $project->slug,
            'division' => $project->division->value,
            'summary' => $project->summary,
            'status' => $project->status->label(),
            'type' => $project->project_type,
            'title' => $project->land_title,
            'overview' => $project->overview ?: $project->summary,
            'marketing' => $project->marketing_summary,
            'locations' => $project->locations->pluck('name')->all(),
            'title_information' => $project->title_information,
            'price_per_sqm' => $project->price_per_sqm,
            'currency' => $project->currency,
            'options' => $project->plotOptions->map(fn ($option): array => [
                'label' => $option->label,
                'size_sqm' => (float) $option->size_sqm,
                'price' => $option->price,
                'currency' => $option->currency,
            ])->all(),
            'payment_plan' => $project->paymentPlan ? [
                'initial_deposit' => $project->paymentPlan->initial_deposit,
                'balance_period' => $project->paymentPlan->balance_period,
                'note' => $project->paymentPlan->note,
                'currency' => $project->paymentPlan->currency,
            ] : null,
            'charges' => $project->charges->map(fn ($charge): array => [
                'label' => $charge->label,
                'value' => $charge->value,
            ])->all(),
            'documents' => $project->documentStages->map(fn ($stage): array => [
                'stage' => $stage->stage,
                'items' => $stage->documents->pluck('name')->all(),
            ])->all(),
            'planned_infrastructure' => $project->infrastructure->pluck('name')->all(),
            'allocation' => $project->allocation_details,
            'construction' => $project->construction_details,
            'policies' => $project->policies->pluck('body')->all(),
            'policy_items' => $project->policies->map(fn ($policy): array => [
                'heading' => $policy->heading,
                'body' => $policy->body,
            ])->all(),
            'disclaimer' => $project->disclaimer,
            'canonical_path' => $project->canonical_path,
            'seo_title' => $project->seo_title,
            'seo_description' => $project->seo_description,
            'video_url' => $media->get(ProjectMediaRole::DetailVideo->value)?->source_url,
            'video_poster' => $media->get(ProjectMediaRole::DetailVideoPoster->value)?->source_url,
            'short_video_url' => $media->get(ProjectMediaRole::PreviewVideo->value)?->source_url,
            'short_video_poster' => $media->get(ProjectMediaRole::PreviewVideoPoster->value)?->source_url,
            'brochure_url' => $media->get(ProjectMediaRole::Brochure->value)?->source_url,
            'hero_image' => $this->preferredProjectImage($project)?->source_url,
            'hero_image_alt' => $this->preferredProjectImage($project)?->alt_text,
            'gallery_image' => $media->get(ProjectMediaRole::GalleryImage->value)?->source_url,
            'gallery_image_alt' => $media->get(ProjectMediaRole::GalleryImage->value)?->alt_text,
        ];
    }

    /** @return array<int, string> */
    private function projectRelations(): array
    {
        return [
            'locations',
            'media',
            'plotOptions',
            'paymentPlan',
            'charges',
            'documentStages.documents',
            'infrastructure',
            'policies',
            'faqGroups.faqs',
        ];
    }

    private function preferredProjectImage(Project $project): ?ProjectMedia
    {
        foreach ([
            ProjectMediaRole::HeroImage,
            ProjectMediaRole::GalleryImage,
            ProjectMediaRole::PreviewVideoPoster,
            ProjectMediaRole::DetailVideoPoster,
        ] as $role) {
            $media = $project->media->first(fn (ProjectMedia $item): bool => $item->role === $role);

            if ($media) {
                return $media;
            }
        }

        return null;
    }
}
