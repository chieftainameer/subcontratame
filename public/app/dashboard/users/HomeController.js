route = "dashboard/users";
arrData = [];
//var arrEstados = [];
console.log(urlBase);
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
        {data: 'name', name: 'name', orderable: true, searchable: true},
        {data: 'company_name', name: 'company_name', orderable: true, searchable: true},
        {data: 'email', name: 'email', orderable: true, searchable: true},
        {data: 'role', name: 'role', orderable: true, searchable: true},
        {data: 'status', name: 'status'},
        {data: 'status', name: 'status'},
    ],
    columnDefs: [
        {
            targets: 1, render: function ( data, type, row, meta ) {
            return row.id
        }},
        {
            targets: 2, render: function ( data, type, row, meta ) {
                let img = row.image == null ? urlWeb + '/dashboard/app-assets/images/avatars/default-user.png' : row.image === 'https://picsum.photos/200/300' ? 'https://picsum.photos/200/300' : urlWeb + '/storage/' + row.image.replace('&amp;','&');
                return '<center><img src="'+img+'" style="width:30px;height:30px; border-radius:50%;"></center>';
        }},
        {
            targets: 3, render: function ( data, type, row, meta ) {
            return row.name
        }},
        {
            targets: 4, render: function ( data, type, row, meta ) {
            return row.company_name
        }},
        {
            targets: 5, render: function ( data, type, row, meta ) {
            return row.email
        }},
        {
            targets: 6, render: function ( data, type, row, meta ) {
                switch (parseInt(row.role)) {
                    case 1:
                        return 'Administrador';
                    case 2:
                        return 'Usuario';
                    default:
                        return '-';
                }
        }},
        {
            targets: 7, render: function ( data, type, row, meta ) {
            return `<center>${ row.status == '0' ? '<span class="text-warning">Inactivo</span>' : row.status == '1' ? '<span class="text-success">Activo</span>' :  row.status == '2' ? '<span class="text-info">Pendiente</span>' :  row.status == '3' ? '<span class="text-danger">Bloqueado</span>':'' }</center>`
        }},
        {
            targets: 8, render: function ( data, type, row, meta ) {
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
        {
            targets: 0, render: function ( data, type, row, meta ) {
                let checkboxInp = `<input type="checkbox" class="departures-checkboxes" name="delete_departures" value = ${row.id} />`;
            return ` ${ row.status == 2 ? checkboxInp : ''}`; 
        }},
    ],
    drawCallback: function(settings) {
        feather.replace();
        arrData = this.api().rows().data();
    }
});

