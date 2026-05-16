/* teacher/group/index_group.blade.php */

(function () {
    var modal    = document.getElementById('createGroupModal');
    var modalBox = document.getElementById('createGroupModalBox');

    function openModal() {
        modal.style.display = 'flex';
        // Prevent body scroll while modal is open
        document.body.style.overflow = 'hidden';
        // Focus name field
        setTimeout(function () {
            var f = document.getElementById('groupNameInput');
            if (f) f.focus();
        }, 80);
    }

    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    // Open buttons
    var btn1 = document.getElementById('openCreateModal');
    var btn2 = document.getElementById('openCreateModal2');
    if (btn1) btn1.addEventListener('click', function(e){ e.stopPropagation(); openModal(); });
    if (btn2) btn2.addEventListener('click', function(e){ e.stopPropagation(); openModal(); });

    // Close buttons
    document.getElementById('closeModalBtn').addEventListener('click', closeModal);
    document.getElementById('cancelModalBtn').addEventListener('click', closeModal);

    // Click backdrop to close
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });

    // Escape key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });

    // Student search filter
    window.filterStudents = function(q) {
        q = q.toLowerCase().trim();
        document.querySelectorAll('#studentList .student-check-item').forEach(function(item) {
            var match = !q
                || item.dataset.name.includes(q)
                || item.dataset.sid.includes(q);
            item.style.display = match ? '' : 'none';
        });
    };

})();
