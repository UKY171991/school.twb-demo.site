

<?php $__env->startSection('title', 'Edit School'); ?>
<?php $__env->startSection('page-title', 'Modify Institution'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto">
    <!-- Breadcrumbs -->
    <div class="flex items-center space-x-2 text-xs font-bold uppercase tracking-widest text-slate-400 mb-6">
        <a href="<?php echo e(route('admin.schools')); ?>" class="hover:text-indigo-600 transition-colors">Schools</a>
        <span class="opacity-50">/</span>
        <span class="text-indigo-500">Edit Profile</span>
    </div>

    <div class="glass-card rounded-3xl overflow-hidden shadow-2xl shadow-indigo-100/50">
        <div class="px-10 py-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Modify Institutional Profile</h2>
                <p class="text-sm text-slate-400 font-medium">Update settings for <strong><?php echo e($school->name); ?></strong></p>
            </div>
            <div class="w-14 h-14 bg-indigo-50 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-50 transition-transform hover:rotate-6">
                <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
        </div>

        <form method="POST" action="<?php echo e(route('admin.schools.update', $school)); ?>" class="p-10" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <!-- Identity Section -->
                <div class="space-y-8">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 014 0v1m-4 0a2 2 0 014 0v1"></path></svg>
                        </div>
                        <h4 class="text-sm font-black text-slate-700 uppercase tracking-wider">Identity Details</h4>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Official Name</label>
                            <input type="text" name="name" value="<?php echo e(old('name', $school->name)); ?>" required
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Unique Code</label>
                                <input type="text" name="code" value="<?php echo e(old('code', $school->code)); ?>" required
                                       class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700">
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Established</label>
                                <input type="date" name="established_date" value="<?php echo e(old('established_date', $school->established_date?->format('Y-m-d'))); ?>"
                                       class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Level</label>
                                <select name="level" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold text-slate-700">
                                    <option value="elementary" <?php echo e($school->level == 'elementary' ? 'selected' : ''); ?>>Elementary</option>
                                    <option value="middle" <?php echo e($school->level == 'middle' ? 'selected' : ''); ?>>Middle School</option>
                                    <option value="high" <?php echo e($school->level == 'high' ? 'selected' : ''); ?>>High School</option>
                                    <option value="k12" <?php echo e($school->level == 'k12' ? 'selected' : ''); ?>>K-12</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Type</label>
                                <select name="type" required class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-semibold text-slate-700">
                                    <option value="public" <?php echo e($school->type == 'public' ? 'selected' : ''); ?>>Public</option>
                                    <option value="private" <?php echo e($school->type == 'private' ? 'selected' : ''); ?>>Private</option>
                                    <option value="international" <?php echo e($school->type == 'international' ? 'selected' : ''); ?>>International</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Change Logo</label>
                            <div class="flex items-center space-x-4">
                                <?php if($school->logo): ?>
                                    <div class="w-16 h-16 rounded-2xl border border-slate-200 p-1 bg-white">
                                        <img src="<?php echo e(asset('storage/' . $school->logo)); ?>" class="w-full h-full object-cover rounded-xl shadow-sm">
                                    </div>
                                <?php endif; ?>
                                <div class="flex-1 relative group">
                                    <input type="file" name="logo" id="logo-input" class="hidden">
                                    <label for="logo-input" class="w-full flex items-center justify-between px-5 py-3 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl hover:bg-slate-100/50 hover:border-indigo-300 transition-all cursor-pointer">
                                        <span class="text-xs font-bold text-slate-400" id="logo-filename">Upload new logo</span>
                                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact & Administration Section -->
                <div class="space-y-8">
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <h4 class="text-sm font-black text-slate-700 uppercase tracking-wider">Administration</h4>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Principal Name</label>
                            <input type="text" name="principal_name" value="<?php echo e(old('principal_name', $school->principal_name)); ?>"
                                   class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Email</label>
                                <input type="email" name="email" value="<?php echo e(old('email', $school->email)); ?>"
                                       class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700">
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Phone</label>
                                <input type="text" name="phone" value="<?php echo e(old('phone', $school->phone)); ?>"
                                       class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Address</label>
                            <textarea name="address" rows="2" 
                                      class="w-full px-5 py-3 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700"><?php echo e(old('address', $school->address)); ?></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2 ml-1">Signature</label>
                            <div class="flex items-center space-x-4">
                                <?php if($school->principal_signature): ?>
                                    <div class="h-12 w-24 rounded-lg border border-slate-200 bg-white p-1">
                                        <img src="<?php echo e(asset('storage/' . $school->principal_signature)); ?>" class="w-full h-full object-contain overflow-hidden">
                                    </div>
                                <?php endif; ?>
                                <div class="flex-1 relative group">
                                    <input type="file" name="principal_signature" id="sig-input" class="hidden">
                                    <label for="sig-input" class="w-full flex items-center justify-between px-5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl hover:border-emerald-300 transition-all cursor-pointer group">
                                        <span class="text-xs font-bold text-slate-400" id="sig-filename">Update scan</span>
                                        <div class="w-6 h-6 rounded bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-indigo-50/30 rounded-2xl border border-indigo-100">
                            <label class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" name="is_active" value="1" <?php echo e($school->is_active ? 'checked' : ''); ?> class="sr-only peer">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </div>
                                <span class="ml-3 text-sm font-bold text-slate-700 uppercase tracking-wider">Account Active</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-10">
                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-3 ml-1">Profile Description</label>
                <textarea name="description" rows="4" 
                          class="w-full px-6 py-5 bg-slate-50 border border-slate-200 rounded-[2rem] focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-medium text-slate-700"><?php echo e(old('description', $school->description)); ?></textarea>
            </div>

            <!-- Footer Actions -->
            <div class="mt-12 pt-8 border-t border-slate-100 flex items-center justify-end space-x-4">
                <a href="<?php echo e(route('admin.schools')); ?>" class="btn-secondary">
                    Cancel Changes
                </a>
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Update Profile
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle File Names Display
    const inputs = {
        'logo-input': 'logo-filename',
        'sig-input': 'sig-filename'
    };

    Object.keys(inputs).forEach(id => {
        const input = document.getElementById(id);
        const label = document.getElementById(inputs[id]);
        if (input) {
            input.addEventListener('change', function(e) {
                if (this.files && this.files[0]) {
                    label.textContent = this.files[0].name;
                    label.classList.add('text-indigo-600', 'font-black');
                }
            });
        }
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\git\school.twb-demo.site\resources\views/admin/schools/edit.blade.php ENDPATH**/ ?>