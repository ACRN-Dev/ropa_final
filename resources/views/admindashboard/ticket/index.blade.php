@extends('layouts.admin')

@section('title', 'Admin Ticket Management')

@section('content')
<div class="container mx-auto p-4 sm:p-6" x-data="ticketModal()">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h2 class="text-2xl font-bold text-orange-500 flex items-center">Ticket Management</h2>

        <form method="GET" action="{{ route('admin.tickets.index') }}" class="mt-4 sm:mt-0">
            <input type="text" name="search" placeholder="Search tickets..."
                   value="{{ request('search') }}"
                   class="px-3 py-2 border rounded-lg w-64 focus:ring-orange-500 focus:border-orange-500">
        </form>
    </div>

    {{-- TABS --}}
    @php $tab = request('tab', 'pending'); @endphp
    <div class="flex space-x-4 border-b mb-6">
        <a href="{{ route('admin.tickets.index', ['tab' => 'pending']) }}"
           class="pb-2 {{ $tab == 'pending' ? 'border-b-4 border-orange-500 text-orange-600' : 'text-gray-500' }}">
            Pending Tickets ({{ $pending_count }})
        </a>
        <a href="{{ route('admin.tickets.index', ['tab' => 'resolved']) }}"
           class="pb-2 {{ $tab == 'resolved' ? 'border-b-4 border-green-600 text-green-600' : 'text-gray-500' }}">
            Resolved Tickets ({{ $completed_count }})
        </a>
    </div>

    {{-- TICKET LIST --}}
    @php $tickets = $tab == 'resolved' ? $completed_tickets : $pending_tickets; @endphp
    <div class="bg-white shadow rounded-lg p-4">
        @if ($tickets->count() == 0)
            <p class="text-center text-gray-500 py-6">No tickets found.</p>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead>
                <tr class="bg-gray-100 text-left text-sm text-gray-600">
                    <th class="p-3">Ticket ID</th>
                    <th class="p-3">Title</th>
                    <th class="p-3">Risk</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">ROPA</th>
                    <th class="p-3">Created By</th>
                    <th class="p-3">Created</th>
                    <th class="p-3">Actions</th>
                </tr>
                </thead>
                <tbody class="text-sm">
                @foreach ($tickets as $ticket)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $ticket->id }}</td>
                        <td class="p-3 font-medium">{{ $ticket->title }}</td>
                        <td class="p-3">
                            @php
                                $colors = [
                                    'low'=>'bg-green-100 text-green-700',
                                    'medium'=>'bg-yellow-100 text-yellow-700',
                                    'high'=>'bg-orange-100 text-orange-700',
                                    'critical'=>'bg-red-100 text-red-700 font-bold'
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded text-xs {{ $colors[$ticket->risk_level] }}">
                                {{ ucfirst($ticket->risk_level) }}
                            </span>
                        </td>
                        <td class="p-3">
                            @php $statusColors = ['open'=>'bg-yellow-200 text-yellow-800','resolved'=>'bg-green-200 text-green-800']; @endphp
                            <span class="px-2 py-1 rounded text-xs {{ $statusColors[$ticket->status] ?? 'bg-gray-300 text-gray-800' }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </td>
                        <td class="p-3">{{ $ticket->ropa->organisation_name ?? 'N/A' }}</td>
                        <td class="p-3">{{ $ticket->user->name ?? 'N/A' }}</td>
                        <td class="p-3">{{ $ticket->created_at->format('Y-m-d') }}<br>
                            <span class="text-xs text-gray-500">{{ $ticket->created_at->diffForHumans() }}</span>
                        </td>
                        <td class="p-3">
                            <button @click="openModal({{ $ticket->id }})" class="text-blue-600 hover:underline mr-2">View</button>
                            @if ($ticket->status != 'resolved')
                                <a href="{{ route('admin.tickets.edit', $ticket->id) }}" class="text-orange-600 hover:underline">Update</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $tickets->links() }}</div>
        @endif
    </div>

    {{-- MODAL --}}
    <div x-show="isOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-xl shadow-xl w-11/12 md:w-2/3 p-6 relative max-h-[90vh] overflow-y-auto" @click.away="closeModal()">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-orange-500">Ticket Details</h3>
                <button @click="closeModal()" class="text-gray-500 hover:text-gray-800">&times;</button>
            </div>

            <template x-if="loading">
                <p class="text-gray-500">Loading...</p>
            </template>

            <div x-html="ticketContent" class="mb-4 space-y-2" x-show="!loading"></div>

            <form :action="`/admin/tickets/${ticketId}/close`" method="POST" class="space-y-3" x-show="!loading" @submit.prevent="submitComment($event)">
                @csrf
                <textarea name="comment" placeholder="Add comment..." class="w-full border rounded px-3 py-2" required></textarea>
                <div class="flex justify-end space-x-2 mt-2">
                    <button type="button" @click="closeModal()" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded hover:bg-orange-600">Close Ticket</button>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- ALPINE JS --}}
<script src="//unpkg.com/alpinejs" defer></script>
<script>
function ticketModal() {
    return {
        isOpen: false,
        ticketId: null,
        ticketContent: '',
        loading: false,

        openModal(id) {
            this.ticketId = id;
            this.isOpen = true;
            this.loading = true;
            this.ticketContent = '';
            fetch(`/admin/tickets/${id}`)
                .then(res => res.text())
                .then(html => {
                    this.ticketContent = html;
                    this.loading = false;
                })
                .catch(err => {
                    this.ticketContent = `<p class="text-red-500">Failed to load ticket details.</p>`;
                    this.loading = false;
                    console.error(err);
                });
        },

        closeModal() {
            this.isOpen = false;
            this.ticketId = null;
            this.ticketContent = '';
        },

        submitComment(event) {
            const textarea = event.target.querySelector('textarea[name="comment"]');
            if (!textarea.value.trim()) {
                alert('Please add a comment before closing the ticket.');
                textarea.focus();
                return;
            }

            fetch(event.target.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ comment: textarea.value }),
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    this.closeModal();
                    location.reload();
                } else {
                    alert('Failed to close ticket.');
                }
            })
            .catch(err => console.error(err));
        }
    }
}
</script>
@endsection
