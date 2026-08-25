@props(['contact', 'showDetails' => true])

@php
    $phones = $contact['phones'] ?? [];
    $emails = $contact['emails'] ?? [];

    if ($phones === [] && ($contact['phone_url'] ?? null)) {
        $phones = [['label' => 'Telephone', 'display' => $contact['phone'], 'url' => $contact['phone_url']]];
    }

    if ($emails === [] && ($contact['email_url'] ?? null)) {
        $emails = [['label' => 'Email', 'address' => $contact['email'], 'url' => $contact['email_url']]];
    }

    $hasContactChannel = $phones !== [] || $emails !== [] || ($contact['whatsapp_url'] ?? null);
@endphp

@if ($hasContactChannel || ($showDetails && ($contact['address'] || $contact['business_hours'])))
    <div class="grid gap-4 sm:grid-cols-2" data-contact-channels>
        @foreach ($phones as $phone)
            <a href="{{ $phone['url'] }}" class="contact-channel"><span class="contact-channel-label">{{ $phone['label'] }}</span><span>{{ $phone['display'] }}</span></a>
        @endforeach
        @foreach ($emails as $email)
            <a href="{{ $email['url'] }}" class="contact-channel"><span class="contact-channel-label">{{ $email['label'] }}</span><span class="break-all">{{ $email['address'] }}</span></a>
        @endforeach
        @if ($contact['whatsapp_url'])
            <a href="{{ $contact['whatsapp_url'] }}" class="contact-channel"><span class="contact-channel-label">WhatsApp</span><span>Chat with the team</span></a>
        @endif
        @if ($showDetails && $contact['address'])
            <div class="contact-channel"><span class="contact-channel-label">Office</span><span>{{ $contact['address'] }}</span></div>
        @endif
        @if ($showDetails && $contact['business_hours'])
            <div class="contact-channel"><span class="contact-channel-label">Business hours</span><span>{{ $contact['business_hours'] }}</span></div>
        @endif
    </div>
@endif
