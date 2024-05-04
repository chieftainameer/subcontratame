
$('#frmResetPassword').validate({
  rules:{
        password:{
            minlength:8
        },      
        password_confirmation:{
            equalTo: function(){
                return $('#frmResetPassword input[name=password]'); 
            }
        }
  },
  submitHandler: function (form) {
  itemData = new FormData(form);
  itemData.delete('password_confirmation');
  axios.post('/client/reset-password', itemData)
    .then(function (res) {
        Core.showToast('success', res.data.message);
        setTimeout(() => {
            window.location = $('#urlRoute').val() + '/client/login';
        }, 3000);
    })
    .catch(function (err) {
        console.log(err);
        Core.showAlert('error','No ha sido posible crear el registro, por favor verifique su información e intente nuevamente.');
    }).finally(function(){
        $('#frmResetPassword').trigger('reset');
        $('#frmResetPassword').validate().resetForm();
        $('input,textarea,select').removeClass('is-invalid');
        
    });
}});