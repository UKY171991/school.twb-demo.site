@extends('layouts.admin')

@section('title', 'Teacher Salaries')
@section('page-title', 'Payroll Management')

@section('content')
<div class="space-y-8">
    <!-- Header Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Teacher Salaries</h2>
            <p class="text-slate-500 font-medium">Manage faculty payroll and disbursement history</p>
        </div>
        <a href="{{ route('admin.salaries.create') }}" class="btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Process New Salary
        </a>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="glass-card p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3m-3-6h6m6 1a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Total Paid</p>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">₹{{ number_format($salaries->where('status', 'paid')->sum('amount'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="glass-card p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Pending Disburtion</p>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">₹{{ number_format($salaries->where('status', 'pending')->sum('amount'), 2) }}</h3>
                </div>
            </div>
        </div>
        <div class="glass-card p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Active Faculty</p>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">{{ $salaries->unique('teacher_id')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="glass-card rounded-[2rem] overflow-hidden shadow-2xl shadow-slate-200/60 border border-white">
        <div class="table-responsive">
            <table class="table-premium w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-widest">Teacher</th>
                        <th class="px-6 py-6 text-[11px] font-black text-slate-400 uppercase tracking-widest">School</th>
                        <th class="px-6 py-6 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Month/Year</th>
                        <th class="px-6 py-6 text-[11px] font-black text-slate-400 uppercase tracking-widest">Amount</th>
                        <th class="px-6 py-6 text-[11px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($salaries as $salary)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl gradient-primary flex items-center justify-center text-white font-black text-lg shadow-lg shadow-indigo-100">
                                    {{ substr($salary->teacher?->user?->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-black text-slate-800 tracking-tight">{{ $salary->teacher?->user?->name }}</div>
                                    <div class="text-xs font-bold text-slate-400 uppercase tracking-tighter">{{ $salary->teacher?->employee_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 font-bold text-slate-600">{{ $salary->school?->name }}</td>
                        <td class="px-6 py-6 text-center">
                            <span class="px-3 py-1 bg-slate-100 rounded-full text-[11px] font-black text-slate-500 uppercase tracking-widest">
                                {{ $salary->month }} {{ $salary->year }}
                            </span>
                        </td>
                        <td class="px-6 py-6">
                            <div class="font-black text-slate-800">₹{{ number_format($salary->amount, 2) }}</div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            @if($salary->status === 'paid')
                                <span class="badge-premium bg-emerald-50 text-emerald-600 border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>
                                    Disbursed
                                </span>
                            @else
                                <span class="badge-premium bg-amber-50 text-amber-600 border-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-2"></span>
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <form action="{{ route('admin.salaries.destroy', $salary) }}" method="POST" class="confirm-action inline" data-confirm="Are you sure you want to delete this salary record?">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-3 text-rose-500 hover:bg-rose-50 rounded-2xl transition-all" title="Delete record">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <h3 class="text-lg font-black text-slate-400 uppercase tracking-widest">No salary records found</h3>
                                <p class="text-slate-400 text-sm mt-1">Start by processing a new salary disbursement.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
            {{ $salaries->links() }}
        </div>
    </div>
</div>
@endsection
