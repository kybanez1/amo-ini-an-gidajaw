/* teacher/dashboard.blade.php */

function copyTeacherCode() {
    const code = '{{ Auth::user()->teacher_code ?? "" }}';
    navigator.clipboard.writeText(code).then(() => {
        alert('Teacher code ' + code + ' copied!');
    });
}
