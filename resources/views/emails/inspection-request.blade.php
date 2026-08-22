<h1>New inspection request</h1>

<dl>
    <dt>Full name</dt>
    <dd>{{ $details['full_name'] }}</dd>
    <dt>Telephone number</dt>
    <dd>{{ $details['phone'] }}</dd>
    <dt>Email</dt>
    <dd>{{ $details['email'] ?? 'Not supplied' }}</dd>
    <dt>WhatsApp number</dt>
    <dd>{{ $details['whatsapp'] ?? 'Not supplied' }}</dd>
    <dt>Property or opportunity</dt>
    <dd>{{ $details['interest'] }}</dd>
    <dt>Preferred date</dt>
    <dd>{{ $details['preferred_date'] }}</dd>
    <dt>Preferred time period</dt>
    <dd>{{ $details['preferred_time'] ?? 'Not supplied' }}</dd>
    <dt>Preferred contact method</dt>
    <dd>{{ $details['contact_method'] ?? 'Not supplied' }}</dd>
    <dt>Message</dt>
    <dd>{{ $details['message'] ?? 'Not supplied' }}</dd>
</dl>

<p>This submission is an inspection request. It does not confirm an appointment.</p>
