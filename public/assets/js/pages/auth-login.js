/* auth/login.blade.php */

let currentRole = 'teacher';
        let currentMode = 'login';

        function switchRole(role) {
            currentRole = role;
            document.querySelectorAll('.role-btn').forEach(b => b.classList.remove('active'));
            document.querySelector('[data-role="' + role + '"]').classList.add('active');
            document.getElementById('bodyEl').classList.toggle('student-mode', role === 'student');
            showSection();
            updateFooter();
        }

        function switchMode(mode) {
            currentMode = mode;
            document.querySelectorAll('.mode-btn').forEach(b => b.classList.remove('active'));
            document.getElementById(mode + 'ModeBtn').classList.add('active');
            showSection();
            updateFooter();
        }

        function showSection() {
            document.querySelectorAll('.form-section').forEach(s => s.classList.remove('active'));
            const el = document.getElementById(currentRole + '-' + currentMode);
            if (el) el.classList.add('active');
        }

        function updateFooter() {
            const footer = document.getElementById('cardFooter');
            if (currentMode === 'login') {
                footer.innerHTML = "Don't have an account? <a onclick=\"switchMode('register')\">Register here</a>";
            } else {
                footer.innerHTML = "Already have an account? <a onclick=\"switchMode('login')\">Sign in here</a>";
            }
        }

        function togglePw(inputId, btn) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') { input.type = 'text'; btn.textContent = '🙈'; }
            else { input.type = 'password'; btn.textContent = '👁'; }
        }
