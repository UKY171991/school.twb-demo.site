

<?php $__env->startSection('title', 'Marksheets'); ?>
<?php $__env->startSection('page-title', 'Academic Results'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Student Marksheets</h2>
            <p class="text-slate-500 font-medium">Generate and view comprehensive academic performance reports</p>
        </div>
    </div>

    <!-- Student List for Marksheets -->
    <div class="glass-card rounded-[2rem] overflow-hidden shadow-2xl border border-white">
        <div class="table-responsive">
            <table class="table-premium w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-widest">Student Profile</th>
                        <th class="px-6 py-6 text-[11px] font-black text-slate-400 uppercase tracking-widest">Enrollment Status</th>
                        <th class="px-6 py-6 text-[11px] font-black text-slate-400 uppercase tracking-widest">Performance</th>
                        <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $allGrades = $student->enrollments->flatMap->grades;
                        $totalGrades = $allGrades->count();
                        $sumObtained = $allGrades->sum('grade');
                        $sumTotal = $allGrades->sum('total_marks');
                        $avgGrade = ($sumTotal > 0) ? ($sumObtained / $sumTotal) * 100 : 0;
                    ?>
                    <tr class="hover:bg-slate-50/20 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-black text-lg shadow-sm">
                                    <?php echo e(substr($student->user->name, 0, 1)); ?>

                                </div>
                                <div>
                                    <div class="font-black text-slate-800 tracking-tight"><?php echo e($student->user->name); ?></div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?php echo e($student->student_id); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <?php if($student->enrollments->count() > 0): ?>
                                <?php $__currentLoopData = $student->enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="inline-block px-3 py-1 bg-slate-100 rounded-full text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1"><?php echo e($enrollment->classroom?->name); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <span class="text-xs font-bold text-slate-300 italic uppercase">Not Enrolled</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-6">
                            <?php if($totalGrades > 0): ?>
                                <div class="flex flex-col">
                                    <div class="flex items-center space-x-2">
                                        <div class="text-sm font-black text-slate-700"><?php echo e(number_format($avgGrade, 1)); ?>%</div>
                                        <div class="flex-1 h-1.5 w-24 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full gradient-primary" style="width: <?php echo e($avgGrade); ?>%"></div>
                                        </div>
                                    </div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter mt-1"><?php echo e($totalGrades); ?> Assessments recorded</div>
                                </div>
                            <?php else: ?>
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest">No Data</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <a href="<?php echo e(route('admin.marksheets.show', $student)); ?>" class="inline-flex items-center btn-primary py-2 px-4 shadow-md bg-indigo-600 hover:bg-indigo-700 text-xs font-black uppercase tracking-widest rounded-xl transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                Analyze Performance
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center text-slate-400 font-bold uppercase tracking-widest italic">No students found to generate marksheets</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100 font-bold">
            <?php echo e($students->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\git\school.twb-demo.site\resources\views/admin/marksheets/index.blade.php ENDPATH**/ ?>