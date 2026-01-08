@extends('layouts.admin')

@section('title', 'Submit Grade')
@section('page-title', 'Assessment Registry')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-8">
        <a href="{{ route('admin.grades') }}" class="hover:text-emerald-600 transition-colors">Gradebook</a>
        <span class="opacity-30">/</span>
        <span class="text-emerald-500">New Performance Entry</span>
    </div>

    <div class="glass-card rounded-[2.5rem] overflow-hidden shadow-2xl border border-white">
        <div class="p-10 border-b border-slate-50 bg-slate-50/30">
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Academic Assessment</h2>
            <p class="text-sm text-slate-400 font-medium">Record a new student performance metric and evaluator feedback</p>
        </div>

        <form method="POST" action="{{ route('admin.grades.store') }}" class="p-10 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Student Enrollment</label>
                        <select name="enrollment_id" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-700">
                            <option value="">Awaiting selection...</option>
                            @foreach($enrollments as $enrollment)
                                <option value="{{ $enrollment->id }}">{{ $enrollment->student->user->name }} - {{ $enrollment->classroom->name }} ({{ $enrollment->classroom->subject->name }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Exam Name</label>
                        <input type="text" name="exam_name" required placeholder="e.g. Midterm 2024, Final Exam"
                               value="Final Exam 2024"
                               class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-800">
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Obtained</label>
                            <input type="number" name="grade" step="0.1" required placeholder="85"
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-black text-slate-800 tracking-wider">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Total</label>
                            <input type="number" name="total_marks" required value="100"
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-black text-slate-800">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Passing</label>
                            <input type="number" name="passing_marks" required value="33"
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-black text-slate-800">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Evaluator Remarks</label>
                        <textarea name="comments" rows="5" placeholder="Detailed feedback on student performance..."
                                  class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-slate-600"></textarea>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.grades') }}" class="btn-secondary">Dismiss</a>
                <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    Certify Assessment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
