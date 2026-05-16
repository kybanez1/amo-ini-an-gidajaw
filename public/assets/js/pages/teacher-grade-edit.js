/* teacher/grade/edit.blade.php */

const maxScore = {{ $project->max_score }};

function updatePreview(val) {
    const preview  = document.getElementById('scorePreview');
    const previewV = document.getElementById('previewVal');
    const num = parseInt(val);
    if (val !== '' && !isNaN(num)) {
        const pct = Math.round((num / maxScore) * 100);
        let grade = pct >= 90 ? '🏆 Excellent'
                  : pct >= 80 ? '✅ Good'
                  : pct >= 70 ? '📝 Average'
                  : pct >= 60 ? '⚠️ Below Average'
                  : '❌ Failing';
        previewV.textContent = num + ' / ' + maxScore + ' (' + pct + '%) — ' + grade;
        preview.style.display = 'flex';
    } else {
        preview.style.display = 'none';
    }
}

const initVal = document.getElementById('scoreInput').value;
if (initVal) updatePreview(initVal);
