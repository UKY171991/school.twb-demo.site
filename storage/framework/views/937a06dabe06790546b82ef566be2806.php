

<?php $__env->startSection('title', 'Student Marksheet'); ?>
<?php $__env->startSection('page-title', 'Academic Performance Analysis'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Header with Student Profile -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center space-x-6">
            <div class="w-20 h-20 rounded-[2rem] gradient-primary flex items-center justify-center text-white text-3xl font-black shadow-2xl ring-4 ring-white">
                <?php echo e(substr($student->user->name, 0, 1)); ?>

            </div>
            <div>
                <h2 class="text-3xl font-black text-slate-800 tracking-tight"><?php echo e($student->user->name); ?></h2>
                <div class="flex items-center space-x-3 text-slate-500 font-bold text-sm mt-1 uppercase tracking-widest">
                    <span>ID: <?php echo e($student->student_id); ?></span>
                    <span class="opacity-20">•</span>
                    <span><?php echo e($student->school?->name ?? 'Main Academy'); ?></span>
                </div>
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="<?php echo e(route('admin.marksheets')); ?>" class="btn-secondary py-3 px-6 rounded-2xl font-black uppercase tracking-widest text-xs">
                Back to List
            </a>
            <?php if($examName): ?>
                <a href="<?php echo e(route('admin.marksheets.print', ['student' => $student->id, 'exam' => $examName])); ?>" target="_blank" class="btn-primary py-3 px-6 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Print Marksheet
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar: Exam Selection -->
        <div class="lg:col-span-1 space-y-6">
            <div class="glass-card rounded-[2rem] p-6 border border-white shadow-xl">
                <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-6">Available Examinations</h3>
                <div class="space-y-3">
                    <?php $__empty_1 = true; $__currentLoopData = $exams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('admin.marksheets.show', ['student' => $student->id, 'exam' => $exam])); ?>" 
                           class="flex items-center justify-between p-4 rounded-2xl transition-all <?php echo e($examName == $exam ? 'bg-indigo-600 text-white shadow-lg' : 'bg-slate-50 text-slate-600 hover:bg-slate-100 font-black'); ?>">
                            <span class="font-black tracking-tight text-sm"><?php echo e($exam); ?></span>
                            <?php if($examName == $exam): ?>
                                <svg class="w-5 h-5 text-indigo-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 1.414L10.586 9H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z" clip-rule="evenodd"></path></svg>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="py-10 text-center text-slate-400 font-bold uppercase tracking-widest text-[10px] italic">
                            No assessments recorded
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($grades->count() > 0): ?>
            <!-- Performance Summary -->
            <?php
                $totalObtained = $grades->sum('grade');
                $totalMax = $grades->sum('total_marks');
                $percentage = ($totalMax > 0) ? ($totalObtained / $totalMax) * 100 : 0;
                $passed = true;
                foreach($grades as $g) { if($g->grade < $g->passing_marks) $passed = false; }
            ?>
            <div class="glass-card rounded-[2.5rem] p-8 border border-white shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="relative">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Final Aggregate</div>
                    <div class="text-5xl font-black text-slate-800 tracking-tighter mb-2"><?php echo e(number_format($percentage, 1)); ?>%</div>
                    
                    <div class="flex items-center space-x-2 mb-6">
                        <?php if($passed): ?>
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-600 rounded-full text-[10px] font-black uppercase tracking-widest">PASSED</span>
                        <?php else: ?>
                            <span class="px-3 py-1 bg-rose-100 text-rose-600 rounded-full text-[10px] font-black uppercase tracking-widest">FAILED</span>
                        <?php endif; ?>
                        <span class="text-slate-400 font-bold text-[10px] uppercase"><?php echo e($totalObtained); ?> / <?php echo e($totalMax); ?> Marks</span>
                    </div>

                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full <?php echo e($passed ? 'bg-emerald-500' : 'bg-rose-500'); ?>" style="width: <?php echo e($percentage); ?>%"></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Main Content: Marksheet Table -->
        <div class="lg:col-span-3">
            <?php if($examName): ?>
                <div class="glass-card rounded-[2.5rem] overflow-hidden border border-white shadow-2xl">
                    <div class="p-10 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                        <div>
                            <h3 class="text-2xl font-black text-slate-800 tracking-tight"><?php echo e($examName); ?> Details</h3>
                            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">Official Academic Record</p>
                        </div>
                        <div class="w-16 h-16 rounded-2xl bg-white shadow-lg border border-slate-100 flex items-center justify-center">
                            <?php if($passed): ?>
                                <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <?php else: ?>
                                <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table-premium w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Subject</th>
                                    <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Marks Obtained</th>
                                    <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Total Marks</th>
                                    <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Percentage</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Result</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $__empty_1 = true; $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php
                                    $p = ($grade->total_marks > 0) ? ($grade->grade / $grade->total_marks) * 100 : 0;
                                    $isPass = $grade->grade >= $grade->passing_marks;
                                ?>
                                <tr class="group hover:bg-slate-50/30 transition-all">
                                    <td class="px-8 py-6">
                                        <div class="font-black text-slate-800 tracking-tight"><?php echo e($grade->enrollment->classroom->subject->name); ?></div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest"><?php echo e($grade->enrollment->classroom->name); ?></div>
                                    </td>
                                    <td class="px-6 py-6 text-center font-black text-slate-700"><?php echo e($grade->grade); ?></td>
                                    <td class="px-6 py-6 text-center font-bold text-slate-400"><?php echo e($grade->total_marks); ?></td>
                                    <td class="px-6 py-6">
                                        <div class="flex flex-col items-center">
                                            <div class="text-xs font-black text-slate-700 mb-1"><?php echo e(number_format($p, 1)); ?>%</div>
                                            <div class="w-16 h-1 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full <?php echo e($isPass ? 'bg-indigo-500' : 'bg-rose-500'); ?>" style="width: <?php echo e($p); ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <?php if($isPass): ?>
                                            <span class="inline-flex px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-emerald-100">Pass</span>
                                        <?php else: ?>
                                            <span class="inline-flex px-3 py-1 bg-rose-50 text-rose-600 rounded-lg text-[9px] font-black uppercase tracking-widest border border-rose-100">Fail</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center text-slate-300 font-bold uppercase tracking-widest italic">No marks recorded for this exam</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($grades->count() > 0): ?>
                    <div class="p-10 bg-slate-50/50 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Evaluator Remarks</h4>
                            <div class="p-6 bg-white rounded-3xl border border-slate-100 text-sm text-slate-600 font-medium italic leading-relaxed">
                                <?php $remarks = $grades->where('comments', '!=', null)->pluck('comments')->first(); ?>
                                "<?php echo e($remarks ?? 'Standard performance recorded for this examination period. The student has shown ' . ($passed ? 'satisfactory' : 'insufficient') . ' understanding of the curriculum.'); ?>"
                            </div>
                        </div>
                        <div class="flex flex-col justify-end items-end">
                            <div class="text-right space-y-1">
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Academic Controller</div>
                                <div class="w-32 h-12 border-b border-slate-200 mb-2"></div>
                                <div class="text-xs font-black text-slate-800 tracking-tight uppercase">Verified Record</div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="glass-card rounded-[2.5rem] border border-white shadow-2xl p-20 flex flex-col items-center justify-center text-center space-y-6">
                    <div class="w-24 h-24 rounded-[2rem] bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-400 shadow-inner">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 tracking-tight">Select an Examination</h3>
                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest mt-2">Choose an exam from the sidebar to view detailed performance</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\git\school.twb-demo.site\resources\views/admin/marksheets/show.blade.php ENDPATH**/ ?>