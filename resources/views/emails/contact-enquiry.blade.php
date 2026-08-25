<h1>New contact enquiry {{ $enquiry->reference }}</h1>

<dl>
    <dt>Enquiry reference</dt>
    <dd>{{ $enquiry->reference }}</dd>
    <dt>Status</dt>
    <dd>{{ ucfirst($enquiry->status) }}</dd>
    <dt>Full name</dt>
    <dd>{{ $enquiry->full_name }}</dd>
    <dt>Telephone number</dt>
    <dd>{{ $enquiry->phone }}</dd>
    <dt>Email</dt>
    <dd>{{ $enquiry->email ?: 'Not supplied' }}</dd>
    <dt>WhatsApp number</dt>
    <dd>{{ $enquiry->whatsapp ?: 'Not supplied' }}</dd>
    <dt>Preferred contact method</dt>
    <dd>{{ $enquiry->preferred_contact_method }}</dd>
    <dt>Enquiry type</dt>
    <dd>{{ $enquiry->enquiry_type }}</dd>
    <dt>Project type</dt>
    <dd>{{ $enquiry->project_type ?: 'Not supplied' }}</dd>
    <dt>Proposed location</dt>
    <dd>{{ $enquiry->proposed_location ?: 'Not supplied' }}</dd>
    <dt>Current project stage</dt>
    <dd>{{ $enquiry->project_stage ?: 'Not supplied' }}</dd>
    <dt>Scope or requirement summary</dt>
    <dd>{{ $enquiry->scope_summary ?: 'Not supplied' }}</dd>
    <dt>Message</dt>
    <dd>{{ $enquiry->message }}</dd>
</dl>

<p>This enquiry is saved in the Selotemna website database.</p>
