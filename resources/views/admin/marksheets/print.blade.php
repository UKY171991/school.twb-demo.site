<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marksheet - {{ $student->user->name }} - {{ $examName }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; margin: 0; background: white; }
            .print-shadow { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
        }
        .certificate-border { border: 20px solid transparent; border-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0H100V100H0V0ZM5 5V95H95V5H5Z' fill='%236366f1'/%3E%3C/svg%3E") 30 stretch; }
    </style>
</head>
<body class="bg-slate-100 p-8">
    <div class="max-w-[1000px] mx-auto bg-white p-12 shadow-2xl relative overflow-hidden print-shadow">
        <!-- Certificate-style Decoration -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-600/5 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-600/5 rounded-full -ml-32 -mb-32"></div>

        <!-- Header -->
        <div class="flex items-center justify-between border-b-4 border-indigo-600 pb-8 relative">
            <div class="flex items-center space-x-6">
                @if($student->school && $student->school->logo)
                    <img src="{{ asset('storage/' . $student->school->logo) }}" alt="Logo" class="h-24 w-24 object-contain">
                @else
                    <div class="w-24 h-24 bg-indigo-600 flex items-center justify-center text-white text-4xl font-black rounded-3xl">
                        {{ substr($student->school?->name ?? 'S', 0, 1) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight uppercase">{{ $student->school?->name ?? 'Academic Excellence Academy' }}</h1>
                    <p class="text-indigo-600 font-extrabold tracking-widest text-xs uppercase">{{ $student->school?->address ?? 'Administrative Headquarters, Global Education District' }}</p>
                    <p class="text-slate-400 font-bold text-[10px] mt-1 italic uppercase font-medium">RECOGNIZED EDUCATIONAL INSTITUTION • OFFICIAL TRANSCRIPT</p>
                </div>
            </div>
            <div class="text-right">
                <div class="bg-indigo-600 text-white px-6 py-2 rounded-xl text-xl font-black tracking-tighter uppercase mb-2">MARKSHEET</div>
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Serial No: AC-{{ date('Y') }}-{{ $student->id }}</div>
            </div>
        </div>

        <!-- Student Information -->
        <div class="grid grid-cols-2 gap-12 my-10 bg-slate-50 p-8 rounded-[2rem] border border-slate-100">
            <div class="space-y-4">
                <div class="flex flex-col">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Student Full Name</span>
                    <span class="text-xl font-black text-slate-800 tracking-tight">{{ $student->user->name }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Student Reg ID</span>
                    <span class="text-lg font-bold text-slate-600">{{ $student->student_id }}</span>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex flex-col">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Examination Type</span>
                    <span class="text-xl font-black text-indigo-600 tracking-tight">{{ $examName }}</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Academic Session</span>
                    <span class="text-lg font-bold text-slate-600 uppercase">{{ date('Y') }} - {{ date('Y') + 1 }}</span>
                </div>
            </div>
        </div>

        <!-- Marks Table -->
        <div class="mb-10 overflow-hidden rounded-[2rem] border-2 border-slate-200">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-800 text-white uppercase text-[10px] font-black tracking-widest">
                        <th class="px-8 py-5">Subject Description</th>
                        <th class="px-6 py-5 text-center">MM (Max)</th>
                        <th class="px-6 py-5 text-center">MO (Obt)</th>
                        <th class="px-6 py-5 text-center">Status</th>
                        <th class="px-8 py-5 text-right">Performance</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-slate-100">
                    @php 
                        $totalObtained = 0; 
                        $totalMax = 0; 
                        $allPassed = true;
                    @endphp
                    @foreach($grades as $grade)
                        @php
                            $totalObtained += $grade->grade;
                            $totalMax += $grade->total_marks;
                            $p = ($grade->total_marks > 0) ? ($grade->grade / $grade->total_marks) * 100 : 0;
                            $isPass = $grade->grade >= $grade->passing_marks;
                            if(!$isPass) $allPassed = false;
                        @endphp
                        <tr class="text-sm font-bold text-slate-700">
                            <td class="px-8 py-5">
                                <span class="font-extrabold text-slate-800">{{ $grade->enrollment->classroom->subject->name }}</span>
                                <br>
                                <span class="text-[9px] text-slate-400 uppercase tracking-widest">{{ $grade->enrollment->classroom->name }}</span>
                            </td>
                            <td class="px-6 py-5 text-center font-black">{{ (int)$grade->total_marks }}</td>
                            <td class="px-6 py-5 text-center font-black text-indigo-600">{{ $grade->grade }}</td>
                            <td class="px-6 py-5 text-center">
                                @if($isPass)
                                    <span class="text-emerald-600 uppercase text-[10px] tracking-tight">P</span>
                                @else
                                    <span class="text-rose-600 uppercase text-[10px] tracking-tight font-black">F</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <span class="text-[11px] font-black">{{ number_format($p, 1) }}%</span>
                                    <div class="w-20 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full {{ $isPass ? 'bg-indigo-500' : 'bg-rose-500' }}" style="width: {{ $p }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-indigo-50/50">
                        <td class="px-8 py-6 font-black text-indigo-700 uppercase tracking-widest text-sm">Grand Total Aggregate</td>
                        <td class="px-6 py-6 text-center font-black text-slate-800 text-lg">{{ $totalMax }}</td>
                        <td class="px-6 py-6 text-center font-black text-indigo-700 text-2xl tracking-tighter">{{ $totalObtained }}</td>
                        <td colspan="2" class="px-8 py-6 text-right">
                            <span class="bg-indigo-600 text-white px-5 py-2 rounded-xl text-lg font-black tracking-tighter">
                                {{ number_format(($totalMax > 0 ? ($totalObtained/$totalMax)*100 : 0), 1) }}%
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Result Footer -->
        <div class="grid grid-cols-3 gap-8 mt-12 mb-8">
            <div class="flex flex-col">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Final Result</span>
                <span class="text-2xl font-black {{ $allPassed ? 'text-emerald-600' : 'text-rose-600' }} uppercase tracking-widest">
                    {{ $allPassed ? 'PASSED' : 'PROMOTED WITH FAILURES' }}
                </span>
            </div>
            <div class="flex flex-col">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Issue Date</span>
                <span class="text-lg font-black text-slate-800 tracking-tight uppercase">{{ date('d M Y') }}</span>
            </div>
            <div class="flex flex-col items-end">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Digital Authenticity Code</span>
                <span class="text-xs font-mono font-bold text-slate-400 bg-slate-50 px-3 py-1 rounded-lg">VERIFIED-{{ strtoupper(Str::random(8)) }}</span>
            </div>
        </div>

        <!-- Signatures -->
        <div class="flex items-end justify-between mt-20 pt-10 border-t border-slate-100">
            <div class="text-center">
                <div class="w-48 h-12 border-b-2 border-slate-200 mb-2"></div>
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Class Teacher</div>
            </div>
            <div class="text-center relative">
                @if($student->school && $student->school->principal_signature)
                    <img src="{{ asset('storage/' . $student->school->principal_signature) }}" alt="Principal Signature" class="h-16 mb-[-10px] absolute bottom-2 left-1/2 -translate-x-1/2">
                @endif
                <div class="w-48 h-12 border-b-2 border-slate-200 mb-2"></div>
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Principal / Headmaster</div>
            </div>
            <div class="text-center">
                <div class="w-48 h-12 border-b-2 border-slate-200 mb-2 flex items-center justify-center">
                    <div class="w-20 h-20 rounded-full border-4 border-indigo-100 opacity-30 flex items-center justify-center text-[10px] font-black text-indigo-200 uppercase rotate-12">SEAL</div>
                </div>
                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Institutional Seal</div>
            </div>
        </div>

        <div class="mt-12 text-center no-print">
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-12 py-4 rounded-2xl shadow-2xl transition-all uppercase tracking-widest text-xs">
                Download / Print Document
            </button>
        </div>
    </div>
</body>
</html>
