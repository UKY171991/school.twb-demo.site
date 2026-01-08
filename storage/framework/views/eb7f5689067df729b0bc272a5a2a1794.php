

<?php $__env->startSection('title', 'Academic Grades'); ?>
<?php $__env->startSection('page-title', 'Assessment Records'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8 font-sans">
    <!-- Header Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Academic Gradebook</h2>
            <p class="text-slate-500 font-medium">Coordinate student performance metrics across different classrooms</p>
        </div>
        <a href="<?php echo e(route('admin.grades.create')); ?>" class="btn-primary" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Submit New Grade
        </a>
    </div>

    <!-- Table -->
    <div class="glass-card rounded-[2rem] overflow-hidden shadow-2xl shadow-slate-200/60 border border-white">
        <div class="table-responsive">
            <table class="table-premium w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 uppercase tracking-widest text-[11px] font-black text-slate-400">
                        <th class="px-8 py-6">Student Scholar</th>
                        <th class="px-6 py-6">Classroom Context</th>
                        <th class="px-6 py-6 text-center">Score / Grade</th>
                        <th class="px-6 py-6">Evaluator Remarks</th>
                        <th class="px-8 py-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 italic-shadow">
                    <?php $__empty_1 = true; $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-emerald-50/20 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-xl gradient-secondary flex items-center justify-center text-white font-black text-xs shadow-md">
                                    <?php echo e(substr($grade->enrollment?->student?->user?->name, 0, 1)); ?>

                                </div>
                                <div>
                                    <div class="font-black text-slate-700 tracking-tight"><?php echo e($grade->enrollment?->student?->user?->name); ?></div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest"><?php echo e($grade->enrollment?->student?->student_id); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6">
                            <span class="px-3 py-1 bg-slate-100 rounded-lg text-[10px] font-black text-slate-500 uppercase tracking-widest border border-slate-200"><?php echo e($grade->enrollment?->classroom?->name); ?></span>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <div class="inline-block px-4 py-2 bg-emerald-50 text-emerald-700 rounded-2xl border border-emerald-100 font-black text-lg tracking-tighter shadow-sm">
                                <?php echo e($grade->score); ?><span class="text-xs ml-0.5 opacity-60">%</span>
                            </div>
                            <div class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mt-1"><?php echo e($grade->grade); ?> Rank</div>
                        </td>
                        <td class="px-6 py-6">
                            <p class="text-xs font-medium text-slate-400 line-clamp-1 max-w-[200px]" title="<?php echo e($grade->comments); ?>">
                                <?php echo e($grade->comments ?? 'Regular academic progress noted.'); ?>

                            </p>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="<?php echo e(route('admin.grades.edit', $grade)); ?>" class="p-3 text-indigo-500 hover:bg-slate-100 rounded-2xl transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>
                                <form action="<?php echo e(route('admin.grades.destroy', $grade)); ?>" method="POST" class="confirm-action inline" data-confirm="Erase this assessment record?">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-3 text-rose-500 hover:bg-rose-50 rounded-2xl transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center opacity-40">
                                <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <h3 class="text-sm font-black uppercase tracking-widest text-slate-400">No grades recorded</h3>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($grades->hasPages()): ?>
        <div class="px-8 py-6 border-t border-slate-100">
            <?php echo e($grades->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\git\school.twb-demo.site\resources\views/admin/grades/index.blade.php ENDPATH**/ ?>