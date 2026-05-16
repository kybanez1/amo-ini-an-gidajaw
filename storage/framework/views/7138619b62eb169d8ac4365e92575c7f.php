<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/student-teacher-join.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="wrap">

    <?php if(session('success')): ?>
        <div style="margin-bottom:1.5rem;padding:1rem;background:#dcfce7;border:1px solid #bbf7d0;
                    color:#166534;border-radius:12px;font-size:.9rem;">
            ✅ <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <div class="card">

        <div class="icon">🎓</div>
        <div class="title">Enter Teacher Code</div>
        <div class="sub">
            Ask your teacher for their 6-character personal code.
            Once entered, you'll appear in their student list and
            they can assign you to groups and projects.
        </div>

        <form method="POST" action="<?php echo e(route('student.teacher.join.store')); ?>">
            <?php echo csrf_field(); ?>

            <label class="label" for="teacher_code">Teacher Code</label>

            <input type="text"
                   id="teacher_code"
                   name="teacher_code"
                   class="code-input"
                   placeholder="ABC123"
                   maxlength="6"
                   value="<?php echo e(old('teacher_code')); ?>"
                   autocomplete="off"
                   autofocus
                   oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g,'')">

            <?php $__errorArgs = ['teacher_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="error">⚠️ <?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <button type="submit" class="btn-join">
                ✅ Register with Teacher
            </button>

        </form>

        <a href="<?php echo e(route('student.dashboard')); ?>" class="back-link">← Back to Dashboard</a>

    </div>

    
    <?php if($myTeachers->isNotEmpty()): ?>
    <div class="card">
        <div style="font-weight:700;margin-bottom:1rem;">👩‍🏫 My Teachers</div>
        <div class="teacher-list">
            <?php $__currentLoopData = $myTeachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="teacher-row">
                    <div class="avatar"><?php echo e(strtoupper(substr($teacher->name, 0, 1))); ?></div>
                    <div>
                        <div class="t-name"><?php echo e($teacher->name); ?></div>
                        <div class="t-email"><?php echo e($teacher->email); ?></div>
                    </div>
                    <div style="margin-left:auto;">
                        <span style="background:#dcfce7;color:#166534;padding:.3rem .7rem;
                                     border-radius:999px;font-size:.72rem;font-weight:700;">
                            ✅ Registered
                        </span>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/student/teacher/join.blade.php ENDPATH**/ ?>