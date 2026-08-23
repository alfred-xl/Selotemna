<h1>New inspection request {{ $inspection->reference }}</h1>

<dl>
    <dt>Request reference</dt>
    <dd>{{ $inspection->reference }}</dd>
    <dt>Status</dt>
    <dd>{{ ucfirst($inspection->status) }}</dd>
    <dt>Full name</dt>
    <dd>{{ $inspection->full_name }}</dd>
    <dt>Telephone number</dt>
    <dd>{{ $inspection->phone }}</dd>
    <dt>Email</dt>
    <dd>{{ $inspection->email ?: 'Not supplied' }}</dd>
    <dt>WhatsApp number</dt>
    <dd>{{ $inspection->whatsapp ?: 'Not supplied' }}</dd>
    <dt>Property or opportunity</dt>
    <dd>{{ $inspection->project_name }}</dd>
    <dt>Preferred date</dt>
    <dd>{{ $inspection->preferred_date->format('j F Y') }}</dd>
    <dt>Preferred time period</dt>
    <dd>{{ $inspection->preferred_time ?: 'No preference' }}</dd>
    <dt>Preferred contact method</dt>
    <dd>{{ $inspection->preferred_contact_method }}</dd>
    <dt>Message</dt>
    <dd>{{ $inspection->message ?: 'Not supplied' }}</dd>
</dl>

<p>This request is saved in the Selotemna website database. It does not confirm an appointment.</p>
