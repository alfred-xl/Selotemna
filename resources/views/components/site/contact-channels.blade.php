@props(['contact', 'showDetails' => true])

@php($hasContactChannel = $contact['phone_url'] || $contact['whatsapp_url'] || $contact['email_url'])

@if ($hasContactChannel || ($showDetails && ($contact['address'] || $contact['business_hours'])))
    <div class="grid gap-4 sm:grid-cols-2" data-contact-channels>
        @if ($contact['phone_url'])
            <a href="{{ $contact['phone_url'] }}" class="contact-channel"><span class="contact-channel-label">Telephone</span><span>{{ $contact['phone'] }}</span></a>
        @endif
        @if ($contact['whatsapp_url'])
            <a href="{{ $contact['whatsapp_url'] }}" class="contact-channel"><span class="contact-channel-label">WhatsApp</span><span>Chat with the team</span></a>
        @endif
        @if ($contact['email_url'])
            <a href="{{ $contact['email_url'] }}" class="contact-channel"><span class="contact-channel-label">Email</span><span class="break-all">{{ $contact['email'] }}</span></a>
        @endif
        @if ($showDetails && $contact['address'])
            <div class="contact-channel"><span class="contact-channel-label">Office</span><span>{{ $contact['address'] }}</span></div>
        @endif
        @if ($showDetails && $contact['business_hours'])
            <div class="contact-channel"><span class="contact-channel-label">Business hours</span><span>{{ $contact['business_hours'] }}</span></div>
        @endif
    </div>
@endif
