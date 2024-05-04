$("#frmLogin").validate({
    submitHandler: function (form) {
        console.log(form,urlWeb);
        itemData = new FormData(form);
        Core.post('auth/login', itemData)
        .then(function (res) {
            console.log(res.data, urlWeb+'/'+res.data.type+'/home');
            if(res.data.err==false) {
                Core.showToast('success', res.data.message);
                //alert('Acceso correcto.');
                setTimeout(function() {
                    //if(res.data.type!='') {
                       // console.log(urlWeb+'/'+res.data.type+'/home');
                    window.location.href = urlWeb+'/dashboard/home';
                    // } else {
                    //     Core.showToast('error',res.response.data.error.message);
                    // }
                },2500);
            }else{
                Core.showToast('error',err.response.data.error.message);
            }
        })
        .catch(function (err) {
            Core.showToast('error', err.response.data.error);
        });
    }
});

init();

function init() {
    
}
