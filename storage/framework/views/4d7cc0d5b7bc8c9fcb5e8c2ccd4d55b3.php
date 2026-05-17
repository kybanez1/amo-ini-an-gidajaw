<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/teacher-groups.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('title', 'My Groups'); ?>

<?php $__env->startSection('content'); ?>
<div class="pms-page">

    <div class="page-header">
        <div>
            <div class="page-title">👥 My Groups</div>
            <div class="page-subtitle">Organize your students into groups</div>
        </div>

        
        <button class="btn-primary" id="openCreateModal">+ New Group</button>
    </div>

    <?php if(session('success')): ?>
        <div class="alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert-error"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="groups-grid">

        <?php $__empty_1 = true; $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

            <div class="group-card">

                <div class="group-header">
                    <div class="group-icon">👥</div>
                    <span class="badge">ACTIVE</span>
                </div>

                <div class="group-name"><?php echo e($group->name); ?></div>

                <div class="group-desc">
                    <?php if($group->section): ?>
                        🏷️ <?php echo e($group->section->name); ?>

                        <?php if($group->section->school_year): ?> · <?php echo e($group->section->school_year); ?><?php endif; ?>
                        <?php if($group->section->semester): ?> · <?php echo e($group->section->semester); ?><?php endif; ?>
                    <?php else: ?>
                        <span style="color:#9ca3af;">No section assigned</span>
                    <?php endif; ?>
                </div>

                <div class="group-stats">
                    <div class="stat">
                        <div class="stat-value"><?php echo e($group->students->count()); ?></div>
                        <div class="stat-label">Students</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value"><?php echo e($group->projects()->count()); ?></div>
                        <div class="stat-label">Projects</div>
                    </div>
                    <div class="stat">
                        <div class="stat-value"><?php echo e($group->created_at->format('M d')); ?></div>
                        <div class="stat-label">Created</div>
                    </div>
                </div>

                <div class="group-actions">
                    <a href="<?php echo e(route('teacher.groups.show', $group->id)); ?>" class="btn-outline">👁 View</a>
                    <a href="<?php echo e(route('teacher.groups.edit', $group->id)); ?>" class="btn-outline">✏️ Edit</a>
                    <form method="POST"
                          action="<?php echo e(route('teacher.groups.destroy', $group->id)); ?>"
                          onsubmit="return confirm('Delete this group?')">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn-danger">🗑 Delete</button>
                    </form>
                </div>

            </div>

        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <div class="empty-state">
                <div style="font-size:60px;">👥</div>
                <h3>No groups yet</h3>
                <p>Create your first student group.</p>
                <button class="btn-primary" id="openCreateModal2">+ Create Group</button>
            </div>

        <?php endif; ?>

    </div>

</div>


