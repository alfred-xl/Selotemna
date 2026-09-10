<h1>New Omu Creek plot enquiry {{ $enquiry->reference }}</h1>

<dl>
    <dt>Plot preference</dt>
    <dd>{{ $enquiry->plot_label }}</dd>
    <dt>Requested size</dt>
    <dd>{{ $enquiry->plot_size_sqm ? number_format($enquiry->plot_size_sqm).' sqm' : 'Not selected' }}</dd>
    <dt>Rate when submitted</dt>
    <dd>{{ $enquiry->price_per_sqm_snapshot ? '₦'.number_format($enquiry->price_per_sqm_snapshot).' per sqm' : 'Not selected' }}</dd>
    <dt>Estimated base land price</dt>
    <dd>{{ $enquiry->price_snapshot ? '₦'.number_format($enquiry->price_snapshot) : 'Not selected' }}</dd>
    <dt>Payment preference</dt>
    <dd>{{ $enquiry->payment_preference }}</dd>
    <dt>Purchase timeline</dt>
    <dd>{{ $enquiry->purchase_timeline }}</dd>
    <dt>Full name</dt>
    <dd>{{ $enquiry->full_name }}</dd>
    <dt>Telephone</dt>
    <dd>{{ $enquiry->phone }}</dd>
    <dt>Email</dt>
    <dd>{{ $enquiry->email ?: 'Not supplied' }}</dd>
    <dt>WhatsApp</dt>
    <dd>{{ $enquiry->whatsapp ?: 'Not supplied' }}</dd>
    <dt>Preferred contact method</dt>
    <dd>{{ $enquiry->preferred_contact_method }}</dd>
    <dt>Message</dt>
    <dd>{{ $enquiry->message ?: 'Not supplied' }}</dd>
</dl>

<p>This is a base-price estimate. Plot availability, taxes, statutory charges, survey fees and development levies remain subject to confirmation.</p>
<p>This enquiry is saved in the Selotemna admin dashboard.</p>
