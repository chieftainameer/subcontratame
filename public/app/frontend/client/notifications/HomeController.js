route = '/client/notifications';

function showDetailsProject(project_id){
    alert(project_id);


}

function deleteNotification(notification_id){
    bootbox.confirm({
        message: 'Confirmar eliminación',
        buttons: {
            confirm: {
                label: 'Sí',
                className: 'btn-success'
            },
            cancel: {
                label: 'No',
                className: 'btn-danger'
            }
        },
        callback: function(result) {
            if (result==true) {
                Core.get(route + '/destroy/' + notification_id).then(function(res) {
                    Core.showToast('success',res.data.message);
                    setTimeout(() => {
                        window.location = res.data.redirect;
                    }, 1500);
                }).catch(function(err) {
                    console.log(err);
                    //Core.showAlert('error', err.response.data.error.message);
                })
            }
        }
    });
}