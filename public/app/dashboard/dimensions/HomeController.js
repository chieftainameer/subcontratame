route = "dashboard/dimensions";
arrData = [];
//var arrEstados = [];
console.log(urlBase);
tblData = $('#dataTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: urlBase+route+"/datatables",
    language: {
        url: urlBase+'app/Spanish.json',
        pageLength: 10
    },
    columns: [
        {data: 'id', name: 'id', orderable: true, searchable: true},
        {data: 'name', name: 'name', orderable: true, searchable: true},
        {data: 'created_at', name: 'created_at'},
    ],
    columnDefs: [
        {
            targets: 0, render: function ( data, type, row, meta ) {
                return row.id;
            }},
        {
            targets: 1, render: function ( data, type, row, meta ) {
                return row.name;
            }},
        {
            targets: 2, render: function ( data, type, row, meta ) {
                return `<center>
                            <div class="btn-group dropleft">
                                <i data-feather="grid" data-toggle="dropdown" id="btnOpciones" data-index='${meta.row}' data-toggle="modal" data-target="#mdlOpciones" style="cursor: pointer;"></i>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" type="button" id="btnEdit" data-index='${meta.row}' data-toggle="modal" data-target="#mdlEdit"><i class="fa fa-edit text-warning"></i> Editar</button>
                                    <button class="dropdown-item" type="button" id="btnDelete" data-index='${meta.row}'><i class="fa fa-trash text-danger"></i> Eliminar</button>
                                </div>
                            </div>
                        </center>`;
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
};

window.showItem = function() {
    console.log(itemData);
    $('#frmEdit input[name=id]').val(itemData.id);
    $('#frmEdit input[name=name]').val(itemData.name);

}

$('#frmEdit').validate({

    submitHandler: function (form) {
        itemData = new FormData(form);

        // validate password confirm
        if($('#frmEdit input[id=name]').val() != '') {

            //Axios Http Post Request
            Core.crud.update($('#frmEdit input[id=id]').val()).then(function(res) {
                Core.showToast('success','Registro editado exitosamente');
                $('#mdlEdit').modal('hide');
            }).catch(function(err) {
                Core.showToast('error',err.response.data.error.message);
            }).finally(function(){
                getData();
            })
        } else {
            //Axios Http Post Request
            Core.crud.update($('#frmEdit input[id=id]').val()).then(function(res) {
                Core.showToast('success','Registro editado exitosamente');
                getData();
                $('#mdlEdit').modal('hide');
            }).catch(function(err) {
                Core.showToast('error',err.response.data.error.message);
            });
        }
    }});

$('#frmNew').validate({
    // Email Validation
    submitHandler: function (form) {
        itemData = new FormData(form);

        // validate password confirm
        if($('#frmNew input[id=name]').val() != '' ) {
            Core.crud.store().then(function(res) {
                Core.showToast('success','Registro exitoso');
                getData();
                $('#mdlNew').modal('hide');
            })
                .catch(function (err) {
                    console.log(err.response);
                    if(err.response) {
                        console.log(err.response.data.error);
                        Core.showToast('error',err.response.data.error.message);
                    } else {
                        Core.showToast('error','No ha sido posible registrar, verifique su informacón he intente nuevamente.');
                    }
                })
        }
    }});