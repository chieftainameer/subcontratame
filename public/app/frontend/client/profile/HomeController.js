route = '/client/profile';

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

$('#frmEdit select[id=autonomous_community]').on('change', function(){
    if($(this).val() !== ''){
        Core.get(route + '/get-province/' + $('#frmEdit select[id=autonomous_community]').val())
                .then(function (res) {
                    $('#frmEdit select[id=province_id]').empty().trigger('change');
                    //$('#frmCompleteRegistration select[id=province_id]').append($('<option>',{value:''}).text('Selecciona una opción')).trigger('change');
                    res.data.data.forEach((item) => {
                        $('#frmEdit select[id=province_id]').append($('<option>',{value:item.id}).text(item.name)).trigger('change');
                    });
                })
                .catch(function (err) {
                    console.log(err);
                    Core.showAlert('error', err.response.data.error.message);
                });
    }
    else{
        $('#frmEdit select[id=province_id]').empty().trigger('change');
        $('#frmEdit select[id=province_id]').append($('<option>',{value:''}).text('Selecciona una opción')).trigger('change');
    }
});

$('#frmEdit').validate({
    rules:{
        password:{
            minlength: 8,
            maxlength: 15
        },
        password_confirmation:{
            equalTo: '#password'
        }
    },
    messages:{
        payment_method:{
            required: 'Debe marcar al menos una opción'
        },
        category:{
            required: 'Debe marcar al menos una opción'
        }
    },
  submitHandler: function (form) {
  itemData = new FormData(form);
 
  $('#frmEdit input[type=checkbox]:checked').each(function() {
    if(this.checked){
        if($(this).attr('name') === 'category'){
            itemData.append('categories[]', $(this).val());
        }
        else if($(this).attr('name') === 'payment_method'){
            itemData.append('payment_methods[]', $(this).val());
        }
    }
 });

 itemData.delete('autonomous_community');
 itemData.delete('password_confirmation');
 itemData.delete('category[]');
 itemData.delete('payment_method[]');

  //Axios Http Post Request
  Core.post(route + '/update/' +$('#frmEdit input[id=id]').val(), itemData).then(function(res){
    Core.showToast('success', res.data.message);
    setTimeout(() => {
        window.location = res.data.redirect;
    }, 1500);
  }).catch(function(err){
    console.log(err);
  })
}});

function imagePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#frmEdit img[id=imgProfile]').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
