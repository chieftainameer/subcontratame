route = 'dashboard/projects';
arrData = [];
//var arrEstados = [];
tblData = $('#dataTable').DataTable({
    processing: true,
    serverSide: true,
    ajax: urlBase+route+"/datatables",
    language: {
        url: urlBase+'app/Spanish.json',
        pageLength: 5
    },
    columns: [
        {data: 'image', name: 'image', orderable: true, searchable: true},
        {data: 'code', name: 'code', orderable: true, searchable: true},
        {data: 'title', name: 'title', orderable: true, searchable: true},
        {data: 'company_name', name: 'company_name', orderable: true, searchable: true},
        {data: 'status', name: 'status'},
        {data: 'status', name: 'status'},
    ],
    columnDefs: [
        {
            targets: 0, render: function ( data, type, row, meta ) {
                let img = row.image == null ? urlWeb + '/dashboard/app-assets/images/avatars/default-user.png' : row.image === 'https://picsum.photos/200/300' ? 'https://picsum.photos/200/300' : urlWeb + '/storage/' + row.image.replace('&amp;','&');
                return `<center><img src="${ img }" style="width:30px;height:30px; border-radius:50%;"></center>`;
        }},
        {
            targets: 1, render: function ( data, type, row, meta ) {
            return row.code
        }},
        {
            targets: 2, render: function ( data, type, row, meta ) {
            return row.title
        }},
        {
            targets: 3, render: function ( data, type, row, meta ) {
            return row.company_name;
        }},
        {
            targets: 4, render: function ( data, type, row, meta ) {
                switch (parseInt(row.status)) {
                    case 0:
                        return `<center>Creado</center>`;
                        break;
                    case 1:
                        return `<center>Publicado</center>`;
                        break;
                    case 2:
                        return `<center>Vencido</center>`;
                        break;
                    default:
                        return '-';
                }
        }},
        {
            targets: 5, render: function ( data, type, row, meta ) {
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
}

window.showItem = function() {
    console.log(itemData);
    //itemData.role === 2 ? $('.show_field').fadeIn() : $('.show_field').fadeOut(); 
    // $('#frmEdit input[name=email]').attr({required:false,style:'text-align:center;font-size:16px'});
    // $('#frmEdit input[name=id]').val(itemData.id);
    // $('#frmEdit select[name=legal_form_id]').val(itemData.legal_form_id).trigger('change');
    // $('#frmEdit input[name=first_name]').val(itemData.first_name);
    // $('#frmEdit input[name=last_name]').val(itemData.last_name);
    // $('#frmEdit input[name=email]').val(itemData.email);
    // $('#frmEdit input[name=cellphone]').val(itemData.cellphone);
    // setTimeout(() => {
    //     $('#frmEdit textarea[name=tax_residence]').val(itemData.tax_residence).trigger('change');
    //     $('#frmEdit textarea[name=company_description]').val(itemData.company_description).trigger('change');
    // }, 1000);
    // $('#frmEdit input[name=company_name]').val(itemData.company_name);
    
    // $('#frmEdit select[name=role]').val(itemData.role);
    // $('#frmEdit select[name=status]').val(itemData.status);
    // $('#frmEdit input[id=password]').empty().attr('required',false);
    // $('#frmEdit input[id=password_confirmation]').empty().attr('required',false);
    // $('#frmEdit img[id=imgProfile').attr('src',itemData.image == 'https://picsum.photos/200/300' ? 'https://picsum.photos/200/300' : (itemData.image!=null?urlWeb + '/storage/' + itemData.image.replace('&amp;','&'): urlWeb + '/dashboard/app-assets/images/avatars/default-user.png'));
    // $('#frmEdit input[name=nif]').val(itemData.nif);
    // $('#frmEdit input[name=position]').val(itemData.position);
    // $('#frmEdit input[name=key_words]').tagsinput('add', itemData.key_words); 
    
    // $('#frmEdit select[name=autonomous_community]').val(itemData.province.community.id).trigger('change');
    // axios.get('/dashboard/users/get-province/' + itemData.province.community.id)
    // .then(function (res) {
    //     $('#frmEdit select[id=province_id]').empty().trigger('change');
    //     res.data.data.forEach((item) => {
    //         $('#frmEdit select[id=province_id]').append($('<option>',{value:item.id}).text(item.name)).trigger('change');
    //     });
    // })
    // .catch(function (err) {
    //     console.log(err);
    //     Core.showAlert('error', err.response.data.error.message);
    // });

    // itemData.categories.forEach((item) => {
    //     $(`#frmEdit input:checkbox[name=category][value='${item.id}']`).prop('checked', true);
    // });

    // itemData.payment_methods.forEach((item) => {
    //     $(`#frmEdit input:checkbox[name=payment_method][value='${item.id}']`).prop('checked', true);
    // });
}


function init(){
    getData();
}

init();