<?php $__env->startSection('title', 'Create Group'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/teacher-group-create.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="pms-page">
    <div class="breadcrumb">
        <a href="<?php echo e(route('teacher.dashboard')); ?>">Dashboard</a> ›
        <a href="<?php echo e(route('teacher.groups.index')); ?>">Groups</a> › Create
    </div>
    <div class="page-title">👥 Create New Group</div>

    <?php if($errors->any()): ?>
    <div class="alert-error">
        <strong>Please fix the following:</strong>
        <ul style="margin:0.5rem 0 0;padding-left:1.25rem;">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="card">
        <form method="POST" action="<?php echo e(route('teacher.groups.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-field">
                <label>Group Name *</label>
                <input type="text" name="name" value="<?php echo e(old('name')); ?>" required placeholder="e.g. Group Alpha — BSIT 3A" />
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-field">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Brief description of this group..." style="resize:vertical;"><?php echo e(old('description')); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="field-error"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-field">
                <label>Add Students <span style="color:#9ca3af;font-weight:400;">(optional — can add later)</span></label>
                <?php if($students->isEmpty()): ?>
                <div style="padding:1rem;background:#f9fafb;border-radius:8px;color:#9ca3af;font-size:0.85rem;text-align:center;">
                    No students registered yet. You can add students after they register.
                </div>
                <?php else: ?>
                <div class="student-dropdown">
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="student-option">
                        <input type="checkbox" name="students[]" value="<?php echo e($student->id); ?>"
                               <?php echo e(in_array($student->id, old('students', [])) ? 'checked' : ''); ?> />
                        <div>
                            <div style="font-weight:500;"><?php echo e($student->name); ?></div>
                            <div style="font-size:0.75rem;color:#9ca3af;"><?php echo e($student->student_id ?? 'No ID'); ?> · <?php echo e($student->email); ?> · <?php echo e($student->department ?? 'No course'); ?></div>
                        </div>
                    </label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="form-hint"><?php echo e($students->count()); ?> student(s) available</div>
                <?php endif; ?>
            </div>

            <div class="form-actions">
                <a href="<?php echo e(route('teacher.groups.index')); ?>" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary">Create Group</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/teacher/group/create_group.blade.php ENDPATH**/ ?>