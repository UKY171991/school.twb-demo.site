@extends('layouts.admin')

@section('title', 'Modify Grade')
@section('page-title', 'Assessment Update')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-8">
        <a href="{{ route('admin.grades') }}" class="hover:text-emerald-600 transition-colors">Gradebook</a>
        <span class="opacity-30">/</span>
        <span class="text-emerald-500">Edit Archive</span>
    </div>

    <div class="glass-card rounded-[2.5rem] overflow-hidden shadow-2xl border border-white">
        <div class="p-10 border-b border-slate-50 bg-slate-50/30">
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Revise Assessment</h2>
            <p class="text-sm text-slate-400 font-medium">Updating performance metrics for <span class="text-emerald-600 font-black italic">{{ $grade->enrollment?->student?->user?->name }}</span></p>
        </div>

        <form method="POST" action="{{ route('admin.grades.update', $grade) }}" class="p-10 space-y-6">
            @csrf @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Student Enrollment</label>
                        <select name="enrollment_id" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-700">
                            @foreach($enrollments as $enrollment)
                                <option value="{{ $enrollment->id }}" {{ $grade->enrollment_id == $enrollment->id ? 'selected' : '' }}>
                                    {{ $enrollment->student->user->name }} - {{ $enrollment->classroom->name }} ({{ $enrollment->classroom->subject->name }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Exam Name</label>
                        <input type="text" name="exam_name" value="{{ old('exam_name', $grade->exam_name) }}" required
                               class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-800">
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Obtained</label>
                            <input type="number" name="grade" value="{{ old('grade', $grade->grade) }}" step="0.1" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-black text-slate-800 tracking-wider">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Total</label>
                            <input type="number" name="total_marks" value="{{ old('total_marks', $grade->total_marks) }}" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-black text-slate-800">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Passing</label>
                            <input type="number" name="passing_marks" value="{{ old('passing_marks', $grade->passing_marks) }}" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-black text-slate-800">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Evaluator Remarks</label>
                        <textarea name="comments" rows="5"
                                  class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-slate-600">{{ old('comments', $grade->comments) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="{{ route('admin.grades') }}" class="btn-secondary">Discard Changes</a>
                <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    Certify Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
