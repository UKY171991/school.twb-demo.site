

<?php $__env->startSection('title', 'Exam Timetable'); ?>
<?php $__env->startSection('page-title', 'Examination Schedule'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Examination Calendar</h2>
            <p class="text-slate-500 font-medium">Coordinate exam dates, halls and timing across school</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add Form -->
        <div class="lg:col-span-1">
            <div class="glass-card p-8 rounded-[2rem] shadow-xl sticky top-8 border border-white">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 rounded-xl gradient-primary flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <h3 class="text-lg font-black text-slate-800 uppercase tracking-tight">Schedule Exam</h3>
                </div>

                <form method="POST" action="<?php echo e(route('admin.timetables.exam.store')); ?>" class="space-y-5">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Exam Type</label>
                        <input type="text" name="exam_name" required placeholder="e.g. Mid-Term 2026" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Subject</label>
                        <select name="subject_id" required class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold text-slate-700">
                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Assigned Hall</label>
                        <select name="classroom_id" class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold text-slate-700">
                            <?php $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($classroom->id); ?>"><?php echo e($classroom->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Institutional Entity</label>
                        <select name="school_id" required class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold text-slate-700">
                            <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($school->id); ?>"><?php echo e($school->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Date</label>
                        <input type="date" name="exam_date" required class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Start Time</label>
                            <input type="time" name="start_time" required class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">End Time</label>
                            <input type="time" name="end_time" required class="w-full px-5 py-3 bg-slate-50 border border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700">
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full py-4 mt-4">
                        Create Schedule
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
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Date & Time</th>
                            <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Subject</th>
                            <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Hall/Room</th>
                            <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Exam</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php $__empty_1 = true; $__currentLoopData = $timetables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timetable): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50/20 transition-colors">
                            <td class="px-8 py-6 text-sm">
                                <div class="font-black text-slate-800"><?php echo e($timetable->exam_date->format('M d, Y')); ?></div>
                                <div class="text-[10px] font-bold text-indigo-500 uppercase tracking-wider mt-1"><?php echo e(date('H:i', strtotime($timetable->start_time))); ?> - <?php echo e(date('H:i', strtotime($timetable->end_time))); ?></div>
                            </td>
                            <td class="px-6 py-6 font-bold text-slate-700"><?php echo e($timetable->subject?->name); ?></td>
                            <td class="px-6 py-6">
                                <span class="px-3 py-1 bg-slate-100 rounded-lg text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                    <?php echo e($timetable->classroom?->name ?? 'TBA'); ?>

                                </span>
                            </td>
                            <td class="px-6 py-6">
                                <div class="font-black text-slate-800"><?php echo e($timetable->exam_name); ?></div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase truncate max-w-[120px]"><?php echo e($timetable->school?->name); ?></div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center text-slate-400 font-bold uppercase tracking-widest">No schedules created yet</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if($timetables->hasPages()): ?>
                    <div class="px-8 py-6 border-t border-slate-100">
                        <?php echo e($timetables->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\git\school.twb-demo.site\resources\views/admin/timetables/exam.blade.php ENDPATH**/ ?>