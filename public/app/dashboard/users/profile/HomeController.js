route = 'dashboard/users/profile';

$.validator.setDefaults({
    errorElement: 'span',
     errorPlacement: function (error, element) {
         error.addClass('invalid-feedback');
         element.closest('.form-group').append(error);
     },
     highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
     },
     unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
     },
});

$('#frmEdit').validate({
  rules:{
    password:{
        minlength:8
    },
    password_confirmation:{
        equalTo: function(){
            return $('#frmEdit input[name=password]'); 
        }
    }
  },
  submitHandler: function (form) {
  itemData = new FormData(form);
  //Axios Http Post Request
  Core.post(route+'/update/'+$('#frmEdit input[id=id]').val(), itemData).then(function(res){
    Core.showToast('success', res.data.message);
    setTimeout(() => {
        window.location = res.data.redirect;
    }, 2000);
  }).catch(function(err){
    console.log(err);
  })
}});

function imagePreview(input) {
    if (input.files && input.files[0]) {
        let image = input.files[0];

        if(image['type'] != 'image/jpeg' && image['type'] != 'image/png'){
            input.value = '';
            Core.showToast('error','Extensión de imagen inválida, debe ser JPG o PNG');
        }
        else{
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#frmEdit img[id=imgProfile]').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
}