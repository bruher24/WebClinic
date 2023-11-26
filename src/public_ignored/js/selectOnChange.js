window.onload = function () {
    let select= document.getElementById('roleSelect');
    select.addEventListener('change', function () {
        if(select.value === 'doctor') {
            document.getElementById('specLabel').style.display = 'block';
        }else {
            document.getElementById('specLabel').style.display = 'none';
        }
    });
}