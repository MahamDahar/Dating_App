<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminSupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with('user');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function($uq) use ($request) {
                      $uq->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $tickets = $query->latest()->paginate(20);

        // Stats
        $totalTickets    = SupportTicket::count();
        $openTickets     = SupportTicket::where('status', 'open')->count();
        $inProgressTickets = SupportTicket::where('status', 'in_progress')->count();
        $resolvedTickets = SupportTicket::where('status', 'resolved')->count();

        return view('admin.support_tickets.index', compact(
            'tickets',
            'totalTickets', 'openTickets',
            'inProgressTickets', 'resolvedTickets'
        ));
    }

    // View single ticket
    public function show(SupportTicket $ticket)
    {
        $ticket->load('user');

        // Mark as in_progress when admin opens it
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return view('admin.support_tickets.show', compact('ticket'));
    }

    // Reply to ticket
    public function reply(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'admin_reply' => 'required|string|max:1000',
            'status'      => 'required|in:open,in_progress,resolved,closed',
        ]);

        $ticket->update([
            'admin_reply' => $request->admin_reply,
            'status'      => $request->status,
            'replied_at'  => now(),
        ]);

        // Notify user
        Notification::create([
            'user_id' => $ticket->user_id,
            'type'    => 'info',
            'title'   => '📩 Support Reply Received',
            'message' => 'Our support team has replied to your ticket: "' . $ticket->subject . '". Please check your support tickets for the response.',
        ]);

        return back()->with('success', 'Reply sent and user notified.');
    }

    // Delete ticket
    public function destroy(SupportTicket $ticket)
    {
        $ticket->delete();
        return back()->with('success', 'Ticket deleted.');
    }
}