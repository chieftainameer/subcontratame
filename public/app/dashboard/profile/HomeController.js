route = "dashboard/settings/profile";
$('#frmUpdate').validate({
    submitHandler: function (form) {
    itemData = new FormData(form);
    //Axios Http Post Request
    Core.post(route+'/update', itemData).then(function(res) {
        Core.showToast('success','Registro editado exitosamente');
        window.location.href = (urlWeb +'/'+route);
    }).catch(function(err) {
        Core.showToast('error',err.response.data.error.message);
    });
}});

function imagePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#frmUpdate img[id=imgProfile]').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}