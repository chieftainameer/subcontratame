route = "dashboard/payments";
arrData = [];
//arrEstados = [];
tblData = $('#dataTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: urlBase + route + "/datatables",
    language: {
        url: urlBase + 'app/Spanish.json',
        pageLength: 5
    },
    columns: [{
                data: 'code',
                name: 'code'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'date_start',
                name: 'date_start'
            },
            {
                data: 'date_end',
                name: 'date_end'
            },
            {
                data: 'quantity',
                name: 'quantity'
            },
            {
                data: 'type',
                name: 'type'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'status',
                name: 'status',
            }
    ],
    columnDefs: [{
            targets: 0,
            render: function(data, type, row, meta) {
                return row.code;
            }
        },
        {
            targets: 1,
            render: function(data, type, row, meta) {
                return row.name;
            }
        },
        {
            targets: 2,
            render: function(data, type, row, meta) {
                return row.date_start !== null ? moment(row.date_start).format('DD/MM/YYYY') : '';
            }
        },
        {
            targets: 3,
            render: function(data, type, row, meta) {
                return row.date_end !== null ? moment(row.date_end).format('DD/MM/YYYY') : '';
            }
        },
        {
            targets: 4,
            render: function(data, type, row, meta) {
                return row.quantity;
            }
        },
        {
            targets: 5,
            render: function(data, type, row, meta) {
                return `<center>${ row.type == '0' ? 'Empresa' : 'Titular' }</center>`;
            }
        },
        {
            targets: 6,
            render: function(data, type, row, meta) {
                return `<center>${ row.status == '0' ? '<span class="text-warning">Pendiente</span>' : row.status === '1' ? '<span class="text-success">Aprobado</span>' : '<span class="text-danger">Rechazado</span>'}</center>`
            }
        },
        {
            targets: 7,
            render: function(data, type, row, meta) {
                //return Core.buttons(meta.row)
                return `<center>
                            <div class="btn-group dropleft">
                                <i data-feather="grid" data-toggle="dropdown" id="btnOpciones" data-index='${meta.row}' data-toggle="modal" data-target="#mdlOpciones" style="cursor: pointer;"></i>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" type="button" id="btnDetails" data-index='${meta.row}' data-toggle="modal" data-target="#mdlDetails"><i class="fa fa-eye text-info"></i> Detalles</button>
                                    <button class="dropdown-item" type="button" id="btnEdit" data-index='${meta.row}' data-toggle="modal" data-target="#mdlEdit"><i class="fa fa-edit text-info"></i> Editar</button>
                                </div>
                            </div>
                        </center>`;
            }
        },
    ],
    // order: [
    //     [2, 'desc']
    // ],
    drawCallback: function(settings) {
        feather.replace();
        arrData = this.api().rows().data();
    }
});

window.getData = function() {
    //each reload datatable set data to arrData Array
    tblData.ajax.reload(function(json) {
        arrData = json.data;
    });
}

$(document).on('click', '#btnDetails', function() {
    data = $(this).data('index');
    itemData = arrData[data];
    let html = `<div class="row">
                    <div class="col-md-12"> 
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Código</label>
                                    <input type="text" class="form-control" value="${itemData.code !== null ? itemData.code : 'Sin asignar'}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Renovado</label>
                                    <input type="text" class="form-control" value="${itemData.renewal == 0 ? 'No' : 'Sí'}" readonly>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Data</label>
                                    <input type="text" class="form-control" value="${( itemData.data !== null && itemData.data !== '') ? itemData.data : 'Sin asignar'}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Nombre</label>
                                    <input type="text" class="form-control" value="${itemData.name}" readonly>
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Fecha</label>
                                        <input type="text" class="form-control" value="${moment(itemData.date).format('DD/MM/YYYY')}" readonly> 
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Fecha Inicio</label>
                                        <input type="text" class="form-control" value="${moment(itemData.date_start).format('Do MMMM')}" readonly> 
                                    </div>  
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Fecha Fin</label>
                                        <input type="text" class="form-control" value="${moment(itemData.date_end).format('Do MMMM')}" readonly> 
                                    </div>  
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Estado</label>
                                        <input type="text" class="form-control" value="${itemData.status == '0' ? 'Pendiente' : itemData.status == '1' ? 'Pagado' : 'Rechazado'}" readonly> 
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
    $('#details_view').empty().html(html);
    $('#mdlDetails').modal('show');

});

$('#frmNew input[name=quantity]').on('blur',function(){
    if($(this).val() !== ''){
        $('#frmNew input[name=total_amount]').val((parseFloat($(this).val()) * parseFloat($('#frmNew input[name=price_per_code]').val())).toFixed(2));
    }
    else{
        $('#frmNew input[name=total_amount]').val('');
    }
});

$('#frmNew').validate({
    submitHandler: function(form) {
        itemData = new FormData(form);
        itemData.set('date_start', moment($('#frmNew input[name=date_start]').val()).format('YYYY-MM-DD'));
        itemData.set('date_end', moment($('#frmNew input[name=date_end]').val()).format('YYYY-MM-DD'));
        //Axios Http Post Request
        Core.post(route + '/store', itemData).then(function(res) {
            Core.showToast('success', res.data.message);
        }).catch(function(err) {
            console.log(err);
        }).finally(function() {
            $('#mdlNew').modal('hide');
            $('#frmNew')[0].reset();
            $('#frmNew').validate().resetForm();
            $('#tblData').DataTable().ajax.reload();
        });
    }
});

$('#mdlNew').on('show.bs.modal', function() {
    //Axios Http Get Request
    Core.get(route + '/get-code').then(function(res) {
        $('#frmNew input[name=base_code]').val(res.data.code);
    }).catch(function(err) {
        console.log(err);
    })
});

function init(){
    // $('#frmNew select[name=enterprise_id]').select2({
    //     placeholder: 'Seleccione una empresa',
    // });
}

init();