<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $conversations = Message::with(['sender', 'recipient'])
            ->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere('recipient_id', $user->id);
            })
            ->latest()
            ->get()
            ->groupBy('conversation_id')
            ->map(function ($items) {
                return $items->sortByDesc('created_at')->first();
            })
            ->sortByDesc('created_at')
            ->values();

        $unreadCount = Message::where('recipient_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('messages.index', [
            'conversations' => $conversations,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function create(): View
    {
        $user = auth()->user();

        if ($user->role === 'student') {
            $recipients = User::whereIn('role', ['admin', 'staff'])
                ->where('id', '!=', $user->id)
                ->orderBy('name')
                ->get();
        } else {
            $recipients = User::whereIn('role', ['admin', 'staff', 'student'])
                ->where('id', '!=', $user->id)
                ->orderBy('name')
                ->get();
        }

        return view('messages.create', compact('recipients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'recipient_id' => ['required', 'exists:users,id'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:5'],
        ]);

        $recipient = User::findOrFail($validated['recipient_id']);

        $this->ensureAllowedRecipient($user, $recipient);

        $message = Message::create([
            'conversation_id' => (string) Str::uuid(),
            'sender_id' => $user->id,
            'recipient_id' => $recipient->id,
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'is_read' => false,
        ]);

        return redirect()->route('messages.show', $message)
            ->with('success', 'Your message has been sent.');
    }

    public function show(Message $message): View
    {
        abort_unless($message->sender_id === auth()->id() || $message->recipient_id === auth()->id(), 403);

        if (auth()->id() === $message->recipient_id) {
            $message->update(['is_read' => true]);
        }

        $thread = Message::with(['sender', 'recipient'])
            ->where('conversation_id', $message->conversation_id)
            ->latest()
            ->get();

        return view('messages.show', [
            'thread' => $thread,
            'message' => $message,
        ]);
    }

    public function reply(Request $request, Message $message): RedirectResponse
    {
        abort_unless($message->sender_id === auth()->id() || $message->recipient_id === auth()->id(), 403);

        $validated = $request->validate([
            'body' => ['required', 'string', 'min:5'],
        ]);

        $recipientId = auth()->id() === $message->sender_id ? $message->recipient_id : $message->sender_id;

        $reply = Message::create([
            'conversation_id' => $message->conversation_id,
            'sender_id' => auth()->id(),
            'recipient_id' => $recipientId,
            'subject' => preg_match('/^Re:/i', $message->subject) ? $message->subject : 'Re: ' . $message->subject,
            'body' => $validated['body'],
            'is_read' => false,
        ]);

        return redirect()->route('messages.show', $message)
            ->with('success', 'Reply sent successfully.');
    }

    public function destroy(Message $message): RedirectResponse
    {
        abort_unless($message->sender_id === auth()->id() || $message->recipient_id === auth()->id(), 403);

        Message::where('conversation_id', $message->conversation_id)->delete();

        return redirect()->route('messages.index')
            ->with('success', 'Conversation deleted successfully.');
    }

    protected function ensureAllowedRecipient(User $sender, User $recipient): void
    {
        if ($sender->id === $recipient->id) {
            abort(403, 'You cannot message yourself.');
        }

        if ($sender->role === 'student' && ! in_array($recipient->role, ['admin', 'staff'], true)) {
            abort(403, 'Students can only send concerns to admin or staff.');
        }

        if (in_array($sender->role, ['staff', 'faculty'], true) && ! in_array($recipient->role, ['admin', 'staff', 'student'], true)) {
            abort(403, 'Invalid recipient for your role.');
        }
    }
}
