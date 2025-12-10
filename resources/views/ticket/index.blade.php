@extends('layouts.app')

@section('title', 'Ticket Management')

@section('content')
<div class="container mx-auto p-4">

    @if(session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <h2 class="text-2xl font-bold text-orange-500 flex items-center">
            <i data-feather="tag" class="w-7 h-7 mr-2"></i> Ticket Management
        </h2>
    </div>

    <div class="flex space-x-4 mb-8">
        <button onclick="showTab('pending')" 
            id="pendingTab"
            class="px-4 py-2 rounded-lg bg-purple-700 text-white font-semibold hover:bg-purple-800 transition">
            <i data-feather="clock" class="w-4 h-4 mr-1"></i>
            Pending ({{ $pending_count }})
        </button>

        <button onclick="showTab('completed')" 
            id="completedTab"
            class="px-4 py-2 rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700 transition opacity-60">
            <i data-feather="check-circle" class="w-4 h-4 mr-1"></i>
            Completed ({{ $completed_count }})
        </button>
    </div>

    <!-- ====================================================== -->
    <!-- PENDING TAB -->
    <!-- ====================================================== -->
    <div id="pendingSection">

        <!-- CREATE TICKET -->
        <div class="bg-white border shadow p-6 rounded-lg mb-10">
            <h2 class="text-xl font-bold text-orange-600 mb-4">Create New Ticket</h2>

            <form method="POST" action="{{ route('ticket.store') }}" 
                  class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @csrf

                <div>
                    <label class="font-medium">Select ROPA</label>
                    <select name="ropa_id" class="w-full border rounded p-2" required>
                        <option value="">-- Select ROPA --</option>
                        @foreach($ropas as $ropa)
                        <option value="{{ $ropa->id }}">{{ $ropa->organisation_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="font-medium">Ticket Title</label>
                    <input type="text" name="title" class="w-full border rounded p-2" required>
                </div>

                <div>
                    <label class="font-medium">Priority Level</label>
                    <select name="risk_level" class="w-full border rounded p-2" required>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="font-medium">Description</label>
                    <textarea name="description" rows="3" class="w-full border rounded p-2"></textarea>
                </div>

                <div class="sm:col-span-2">
                    <button class="bg-orange-600 text-white px-4 py-2 rounded shadow">
                        Submit Ticket
                    </button>
                </div>
            </form>
        </div>

        <!-- PENDING TABLE -->
        <div class="bg-white shadow border rounded-lg p-6">
            <h2 class="text-xl font-bold text-blue-600 mb-4">
                Pending Tickets ({{ $pending_count }})
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 text-left">Ticket ID</th>
                            <th class="p-3 text-left">Title</th>
                            <th class="p-3 text-left">Risk</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">ROPA</th>
                            <th class="p-3 text-left">Created</th>
                            <th class="p-3 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pending_tickets as $ticket)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-3">{{ $ticket->id }}</td>
                            <td class="p-3">{{ $ticket->title }}</td>

                            <td class="p-3">
                                <span class="px-3 py-1 text-white rounded
                                    @if($ticket->risk_level=='critical') bg-red-700
                                    @elseif($ticket->risk_level=='high') bg-red-500
                                    @elseif($ticket->risk_level=='medium') bg-yellow-500
                                    @else bg-green-600 @endif">
                                    {{ ucfirst($ticket->risk_level) }}
                                </span>
                            </td>

                            <td class="p-3">
                                <span class="px-3 py-1 bg-yellow-200 text-yellow-800 rounded-md text-sm font-medium">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>

                            <td class="p-3">{{ $ticket->ropa->organisation_name ?? 'N/A' }}</td>
                            <td class="p-3">{{ $ticket->created_at->format('d M Y') }}</td>

                            <td class="p-3">
                                <button 
                                    class="bg-blue-600 text-white px-3 py-1 rounded"
                                    onclick="openEditModal(
                                        {{ $ticket->id }},
                                        '{{ addslashes($ticket->title) }}',
                                        '{{ $ticket->risk_level }}',
                                        `{{ addslashes($ticket->description) }}`,
                                        '{{ $ticket->status }}',
                                        '{{ $ticket->ropa_id }}'
                                    )">
                                    Edit
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td class="p-3 text-center text-gray-500" colspan="7">
                                No pending tickets.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $pending_tickets->links() }}
            </div>
        </div>

    </div>

    <!-- COMPLETED TAB -->
    <div id="completedSection" class="hidden">

        <div class="bg-white shadow border rounded-lg p-6">
            <h2 class="text-xl font-bold text-green-600 mb-4">
                Completed Tickets ({{ $completed_count }})
            </h2>

            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-3 text-left">Title</th>
                        <th class="p-3 text-left">ROPA</th>
                        <th class="p-3 text-left">Completed On</th>
                        <th class="p-3 text-left">Comment</th>
                        <th class="p-3 text-left"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($completed_tickets as $ticket)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3">{{ $ticket->title }}</td>
                        <td class="p-3">{{ $ticket->ropa->organisation_name ?? 'N/A' }}</td>
                        <td class="p-3">{{ $ticket->updated_at->format('d M Y') }}</td>
                        <td class="p-3">{{ $ticket->comment ?? 'No comment' }}</td>
                        <td class="p-3">
                           
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td class="p-3 text-center text-gray-500" colspan="5">
                            No completed tickets.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $completed_tickets->links() }}
            </div>
        </div>

    </div>

