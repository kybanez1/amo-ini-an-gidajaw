/* teacher/projects/update_project.blade.php */

let taskCount = {{ max($project->tasks->count(), 1) }};

function addTask()
{
    let wrapper = document.getElementById('tasks-wrapper');

    let html = `
        <div class="task-box">

            <div class="flex-between" style="margin-bottom:1rem;">

                <div class="task-title">
                    Task ${taskCount + 1}
                </div>

                <button type="button"
                        class="remove-task-btn"
                        onclick="removeTask(this)">
                    Remove
                </button>

            </div>

            <div class="form-field">

                <label>Task Title</label>

                <input type="text"
                       name="tasks[${taskCount}][title]">

            </div>

            <div class="form-field">

                <label>Task Description</label>

                <textarea name="tasks[${taskCount}][description]"
                          rows="3"></textarea>

            </div>

            <div class="form-field">

                <label>Task Due Date</label>
                <input type="datetime-local" name="tasks[${taskCount}][due_date]">
            </div>

            <div class="task-field">
                <label>Max Points <span style="color:#9ca3af;font-weight:400;">(default: 100)</span></label>
                <input type="number" name="tasks[${taskCount}][max_points]"
                       min="1" max="10000" placeholder="100" value="100"
                       style="width:100%;">
            </div>

        </div>
    `;

    wrapper.insertAdjacentHTML('beforeend', html);

    taskCount++;
}

function removeTask(button)
{
    button.closest('.task-box').remove();
}

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
