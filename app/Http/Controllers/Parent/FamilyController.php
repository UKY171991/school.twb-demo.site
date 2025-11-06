<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\BaseController;
use App\Models\User;
use App\Models\Student;
use App\Models\FamilyProfile;
use App\Models\StudentPermission;
use App\Models\EmergencyContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FamilyController extends BaseController
{
    public function index()
    {
        $parent = Auth::user();
        $children = $parent->children()->with(['school', 'classModel.teacher'])->get();
        
        // Get family profile
        $familyProfile = FamilyProfile::where('parent_id', $parent->id)->first();
        
        // Get emergency contacts
        $emergencyContacts = EmergencyContact::where('parent_id', $parent->id)->get();
        
        // Get recent permissions
        $recentPermissions = StudentPermission::whereIn('student_id', $children->pluck('id'))
            ->with(['student', 'activity'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('parent.family.index', compact('children', 'familyProfile', 'emergencyContacts', 'recentPermissions'));
    }

    public function profile()
    {
        $parent = Auth::user();
        $familyProfile = FamilyProfile::firstOrCreate(
            ['parent_id' => $parent->id],
            [
                'family_name' => $parent->name . ' Family',
                'primary_contact_name' => $parent->name,
                'primary_contact_phone' => $parent->phone,
                'primary_contact_email' => $parent->email,
            ]
        );
        
        $emergencyContacts = EmergencyContact::where('parent_id', $parent->id)->get();
        
        return view('parent.family.profile', compact('familyProfile', 'emergencyContacts'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'family_name' => 'required|string|max:255',
            'primary_contact_name' => 'required|string|max:255',
            'primary_contact_phone' => 'required|string|max:20',
            'primary_contact_email' => 'required|email|max:255',
            'secondary_contact_name' => 'nullable|string|max:255',
            'secondary_contact_phone' => 'nullable|string|max:20',
            'secondary_contact_email' => 'nullable|email|max:255',
            'home_address' => 'nullable|string',
            'work_address' => 'nullable|string',
            'medical_information' => 'nullable|string',
            'special_instructions' => 'nullable|string',
        ]);

        $parent = Auth::user();
        $familyProfile = FamilyProfile::where('parent_id', $parent->id)->first();
        
        if ($familyProfile) {
            $familyProfile->update($request->all());
        } else {
            FamilyProfile::create(array_merge($request->all(), ['parent_id' => $parent->id]));
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Family profile updated successfully'
            ]);
        }

        return redirect()->route('parent.family.profile')
            ->with('success', 'Family profile updated successfully');
    }

    public function permissions()
    {
        $parent = Auth::user();
        $children = $parent->children()->with(['school', 'classModel'])->get();
        
        $permissions = StudentPermission::whereIn('student_id', $children->pluck('id'))
            ->with(['student', 'activity'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('parent.family.permissions', compact('children', 'permissions'));
    }

    public function updatePermission(Request $request, StudentPermission $permission)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,denied',
            'parent_notes' => 'nullable|string'
        ]);

        // Verify parent has access to this permission
        $parent = Auth::user();
        $studentIds = $parent->children()->pluck('id');
        
        if (!$studentIds->contains($permission->student_id)) {
            abort(403, 'Unauthorized access to permission');
        }

        $permission->update([
            'status' => $request->status,
            'parent_notes' => $request->parent_notes,
            'responded_at' => now()
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Permission updated successfully',
                'data' => $permission->load(['student', 'activity'])
            ]);
        }

        return redirect()->route('parent.family.permissions')
            ->with('success', 'Permission updated successfully');
    }

    public function emergencyContacts()
    {
        $parent = Auth::user();
        $emergencyContacts = EmergencyContact::where('parent_id', $parent->id)->get();
        
        return view('parent.family.emergency-contacts', compact('emergencyContacts'));
    }

    public function storeEmergencyContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'relationship' => 'required|string|max:100',
            'phone_primary' => 'required|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_authorized_pickup' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        $parent = Auth::user();
        
        EmergencyContact::create(array_merge($request->all(), [
            'parent_id' => $parent->id,
            'is_authorized_pickup' => $request->has('is_authorized_pickup')
        ]));

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Emergency contact added successfully'
            ]);
        }

        return redirect()->route('parent.family.emergency-contacts')
            ->with('success', 'Emergency contact added successfully');
    }

    public function updateEmergencyContact(Request $request, EmergencyContact $contact)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'relationship' => 'required|string|max:100',
            'phone_primary' => 'required|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'is_authorized_pickup' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        // Verify parent owns this contact
        if ($contact->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized access to emergency contact');
        }

        $contact->update(array_merge($request->all(), [
            'is_authorized_pickup' => $request->has('is_authorized_pickup')
        ]));

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Emergency contact updated successfully'
            ]);
        }

        return redirect()->route('parent.family.emergency-contacts')
            ->with('success', 'Emergency contact updated successfully');
    }

    public function deleteEmergencyContact(EmergencyContact $contact)
    {
        // Verify parent owns this contact
        if ($contact->parent_id !== Auth::id()) {
            abort(403, 'Unauthorized access to emergency contact');
        }

        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Emergency contact deleted successfully'
        ]);
    }

    public function preferences()
    {
        $parent = Auth::user();
        $familyProfile = FamilyProfile::where('parent_id', $parent->id)->first();
        
        return view('parent.family.preferences', compact('familyProfile'));
    }

    public function updatePreferences(Request $request)
    {
        $request->validate([
            'notification_preferences' => 'array',
            'communication_preferences' => 'array',
            'privacy_settings' => 'array'
        ]);

        $parent = Auth::user();
        $familyProfile = FamilyProfile::where('parent_id', $parent->id)->first();
        
        if (!$familyProfile) {
            $familyProfile = FamilyProfile::create([
                'parent_id' => $parent->id,
                'family_name' => $parent->name . ' Family'
            ]);
        }

        $familyProfile->update([
            'notification_preferences' => $request->notification_preferences ?? [],
            'communication_preferences' => $request->communication_preferences ?? [],
            'privacy_settings' => $request->privacy_settings ?? []
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Preferences updated successfully'
            ]);
        }

        return redirect()->route('parent.family.preferences')
            ->with('success', 'Preferences updated successfully');
    }

    public function childrenOverview()
    {
        $parent = Auth::user();
        $children = $parent->children()->with([
            'school', 
            'classModel.teacher',
            'grades' => function($query) {
                $query->latest()->limit(5);
            },
            'attendance' => function($query) {
                $query->where('date', '>=', now()->subDays(30));
            }
        ])->get();

        // Calculate statistics for each child
        $childrenData = $children->map(function($child) {
            return [
                'student' => $child,
                'academic_stats' => $child->getAcademicStatus(),
                'attendance_stats' => $child->getAttendanceStatistics(),
                'grade_stats' => $child->getGradeStatistics(),
                'recent_grades' => $child->getRecentGrades(3),
                'recent_attendance' => $child->getRecentAttendance(7)
            ];
        });

        return view('parent.family.children-overview', compact('childrenData'));
    }
}