/* teacher/group/show.blade.php */

function copyCode() {
    const code = '{{ $group->join_code ?? "" }}';
    navigator.clipboard.writeText(code).then(() => {
        const confirm = document.getElementById('copyConfirm');
        const btn = document.getElementById('copyBtn');
        confirm.style.display = 'block';
        btn.textContent = '✅ Copied!';
        setTimeout(() => {
            confirm.style.display = 'none';
            btn.textContent = '📋 Copy Code';
        }, 2500);
    });
}
