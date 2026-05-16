# 📋 PROJECT FIXES — WHAT WAS CHANGED & HOW TO APPLY

## 🚀 HOW TO APPLY (Run these commands after replacing your files)

```bash
php artisan migrate
php artisan storage:link
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

---

## ✅ FIX 1 — ADD PROJECT STATUS (Teacher Side)

**Problem:** Create form used wrong status values (`active`, `pending`) not in the DB ENUM.

**DB ENUM allows:** `draft`, `published`, `ongoing`, `completed`, `archived`

**Files changed:**
- `resources/views/teacher/create_project.blade.php`
  - Changed status options from `draft / active / completed` → `draft / published / ongoing / completed`
- `resources/views/teacher/projects/update_project.blade.php`
  - Changed status options from `pending / active / completed` → `draft / published / ongoing / completed / archived`
  - Added `enctype="multipart/form-data"` to the form so file uploads work
  - Added instruction file display and upload field

---

## ✅ FIX 2 — INSTRUCTION FILE VISIBLE TO STUDENTS

**Problem:** File was uploaded but not prominently shown to students.

**Files changed:**
- `app/Models/Project.php`
  - Added `instruction_file`, `instruction_file_name`, `instruction_file_uploaded_at` to `$fillable` and `$casts`
- `app/Http/Controllers/ProjectController.php`
  - Store & Update now save the **original filename** in `instruction_file_name`
  - Store & Update now save the **upload timestamp** in `instruction_file_uploaded_at`
- `database/migrations/2026_05_07_000001_add_instruction_file_to_projects.php` *(new)*
  - Adds the 3 new columns to the `projects` table
- `resources/views/student/projects/show.blade.php`
  - Shows file name, upload date, **View** button (opens in tab), **Download** button
- `resources/views/student/projects/submit.blade.php`
  - Shows instruction file card with View + Download buttons directly on submission page

---

## ✅ FIX 3 — MY GRADES PAGE (Student Side)

**Problem:** "My Grades" sidebar link went to the projects index. No dedicated grades page existed.

**Files changed:**
- `app/Http/Controllers/StudentDashboardController.php`
  - Added `grades()` method that loads graded projects with teacher name, score, feedback
- `resources/views/student/grades.blade.php` *(new)*
  - Dedicated grades page showing: project title, teacher name, score, percentage, remarks, graded date
  - Summary cards: total graded, average score, student ID
  - Color-coded score pills (green ≥75%, orange ≥50%, red <50%)
- `resources/views/layouts/sidebar.blade.php`
  - Fixed "My Grades" link to point to `route('student.grades')` instead of projects index
- `routes/web.php`
  - Added `GET /student/grades` route → `StudentDashboardController@grades` → `student.grades`

---

## ✅ FIX 4 — REMOVED SAVE BUTTON FROM SUBMISSION FORM

**Problem:** Extra "Save Work" button caused confusion. Students had to save first before submitting.

**Files changed:**
- `resources/views/student/projects/submit.blade.php`
  - Removed the `💾 Save Work` button entirely
  - Kept only `🚀 Submit Project` (for new submissions)
  - Added `🔄 Update Submission` (shows when already submitted)
  - Cleaned up unused `.btn-save` CSS
- `app/Http/Controllers/StudentProjectController.php`
  - Fixed `finalize()` to accept content + file **directly** — no prior "save" needed
  - Creates or updates submission in one step on final submit

---

## ✅ FIX 5 — STUDENT ID NUMBER DISPLAY

**Problem:** Student ID wasn't consistently shown across the system.

**Files changed:**
- `app/Models/User.php`
  - Added `student_id` and `department` to `$fillable`
- `app/Http/Controllers/Auth/StudentAuthController.php`
  - Added `unique:users,student_id` validation rule to prevent duplicate IDs
- `database/migrations/2026_05_07_000002_add_unique_student_id_to_users.php` *(new)*
  - Unique constraint enforced via validation (SQLite-compatible)

**Student ID now shown in:**
- ✅ Student dashboard header (`student.dashboard`)
- ✅ My Grades page summary card (`student.grades`)
- ✅ Submit page project banner (`student.projects.submit`)
- ✅ Teacher's group show page (already existed)
- ✅ Teacher's project show submissions table (newly added)
- ✅ Teacher's grade edit page (newly added)
- ✅ Teacher's grade project list (newly added)

---

## ✅ FIX 6 — BUTTONS, FORMS, CONNECTIONS

**Files changed:**
- `resources/views/teacher/projects/show.blade.php`
  - Fixed status badge to recognize `ongoing` and `published` as "active"
  - Added instruction file display panel
  - Added student ID under student name in submissions table
- `resources/views/teacher/grade/edit.blade.php`
  - Added student ID display below student name
- `resources/views/teacher/grade/project.blade.php`
  - Added student ID below student name in grade list
- `routes/web.php`
  - Added `student.grades` route

---

## ✅ FIX 7 — DATABASE RELATIONSHIPS

All existing relationships verified correct:
- `User` → `hasMany(Project)` via `teacher_id` ✅
- `User` → `hasMany(ProjectSubmission)` via `student_id` ✅
- `User` → `belongsToMany(Project)` via `project_student` ✅
- `User` → `belongsToMany(Group)` via `group_student` ✅
- `Project` → `belongsTo(User)` via `teacher_id` ✅
- `Project` → `belongsTo(Group)` ✅
- `Project` → `hasMany(ProjectSubmission)` ✅
- `Project` → `belongsToMany(User)` via `project_student` with pivot columns ✅
- `ProjectSubmission` → `belongsTo(Project)` ✅
- `ProjectSubmission` → `belongsTo(User)` via `student_id` ✅

---

## ✅ FIX 8 — BACKEND / DATABASE ISSUES

- Fixed `instruction_file` column missing from projects migration (new migration added)
- Fixed duplicate `role`/`status` column conflict between two migrations
- Fixed `finalize()` controller — no longer requires prior `save` step
- Added `instruction_file_name` and `instruction_file_uploaded_at` to Project model

---

## ✅ FIX 9 — UI/UX IMPROVEMENTS

- **Toast notifications** — replaced plain Bootstrap alerts with animated auto-dismissing toasts (5 second auto-hide) for: success, error, saved, warning
- **Deadline countdown** — live countdown timer on the student submit page showing days/hours/minutes/seconds remaining (color-coded: green = plenty of time, orange = ≤3 days, red = overdue)
- **Status badges** — properly color the `ongoing` and `published` statuses as "active"
- **Instruction file card** — clean card with file name, upload date, View + Download buttons shown on both student project show AND submit pages
- **Score color coding** on My Grades page — green ≥75%, orange ≥50%, red <50%

---

## 📁 NEW FILES ADDED

| File | Purpose |
|------|---------|
| `resources/views/student/grades.blade.php` | My Grades page for students |
| `database/migrations/2026_05_07_000001_add_instruction_file_to_projects.php` | Adds instruction_file columns to projects |
| `database/migrations/2026_05_07_000002_add_unique_student_id_to_users.php` | Placeholder (validation handles uniqueness) |

---

## 📁 FILES MODIFIED

| File | What Changed |
|------|-------------|
| `app/Models/Project.php` | Added instruction_file fields to fillable + casts |
| `app/Models/User.php` | Added student_id, department to fillable |
| `app/Http/Controllers/ProjectController.php` | Save file name + timestamp on store/update |
| `app/Http/Controllers/StudentProjectController.php` | Fixed finalize() to work without prior save |
| `app/Http/Controllers/StudentDashboardController.php` | Added grades() method, improved index() |
| `app/Http/Controllers/Auth/StudentAuthController.php` | Added unique validation for student_id |
| `database/migrations/2026_05_06_000001_add_student_fields_to_users_table.php` | Added hasColumn guards to prevent duplicate column errors |
| `routes/web.php` | Added student.grades route |
| `resources/views/layouts/app.blade.php` | Replaced Bootstrap alerts with animated toast notifications |
| `resources/views/layouts/sidebar.blade.php` | Fixed My Grades link to point to correct route |
| `resources/views/teacher/create_project.blade.php` | Fixed status options to match DB ENUM |
| `resources/views/teacher/projects/update_project.blade.php` | Fixed status options, added file upload + file display |
| `resources/views/teacher/projects/show.blade.php` | Fixed status badge, added instruction file panel, added student IDs |
| `resources/views/teacher/grade/edit.blade.php` | Added student ID display |
| `resources/views/teacher/grade/project.blade.php` | Added student ID display |
| `resources/views/student/projects/show.blade.php` | Improved instruction file display |
| `resources/views/student/projects/submit.blade.php` | Removed Save button, added countdown, file display, student ID |
