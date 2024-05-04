route = "dashboard/settings/province";
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
        {data: 'name', name: 'name', orderable: true, searchable: true},
        {data: 'community', name: 'community'},
        {data: 'status', name: 'status'},
        {data: 'status', name: 'status'},
    ],
    columnDefs: [
        {
            targets: 0, render: function ( data, type, row, meta ) {
            return row.id
        }},
        {
            targets: 1, render: function ( data, type, row, meta ) {
            return row.name
        }},
        {
            targets: 2, render: function ( data, type, row, meta ) {
            return row.community.name;
        }},
        {
            targets: 3, render: function ( data, type, row, meta ) {
            return `<center>${row.status==1?'<span class="text-success">Activo</span>':'<span class="text-danger">Inactivo</span>'}</center>`
        }},
        {
            targets: 4, render: function ( data, type, row, meta ) {
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
    //console.log(itemData);
    $('#frmEdit input[name=id]').val(itemData.id);
    $('#frmEdit input[name=name]').val(itemData.name);
    setTimeout(() => {
        $('#frmEdit select[name=autonomous_community_id]').val(itemData.autonomous_community_id).trigger('change');    
    }, 500);
    $('#frmEdit select[name=status]').val(itemData.status);
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

$('#mdlNew').on('show.bs.modal', function(){
    //Axios Http Get Request
    Core.get(route + '/get-autonomous-community').then(function(res){
        $('#frmNew select[name=autonomous_community_id]').empty();
        $('#frmNew select[name=autonomous_community_id]').append($('<option>', {value:""}).text("Seleccione un opción"));
        res.data.forEach((item) => {
            $('#frmNew select[name=autonomous_community_id]').append($('<option>', {value:item.id}).text(item.name));
        });
    }).catch(function(err){
      console.log(err);
    })
});

$('#mdlEdit').on('show.bs.modal', function(){
    //Axios Http Get Request
    Core.get(route + '/get-autonomous-community').then(function(res){
        $('#frmEdit select[name=autonomous_community_id]').empty();
        $('#frmEdit select[name=autonomous_community_id]').append($('<option>', {value:""}).text("Seleccione un opción"));
        res.data.forEach((item) => {
            $('#frmEdit select[name=autonomous_community_id]').append($('<option>', {value:item.id}).text(item.name));
        });
    }).catch(function(err){
      console.log(err);
    })
});

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
