<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\BaseController;
use App\Models\Message;
use App\Models\User;
use App\Models\Student;
use App\Models\Meeting;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommunicationController extends BaseController
{
    public function index()
    {
        $parent = Auth::user();
        $children = $parent->children()->with(['school', 'classModel.teacher'])->get();
        
        // Get recent conversations
        $conversations = Conversation::where('parent_id', $parent->id)
            ->with(['teacher', 'student', 'lastMessage'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);
        
        // Get upcoming meetings
        $upcomingMeetings = Meeting::where('parent_id', $parent->id)
            ->where('scheduled_at', '>', now())
            ->with(['teacher', 'student'])
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get();
        
        return view('parent.communication.index', compact('children', 'conversations', 'upcomingMeetings'));
    }

    public function messages(Request $request)
    {
        $parent = Auth::user();
        $conversationId = $request->get('conversation_id');
        
        if ($conversationId) {
            $conversation = Conversation::where('id', $conversationId)
                ->where('parent_id', $parent->id)
                ->with(['teacher', 'student'])
                ->firstOrFail();
            
            $messages = Message::where('conversation_id', $conversationId)
                ->with('sender')
                ->orderBy('created_at')
                ->get();
            
            // Mark messages as read
            Message::where('conversation_id', $conversationId)
                ->where('sender_id', '!=', $parent->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        } else {
            $conversation = null;
            $messages = collect();
        }
        
        // Get available teachers for new conversations
        $children = $parent->children()->with(['classModel.teacher'])->get();
        $teachers = $children->pluck('classModel.teacher')->filter()->unique('id');
        
        return view('parent.communication.messages', compact('conversation', 'messages', 'teachers', 'children'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'conversation_id' => 'nullable|exists:conversations,id',
            'teacher_id' => 'required_without:conversation_id|exists:users,id',
            'student_id' => 'required_without:conversation_id|exists:students,id',
            'subject' => 'required_without:conversation_id|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png'
        ]);

        $parent = Auth::user();
        
        // Handle file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('communication_attachments', 'public');
        }

        if ($request->conversation_id) {
            // Reply to existing conversation
            $conversation = Conversation::where('id', $request->conversation_id)
                ->where('parent_id', $parent->id)
                ->firstOrFail();
        } else {
            // Create new conversation
            $conversation = Conversation::create([
                'parent_id' => $parent->id,
                'teacher_id' => $request->teacher_id,
                'student_id' => $request->student_id,
                'subject' => $request->subject,
                'status' => 'active'
            ]);
        }

        // Create message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $parent->id,
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
            'is_read' => false
        ]);

        // Update conversation timestamp
        $conversation->touch();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => [
                    'message' => $message->load('sender'),
                    'conversation_id' => $conversation->id
                ]
            ]);
        }

        return redirect()->route('parent.communication.messages', ['conversation_id' => $conversation->id])
            ->with('success', 'Message sent successfully');
    }

    public function meetings()
    {
        $parent = Auth::user();
        
        $meetings = Meeting::where('parent_id', $parent->id)
            ->with(['teacher', 'student'])
            ->orderBy('scheduled_at', 'desc')
            ->paginate(15);
        
        $children = $parent->children()->with(['school', 'classModel.teacher'])->get();
        
        return view('parent.communication.meetings', compact('meetings', 'children'));
    }

    public function requestMeeting(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'student_id' => 'required|exists:students,id',
            'preferred_date' => 'required|date|after:today',
            'preferred_time' => 'required|date_format:H:i',
            'purpose' => 'required|string|max:500',
            'meeting_type' => 'required|in:in_person,video_call,phone_call'
        ]);

        $parent = Auth::user();
        
        // Verify parent has access to this student
        $student = $parent->children()->findOrFail($request->student_id);
        
        $meeting = Meeting::create([
            'parent_id' => $parent->id,
            'teacher_id' => $request->teacher_id,
            'student_id' => $request->student_id,
            'requested_at' => now(),
            'preferred_date' => $request->preferred_date,
            'preferred_time' => $request->preferred_time,
            'purpose' => $request->purpose,
            'meeting_type' => $request->meeting_type,
            'status' => 'pending'
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Meeting request sent successfully',
                'data' => $meeting->load(['teacher', 'student'])
            ]);
        }

        return redirect()->route('parent.communication.meetings')
            ->with('success', 'Meeting request sent successfully');
    }

    public function downloadAttachment($messageId)
    {
        $parent = Auth::user();
        
        $message = Message::whereHas('conversation', function($query) use ($parent) {
            $query->where('parent_id', $parent->id);
        })->where('id', $messageId)->firstOrFail();

        if (!$message->attachment_path) {
            abort(404);
        }

        return Storage::disk('public')->download($message->attachment_path);
    }

    public function markAsRead(Request $request)
    {
        $parent = Auth::user();
        $conversationId = $request->conversation_id;
        
        Message::whereHas('conversation', function($query) use ($parent) {
            $query->where('parent_id', $parent->id);
        })
        ->where('conversation_id', $conversationId)
        ->where('sender_id', '!=', $parent->id)
        ->where('is_read', false)
        ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}