

<?php $__env->startSection('title', 'Student ID Cards'); ?>
<?php $__env->startSection('page-title', 'Identification Tags'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 font-sans">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Digital ID Protocol</h2>
            <p class="text-slate-500 font-medium">Verify and generate institutional identification for all active students</p>
        </div>
        <button class="btn-primary" onclick="window.print()" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print All Batches
        </button>
    </div>

    <!-- ID Card Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-10">
        <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="relative group">
            <!-- The Card -->
            <div class="id-card-modern bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-slate-100 overflow-hidden transition-all duration-500 hover:-translate-y-2 hover:shadow-indigo-200/40">
                <!-- Header with School Name -->
                <div class="h-28 gradient-primary p-6 flex items-start justify-between relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="text-[10px] font-black text-white/70 uppercase tracking-[0.2em] mb-1">Official ID</div>
                        <h4 class="text-lg font-black text-white tracking-tighter max-w-[180px] leading-tight"><?php echo e($student->school?->name ?? 'SMS ACADEMY'); ?></h4>
                    </div>
                    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center relative z-10">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <!-- Decorative Circles -->
                    <div class="absolute -right-4 -top-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                </div>

                <!-- Profile Image & Info -->
                <div class="px-8 pb-10 pt-16 relative">
                    <!-- Absolute Centered Avatar -->
                    <div class="absolute -top-12 left-1/2 -translate-x-1/2 w-24 h-24 rounded-3xl border-4 border-white shadow-xl overflow-hidden bg-slate-100 flex items-center justify-center">
                        <span class="text-3xl font-black text-slate-300 font-sans"><?php echo e(substr($student->user->name, 0, 1)); ?></span>
                    </div>

                    <div class="text-center space-y-4">
                        <div>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight"><?php echo e($student->user->name); ?></h3>
                            <p class="text-[11px] font-black text-indigo-500 uppercase tracking-widest mt-1">Active Student</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 py-4 border-y border-slate-100 font-sans">
                            <div class="text-left">
                                <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Enrollment No</span>
                                <span class="text-sm font-black text-slate-700 tracking-tighter"><?php echo e($student->student_id); ?></span>
                            </div>
                            <div class="text-right">
                                <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">Valid Thru</span>
                                <span class="text-sm font-black text-slate-700 tracking-tighter">Dec 2026</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-center">
                            <div class="w-full h-12 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center p-3 opacity-60">
                                <!-- Mock barcode -->
                                <div class="flex space-x-0.5 h-full overflow-hidden">
                                    <?php for($i=0; $i<40; $i++): ?>
                                        <div class="bg-slate-800" style="width: <?php echo e(rand(1, 4)); ?>px;"></div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Floating QR Overlay (Hidden initially, show on hover) -->
            <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-4 group-hover:translate-x-0">
                <div class="w-12 h-12 bg-white rounded-xl shadow-xl p-2 border border-slate-100">
                    <svg viewBox="0 0 24 24" class="text-slate-800" fill="currentColor"><path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h3v2h-3v-2zm-3 0h2v2h-2v-2zm3 3h3v2h-3v-2zm-3 3h2v2h-2v-2zm3-3h3v2h-3v-2zm-3 0h2v2h-2v-2z"></path></svg>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full py-20 text-center glass-card rounded-[3rem]">
            <p class="text-slate-400 font-black uppercase tracking-widest">No student database found</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="mt-10 font-bold">
        <?php echo e($students->links()); ?>

    </div>
</div>

<style>
@media print {
    .sidebar, .top-nav, .btn-primary, .pagination-nav { display: none !important; }
    .content-area { margin: 0 !important; padding: 0 !important; }
    .id-card-modern { break-inside: avoid; border: 1px solid #e2e8f0; }
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\git\school.twb-demo.site\resources\views/admin/idcards/index.blade.php ENDPATH**/ ?>