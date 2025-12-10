<p><strong>Title:</strong> {{ $ticket->title }}</p>
<p><strong>Description:</strong> {{ $ticket->description }}</p>
<p><strong>Risk:</strong> {{ ucfirst($ticket->risk_level) }}</p>
<p><strong>Status:</strong> {{ ucfirst($ticket->status) }}</p>
<p><strong>ROPA:</strong> {{ $ticket->ropa->organisation_name ?? 'N/A' }}</p>
<p><strong>Created By:</strong> {{ $ticket->user->name ?? 'N/A' }}</p>
