<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/teacher-project-create.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="wrap">

    <div class="card">

        <div class="header">
            ➕ Create New Project
        </div>

        <div class="body">

            
            <?php if($errors->any()): ?>
                <div class="error-box">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form
                method="POST"
                action="<?php echo e(route('teacher.projects.store')); ?>"
                enctype="multipart/form-data"
            >
                <?php echo csrf_field(); ?>

                
                <div class="field">
                    <label>Project Title</label>

                    <input
                        type="text"
                        name="title"
                        value="<?php echo e(old('title')); ?>"
                        required
                    >
                </div>

                
                <div class="field">
                    <label>Description</label>

                    <textarea
                        name="description"
                        rows="5"
                        required
                    ><?php echo e(old('description')); ?></textarea>
                </div>

                
                <div class="field">

                    <label>Instruction File <span style="color:#9ca3af;font-weight:400;">(optional)</span></label>

                    
                    <div style="display:flex;gap:0;margin-bottom:.75rem;border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;width:fit-content;">
                        <button type="button" id="tab-file-create"
                                onclick="switchTab('create','file')"
                                style="padding:.5rem 1.1rem;font-size:.82rem;font-weight:600;border:none;cursor:pointer;background:#4f46e5;color:white;">
                            📎 Upload File
                        </button>
                        <button type="button" id="tab-link-create"
                                onclick="switchTab('create','link')"
                                style="padding:.5rem 1.1rem;font-size:.82rem;font-weight:600;border:none;cursor:pointer;background:white;color:#6b7280;">
                            🔗 Paste Link
                        </button>
                    </div>

                    
                    <div id="panel-file-create">
                        <div class="file-box">
                            <input type="file" name="instruction_file">
                            <div class="file-help">
                                Upload PDF, DOCX, PPT, ZIP, Images or any project instructions. Maximum file size: 20MB
                            </div>
                        </div>
                    </div>

                    
                    <div id="panel-link-create" style="display:none;">
                        <input type="url"
                               name="instruction_link"
                               placeholder="https://drive.google.com/... or any URL"
                               value="<?php echo e(old('instruction_link')); ?>"
                               style="width:100%;padding:.75rem 1rem;border:1.5px solid #e5e7eb;border-radius:10px;font-size:.9rem;box-sizing:border-box;">
                        <div class="file-help" style="margin-top:6px;">
                            Paste a Google Drive, Dropbox, OneDrive link, or any URL.
                        </div>
                    </div>

                </div>

                
                <div class="field">
                    <label>Max Score</label>

                    <input
                        type="number"
                        name="max_score"
                        min="1"
                        max="1000"
                        value="<?php echo e(old('max_score')); ?>"
                        required
                    >
                </div>

                
                <div class="field">

                    <label>Assign to Group</label>

                    <select
                        name="group_id"
                        id="groupSelect"
                        required
                    >
                        <option value="">
                            -- Select Group --
                        </option>

                        <?php $__currentLoopData = auth()->user()->groups()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <option
                                value="<?php echo e($group->id); ?>"
                                <?php echo e(old('group_id') == $group->id ? 'selected' : ''); ?>

                            >
                                <?php echo e($group->name); ?>

                            </option>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="field">

                    <label>Start Date</label>

                    <input
                        type="datetime-local"
                        name="start_date"
                        value="<?php echo e(old('start_date')); ?>"
                        required
                    >
                </div>

                
                <div class="field">

                    <label>Due Date</label>

                    <input
                        type="datetime-local"
                        name="due_date"
                        value="<?php echo e(old('due_date')); ?>"
                        required
                    >
                </div>

                
                <div class="field">

                    <label>Status</label>

                    <select
                        name="status"
                        required
                    >
                        <option
                            value="draft"
                            <?php echo e(old('status', 'draft') == 'draft' ? 'selected' : ''); ?>

                        >
                            Draft
                        </option>

                        <option
                            value="published"
                            <?php echo e(old('status') == 'published' ? 'selected' : ''); ?>

                        >
                            Published
                        </option>

                        <option
                            value="ongoing"
                            <?php echo e(old('status') == 'ongoing' ? 'selected' : ''); ?>

                        >
                            Ongoing
                        </option>

                        <option
                            value="completed"
                            <?php echo e(old('status') == 'completed' ? 'selected' : ''); ?>

                        >
                            Completed
                        </option>

                    </select>
                </div>

                
                <hr style="margin:2rem 0;border:none;border-top:1px solid #e5e7eb;">

                <div class="field">

                    <label style="font-size:1rem;font-weight:700;">
                        📋 Assign Tasks
                    </label>

                    <div id="task-wrapper">

                        
                        <div class="task-card">

                            <div class="field">
                                <label>Task Title</label>

                                <input
                                    type="text"
                                    name="tasks[0][title]"
                                    placeholder="Enter task title"
                                >
                            </div>

                            <div class="field">
                                <label>Task Description</label>

                                <textarea
                                    name="tasks[0][description]"
                                    rows="3"
                                    placeholder="Enter task details"
                                ></textarea>
                            </div>

                            <div class="field">
                                <label>Task Due Date</label>
                                <input type="datetime-local" name="tasks[0][due_date]">
                            </div>

                            <div class="field">
                                <label>Max Points
                                    <span style="color:#9ca3af;font-weight:400;">(default: 100)</span>
                                </label>
                                <input type="number"
                                       name="tasks[0][max_points]"
                                       min="1"
                                       max="10000"
                                       placeholder="100"
                                       value="<?php echo e(old('tasks.0.max_points', 100)); ?>"
                                       style="width:100%;">
                            </div>

                        </div>

                    </div>

                    
                    <button
                        type="button"
                        class="btn btn-add"
                        id="add-task-btn"
                    >
                        ➕ Add Another Task
                    </button>

                </div>

                
                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    🚀 Create Project
                </button>

            </form>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/js/pages/teacher-project-create.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/teacher/projects/create.blade.php ENDPATH**/ ?>