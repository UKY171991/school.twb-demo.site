

<?php $__env->startSection('title', 'Edit Classroom'); ?>
<?php $__env->startSection('page-title', 'Venue Modification'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto">
    <div class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-8">
        <a href="<?php echo e(route('admin.classrooms')); ?>" class="hover:text-indigo-600 transition-colors">Venues</a>
        <span class="opacity-30">/</span>
        <span class="text-indigo-500">Edit Archive</span>
    </div>

    <div class="glass-card rounded-[2.5rem] overflow-hidden shadow-2xl border border-white">
        <div class="p-10 border-b border-slate-50 bg-slate-50/30">
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Modify Classroom</h2>
            <p class="text-sm text-slate-400 font-medium">Updating resource assignments for <span class="text-indigo-600 font-black italic"><?php echo e($classroom->name); ?></span></p>
        </div>

        <form method="POST" action="<?php echo e(route('admin.classrooms.update', $classroom)); ?>" class="p-10 space-y-6">
            <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Room Name / ID</label>
                        <input type="text" name="name" value="<?php echo e(old('name', $classroom->name)); ?>" required
                               class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold text-slate-700">
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Student Capacity</label>
                        <input type="number" name="capacity" value="<?php echo e(old('capacity', $classroom->capacity)); ?>" required
                               class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold text-slate-700">
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Lead Faculty</label>
                        <select name="teacher_id" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700">
                            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($teacher->id); ?>" <?php echo e($classroom->teacher_id == $teacher->id ? 'selected' : ''); ?>><?php echo e($teacher->user->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Primary Subject</label>
                        <select name="subject_id" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-700">
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>" <?php echo e($classroom->subject_id == $subject->id ? 'selected' : ''); ?>><?php echo e($subject->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="<?php echo e(route('admin.classrooms')); ?>" class="btn-secondary">Discard Changes</a>
                <button type="submit" class="btn-primary">Release Update</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\git\school.twb-demo.site\resources\views/admin/classrooms/edit.blade.php ENDPATH**/ ?>