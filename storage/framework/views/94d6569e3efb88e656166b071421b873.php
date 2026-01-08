

<?php $__env->startSection('title', 'Classrooms'); ?>
<?php $__env->startSection('page-title', 'Venue Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">
    <!-- Header Action -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Active Classrooms</h2>
            <p class="text-slate-500 font-medium">Manage learning environments and resource distribution</p>
        </div>
        <a href="<?php echo e(route('admin.classrooms.create')); ?>" class="btn-primary">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Construct New Room
        </a>
    </div>

    <!-- Table -->
    <div class="glass-card rounded-[2rem] overflow-hidden shadow-2xl shadow-slate-200/60 border border-white">
        <div class="table-responsive">
            <table class="table-premium w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 uppercase tracking-widest text-[11px] font-black text-slate-400">
                        <th class="px-8 py-6">Venue Identity</th>
                        <th class="px-6 py-6">Faculty Lead</th>
                        <th class="px-6 py-6">Subject Area</th>
                        <th class="px-6 py-6 text-center">Load Capacity</th>
                        <th class="px-8 py-6 text-right">Operations</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 italic-shadow">
                    <?php $__empty_1 = true; $__currentLoopData = $classrooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classroom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-indigo-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 font-black text-xs shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                    <?php echo e(substr($classroom->name, 0, 2)); ?>

                                </div>
                                <div class="font-black text-slate-700 tracking-tight uppercase text-sm"><?php echo e($classroom->name); ?></div>
                            </div>
                        </td>
                        <td class="px-6 py-6 transition-all">
                            <div class="font-bold text-slate-600"><?php echo e($classroom->teacher?->user?->name); ?></div>
                            <div class="text-[10px] font-black text-slate-400 uppercase tracking-tighter"><?php echo e($classroom->teacher?->employee_id); ?></div>
                        </td>
                        <td class="px-6 py-6">
                            <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-black uppercase tracking-widest border border-indigo-100"><?php echo e($classroom->subject?->name); ?></span>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <div class="inline-flex items-center px-4 py-1.5 bg-slate-50 rounded-2xl border border-slate-100">
                                <span class="text-sm font-black text-slate-800"><?php echo e($classroom->capacity); ?></span>
                                <span class="ml-2 text-[9px] font-black text-slate-400 uppercase tracking-widest">Seats</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end space-x-2 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="<?php echo e(route('admin.classrooms.edit', $classroom)); ?>" class="p-3 text-indigo-500 hover:bg-indigo-50 rounded-2xl transition-all" title="Edit Venue">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="<?php echo e(route('admin.classrooms.destroy', $classroom)); ?>" method="POST" class="confirm-action inline" data-confirm="Decommission venue '<?php echo e($classroom->name); ?>'?">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-3 text-rose-500 hover:bg-rose-50 rounded-2xl transition-all" title="Delete record">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                     <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <h3 class="text-lg font-black text-slate-400 uppercase tracking-widest">No classrooms established</h3>
                                <p class="text-slate-400 text-sm mt-1">Found no active learning environments in the database.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if($classrooms->hasPages()): ?>
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
            <?php echo e($classrooms->links()); ?>

        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\git\school.twb-demo.site\resources\views/admin/classrooms/index.blade.php ENDPATH**/ ?>