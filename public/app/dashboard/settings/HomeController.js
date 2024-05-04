// route = "dashboard/settings/autonomous_community";
// arrData = [];
// arrEstados = [];
// tblData = $('#dataTable').DataTable({
//     processing: true,
//     serverSide: true,
//     ajax: urlBase+route+"/datatables",
//     language: {
//         url: urlBase+'app/Spanish.json',
//         pageLength: 5
//     },
//     columns: [
//         {data: 'id', name: 'id', orderable: true, searchable: true},
//         {data: 'name', name: 'name', orderable: true, searchable: true},
//         {data: 'autonomous_community', name: 'autonomous_community', orderable: true, searchable: true},
//         {data: 'status', name: 'status'},
//         {data: 'status', name: 'status'},
//     ],
//     columnDefs: [
//         {
//             targets: 0, render: function ( data, type, row, meta ) {
//             return row.id
//         }},
//         {
//             targets: 1, render: function ( data, type, row, meta ) {
//             return row.name
//         }},
//         {
//             targets: 2, render: function ( data, type, row, meta ) {
//             return `<center>${row.status==1?'<span class="text-success">Activo</span>':'<span class="text-danger">Inactivo</span>'}</center>`
//         }},
//         {
//             targets: 3, render: function ( data, type, row, meta ) {
//             return Core.buttons(meta.row)
//         }},
//     ],
//     drawCallback: function(settings) {
//         feather.replace();
//         arrData = this.api().rows().data();
//     }
// });

// window.getData = function() {
//     //each reload datatable set data to arrData Array
//     tblData.ajax.reload(function( json ){
//         arrData = json.data;
//     });
// }

// window.showItem = function() {
//     console.log(itemData);
//     $('#frmEdit input[name=id]').val(itemData.id);
//     $('#frmEdit input[name=name]').val(itemData.name);
//     $('#frmEdit select[name=autonomous_community]').val(itemData.autonomous_community);
//     $('#frmEdit select[name=status]').val(itemData.status);
// }

// $('#frmNew').validate({
//     submitHandler: function (form) {
//     itemData = new FormData(form);
//     Core.crud.store().then(function(res) {
//       Core.showToast('success','Registro exitoso');
//       getData();
//       $('#mdlNew').modal('hide');
//     })
//     .catch(function (err) {
//         console.log(err.response);
//         if(err.response) {
//             console.log(err.response.data.error);
//           Core.showAlert('error',err.response.data.error.message);
//         } else {
//           Core.showAlert('error','No ha sido posible registrar paciente, verifique su informacón he intente nuevamente.');
//         }
//     })
// }});

// $('#frmEdit').validate({
//     submitHandler: function (form) {
//     itemData = new FormData(form);
//     //Axios Http Post Request
//     Core.crud.update($('#frmEdit input[id=id]').val()).then(function(res) {
//         Core.showToast('success','Registro editado exitosamente');
//         $('#mdlEdit').modal('hide');
//     }).catch(function(err) {
//         Core.showAlert('error','No ha sido posible editar paciente, intente nuevamente');
//     }).finally(function(){
//         getData();
//     })
// }});

// $('#mdlNew').on('hidden.bs.modal', function(){
//     $('#frmNew')[0].reset()
// });

// $('#mdlEdit').on('hidden.bs.modal', function(){
//     $('#frmEdit')[0].reset()
// });

route = "dashboard/settings";

$("#frmEdit").validate({
    submitHandler: function (form) {
        itemData = new FormData(form);
        if($('#frmEdit input[id=id]').val() == '1'){
            Core.post(route+'/update/'+$('#frmEdit input[id=id]').val(), itemData)
            .then(function (res){
                $('#mdlEdit').modal('hide');
                Core.showToast("success",res.data.message);
                window.location = urlWeb + '/' + route + '/' + res.data.direction;
                //getData();
            })
            .catch(function (err) {
                console.log(err);
                Core.showToast('error',err.response.data.message);
            });
        }
        else{
            Core.post(route+'/store', itemData)
            .then(function (res){
                $('#mdlEdit').modal('hide');
                Core.showToast("success",res.data.message);
                window.location = urlWeb + '/' + route + '/' + res.data.direction;
                //getData();
            })
            .catch(function (err) {
                console.log(err);
                Core.showToast('error',err.response.data.message);
            });
        }
}});

function init(){
    $('#privacy_policies').richText();
    $('#terms_conditions').richText();
    $('#about').richText();
}

init()

// function imagePreview(input) {
//   if (input.files && input.files[0]) {
//       var reader = new FileReader();
//       reader.onload = function (e) {
//           $('#frmNew img[id=imgProfile]').attr('src', e.target.result);
//           $('#frmEdit img[id=imgProfile]').attr('src', e.target.result);
//       }
//       reader.readAsDataURL(input.files[0]);
//   }
// }