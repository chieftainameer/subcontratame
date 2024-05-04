route = "dashboard/notifications";
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
        {data: 'image', name: 'image', orderable: true, searchable: true},
        {data: 'title', name: 'title', orderable: true, searchable: true},
        {data: 'city', name: 'city', orderable: true, searchable: true},
        {data: 'date_start', name: 'date_start', orderable: true, searchable: true},
        {data: 'date_end', name: 'date_end', orderable: true, searchable: true},
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
            return `<img src="${row.image!=null?row.image:urlWeb+'/dashboard/app-assets/images/avatars/default-image.jpg'}" class="img-row">`
        }},
        {
            targets: 2, render: function ( data, type, row, meta ) {
            return row.title
        }},
        {
            targets: 3, render: function ( data, type, row, meta ) {
            return moment(row.date_start).format('DD/MM/YYYY')
        }},
        {
            targets: 4, render: function ( data, type, row, meta ) {
                return moment(row.date_end).format('DD/MM/YYYY')
        }},
        {
            targets: 5, render: function ( data, type, row, meta ) {
            return row.city;
        }},
        {
            targets: 6, render: function ( data, type, row, meta ) {
            return `<center>${row.status==1?'<span class="text-success">Activo</span>':'<span class="text-danger">Inactivo</span>'}</center>`
        }},
        {
            targets: 7, render: function ( data, type, row, meta ) {
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
    $('#frmEdit input[name=id]').val(itemData.id);
    $('#frmEdit input[name=title]').val(itemData.title);
    $('#frmEdit input[name=subtitle]').val(itemData.subtitle);
    $('#frmEdit select[name=city_id]').val(itemData.city_id);
    $('#frmEdit input[name=date_start]').val(itemData.date_start);
    $('#frmEdit input[name=date_end]').val(itemData.date_end);
    $('#frmEdit textarea[name=description]').val(itemData.description);
    $('#frmEdit select[name=status]').val(itemData.status);
    $('#frmEdit select[id=type]').val(itemData.type);
    $('#frmEdit img[id=imgProfile').attr('src',itemData.image?itemData.image.replace('&amp;', '&'):urlWeb+'/dashboard/app-assets/images/avatars/default-image.jpg');
}

$('#frmNew').validate({
  submitHandler: function (form) {
  itemData = new FormData(form);
  Core.crud.store().then(function(res) {
    Core.showToast('success','Registro exitoso');
    getData();
    $('#mdlNew').modal('hide');
    $('#frmNew')[0].reset();
    $('#frmNew img[id=imgProfile]').attr('src',urlWeb+'/dashboard/app-assets/images/avatars/default-image.jpg');
  })
  .catch(function (err) {
      console.log(err.response);
      if(err.response) {
          console.log(err.response.data.error);
        Core.showToast('error',err.response.data.error.message);
      } else {
        Core.showToast('error','No ha sido posible registrar paciente, verifique su informacón he intente nuevamente.');
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
        Core.showToast('error','No ha sido posible editar paciente, intente nuevamente');
    }).finally(function(){
        getData();
    })
}});

$('#mdlNew').on('hidden.bs.modal', function(){
    $('#frmNew')[0].reset();
    $('#frmNew img[id=imgProfile]').attr('src',urlBase+'dashboard/app-assets/images/avatars/default-user.png');
});

$('#mdlEdit').on('hidden.bs.modal', function(){
    $('#frmEdit')[0].reset();
    $('#frmNew img[id=imgProfile]').attr('src',urlBase+'dashboard/app-assets/images/avatars/default-image.png');
});

function imagePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#frmNew img[id=imgProfile]').attr('src', e.target.result);
            $('#frmEdit img[id=imgProfile]').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
