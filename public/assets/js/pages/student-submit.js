/* student/projects/submit.blade.php */

function showFile(input){

    const fileName =
        document.getElementById('fileName');

    if(input.files.length > 0){

        fileName.textContent =
            '📄 ' + input.files[0].name;

    }else{

        fileName.textContent = '';

    }
}