$("#borrarSeleccionados").click(function(){

    let ids = [];
    if(confirm("Estas seguro de que quieres ejecutar esta operación"))
    {
        $(".departures-checkboxes:checked").each(function(){
            ids.push($(this).val());
        });
    }
    if(ids.length > 0)
    {
       $.ajax({
        url: deleteRoute,
        method: 'POST',
        data: {
            "_token": csrfToken,
            "ids": ids
        },
        success: function(data){
            window.location.reload();
        },
        error: function(data){
            alert(data);
        }
       });
    }
    else
    {
        alert("Seleccione al menos un registro");
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
    itemData.role === 2 ? $('.show_field').fadeIn() : $('.show_field').fadeOut(); 
    $('#frmEdit input[name=email]').attr({required:false,style:'text-align:center;font-size:16px'});
    $('#frmEdit input[name=id]').val(itemData.id);
    $('#frmEdit select[name=legal_form_id]').val(itemData.legal_form_id).trigger('change');
    $('#frmEdit input[name=first_name]').val(itemData.first_name);
    $('#frmEdit input[name=last_name]').val(itemData.last_name);
    $('#frmEdit input[name=email]').val(itemData.email);
    $('#frmEdit input[name=cellphone]').val(itemData.cellphone);
    setTimeout(() => {
        $('#frmEdit textarea[name=tax_residence]').val(itemData.tax_residence).trigger('change');
        $('#frmEdit textarea[name=company_description]').val(itemData.company_description).trigger('change');
    }, 1000);
    $('#frmEdit input[name=company_name]').val(itemData.company_name);
    
    $('#frmEdit select[name=role]').val(itemData.role);
    $('#frmEdit select[name=status]').val(itemData.status);
    $('#frmEdit input[id=password]').empty().attr('required',false);
    $('#frmEdit input[id=password_confirmation]').empty().attr('required',false);
    $('#frmEdit img[id=imgProfile').attr('src',itemData.image == 'https://picsum.photos/200/300' ? 'https://picsum.photos/200/300' : (itemData.image!=null?urlWeb + '/storage/' + itemData.image.replace('&amp;','&'): urlWeb + '/dashboard/app-assets/images/avatars/default-user.png'));
    $('#frmEdit input[name=nif]').val(itemData.nif);
    $('#frmEdit input[name=position]').val(itemData.position);
    $('#frmEdit input[name=key_words]').tagsinput('add', itemData.key_words);

    if(itemData.hide_email == 1)
    {
        $('#frmEdit input[name=hide_email]').attr('checked',true);
    }

    if(itemData.hide_cellphone == 1)
    {
        $('#frmEdit input[name=hide_cellphone]').attr('checked',true);
    }  
    
    $('#frmEdit select[name=autonomous_community]').val(itemData.province.community.id).trigger('change');
    axios.get('/dashboard/users/get-province/' + itemData.province.community.id)
    .then(function (res) {
        $('#frmEdit select[id=province_id]').empty().trigger('change');
        res.data.data.forEach((item) => {
            $('#frmEdit select[id=province_id]').append($('<option>',{value:item.id}).text(item.name)).trigger('change');
        });
    })
    .catch(function (err) {
        console.log(err);
        Core.showAlert('error', err.response.data.error.message);
    });

    itemData.categories.forEach((item) => {
        $(`#frmEdit input:checkbox[name=category][value='${item.id}']`).prop('checked', true);
    });

    itemData.payment_methods.forEach((item) => {
        $(`#frmEdit input:checkbox[name=payment_method][value='${item.id}']`).prop('checked', true);
    });
}

$('#frmNew').validate({
   // Email Validation
  rules:{
    email:{
        remote:{
            url: 'users/check-mail',
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{
                email: function(){
                    return $('#frmNew input[name=email]').val();
                }
            }
       },
    },
    password:{
        minlength:8
    },
    password_confirmation:{
        equalTo: function(){
            return $('#frmNew input[name=password]'); 
        }
    }
  },
  messages:{
    email:{
        remote: 'El correo ingresado ya esta registrado.'
    }
  },
  submitHandler: function (form) {
  itemData = new FormData(form);
  itemData.delete('password_confirmation');
  itemData.delete('autonomous_community');
  $('#frmNew input[type=checkbox]:checked').each(function() {
            if(this.checked){
                if($(this).attr('name') === 'category'){
                    itemData.append('categories[]', $(this).val());
                }
                else if($(this).attr('name') === 'payment_method'){
                    itemData.append('payment_methods[]', $(this).val());
                }
            }
   });
   itemData.delete('category[]');
   itemData.delete('payment_method[]');
  // validate password confirm
  if($('#frmNew input[id=password]').val() != '' && $('#frmNew input[id=password_confirmation]').val() != '') {
    if($('#frmNew input[id=password]').val() != $('#frmNew input[id=password_confirmation]').val()) {
        toastr.error('Las contraseñas no coinciden');
        return false;
    }
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

$('#frmNew select[name=legal_form_id]').on('change',function(){
    if($(this).val() !== ''){
        if($('#frmNew select[name=legal_form_id] option:selected').text() == 'Otro'){
            $('.view_other').fadeIn();
        }
        else{
            $('#frmNew input[name=other]').val("");
            $('.view_other').fadeOut();
        }
    }
    else{
        $('#frmNew input[name=other]').val("");
        $('.view_other').fadeOut();
    }
});

$('#frmEdit').validate({
    rules:{
        password:{
            minlength:8  
        },
        password_confirmation:{
            equalTo: function(){
                return $('#frmEdit input[name=password]'); 
            }
        }
    },
    submitHandler: function (form) {
    itemData = new FormData(form);
    itemData.delete('password_confirmation');
    itemData.delete('autonomous_community');
    $('#frmEdit input[type=checkbox]:checked').each(function() {
            if(this.checked){
                if($(this).attr('name') === 'category'){
                    itemData.append('categories[]', $(this).val());
                }
                else if($(this).attr('name') === 'payment_method'){
                    itemData.append('payment_methods[]', $(this).val());
                }
            }
    });
    itemData.delete('category[]');
    itemData.delete('payment_method[]');
    
    // validate password confirm
    if($('#frmEdit input[id=password]').val() != '' || $('#frmEdit input[id=password_confirmation]').val() != '') {
        if($('#frmEdit input[id=password]').val() != $('#frmEdit input[id=password_confirmation]').val()) {
            toastr.error('Las contraseñas no coinciden');
        }
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

$('#frmNew select[name=role]').on('change', function(){
    if($(this).val() !== ''){
        if($('#frmNew select[name=role] option:selected').text() === 'Administrador'){
            $('.show_field').fadeOut();
            $('#frmNew input[name=nif]').attr('required', false);
            $('#frmNew select[name=legal_form_id]').attr('required', false);
            $('#frmNew input[name=company_name]').attr('required', false);
            $('#frmNew textarea[name=company_description]').attr('required', false);
            $('#frmNew textarea[name=tax_residence]').attr('required', false);
            $('#frmNew input[name=position]').attr('required', false);
            $('#frmNew input[name=cellphone]').attr('required', false);
            $('#frmNew input[name=category]').attr('required', false);
            $('#frmNew input[name=payment_method]').attr('required', false);

            $('#frmNew input[name=nif]').val('');
            $('#frmNew select[name=legal_form_id]').val('');
            $('#frmNew input[name=company_name]').val('');
            $('#frmNew textarea[name=company_description]').val('');
            $('#frmNew textarea[name=tax_residence]').val('');
            $('#frmNew input[name=position]').val('');
            $('#frmNew input[type=checkbox]').each(function(){
                if(this.checked){
                    $(this).prop('checked', false);
                }
            });

        }
        else if($('#frmNew select[name=role] option:selected').text() === 'Usuario'){
            $('.show_field').fadeIn();
            $('#frmNew input[name=nif]').attr('required', true);
            $('#frmNew select[name=legal_form_id]').attr('required', true);
            $('#frmNew input[name=company_name]').attr('required', true);
            $('#frmNew textarea[name=company_description]').attr('required', true);
            $('#frmNew textarea[name=tax_residence]').attr('required', true);
            $('#frmNew input[name=position]').attr('required', true);
            $('#frmNew input[name=cellphone]').attr('required', true);
            $('#frmNew input[name=category]').attr('required', true);
            $('#frmNew input[name=payment_method]').attr('required', true);

            $('#frmNew input[name=nif]').val('');
            $('#frmNew select[name=legal_form_id]').val('');
            $('#frmNew input[name=company_name]').val('');
            $('#frmNew textarea[name=company_description]').val('');
            $('#frmNew textarea[name=tax_residence]').val('');
            $('#frmNew input[name=position]').val('');
            $('#frmNew input[type=checkbox]').each(function(){
                if(this.checked){
                    $(this).prop('checked', false);
                }
            });
        }
    }
    else{
        
    }
});

$('#frmEdit select[name=role_e]').on('change', function(){
    if($(this).val() !== ''){
        if($('#frmEdit select[name=role] option:selected').text() === 'Administrador'){
            $('.show_field').fadeOut();
            $('#frmEdit input[name=nif]').attr('required', false);
            $('#frmEdit select[name=legal_form_id]').attr('required', false);
            $('#frmEdit input[name=company_name]').attr('required', false);
            $('#frmEdit textarea[name=company_description]').attr('required', false);
            $('#frmEdit textarea[name=tax_residence]').attr('required', false);
            $('#frmEdit input[name=position]').attr('required', false);
            $('#frmEdit input[name=cellphone]').attr('required', false);

            $('#frmEdit input[name=nif]').val('');
            $('#frmEdit select[name=legal_form_id]').val('');
            $('#frmEdit input[name=company_name]').val('');
            $('#frmEdit textarea[name=company_description]').val('');
            $('#frmEdit textarea[name=tax_residence]').val('');
            $('#frmEdit input[name=position]').val('');
            $('#frmEdit input[type=checkbox]').each(function(){
                if(this.checked){
                    $(this).prop('checked', false);
                }
            });
        }
        else if($('#frmEdit select[name=role] option:selected').text() === 'Usuario'){
            $('.show_field').fadeIn();
            $('#frmEdit input[name=nif]').attr('required', true);
            $('#frmEdit select[name=legal_form_id]').attr('required', true);
            $('#frmEdit input[name=company_name]').attr('required', true);
            $('#frmEdit textarea[name=company_description]').attr('required', true);
            $('#frmEdit textarea[name=tax_residence]').attr('required', true);
            $('#frmEdit input[name=position]').attr('required', true);
            $('#frmEdit input[name=cellphone]').attr('required', true);

            $('#frmEdit input[name=nif]').val('');
            $('#frmEdit select[name=legal_form_id]').val('');
            $('#frmEdit input[name=company_name]').val('');
            $('#frmEdit textarea[name=company_description]').val('');
            $('#frmEdit textarea[name=tax_residence]').val('');
            $('#frmEdit input[name=position]').val('');
            $('#frmEdit input[type=checkbox]').each(function(){
                if(this.checked){
                    $(this).prop('checked', false);
                }
            });
        }
    }
});

$('#frmEdit select[name=legal_form_id]').on('change',function(){
    if($(this).val() !== ''){
        if($('#frmEdit select[name=legal_form_id] option:selected').text() == 'Otro'){
            $('.view_other').fadeIn();
        }
        else{
            $('#frmEdit input[name=other]').val("");
            $('.view_other').fadeOut();
        }
    }
    else{
        $('#frmEdit input[name=other]').val("");
        $('.view_other').fadeOut();
    }
});

$('#frmNew select[id=autonomous_community]').on('change', function(){
    if($(this).val() !== ''){
        axios.get('/dashboard/users/get-province/' + $('#frmNew select[id=autonomous_community]').val())
                .then(function (res) {
                    $('#frmNew select[id=province_id]').empty().trigger('change');
                    //$('#frmCompleteRegistration select[id=province_id]').append($('<option>',{value:''}).text('Selecciona una opción')).trigger('change');
                    res.data.data.forEach((item) => {
                        $('#frmNew select[id=province_id]').append($('<option>',{value:item.id}).text(item.name)).trigger('change');
                    });
                })
                .catch(function (err) {
                    console.log(err);
                    Core.showAlert('error', err.response.data.error.message);
                });
    }
    else{
        $('#frmNew select[id=province_id]').empty().trigger('change');
        $('#frmNew select[id=province_id]').append($('<option>',{value:''}).text('Selecciona una opción')).trigger('change');
    }
});

$('#frmEdit select[id=autonomous_community]').on('change', function(){
    if($(this).val() !== ''){
        axios.get('/dashboard/users/get-province/' + $('#frmEdit select[id=autonomous_community]').val())
                .then(function (res) {
                    $('#frmEdit select[id=province_id]').empty().trigger('change');
                    //$('#frmCompleteRegistration select[id=province_id]').append($('<option>',{value:''}).text('Selecciona una opción')).trigger('change');
                    res.data.data.forEach((item) => {
                        $('#frmEdit select[id=province_id]').append($('<option>',{value:item.id}).text(item.name)).trigger('change');
                    });
                })
                .catch(function (err) {
                    console.log(err);
                    Core.showAlert('error', err.response.data.error.message);
                });
    }
    else{
        $('#frmEdit select[id=province_id]').empty().trigger('change');
        $('#frmEdit select[id=province_id]').append($('<option>',{value:''}).text('Selecciona una opción')).trigger('change');
    }
});

// $('#frmEdit select[name=legal_form_id]').change(function(){
//     if($(this).val() !== ''){
//         if($(this).text() === 'Otro'){$('.view_other').fadeIn();}
//         else{$('.view_other').fadeOut();}
//     }
//     else{$('.view_other').fadeOut();}
// });

$('#mdlNew').on('show.bs.modal', function(){
    $('#frmNew input[name=email]').attr('readonly', false);
    $('#frmNew select[name=role]').attr('required', true);
    $('#frmNew input[name=first_name]').attr('required', true);
    $('#frmNew input[name=last_name]').attr('required', true);
    $('#frmNew input[name=email]').attr('required', true);
    $('#frmNew input[name=cellphone]').attr('required', true);
    $('#frmNew input[name=password]').attr('required', true);
    $('#frmNew input[name=password_confirmation]').attr('required', true);
    $('#frmNew select[name=status]').attr('required', true);
    $('#frmNew select[name=autonomous_community]').attr('required', true);
    $('#frmNew select[name=province_id]').attr('required', true);

    $('#frmNew img[id=imgProfile]').attr('src',urlBase+'dashboard/app-assets/images/avatars/default-user.png');
});

$('#mdlNew').on('hidden.bs.modal', function(){
    $('.show_field').fadeOut();
    $('#frmNew')[0].reset();
    $('#frmNew').validate().resetForm();
    $('input,textarea,select').removeClass('is-invalid');
    $('#frmNew input[name=key_words]').tagsinput('removeAll');
    $('#frmNew img[id=imgProfile]').attr('src',urlBase+'dashboard/app-assets/images/avatars/default-user.png');
    $('#frmNew input[type=checkbox]').each(function(){
        if(this.checked){
            $(this).prop('checked', false);
        }
    });
});

$('#mdlEdit').on('hidden.bs.modal', function(){
    $('.show_field').fadeOut();
    $('#frmEdit')[0].reset();
    $('#frmEdit').validate().resetForm();
    $('input,textarea,select').removeClass('is-invalid');
    $('#frmEdit input[name=key_words]').tagsinput('removeAll');
    $('#frmEdit img[id=imgProfile]').attr('src',urlBase+'dashboard/app-assets/images/avatars/default-user.png');
    $('#frmEdit input[type=checkbox]').each(function(){
        if(this.checked){
            $(this).prop('checked', false);
        }
    });
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

function init(){
    $('.view_other').fadeOut();
    $('.show_field').fadeOut();
    // $('#frmNew textarea[name=tax_residence]').richText({
    //     // code
    //     removeStyles: false,
    //     code: false,
    // });
    // $('#frmNew textarea[name=company_description]').richText({
    //     // code
    //     removeStyles: false,
    //     code: false,
    // });
    // $('#frmEdit textarea[name=tax_residence]').richText({
    //     // code
    //     removeStyles: false,
    //     code: false,
    // });
    // $('#frmEdit textarea[name=company_description]').richText({
    //     // code
    //     removeStyles: false,
    //     code: false,
    // });
}

init();