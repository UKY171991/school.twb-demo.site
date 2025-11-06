<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Message;
use App\Models\Announcement;
use App\Models\Feedback;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CommunicationController extends Controller
{
    /**
     * Display communication dashboard
     */
    public function index()
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        // Get communication statistics
        $stats = [
            'unread_messages' => Message::forUser($user->id)->unread()->count(),
            'total_messages' => Message::forUser($user->id)->count(),
            'unread_announcements' => $this->getUnreadAnnouncementsCount($user),
            'total_announcements' => $this->getAnnouncementsForUser($user)->count(),
            'pending_feedback' => Feedback::byStudent($student->id)->pending()->count(),
            'total_feedback' => Feedback::byStudent($student->id)->count(),
        ];

        // Get recent items
        $recentMessages = Message::forUser($user->id)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentAnnouncements = $this->getAnnouncementsForUser($user)
            ->with('author')
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return view('student.communication.index', compact('student', 'stats', 'recentMessages', 'recentAnnouncements'));
    }

    /**
     * Display messages
     */
    public function messages(Request $request)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        $filter = $request->get('filter', 'all'); // all, unread, sent, received
        
        $messagesQuery = Message::forUser($user->id)
            ->with(['sender', 'receiver'])
            ->rootMessages(); // Only show root messages, not replies

        switch ($filter) {
            case 'unread':
                $messagesQuery->where('receiver_id', $user->id)->unread();
                break;
            case 'sent':
                $messagesQuery->where('sender_id', $user->id);
                break;
            case 'received':
                $messagesQuery->where('receiver_id', $user->id);
                break;
        }

        $messages = $messagesQuery->orderBy('created_at', 'desc')->paginate(20);

        // Get teachers for compose message
        $teachers = Teacher::where('school_id', $student->school_id)
            ->with('user')
            ->get();

        return view('student.communication.messages', compact('student', 'messages', 'teachers', 'filter'));
    }

    /**
     * Show specific message thread
     */
    public function showMessage(Message $message)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        // Check if user has access to this message
        if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) {
            abort(403, 'Unauthorized access to message.');
        }

        // Mark as read if user is the receiver
        if ($message->receiver_id === $user->id) {
            $message->markAsRead();
        }

        // Get conversation thread
        $conversation = $message->getConversationThread();

        return view('student.communication.message-thread', compact('student', 'message', 'conversation'));
    }

    /**
     * Send new message
     */
    public function sendMessage(Request $request)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found'], 404);
        }

        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'in:low,normal,high,urgent',
            'attachments.*' => 'file|max:10240', // 10MB max per file
        ]);

        // Handle file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message-attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                ];
            }
        }

        $message = Message::create([
            'school_id' => $student->school_id,
            'sender_id' => $user->id,
            'receiver_id' => $request->receiver_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'priority' => $request->priority ?? 'normal',
            'attachments' => $attachments,
            'status' => 'sent',
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => $message->load(['sender', 'receiver'])
            ]);
        }

        return redirect()->route('student.communication.messages')
            ->with('success', 'Message sent successfully.');
    }

    /**
     * Reply to message
     */
    public function replyMessage(Request $request, Message $message)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found'], 404);
        }

        // Check if user has access to this message
        if ($message->sender_id !== $user->id && $message->receiver_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string',
            'attachments.*' => 'file|max:10240',
        ]);

        // Handle file uploads
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('message-attachments', 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                ];
            }
        }

        // Determine receiver (reply to sender if current user is receiver, otherwise reply to receiver)
        $receiverId = $message->sender_id === $user->id ? $message->receiver_id : $message->sender_id;
        $rootMessage = $message->parent_message_id ? $message->parentMessage : $message;

        $reply = Message::create([
            'school_id' => $student->school_id,
            'sender_id' => $user->id,
            'receiver_id' => $receiverId,
            'subject' => 'Re: ' . $rootMessage->subject,
            'message' => $request->message,
            'priority' => $rootMessage->priority,
            'attachments' => $attachments,
            'parent_message_id' => $rootMessage->id,
            'status' => 'sent',
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully',
                'data' => $reply->load(['sender', 'receiver'])
            ]);
        }

        return redirect()->back()->with('success', 'Reply sent successfully.');
    }

    /**
     * Display announcements
     */
    public function announcements(Request $request)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        $filter = $request->get('filter', 'all'); // all, unread, pinned, type
        $type = $request->get('type');

        $announcementsQuery = $this->getAnnouncementsForUser($user)->with('author');

        if ($filter === 'unread') {
            $readAnnouncementIds = \App\Models\AnnouncementRead::where('user_id', $user->id)
                ->pluck('announcement_id')
                ->toArray();
            $announcementsQuery->whereNotIn('id', $readAnnouncementIds);
        } elseif ($filter === 'pinned') {
            $announcementsQuery->pinned();
        }

        if ($type) {
            $announcementsQuery->byType($type);
        }

        $announcements = $announcementsQuery->orderBy('is_pinned', 'desc')
            ->orderBy('published_at', 'desc')
            ->paginate(20);

        // Mark announcements as read when viewed
        foreach ($announcements as $announcement) {
            $announcement->markAsReadByUser($user->id);
        }

        return view('student.communication.announcements', compact('student', 'announcements', 'filter', 'type'));
    }

    /**
     * Show specific announcement
     */
    public function showAnnouncement(Announcement $announcement)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        // Check if announcement is targeted to this user
        if (!$announcement->isTargetedToUser($user)) {
            abort(403, 'This announcement is not available to you.');
        }

        // Mark as read
        $announcement->markAsReadByUser($user->id);

        return view('student.communication.announcement-detail', compact('student', 'announcement'));
    }

    /**
     * Display feedback form and submitted feedback
     */
    public function feedback(Request $request)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return redirect()->route('login')->with('error', 'Student profile not found.');
        }

        $filter = $request->get('filter', 'all'); // all, pending, responded

        $feedbackQuery = Feedback::byStudent($student->id)->with(['subject', 'teacher', 'respondedBy']);

        if ($filter === 'pending') {
            $feedbackQuery->pending();
        } elseif ($filter === 'responded') {
            $feedbackQuery->responded();
        }

        $feedbacks = $feedbackQuery->orderBy('created_at', 'desc')->paginate(20);

        // Get subjects and teachers for feedback form
        $subjects = Subject::where('school_id', $student->school_id)->get();
        $teachers = Teacher::where('school_id', $student->school_id)->with('user')->get();

        return view('student.communication.feedback', compact('student', 'feedbacks', 'subjects', 'teachers', 'filter'));
    }

    /**
     * Submit feedback
     */
    public function submitFeedback(Request $request)
    {
        $user = auth()->user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found'], 404);
        }

        $request->validate([
            'type' => 'required|in:course_evaluation,teacher_feedback,suggestion,complaint,general',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'subject_id' => 'nullable|exists:subjects,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'rating' => 'nullable|integer|min:1|max:5',
            'ratings' => 'nullable|array',
            'ratings.*' => 'integer|min:1|max:5',
            'is_anonymous' => 'boolean',
        ]);

        $feedback = Feedback::create([
            'school_id' => $student->school_id,
            'student_id' => $student->id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
            'type' => $request->type,
            'title' => $request->title,
            'content' => $request->content,
            'rating' => $request->rating,
            'ratings' => $request->ratings,
            'is_anonymous' => $request->boolean('is_anonymous'),
            'status' => 'submitted',
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Feedback submitted successfully',
                'data' => $feedback
            ]);
        }

        return redirect()->route('student.communication.feedback')
            ->with('success', 'Feedback submitted successfully.');
    }

    /**
     * Get announcements for user
     */
    private function getAnnouncementsForUser(User $user)
    {
        return Announcement::bySchool($user->school_id)
            ->active()
            ->forUser($user);
    }

    /**
     * Get unread announcements count for user
     */
    private function getUnreadAnnouncementsCount(User $user): int
    {
        $allAnnouncements = $this->getAnnouncementsForUser($user)->pluck('id')->toArray();
        $readAnnouncements = \App\Models\AnnouncementRead::where('user_id', $user->id)
            ->whereIn('announcement_id', $allAnnouncements)
            ->pluck('announcement_id')
            ->toArray();
        
        return count(array_diff($allAnnouncements, $readAnnouncements));
    }
}