<div id="createGroupModal">
    <div class="modal-box" id="createGroupModalBox">

        <div class="modal-head">
            <div class="modal-head-title">👥 Create New Group</div>
            <button type="button" class="modal-close-btn" id="closeModalBtn">×</button>
        </div>

        <div class="modal-body">

            <?php if($errors->any()): ?>
            <div style="background:#fef2f2;border:1px solid #fecaca;color:#dc2626;padding:.75rem 1rem;border-radius:8px;font-size:.82rem;margin-bottom:1rem;">
                <strong>Please fix the following:</strong>
                <ul style="margin:.4rem 0 0;padding-left:1.2rem;">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($e); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('teacher.groups.store')); ?>" id="createGroupForm">
                <?php echo csrf_field(); ?>

                
                <div class="form-group">
                    <label>Group Name <span style="color:#ef4444;">*</span></label>
                    <input type="text"
                           name="name"
                           id="groupNameInput"
                           value="<?php echo e(old('name')); ?>"
                           placeholder="e.g. BSIT 3A — Group Alpha"
                           required>
                </div>

                
                <div class="form-group">
                    <label>Class Section <span style="color:#9ca3af;font-weight:400;font-size:.8rem;">(optional)</span></label>
                    <?php if($sections->isEmpty()): ?>
                        <div style="padding:.75rem 1rem;background:#fffbeb;border:1.5px dashed #fbbf24;border-radius:8px;font-size:.82rem;color:#92400e;">
                            ⚠️ No sections found.
                            <a href="<?php echo e(route('teacher.sections.index')); ?>" style="color:#6366f1;font-weight:600;">Go create a section →</a>
                            <span style="color:#9ca3af;display:block;margin-top:.3rem;font-size:.75rem;">You can still create the group and assign a section later.</span>
                        </div>
                        <input type="hidden" name="section_id" value="">
                    <?php else: ?>
                        <div style="position:relative;">
                            <select name="section_id" id="sectionPicker"
                                style="width:100%;padding:.55rem 2.2rem .55rem .85rem;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.88rem;background:#fff;color:#374151;appearance:none;cursor:pointer;transition:border-color .2s;">
                                <option value="">— Select a class section —</option>
                                <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sec->id); ?>"
                                        <?php echo e(old('section_id') == $sec->id ? 'selected' : ''); ?>

                                        data-name="<?php echo e($sec->name); ?>"
                                        data-students='<?php echo e(json_encode($sec->students->pluck("id"))); ?>'>
                                        <?php echo e($sec->name); ?>

                                        <?php if($sec->school_year): ?> · <?php echo e($sec->school_year); ?><?php endif; ?>
                                        <?php if($sec->semester): ?> · <?php echo e($sec->semester); ?><?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <span style="position:absolute;right:.8rem;top:50%;transform:translateY(-50%);color:#9ca3af;pointer-events:none;">▾</span>
                        </div>
                    <?php endif; ?>
                    <?php $__errorArgs = ['section_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div style="color:#ef4444;font-size:.75rem;margin-top:.3rem;"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="form-group">
                    <label>Add Students <span style="color:#9ca3af;font-weight:400;">(optional — you can add later)</span></label>

                    <?php
                        $allStudents = auth()->user()->myStudents()->orderBy('name')->get();
                    ?>

                    <?php if($allStudents->isEmpty()): ?>
                        <div style="padding:.85rem;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;color:#9ca3af;font-size:.82rem;text-align:center;">
                            No students registered yet.
                        </div>
                    <?php else: ?>
                        
                        <div style="display:flex;gap:.4rem;flex-wrap:wrap;margin-bottom:.5rem;">
                            <button type="button" class="sg-filter-btn sg-active" data-filter="all">
                                All <span class="sg-count" id="sgCountAll"><?php echo e($allStudents->count()); ?></span>
                            </button>
                            <button type="button" class="sg-filter-btn" data-filter="section" id="sgFilterSection" style="display:none;">
                                In Section <span class="sg-count" id="sgCountSection">0</span>
                            </button>
                            <button type="button" class="sg-filter-btn" data-filter="selected">
                                Selected <span class="sg-count" id="sgCountSelected">0</span>
                            </button>
                        </div>

                        <input type="text"
                               id="studentSearchInput"
                               placeholder="🔍 Search by name or ID..."
                               style="margin-bottom:8px;width:100%;padding:.5rem .85rem;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.85rem;box-sizing:border-box;"
                               oninput="sgApplyFilter()">

                        <div class="student-checklist" id="studentList">
                            <?php $__currentLoopData = $allStudents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="student-check-item"
                                   data-name="<?php echo e(strtolower($s->name)); ?>"
                                   data-sid="<?php echo e(strtolower($s->student_id ?? '')); ?>"
                                   data-uid="<?php echo e($s->id); ?>">
                                <input type="checkbox"
                                       name="students[]"
                                       value="<?php echo e($s->id); ?>"
                                       class="sg-checkbox"
                                       style="accent-color:#4f46e5;flex-shrink:0;"
                                       <?php echo e(in_array($s->id, old('students', [])) ? 'checked' : ''); ?>>
                                <div>
                                    <div style="font-weight:600;font-size:.85rem;display:flex;align-items:center;gap:.3rem;flex-wrap:wrap;">
                                        <?php echo e($s->name); ?>

                                        <span class="sg-in-section-tag" style="display:none;padding:.1rem .4rem;background:#dcfce7;color:#16a34a;border-radius:999px;font-size:.65rem;font-weight:700;">✔ In section</span>
                                    </div>
                                    <div style="font-size:.72rem;color:#9ca3af;">
                                        ID: <?php echo e($s->student_id ?? '—'); ?> &nbsp;·&nbsp; <?php echo e($s->department ?? 'No course'); ?>

                                    </div>
                                </div>
                            </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <div id="sgNoMatch" style="display:none;padding:1rem;text-align:center;color:#9ca3af;font-size:.82rem;">No students match.</div>
                        </div>

                        <div id="sgHint" style="font-size:.72rem;color:#9ca3af;margin-top:5px;">
                            <?php echo e($allStudents->count()); ?> student(s) available
                        </div>
                    <?php endif; ?>
                </div>

                <div class="modal-foot">
                    <button type="button" class="btn-outline" id="cancelModalBtn">Cancel</button>
                    <button type="submit" class="btn-primary">✅ Create Group</button>
                </div>

            </form>
        </div>

    </div>
</div>

<style>
.sg-filter-btn {
    padding:.28rem .75rem;border-radius:999px;border:1.5px solid #e5e7eb;
    background:#f9fafb;color:#6b7280;font-size:.74rem;font-weight:600;cursor:pointer;transition:all .15s;
}
.sg-filter-btn:hover,.sg-filter-btn.sg-active{background:#eef2ff;border-color:#6366f1;color:#4f46e5;}
.sg-filter-btn.sg-active{box-shadow:0 0 0 3px rgba(99,102,241,.12);}
.sg-count{background:#6366f1;color:#fff;border-radius:999px;padding:0 .38rem;font-size:.66rem;margin-left:.2rem;}
</style>

<script>
(function(){
    var sgFilter = 'all';
    var sgSectionIds = [];

    function sgRows(){ return [].slice.call(document.querySelectorAll('#studentList .student-check-item')); }

    function sgUpdateCounts(){
        var rows = sgRows();
        var el = document.getElementById('sgCountAll');
        if(el) el.textContent = rows.length;
        el = document.getElementById('sgCountSection');
        if(el) el.textContent = rows.filter(function(r){ return sgSectionIds.indexOf(+r.dataset.uid) > -1; }).length;
        el = document.getElementById('sgCountSelected');
        if(el) el.textContent = rows.filter(function(r){ return r.querySelector('.sg-checkbox').checked; }).length;
    }

    window.sgApplyFilter = function(){
        var q = (document.getElementById('studentSearchInput')||{}).value;
        q = q ? q.toLowerCase().trim() : '';
        var vis = 0;
        sgRows().forEach(function(r){
            var uid = +r.dataset.uid;
            var checked = r.querySelector('.sg-checkbox').checked;
            var matchQ = !q || r.dataset.name.indexOf(q)>-1 || r.dataset.sid.indexOf(q)>-1;
            var show = matchQ;
            if(sgFilter==='section')  show = show && sgSectionIds.indexOf(uid)>-1;
            if(sgFilter==='selected') show = show && checked;
            r.style.display = show ? '' : 'none';
            if(show) vis++;
        });
        var nm = document.getElementById('sgNoMatch');
        if(nm) nm.style.display = vis===0 ? 'block' : 'none';
        var hint = document.getElementById('sgHint');
        if(hint) hint.textContent = vis + ' student(s) ' + (sgFilter==='section' ? 'in this section' : sgFilter==='selected' ? 'selected' : 'shown');
        sgUpdateCounts();
    };

    function sgSetFilter(f){
        sgFilter = f;
        [].slice.call(document.querySelectorAll('.sg-filter-btn')).forEach(function(b){
            b.classList.toggle('sg-active', b.dataset.filter===f);
        });
        sgApplyFilter();
    }

    document.addEventListener('DOMContentLoaded', function(){
        // Section picker
        var picker = document.getElementById('sectionPicker');
        if(picker){
            picker.addEventListener('change', function(){
                var opt = picker.options[picker.selectedIndex];
                var ids = [];
                try{ ids = JSON.parse(opt.dataset.students||'[]'); }catch(e){}
                sgSectionIds = ids;

                sgRows().forEach(function(r){
                    var inSec = ids.indexOf(+r.dataset.uid)>-1;
                    r.classList.toggle('sg-in-section', inSec);
                    var tag = r.querySelector('.sg-in-section-tag');
                    if(tag) tag.style.display = inSec ? 'inline-flex' : 'none';
                });

                var fsBtn = document.getElementById('sgFilterSection');
                if(fsBtn) fsBtn.style.display = picker.value ? '' : 'none';
                sgSetFilter(picker.value ? 'section' : 'all');
            });
            if(picker.value) picker.dispatchEvent(new Event('change'));
        }

        // Filter buttons
        [].slice.call(document.querySelectorAll('.sg-filter-btn')).forEach(function(b){
            b.addEventListener('click', function(){ sgSetFilter(b.dataset.filter); });
        });

        // Checkbox change → update counts
        var list = document.getElementById('studentList');
        if(list) list.addEventListener('change', function(){
            sgUpdateCounts();
            if(sgFilter==='selected') sgApplyFilter();
        });

        sgUpdateCounts();
    });
})();
</script>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('assets/js/pages/teacher-groups.js')); ?>"></script>
<?php if($errors->any()): ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = document.getElementById('createGroupModal');
        if (modal) {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            setTimeout(function () {
                var f = document.getElementById('groupNameInput');
                if (f) f.focus();
            }, 80);
        }
    });
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/teacher/group/index_group.blade.php ENDPATH**/ ?>