<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RopaIssue;
use App\Models\Ropa;
use App\Models\User;
use Illuminate\Http\Request;

class RopaIssueController extends Controller
{
    /**
     * USER ticket dashboard
     */
    public function userIndex()
    {
        $user = auth()->user();

        $pending_tickets = RopaIssue::where('user_id', $user->id)
            ->where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $completed_tickets = RopaIssue::where('user_id', $user->id)
            ->where('status', 'resolved')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $pending_count = RopaIssue::where('user_id', $user->id)
            ->where('status', 'open')
            ->count();

        $completed_count = RopaIssue::where('user_id', $user->id)
            ->where('status', 'resolved')
            ->count();

        $ropas = Ropa::where('user_id', $user->id)->get();

        return view('ticket.index', compact(
            'pending_tickets',
            'completed_tickets',
            'pending_count',
            'completed_count',
            'ropas'
        ));
    }

    /**
     * ADMIN & USER Index
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {

            $pending_tickets = RopaIssue::where('status', 'open')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $completed_tickets = RopaIssue::where('status', 'resolved')
                ->orderBy('updated_at', 'desc')
                ->paginate(10);

            $pending_count = RopaIssue::where('status', 'open')->count();
            $completed_count = RopaIssue::where('status', 'resolved')->count();

            $ropas = Ropa::all();

            return view('admindashboard.ticket.index', compact(
                'pending_tickets',
                'completed_tickets',
                'pending_count',
                'completed_count',
                'ropas'
            ));
        }

        // Non-admin users
        return $this->userIndex();
    }


    /**
     * CREATE FORM
     */
    public function create()
    {
        $user = auth()->user();
        $ropas = Ropa::where('user_id', $user->id)->get();

        return view('ticket.create', compact('ropas'));
    }


    /**
     * STORE new ticket
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ropa_id'     => 'required|exists:ropas,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'risk_level'  => 'nullable|in:low,medium,high,critical',
        ]);

        RopaIssue::create([
            'ropa_id'     => $validated['ropa_id'],
            'user_id'     => auth()->id(),
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'risk_level'  => $validated['risk_level'] ?? 'low',
            'status'      => 'open',
        ]);

        return redirect()->route('ticket.index')
            ->with('success', 'Ticket created successfully.');
    }


    /**
     * Show Ticket (ADMIN MODAL)
     */
    public function show($id)
    {
        $ticket = RopaIssue::with(['user', 'ropa'])->findOrFail($id);
        return view('admindashboard.ticket.modal', compact('ticket'));
    }


    /**
     * CLOSE TICKET (AJAX)
     */
    public function close(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $ticket = RopaIssue::findOrFail($id);

        $ticket->status = 'resolved';
        $ticket->comment = $request->comment; // stored in DB table
        $ticket->save();

        return response()->json([
            'success' => true,
            'message' => 'Ticket closed successfully.',
            'ticket_id' => $ticket->id,
        ]);
    }


    /**
     * EDIT FORM
     */
    public function edit($id)
    {
        $issue = RopaIssue::findOrFail($id);
        $ropas = Ropa::all();
        return view('ticket.edit', compact('issue', 'ropas'));
    }


    /**
     * UPDATE ticket
     */
    public function update(Request $request, $id)
    {
        $issue = RopaIssue::findOrFail($id);

        $validated = $request->validate([
            'ropa_id'     => 'required|exists:ropas,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'risk_level'  => 'required|in:low,medium,high,critical',
            'status'      => 'required|in:open,resolved',
        ]);

        $issue->update($validated);

        return redirect()->route('ticket.index')
            ->with('success', 'Ticket updated successfully.');
    }


    /**
     * DELETE ticket
     */
    public function destroy($id)
    {
        $issue = RopaIssue::findOrFail($id);
        $issue->delete();

        return redirect()->route('ticket.index')
            ->with('success', 'Ticket deleted successfully.');
    }
}
