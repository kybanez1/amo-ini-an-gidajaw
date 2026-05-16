/* teacher/projects/create.blade.php */

let taskIndex = 1;

document.getElementById('add-task-btn').addEventListener('click', function () {

    const wrapper = document.getElementById('task-wrapper');

    const taskHTML = `
        <div class="task-card">

            <button
                type="button"
                class="remove-btn"
                onclick="this.parentElement.remove()"
            >
                ✕
            </button>

            <div class="field">
                <label>Task Title</label>

                <input
                    type="text"
                    name="tasks[${taskIndex}][title]"
                    placeholder="Enter task title"
                >
            </div>

            <div class="field">
                <label>Task Description</label>

                <textarea
                    name="tasks[${taskIndex}][description]"
                    rows="3"
                    placeholder="Enter task details"
                ></textarea>
            </div>

            <div class="field">
                <label>Task Due Date</label>

                <input
                    type="datetime-local"
                    name="tasks[${taskIndex}][due_date]"
                >
            </div>

            <div class="field">
                <label>Max Points <span style="color:#9ca3af;font-weight:400;">(default: 100)</span></label>
                <input type="number"
                       name="tasks[${taskIndex}][max_points]"
                       min="1"
                       max="10000"
                       placeholder="100"
                       value="100"
                       style="width:100%;">
            </div>

        </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', taskHTML);

    taskIndex++;
});

function switchTab(form, tab) {
    var filePanel  = document.getElementById('panel-file-' + form);
    var linkPanel  = document.getElementById('panel-link-' + form);
    var fileBtn    = document.getElementById('tab-file-' + form);
    var linkBtn    = document.getElementById('tab-link-' + form);

    if (tab === 'file') {
        filePanel.style.display = 'block';
        linkPanel.style.display = 'none';
        fileBtn.style.background = '#4f46e5';
        fileBtn.style.color      = 'white';
        linkBtn.style.background = 'white';
        linkBtn.style.color      = '#6b7280';
    } else {
        filePanel.style.display = 'none';
        linkPanel.style.display = 'block';
        linkBtn.style.background = '#4f46e5';
        linkBtn.style.color      = 'white';
        fileBtn.style.background = 'white';
        fileBtn.style.color      = '#6b7280';
    }
}
