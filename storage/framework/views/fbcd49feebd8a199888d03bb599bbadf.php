

<?php $__env->startSection('title', 'Modify Grade'); ?>
<?php $__env->startSection('page-title', 'Assessment Update'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-8">
        <a href="<?php echo e(route('admin.grades')); ?>" class="hover:text-emerald-600 transition-colors">Gradebook</a>
        <span class="opacity-30">/</span>
        <span class="text-emerald-500">Edit Archive</span>
    </div>

    <div class="glass-card rounded-[2.5rem] overflow-hidden shadow-2xl border border-white">
        <div class="p-10 border-b border-slate-50 bg-slate-50/30">
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Revise Assessment</h2>
            <p class="text-sm text-slate-400 font-medium">Updating performance metrics for <span class="text-emerald-600 font-black italic"><?php echo e($grade->enrollment?->student?->user?->name); ?></span></p>
        </div>

        <form method="POST" action="<?php echo e(route('admin.grades.update', $grade)); ?>" class="p-10 space-y-6">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Student Enrollment</label>
                        <select name="enrollment_id" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-bold text-slate-700">
                            <?php $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($enrollment->id); ?>" <?php echo e($grade->enrollment_id == $enrollment->id ? 'selected' : ''); ?>>
                                    <?php echo e($enrollment->student->user->name); ?> - <?php echo e($enrollment->classroom->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Percentage (%)</label>
                            <input type="number" name="score" value="<?php echo e(old('score', $grade->score)); ?>" step="0.1" max="100" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-black text-slate-800 tracking-wider">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Rank/Grade</label>
                            <input type="text" name="grade" value="<?php echo e(old('grade', $grade->grade)); ?>" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-black text-slate-800">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Evaluator Remarks</label>
                        <textarea name="comments" rows="5"
                                  class="w-full px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all font-medium text-slate-600"><?php echo e(old('comments', $grade->comments)); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="<?php echo e(route('admin.grades')); ?>" class="btn-secondary">Discard Changes</a>
                <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    Certify Update
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\git\school.twb-demo.site\resources\views/admin/grades/edit.blade.php ENDPATH**/ ?>