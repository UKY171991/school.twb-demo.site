@extends('layouts.admin')

@section('title', 'Class Timetable')
@section('page-title', 'Weekly Schedule')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Class Timetables</h2>
            <p class="text-slate-500 font-medium">Manage weekly recurring lecture schedules and faculty assignments</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add Form -->
        <div class="lg:col-span-1">
            <div class="glass-card p-8 rounded-[2rem] shadow-xl sticky top-8 border border-white">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 rounded-xl gradient-secondary flex items-center justify-center text-white shadow-lg shadow-blue-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Add Session</h3>
                </div>

                <form method="POST" action="{{ route('admin.timetables.class.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Day of Week</label>
                        <select name="day_of_week" required class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-semibold text-slate-700">
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Classroom</label>
                        <select name="classroom_id" required class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-semibold text-slate-700">
                            @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Subject</label>
                        <select name="subject_id" required class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-semibold text-slate-700">
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Assigned Teacher</label>
                        <select name="teacher_id" required class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-semibold text-slate-700">
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->user?->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Campus</label>
                        <select name="school_id" required class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-semibold text-slate-700">
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Start</label>
                            <input type="time" name="start_time" required class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-slate-700">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">End</label>
                            <input type="time" name="end_time" required class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-slate-700">
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full py-4 mt-2" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                        Save Slot
                    </button>
                </form>
            </div>
        </div>

        <!-- Timetable View -->
        <div class="lg:col-span-2">
            <div class="glass-card rounded-[2rem] overflow-hidden shadow-2xl border border-white">
                <table class="table-premium w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Schedule</th>
                            <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Class / Subject</th>
                            <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Faculty</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($timetables as $timetable)
                        <tr class="hover:bg-slate-50/20 transition-colors">
                            <td class="px-8 py-6">
                                <div class="font-black text-slate-800 tracking-tight uppercase text-xs">{{ $timetable->day_of_week }}</div>
                                <div class="text-[10px] font-bold text-blue-500 uppercase tracking-wider mt-1">{{ date('H:i', strtotime($timetable->start_time)) }} - {{ date('H:i', strtotime($timetable->end_time)) }}</div>
                            </td>
                            <td class="px-6 py-6 font-bold text-slate-700">
                                <div class="text-sm">{{ $timetable->subject?->name }}</div>
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-0.5">{{ $timetable->classroom?->name }}</div>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xs">
                                        {{ substr($timetable->teacher?->user?->name, 0, 1) }}
                                    </div>
                                    <div class="text-xs font-bold text-slate-600 truncate max-w-[120px]">{{ $timetable->teacher?->user?->name }}</div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-8 py-20 text-center text-slate-400 font-bold uppercase tracking-widest">Weekly schedule is empty</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($timetables->hasPages())
                    <div class="px-8 py-6 border-t border-slate-100">
                        {{ $timetables->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
