<link rel="stylesheet" href="<?php echo e(asset('assets/css/pages/auth-login.css')); ?>">
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
    <title>PMS — Sign In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet" />
</head>
<body id="bodyEl">
    <div class="wrapper">
        <div class="brand">
            <div class="brand-logo">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7l10 5 10-5-10-5z"/>
                    <path d="M2 17l10 5 10-5"/>
                    <path d="M2 12l10 5 10-5"/>
                </svg>
            </div>
            <h1>Project Management System</h1>
            <p>One portal for teachers &amp; students</p>
        </div>

        <div class="card" id="mainCard">
            <div class="role-toggle">
                <button class="role-btn active" data-role="teacher" onclick="switchRole('teacher')">
                    <span>🎓</span> Teacher
                </button>
                <button class="role-btn" data-role="student" onclick="switchRole('student')">
                    <span>👨‍💻</span> Student
                </button>
            </div>

            <div class="mode-strip">
                <button class="mode-btn active" id="loginModeBtn" onclick="switchMode('login')">Sign In</button>
                <button class="mode-btn" id="registerModeBtn" onclick="switchMode('register')">Create Account</button>
            </div>

            <div class="form-body">
                <?php if($errors->any()): ?>
                <div class="alert">
                    ⚠️
                    <div>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($error); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if(session('success')): ?>
                <div class="alert alert-success">
                    ✅ <?php echo e(session('success')); ?>

                </div>
                <?php endif; ?>

                <!-- TEACHER LOGIN -->
                <div class="form-section active" id="teacher-login">
                    <div class="role-badge teacher">🎓 &nbsp;Signing in as <strong>Teacher</strong></div>
                    <div class="teacher-hint show">⚡ Teacher accounts use institutional email (e.g. j.smith@school.edu)</div>
                    <form method="POST" action="<?php echo e(route('teacher.login')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="field">
                            <label for="tl-email">Institutional Email</label>
                            <input type="email" id="tl-email" name="email" value="<?php echo e(old('email')); ?>" required placeholder="yourname@school.edu" autocomplete="email" />
                        </div>
                        <div class="field">
                            <label for="tl-password">Password</label>
                            <div class="pw-wrap">
                                <input type="password" id="tl-password" name="password" required placeholder="Your password" />
                                <button type="button" class="pw-toggle" onclick="togglePw('tl-password',this)">👁</button>
                            </div>
                        </div>
                        <div class="extras">
                            <label><input type="checkbox" name="remember" /> Remember me</label>
                        </div>
                        <button type="submit" class="submit-btn">Sign In as Teacher →</button>
                    </form>
                </div>

                <!-- STUDENT LOGIN -->
                <div class="form-section" id="student-login">
                    <div class="role-badge student">👨‍💻 &nbsp;Signing in as <strong>Student</strong></div>
                    <form method="POST" action="<?php echo e(route('student.login')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="field">
                            <label for="sl-email">Student Email</label>
                            <input type="email" id="sl-email" name="email" value="<?php echo e(old('email')); ?>" required placeholder="yourname@student.edu" autocomplete="email" />
                        </div>
                        <div class="field">
                            <label for="sl-password">Password</label>
                            <div class="pw-wrap">
                                <input type="password" id="sl-password" name="password" required placeholder="Your password" />
                                <button type="button" class="pw-toggle" onclick="togglePw('sl-password',this)">👁</button>
                            </div>
                        </div>
                        <div class="extras">
                            <label><input type="checkbox" name="remember" /> Remember me</label>
                        </div>
                        <button type="submit" class="submit-btn">Sign In as Student →</button>
                    </form>
                </div>

                <!-- TEACHER REGISTER -->
                <div class="form-section" id="teacher-register">
                    <div class="role-badge teacher">🎓 &nbsp;Creating a <strong>Teacher Account</strong></div>
                    <div class="info-notice">📋 Students will see your name when you create groups and assign projects.</div>
                    <form method="POST" action="<?php echo e(route('teacher.register')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="field">
                            <label for="tr-name">Full Name</label>
                            <input type="text" id="tr-name" name="name" value="<?php echo e(old('name')); ?>" required placeholder="Prof. Maria Santos" />
                        </div>
                        <div class="field">
                            <label for="tr-email">Institutional Email</label>
                            <input type="email" id="tr-email" name="email" value="<?php echo e(old('email')); ?>" required placeholder="m.santos@school.edu" />
                        </div>
                        <div class="field">
                            <label for="tr-dept">Department / Subject</label>
                            <select id="tr-dept" name="department">
                                <option value="">— Select Department —</option>
                                <option value="Computer Science">Computer Science</option>
                                <option value="Information Technology">Information Technology</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Mathematics">Mathematics</option>
                                <option value="Natural Sciences">Natural Sciences</option>
                                <option value="Business Administration">Business Administration</option>
                                <option value="Social Sciences">Social Sciences</option>
                                <option value="Arts and Humanities">Arts and Humanities</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="field-row">
                            <div class="field">
                                <label for="tr-password">Password</label>
                                <div class="pw-wrap">
                                    <input type="password" id="tr-password" name="password" required placeholder="Min. 8 chars" />
                                    <button type="button" class="pw-toggle" onclick="togglePw('tr-password',this)">👁</button>
                                </div>
                            </div>
                            <div class="field">
                                <label for="tr-confirm">Confirm Password</label>
                                <input type="password" id="tr-confirm" name="password_confirmation" required placeholder="Repeat password" />
                            </div>
                        </div>
                        <button type="submit" class="submit-btn">Create Teacher Account →</button>
                    </form>
                </div>

                <!-- STUDENT REGISTER -->
                <div class="form-section" id="student-register">
                    <div class="role-badge student">👨‍💻 &nbsp;Creating a <strong>Student Account</strong></div>
                    <div class="info-notice">🔍 Your teacher gains access to your name, student ID, and course once you register.</div>
                    <form method="POST" action="<?php echo e(route('student.register')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="field">
                            <label for="sr-name">Full Name</label>
                            <input type="text" id="sr-name" name="name" value="<?php echo e(old('name')); ?>" required placeholder="Juan Dela Cruz" />
                        </div>
                        <div class="field">
                            <label for="sr-email">Student Email</label>
                            <input type="email" id="sr-email" name="email" value="<?php echo e(old('email')); ?>" required placeholder="juandelacruz@student.edu" />
                        </div>
                        <div class="field-row">
                            <div class="field">
                                <label for="sr-sid">Student ID</label>
                                <input type="text" id="sr-sid" name="student_id" value="<?php echo e(old('student_id')); ?>" placeholder="2024-00001" />
                            </div>
                            <div class="field">
                                <label for="sr-course">Course / Program</label>
                                <select id="sr-course" name="department">
                                    <option value="">— Select Course —</option>
                                    <option value="BSCS">BS Computer Science</option>
                                    <option value="BSIT">BS Information Technology</option>
                                    <option value="BSEE">BS Electrical Engineering</option>
                                    <option value="BSCE">BS Civil Engineering</option>
                                    <option value="BSME">BS Mechanical Engineering</option>
                                    <option value="BSBA">BS Business Administration</option>
                                    <option value="BSMath">BS Mathematics</option>
                                    <option value="BSED">BS Education</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="field-row">
                            <div class="field">
                                <label for="sr-password">Password</label>
                                <div class="pw-wrap">
                                    <input type="password" id="sr-password" name="password" required placeholder="Min. 8 chars" />
                                    <button type="button" class="pw-toggle" onclick="togglePw('sr-password',this)">👁</button>
                                </div>
                            </div>
                            <div class="field">
                                <label for="sr-confirm">Confirm Password</label>
                                <input type="password" id="sr-confirm" name="password_confirmation" required placeholder="Repeat password" />
                            </div>
                        </div>
                        <button type="submit" class="submit-btn">Create Student Account →</button>
                    </form>
                </div>
            </div>

            <div class="card-footer" id="cardFooter">
                Don't have an account? <a onclick="switchMode('register')">Register here</a>
            </div>
        </div>
    </div>
</body>
</html>

<script src="<?php echo e(asset('assets/js/pages/auth-login.js')); ?>"></script>
<?php /**PATH C:\wamp64\www\AyawGub-a-main\resources\views/auth/login.blade.php ENDPATH**/ ?>