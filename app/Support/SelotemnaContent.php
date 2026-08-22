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

        return $property;
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
        $groups = config('selotemna.temporary_projects', []);

        return array_map(function (array $group) use ($division): array {
            $items = app()->environment('production') ? [] : array_slice($group['items'] ?? [], 0, 2);

            if ($division !== null) {
                $items = array_values(array_filter($items, fn (array $project): bool => ($project['division'] ?? null) === $division));
            }

            $group['items'] = $items;

            return $group;
        }, $groups);
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
}
