$('#frmLogin').validate({
    submitHandler: function (form) {
        console.log(form,urlWeb);
    itemData = new FormData(form);
    axios.post('/client/login', itemData)
    .then(function (res) {
      if(res.data.err==false) {
          Core.showToast('success', res.data.message);
          setTimeout(function() {
              window.location.href = $('#urlRoute').val();
          },2500);
      }else{
          Core.showToast('error','Datos de acceso incorrectos, intente nuevamente.');
      }
     })
     .catch(function (err) {
         console.log(err.response);
          Core.showToast('error', err.response.data.error.message);
      }).finally(function(){
          $('#frmLogin').trigger('reset');
          $('#frmLogin').validate().resetForm();
          $('input,textarea,select').removeClass('is-invalid');
      });
  }});

  $('#frmForgotPassword').validate({
    submitHandler: function (form) {
    itemData = new FormData(form);
    axios.post('/client/send-password', itemData)  
          .then(function (res) {
            
                if(parseInt(res.data.show) == 1){
                    Core.showToast('error', res.data.message);    
                }
                else if(parseInt(res.data.show) == 0){
                    Core.showToast('success', res.data.message);
                    setTimeout(() => {
                        window.location = $('#urlRoute').val() + '/client/login';    
                    }, 2000);
                }   
            
             
          })
          .catch(function (err) {
              console.log(err);
              Core.showAlert('error', err.response.data.error.message);
          }).finally(function(){
              $('#frmForgotPassword').trigger('reset');
              $('#frmForgotPassword').validate().resetForm();
              $('input,textarea,select').removeClass('is-invalid');
          });
}});
  
  