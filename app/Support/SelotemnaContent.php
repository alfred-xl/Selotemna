<?php

namespace App\Support;

final class SelotemnaContent
{
    /** @return array<string, string|null> */
    public function contactDetails(): array
    {
        $phone = trim((string) config('selotemna.phone'));
        $whatsApp = trim((string) config('selotemna.whatsapp'));
        $email = trim((string) config('selotemna.email'));
        $phoneDigits = preg_replace('/[^0-9+]/', '', $phone) ?: null;
        $whatsAppDigits = preg_replace('/\D/', '', $whatsApp) ?: null;
        $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;

        return [
            'phone' => $phone ?: null,
            'phone_url' => $phoneDigits ? 'tel:'.$phoneDigits : null,
            'whatsapp' => $whatsApp ?: null,
            'whatsapp_url' => $whatsAppDigits ? 'https://wa.me/'.$whatsAppDigits : null,
            'email' => $validEmail,
            'email_url' => $validEmail ? 'mailto:'.$validEmail : null,
            'address' => $this->optionalString(config('selotemna.address')),
            'business_hours' => $this->optionalString(config('selotemna.business_hours')),
        ];
    }

    /** @return array<string, mixed> */
    public function featuredProperty(): array
    {
        $property = config('selotemna.featured_property', []);
        $property['video_url'] = $this->optionalString($property['video_url'] ?? null);
        $property['video_poster'] = $this->optionalString($property['video_poster'] ?? null);
        $property['short_video_url'] = $this->optionalString($property['short_video_url'] ?? null);
        $property['short_video_poster'] = $this->optionalString($property['short_video_poster'] ?? null);

        return $property;
    }

    /** @return array<string, string|null> */
    public function siteMedia(): array
    {
        return collect(config('selotemna.media', []))
            ->mapWithKeys(fn (mixed $path, string $key): array => [$key => $this->publicAsset($path)])
            ->all();
    }

    /** @return array<string, array<string, string>> */
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

                if (! $url || ! $sourceUrl || ! $credit || ! $alt || ! str_starts_with($sourceUrl, 'https://')) {
                    return null;
                }

                return compact('url', 'sourceUrl', 'credit', 'alt');
            })
            ->filter()
            ->all();
    }

    public function inspectionEmailDestination(): ?string
    {
        $email = $this->contactDetails()['email'];
        $mailer = trim((string) config('mail.default'));

        if (! $email || $mailer === '' || in_array($mailer, ['array', 'log'], true)) {
            return null;
        }

        return $email;
    }

    /** @return array<string, array<string, mixed>> */
    public function projectGroups(?string $division = null): array
    {
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
                    ? $featuredProperty['short_video_poster']
                    : $this->publicAsset($project['media_path'] ?? null);

                return $project;
            }, $items);

            $group['items'] = $items;

            return $group;
        }, $groups);

        return array_filter($groups, fn (array $group): bool => $group['items'] !== []);
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
    public function faqGroups(): array
    {
        return config('selotemna.faq_groups', []);
    }

    /** @return array<int, array<string, mixed>> */
    public function homepageFaqs(): array
    {
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

    private function publicAsset(mixed $path): ?string
    {
        $path = $this->optionalString($path);

        if ($path === null) {
            return null;
        }

        $relativePath = ltrim(str_replace('\\', '/', $path), '/');

        return is_file(public_path($relativePath)) ? asset($relativePath) : null;
    }
}
