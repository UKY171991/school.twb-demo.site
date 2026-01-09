

<?php $__env->startSection('title', 'Edit Student'); ?>
<?php $__env->startSection('page-title', 'Modify Student Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto">
    <!-- Breadcrumbs -->
    <div class="flex items-center space-x-2 text-xs font-bold uppercase tracking-widest text-slate-400 mb-6">
        <a href="<?php echo e(route('admin.students')); ?>" class="hover:text-indigo-600 transition-colors">Students</a>
        <span class="opacity-50">/</span>
        <span class="text-indigo-500">Edit Profile</span>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden shadow-2xl shadow-blue-100/50">
        <div class="px-10 py-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Student Profile Update</h2>
                <p class="text-sm text-slate-400 font-medium">Modifying records for <strong><?php echo e($student->user->name); ?></strong></p>
            </div>
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-50 transition-transform hover:scale-110">
                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('admin.students.update', $student)); ?>" class="p-10" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Personal Details -->
                <div class="space-y-8">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <h4 class="text-sm font-black text-slate-700 uppercase tracking-wider">Personal Record</h4>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Full Name</label>
                            <input type="text" name="name" value="<?php echo e(old('name', $student->user->name)); ?>" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-slate-700">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="<?php echo e(old('date_of_birth', $student->date_of_birth)); ?>"
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-slate-700">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Residential Address</label>
                            <textarea name="address" rows="3" 
                                      class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-slate-700"><?php echo e(old('address', $student->address)); ?></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Update Photograph</label>
                            <div class="flex items-center space-x-6 bg-white p-4 rounded-2xl border-2 border-slate-100">
                                <?php if($student->image): ?>
                                    <div class="relative group">
                                        <img src="<?php echo e(asset('storage/' . $student->image)); ?>" alt="Current image" class="w-16 h-16 rounded-xl object-cover ring-2 ring-blue-100">
                                        <div class="absolute inset-0 bg-blue-600/10 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    </div>
                                <?php else: ?>
                                    <div class="w-16 h-16 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="flex-1">
                                    <input type="file" name="image" id="image" class="hidden" accept="image/*">
                                    <label for="image" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-xl text-[10px] font-black uppercase tracking-widest cursor-pointer hover:bg-blue-100 transition-colors">
                                        <svg class="w-3 h-3 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        Change Photo
                                    </label>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase mt-2 ml-1 italic tracking-tight">Max size 2MB (PNG/JPG)</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Credentials -->
                <div class="space-y-8">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0014 20.3m5.988-5.904A10.033 10.033 0 0119.3 20M12 11h.01M12 11l-.01.01M12 11L12 11z"></path></svg>
                        </div>
                        <h4 class="text-sm font-black text-slate-700 uppercase tracking-wider">Account Data</h4>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Student ID / Enrollment No.</label>
                            <input type="text" name="student_id" value="<?php echo e(old('student_id', $student->student_id)); ?>" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-black text-slate-800 tracking-wider">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Official Email Address</label>
                            <input type="email" name="email" value="<?php echo e(old('email', $student->user->email)); ?>" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-slate-700">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Emergency Contact</label>
                            <input type="text" name="phone" value="<?php echo e(old('phone', $student->phone)); ?>"
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all font-medium text-slate-700">
                        </div>

                        <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 italic text-xs text-amber-700 font-medium">
                            * Password remains unchanged unless modified via secure reset protocol.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="mt-12 pt-8 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="<?php echo e(route('admin.students')); ?>" class="btn-secondary">
                    Cancel Changes
                </a>
                <button type="submit" class="btn-primary" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Update Record
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\git\school.twb-demo.site\resources\views/admin/students/edit.blade.php ENDPATH**/ ?>