route = "dashboard/settings/pais";
arrData = [];
arrEstados = [];
tblData = $('#dataTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: urlBase+route+"/datatables",
    language: {
        url: urlBase+'app/Spanish.json',
        pageLength: 5
    },
    columns: [
        {data: 'id', name: 'id', orderable: true, searchable: true},
        {data: 'name_es', name: 'name_es', orderable: true, searchable: true},
    ],
    columnDefs: [
        {
            targets: 0, render: function ( data, type, row, meta ) {
            return row.id
        }},
        {
            targets: 1, render: function ( data, type, row, meta ) {
            return row.name_es
        }},
        {
            targets: 2, render: function ( data, type, row, meta ) {
            return Core.buttons(meta.row)
        }},
    ],
    drawCallback: function(settings) {
        feather.replace();
        arrData = this.api().rows().data();
    }
});

window.getData = function() {
    //each reload datatable set data to arrData Array
    tblData.ajax.reload(function( json ){
        arrData = json.data;
    });
}

window.showItem = function() {
    console.log(itemData);
    $('#frmEdit input[name=id]').val(itemData.id);
    $('#frmEdit input[name=name_es]').val(itemData.name_es);
    $('#frmEdit input[name=name_en]').val(itemData.name_en);
}

$('#frmNew').validate({
    submitHandler: function (form) {
    itemData = new FormData(form);
    Core.crud.store().then(function(res) {
      Core.showToast('success','Registro exitoso');
      getData();
      $('#mdlNew').modal('hide');
    })
    .catch(function (err) {
        console.log(err.response);
        if(err.response) {
            console.log(err.response.data.error);
          Core.showAlert('error',err.response.data.error.message);
        } else {
          Core.showAlert('error','No ha sido posible registrar paciente, verifique su informacón he intente nuevamente.');
        }
    })
}});

$('#frmEdit').validate({
    submitHandler: function (form) {
    itemData = new FormData(form);
    //Axios Http Post Request
    Core.crud.update($('#frmEdit input[id=id]').val()).then(function(res) {
        Core.showToast('success','Registro editado exitosamente');
        $('#mdlEdit').modal('hide');
    }).catch(function(err) {
        Core.showAlert('error','No ha sido posible editar paciente, intente nuevamente');
    }).finally(function(){
        getData();
    })
}});

$('#mdlNew').on('hidden.bs.modal', function(){
    $('#frmNew')[0].reset();
    $('#frmNew').validate().resetForm();
    $('input,textarea,select').removeClass('is-invalid');
});

$('#mdlEdit').on('hidden.bs.modal', function(){
    $('#frmEdit')[0].reset();
    $('#frmEdit').validate().resetForm();
    $('input,textarea,select').removeClass('is-invalid');
});

