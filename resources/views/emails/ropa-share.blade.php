<h2>Hello,</h2>

<p>You have received a shared ROPA record.</p>

<p><strong>Organisation:</strong> {{ $ropa->organisation_name ?? $ropa->other_organisation_name }}</p>
<p><strong>Department:</strong> {{ $ropa->department ?? $ropa->other_department }}</p>
<p><strong>Status:</strong> {{ ucfirst($ropa->status) }}</p>

<p>The attachment contains the full details.</p>

<p>Regards,<br>Your System</p>
