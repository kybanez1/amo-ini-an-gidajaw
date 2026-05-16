/* teacher/students/index.blade.php */

function copyCode() {
    const code = '{{ $teacher->teacher_code ?? "" }}';
    navigator.clipboard.writeText(code).then(() => {
        document.getElementById('copyConfirm').style.display = 'block';
        document.getElementById('copyBtn').textContent = '✅ Copied!';
        setTimeout(() => {
            document.getElementById('copyConfirm').style.display = 'none';
            document.getElementById('copyBtn').textContent = '📋 Copy';
        }, 2500);
    });
}