</div>

<script>
function showTab(tab) {
    const pending = document.getElementById('pendingSection');
    const completed = document.getElementById('completedSection');

    const tPending = document.getElementById('pendingTab');
    const tCompleted = document.getElementById('completedTab');

    if (tab === 'pending') {
        pending.classList.remove('hidden');
        completed.classList.add('hidden');

        tPending.classList.remove('opacity-60');
        tCompleted.classList.add('opacity-60');
    }
    else {
        completed.classList.remove('hidden');
        pending.classList.add('hidden');

        tCompleted.classList.remove('opacity-60');
        tPending.classList.add('opacity-60');
    }

    feather.replace();
}
</script>

<!-- ====================================================== -->
<!-- EDIT MODAL -->
<!-- ====================================================== -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-lg p-6">

        <h2 class="text-xl font-bold text-blue-600 mb-4">Edit Ticket</h2>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="font-medium">Title</label>
                <input type="text" name="title" id="editTitle" class="w-full border rounded p-2" required>
            </div>

            <div class="mb-3">
                <label class="font-medium">Priority Level </label>
                <select name="risk_level" id="editRisk" class="w-full border rounded p-2" required>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="critical">Critical</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="font-medium">Description</label>
                <textarea name="description" id="editDescription" rows="3" class="w-full border rounded p-2"></textarea>
            </div>

            <!-- STATUS FIELD -->
            <div class="mb-3">
                <label class="font-medium">Status</label>
                <select name="status" id="editStatus" class="w-full border rounded p-2" required>
                    <option value="open">Open</option>
                    <option value="resolved">Resolved</option>
                </select>
            </div>

            <!-- ROPA FIELD -->
            <div class="mb-3">
                <label class="font-medium">Select ROPA</label>
                <select name="ropa_id" id="editRopa" class="w-full border rounded p-2" required>
                    @foreach($ropas as $ropa)
                        <option value="{{ $ropa->id }}">{{ $ropa->organisation_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end space-x-3 mt-4">
                <button type="button" onclick="closeEditModal()" 
                    class="px-4 py-2 bg-gray-600 text-white rounded">
                    Cancel
                </button>

                <button class="px-4 py-2 bg-blue-600 text-white rounded">
                    Update Ticket
                </button>
            </div>

        </form>
    </div>
</div>

<script>
function openEditModal(id, title, risk, description, status, ropa_id) {

    document.getElementById('editTitle').value = title;
    document.getElementById('editRisk').value = risk;
    document.getElementById('editDescription').value = description;
    document.getElementById('editStatus').value = status;
    document.getElementById('editRopa').value = ropa_id;

    document.getElementById('editForm').action = "/ticket/" + id;

    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}
</script>

@endsection
