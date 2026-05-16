<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/student-group-join.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="join-wrap">

    <?php if(session('success')): ?>
        <div style="margin-bottom:1.5rem;padding:1rem;background:#dcfce7;border:1px solid #bbf7d0;color:#166534;border-radius:12px;font-size:.9rem;">
            ✅ <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="join-card">

        <div class="join-icon">🔑</div>

        <div class="join-title">Join a Group</div>

        <div class="join-sub">
            Enter the 6-character code your teacher gave you to join their group and access assigned projects.
        </div>

        <form method="POST" action="<?php echo e(route('student.groups.join.store')); ?>">
            <?php echo csrf_field(); ?>

            <label class="form-label" for="join_code">
                Group Join Code
            </label>

            <input type="text"
                   id="join_code"
                   name="join_code"
                   class="code-input"
                   placeholder="ABC123"
                   maxlength="6"
                   value="<?php echo e(old('join_code')); ?>"
                   autocomplete="off"
                   autofocus
                   oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g,'')">

            <?php $__errorArgs = ['join_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="error-msg">⚠️ <?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

            <button type="submit" class="btn-join">
                🚀 Join Group
            </button>

        </form>

        <a href="<?php echo e(route('student.dashboard')); ?>" class="back-link">
            ← Back to Dashboard
        </a>

        <div class="how-it-works">
            <div class="how-title">How it works</div>
            <div class="how-step">
                <div class="how-num">1</div>
                <span>Your teacher creates a group and shares the 6-character join code with the class.</span>
            </div>
            <div class="how-step">
                <div class="how-num">2</div>
                <span>Enter the code above — it's case-insensitive.</span>
            </div>
            <div class="how-step">
                <div class="how-num">3</div>
                <span>You're instantly added to the group and all its assigned projects appear in your dashboard.</span>
            </div>
        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/student/group/join.blade.php ENDPATH**/ ?>