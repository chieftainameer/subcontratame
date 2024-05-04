//route = 'frontend/client';
arrStates = [];

$.validator.setDefaults({
    errorElement: 'span',
     errorPlacement: function (error, element) {
         error.addClass('invalid-feedback');
         element.closest('.form-group').append(error);
         element.closest('.tagInput').append(error);
     },
     highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
     },
     unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
     },
 });

$('#frmRegister').validate({
    rules:{
        email:{
            remote:{
                url: 'check-mail',
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:{
                    email: function(){
                        return $('#frmRegister input[name=email]').val();
                    }
                }
           },
        },
        password:{
            minlength:8
        },
        password_confirmation:{
            equalTo: function(){
                return $('#frmRegister input[name=password]'); 
            }
        }
    },
    messages:{
        first_name:{
            required: 'Este campo es obligatorio.'
        },
        last_name:{
            required: 'Este campo es obligatorio.'
        },
        email:{
            required: 'Este campo es obligatorio.',
            email: 'Por favor, introduce una dirección de correo electrónico válida.',
            remote: 'El correo ingresado ya esta registrado.'
        },
        password:{
            required: 'Este campo es obligatorio.',
            minlength: 'La longitud minima es de 8 carácteres.'
        },
        password_confirmation:{
            equalTo: 'No coinciden los campos.'
        },
        i_agree:{
            required: 'Debe marcar la opción.'
        },
    },
    submitHandler: function (form) {
         itemData = new FormData(form);
         itemData.set('i_agree', $('#frmRegister input[name=i_agree]').is(':checked') ? 1 : 0);
         itemData.delete('password_confirmation');
          
         axios.post('/client/register', itemData)
         .then(function (res) {
                   Core.showToast('success', res.data.message);
         })
         .catch(function (err) {
            console.log(err);
            Core.showAlert('error','No ha sido posible crear el registro, por favor verifique su información e intente nuevamente.');
         }).finally(function(){
              $('#frmRegister').trigger('reset');
              $('#frmRegister').validate().resetForm();
              $('input,textarea,select').removeClass('is-invalid');
         });
       }}
);

$('#frmCompleteRegistration select[id=autonomous_community]').on('change', function(){
    if($(this).val() !== ''){
        axios.get('/client/get-province/' + $('#frmCompleteRegistration select[id=autonomous_community]').val())
                .then(function (res) {
                    $('#frmCompleteRegistration select[id=province_id]').empty().trigger('change');
                    //$('#frmCompleteRegistration select[id=province_id]').append($('<option>',{value:''}).text('Selecciona una opción')).trigger('change');
                    res.data.data.forEach((item) => {
                        $('#frmCompleteRegistration select[id=province_id]').append($('<option>',{value:item.id}).text(item.name)).trigger('change');
                    });
                })
                .catch(function (err) {
                    console.log(err);
                    Core.showAlert('error', err.response.data.error.message);
                });
    }
    else{
        $('#frmCompleteRegistration select[id=province_id]').empty().trigger('change');
        $('#frmCompleteRegistration select[id=province_id]').append($('<option>',{value:''}).text('Selecciona una opción')).trigger('change');
    }
});

$('#frmCompleteRegistration').validate({
  rules:{
    key_words:{
        required: true
    }
  },
  messages:{
    category:{
        required: 'Debe marcar al menos una opción'
    },
    payment_method:{
        required: 'Debe marcar al menos una opción'
    },
  },
  messages:{
    category:{
        required: 'Debe seleccionar por lo menos una categoría'
    },
    payment_method:{
        required: 'Debe seleccionar por lo menos un método de pago'
    },
    key_words:{
        required: 'Debe ingresar las palabras claves'
    }
  },
  submitHandler: function (form) {
  itemData = new FormData(form);
  itemData.delete('autonomous_community');
  $('#frmCompleteRegistration input[type=checkbox]:checked').each(function() {
        if(this.checked){
            if($(this).attr('name') === 'category'){
                itemData.append('categories[]', $(this).val());
            }
            else if($(this).attr('name') === 'payment_method'){
                itemData.append('payment_methods[]', $(this).val());
            }
            
        }
  });
  itemData.delete('category[]');
  itemData.delete('payment_method[]');
  
  axios.post('/client/complete', itemData)
  .then(function (res) {
        Core.showToast('success', res.data.message);
        setTimeout(() => {
            window.location = res.data.redirect; //$('#urlRoute').val() + '';
        }, 2500);
    })
    .catch(function (err) {
        Core.showAlert('error', err.response.data.error.message);
    });
}});

function imagePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#frmCompleteRegistration img[id=imgProfile]').attr('src', e.target.result);
            //$('#frmEdit img[id=imgProfile]').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function init(){ 
    //$('#frmCompleteRegistration textarea[name=tax_residence]').richText();
    //$('#frmCompleteRegistration textarea[name=company_description]').richText();
}
init();