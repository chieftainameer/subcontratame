route = '/client/projects/my-projects/departures';

var arrOptions = [];
var arrOptionsEdit = [];
var arrFiles = [];
var arrDeleteVariables = [];
var objectFiles = new Object();
var base64 = '';

$('#per-page').on('change',function(){
    $('#formPartidas').submit();
});

$.validator.setDefaults({
    errorElement: 'span',
     errorPlacement: function (error, element) {
         error.addClass('invalid-feedback');
         element.closest('.form-group').append(error);
     },
     highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
     },
     unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
     },
});

jQuery.validator.addMethod('ckrequired', function (value, element, params) {
    var idname = jQuery(element).attr('name');
    var messageLength =  jQuery.trim ( CKEDITOR.instances[idname].getData() );
    return !params  || messageLength.length !== 0;
}, 'Este campo es requerido');

var tableVariableDataNew = $("#frmNew table[id=tableVariableData]").DataTable({
    destroy: true,
    info: false,
    paging :false,
    searching: false,
    ordering: false,
    columnDefs:[
        { targets: [0], visible: true, searchable: true, orderable: false },   // description
        { targets: [1], visible: true, searchable: true, orderable: false },   // options
        { targets: [2], visible: true, searchable: true, orderable: false },   // required
        { targets: [3], visible: true, searchable: true, orderable: false },   // visible
        { targets: [4], visible: true, searchable: false, orderable: false },  // Acciones
        
        { targets: [5], visible: false, searchable: true, orderable: false }, // type
        { targets: [6], visible: false, searchable: false, orderable: false }, // options
        { targets: [7], visible: false, searchable: false, orderable: false }, // text
        { targets: [8], visible: false, searchable: false, orderable: false }, // id
        { targets: [9], visible: false, searchable: false, orderable: false },  // file (up)
        { targets: [10], visible: false, searchable: false, orderable: false }, // Aux file (down)
        { targets: [11], visible: false, searchable: false, orderable: false }, // Download file (up)
        { targets: [12], visible: false, searchable: false, orderable: false }, // Aux download file (down)
    ],      
});

var tableVariableDataEdit = $("#frmEdit table[id=tableVariableData]").DataTable({
    destroy: true,
    info: false,
    paging :false,
    searching: false,
    ordering: false,
    columnDefs:[
        { targets: [0], visible: true, searchable: true, orderable: false },   // description
        { targets: [1], visible: true, searchable: true, orderable: false },   // options
        { targets: [2], visible: true, searchable: true, orderable: false },   // required
        { targets: [3], visible: true, searchable: true, orderable: false },   // visible
        { targets: [4], visible: true, searchable: false, orderable: false },  // Acciones
        
        { targets: [5], visible: false, searchable: true, orderable: false }, // type
        { targets: [6], visible: false, searchable: false, orderable: false }, // options
        { targets: [7], visible: false, searchable: false, orderable: false }, // text
        { targets: [8], visible: false, searchable: false, orderable: false }, // id
        { targets: [9], visible: false, searchable: false, orderable: false }, // file (up)
        { targets: [10], visible: false, searchable: false, orderable: false }, // Aux file (down)
        { targets: [11], visible: false, searchable: false, orderable: false }, // Download file (up)
        { targets: [12], visible: false, searchable: false, orderable: false }, // Aux download file (down)
    ],      
});

$('#frmVariableNew').validate({
  messages:{
    type:{
        required: 'Debe seleccionar un tipo de variable'
    }
  },
  submitHandler: function (form) {
    let aplication;

    if($('#frmVariableNew input:checkbox[id=visible]').is(':checked')){
        if($('#frmVariableNew input[id=modality]').val() === 'new'){
            buttons = `<center>
                            <div class="d-flex">
                                <div class="dropdown" style="width: 50%">
                                    <i class="fa fa-bars" aria-hidden="true" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" style="cursor: pointer"></i>
                                    <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu2"
                                        style="width: 100%">
                                        <a class="dropdown-item btnDelete text-danger" style="cursor: pointer"><i class="fa fa-trash"></i>&nbspEliminar</a>
                                    </div>
                                </div>
                                <div style="width: 50%">
                                    <i class="fa fa-eye text-primary" aria-hidden="true"></i>
                                </div>
                            </div>
                        </center>`;
        }  
        else if($('#frmVariableNew input[id=modality]').val() === 'edit'){
            buttons = `<center>
                            <div class="d-flex">
                                <div class="dropdown" style="width: 50%">
                                    <i class="fa fa-bars" aria-hidden="true" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" style="cursor: pointer"></i>
                                    <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu2"
                                        style="width: 100%">
                                        <a class="dropdown-item btnDeleteEdit text-danger" style="cursor: pointer"><i class="fa fa-trash"></i>&nbspEliminar</a>
                                    </div>
                                </div>
                                <div style="width: 50%">
                                    <i class="fa fa-eye text-primary" aria-hidden="true"></i>
                                </div>
                            </div>
                        </center>`;
        }
    }
    else{
        if($('#frmVariableNew input[id=modality]').val() === 'new'){
            buttons = `<center>
                            <div class="d-flex">
                                <div class="dropdown" style="width: 50%">
                                    <i class="fa fa-bars" aria-hidden="true" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" style="cursor: pointer"></i>
                                    <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu2"
                                        style="width: 100%">
                                        <a class="dropdown-item btnDelete text-danger" style="cursor: pointer"><i class="fa fa-trash"></i>&nbspEliminar</a>
                                    </div>
                                </div>
                                <div style="width: 50%">
                                    <i class="fa fa-eye-slash text-primary" aria-hidden="true"></i>
                                </div>
                            </div>
                        </center>`;
        }  
        else if($('#frmVariableNew input[id=modality]').val() === 'edit'){
            buttons = `<center>
                            <div class="d-flex">
                                <div class="dropdown" style="width: 50%">
                                    <i class="fa fa-bars" aria-hidden="true" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" style="cursor: pointer"></i>
                                    <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu2"
                                        style="width: 100%">
                                        <a class="dropdown-item btnDeleteEdit text-danger" style="cursor: pointer"><i class="fa fa-trash"></i>&nbspEliminar</a>
                                    </div>
                                </div>
                                <div style="width: 50%">
                                    <i class="fa fa-eye-slash text-primary" aria-hidden="true"></i>
                                </div>
                            </div>
                        </center>`;
        }
    }
    
  // options
  if($('#frmVariableNew select[id=type]').val() === '1' || $('#frmVariableNew select[id=type]').val() === '2'){
    aplication = arrOptions.length;
    if($('#frmVariableNew input[id=modality]').val() === 'new'){
        tableVariableDataNew.row.add([
            $('#frmVariableNew input[id=description]').val(),
            aplication,
            $('#frmVariableNew input:checkbox[id=required]').is(':checked') ? 'Sí': 'No',
            $('#frmVariableNew input:checkbox[id=visible]').is(':checked') ? 'Sí' : 'No',
            buttons,
            $('#frmVariableNew select[id=type]').val(),
            JSON.stringify(arrOptions),
            '',
            '',
            '',
            '',
            '',
            ''
        ]).draw();
        $('#mdlVariableNew').modal('hide');
    }
    else if($('#frmVariableNew input[id=modality]').val() === 'edit'){
        tableVariableDataEdit.row.add([
            $('#frmVariableNew input[id=description]').val(),
            aplication,
            $('#frmVariableNew input:checkbox[id=required]').is(':checked') ? 'Sí': 'No',
            $('#frmVariableNew input:checkbox[id=visible]').is(':checked') ? 'Sí' : 'No',
            buttons,
            $('#frmVariableNew select[id=type]').val(),
            JSON.stringify(arrOptions),
            '',
            '',
            '',
            '',
            '',
            ''
        ]).draw();
        $('#mdlVariableNew').modal('hide');
    }
  } // Download Information
  else if($('#frmVariableNew select[id=type]').val() === '3'){
    aplication = 1;
    if($('#frmVariableNew input[id=modality]').val() === 'new'){
        tableVariableDataNew.row.add([
            $('#frmVariableNew input[id=description]').val(),
            aplication,
            $('#frmVariableNew input:checkbox[id=required]').is(':checked') ? 'Sí': 'No',
            $('#frmVariableNew input:checkbox[id=visible]').is(':checked') ? 'Sí' : 'No',
            buttons,
            $('#frmVariableNew select[id=type]').val(), // type
            '',
            '',
            '',
            '',
            '',
            $('#frmVariableNew input[id=filebase64]').val(),
            ''
        ]).draw();
        $('#mdlVariableNew').modal('hide');
    }
    else if($('#frmVariableNew input[id=modality]').val() === 'edit'){
        tableVariableDataEdit.row.add([
            $('#frmVariableNew input[id=description]').val(),
            aplication,
            $('#frmVariableNew input:checkbox[id=required]').is(':checked') ? 'Sí': 'No',
            $('#frmVariableNew input:checkbox[id=visible]').is(':checked') ? 'Sí' : 'No',
            // `<center>
            //     <div class="dropdown">
            //         <i class="fa fa-bars" aria-hidden="true" data-toggle="dropdown"
            //             aria-haspopup="true" aria-expanded="false" style="cursor: pointer"></i>
            //         <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu2"
            //             style="width: 100%">
            //             <a class="dropdown-item btnDeleteEdit text-danger" style="cursor: pointer"><i class="fa fa-trash"></i>&nbspEliminar</a>
            //         </div>
            //     </div>
            // </center>`,
            buttons,
            $('#frmVariableNew select[id=type]').val(), // type
            '',
            '',
            '',
            '',
            '',
            $('#frmVariableNew input[id=filebase64]').val(),
            ''
        ]).draw();
        $('#mdlVariableNew').modal('hide');
    } 
    
  } // Request Information
  else if($('#frmVariableNew select[id=type]').val() === '4'){
    aplication = 1;
    if($('#frmVariableNew input[id=modality]').val() === 'new'){
        tableVariableDataNew.row.add([
            $('#frmVariableNew input[id=description]').val(),
            aplication,
            $('#frmVariableNew input:checkbox[id=required]').is(':checked') ? 'Sí': 'No',
            $('#frmVariableNew input:checkbox[id=visible]').is(':checked') ? 'Sí' : 'No',
            buttons,
            $('#frmVariableNew select[id=type]').val(), // type
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ]).draw();
        $('#mdlVariableNew').modal('hide');
    }
    else if($('#frmVariableNew input[id=modality]').val() === 'edit'){
        tableVariableDataEdit.row.add([
            $('#frmVariableNew input[id=description]').val(),
            aplication,
            $('#frmVariableNew input:checkbox[id=required]').is(':checked') ? 'Sí': 'No',
            $('#frmVariableNew input:checkbox[id=visible]').is(':checked') ? 'Sí' : 'No',
            buttons,
            $('#frmVariableNew select[id=type]').val(), // type
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ]).draw();
        $('#mdlVariableNew').modal('hide');
    } 
    
  } // Text and Image
  else if($('#frmVariableNew select[id=type]').val() === '5'){
    aplication = 1;
    if($('#frmVariableNew input[id=modality]').val() === 'new'){
        if($('#frmVariableNew textarea[name=text]').val() === ''){
            Core.showToast('error','Debe agregar un texto');
        }
        else{
            tableVariableDataNew.row.add([
                $('#frmVariableNew input[id=description]').val(),
                aplication,
                $('#frmVariableNew input:checkbox[id=required]').is(':checked') ? 'Sí': 'No',
                $('#frmVariableNew input:checkbox[id=visible]').is(':checked') ? 'Sí' : 'No',
                buttons,
                $('#frmVariableNew select[id=type]').val(), // type
                '',
                $('#frmVariableNew textarea[id=text]').val(),
                '',
                $('#frmVariableNew input[id=filebase64]').val(),
                '',
                '',
                ''
            ]).draw();
            $('#mdlVariableNew').modal('hide');
        }
        
    }
    else if($('#frmVariableNew input[id=modality]').val() === 'edit'){
        if($('#frmVariableNew textarea[name=text]').val() === ''){
            Core.showToast('error','Debe ingresar un texto');
        }
        else{
            tableVariableDataEdit.row.add([
                $('#frmVariableNew input[id=description]').val(),
                aplication,
                $('#frmVariableNew input:checkbox[id=required]').is(':checked') ? 'Sí': 'No',
                $('#frmVariableNew input:checkbox[id=visible]').is(':checked') ? 'Sí' : 'No',
                buttons,
                $('#frmVariableNew select[id=type]').val(), // type
                '',
                $('#frmVariableNew textarea[id=text]').val(),
                '',
                $('#frmVariableNew input[id=filebase64]').val(),
                '',
                '',
                ''
            ]).draw();
            $('#mdlVariableNew').modal('hide');
        }
    } 
    
  }
  // Close window
}});

$('#frmVariableEdit').validate({
    messages:{
      type:{
          required: 'Debe seleccionar un tipo de variable'
      }
    },
    submitHandler: function (form) {
        let aplication;
        if($('#frmVariableEdit input:checkbox[id=visible]').is(':checked')){
            buttons = `<center>
                                <div class="d-flex">
                                    <div class="dropdown" style="width: 50%">
                                        <i class="fa fa-bars text-primary" aria-hidden="true" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false" style="cursor: pointer"></i>
                                        <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu2"
                                            style="width: 100%">
                                            <a class="dropdown-item btnEdit text-warning" onclick="editVariable(${$('#frmVariableEdit input[id=id]').val()},${$('#frmVariableEdit input[id=index]').val()})" style="cursor: pointer;"><i class="fa fa-pencil"></i>&nbsp;Editar</a>
                                            <a class="dropdown-item btnDelete text-danger" onclick="deleteVariable(${$('#frmVariableEdit input[id=id]').val()},${$('#frmVariableEdit input[id=index]').val()})" style="cursor: pointer;"><i class="fa fa-trash"></i>&nbsp;Eliminar</a>
                                        </div>
                                    </div>
                                    <div style="width: 50%">
                                        <i class="fa fa-eye text-primary" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </center>`;
        }
        else{
            buttons = `<center>
                                <div class="d-flex">
                                    <div class="dropdown" style="width: 50%">
                                        <i class="fa fa-bars text-primary" aria-hidden="true" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false" style="cursor: pointer"></i>
                                        <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu2"
                                            style="width: 100%">
                                            <a class="dropdown-item btnEdit text-warning" onclick="editVariable(${$('#frmVariableEdit input[id=id]').val()},${$('#frmVariableEdit input[id=index]').val()})" style="cursor: pointer;"><i class="fa fa-pencil"></i>&nbsp;Editar</a>
                                            <a class="dropdown-item btnDelete text-danger" onclick="deleteVariable(${$('#frmVariableEdit input[id=id]').val()},${$('#frmVariableEdit input[id=index]').val()})" style="cursor: pointer;"><i class="fa fa-trash"></i>&nbsp;Eliminar</a>
                                        </div>
                                    </div>
                                    <div style="width: 50%">
                                        <i class="fa fa-eye-slash text-primary" aria-hidden="true"></i>
                                    </div>
                                </div>
                        </center>`;
        }
    // options
    if($('#frmVariableEdit select[id=type]').val() === '1' || $('#frmVariableEdit select[id=type]').val() === '2'){
        aplication = arrOptionsEdit.length;

        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 0).data($('#frmVariableEdit input[id=description]').val());
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 1).data(aplication);
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 2).data($('#frmVariableEdit input:checkbox[id=required]').is(':checked')?'Sí':'No');
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 3).data($('#frmVariableEdit input:checkbox[id=visible]').is(':checked')?'Sí':'No');
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 4).data(buttons);
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 5).data($('#frmVariableEdit select[id=type]').val());
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 6).data(JSON.stringify(arrOptionsEdit));
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 7).data('');
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 8).data($('#frmVariableEdit input[id=id]').val());
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 9).data('');
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 10).data('');
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 11).data('');
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 12).data('');
        $('#mdlVariableEdit').modal('hide');
    } // Download Information
    else if($('#frmVariableEdit select[id=type]').val() === '3'){
      aplication = 1;
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 0).data($('#frmVariableEdit input[id=description]').val());
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 1).data(aplication);
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 2).data($('#frmVariableEdit input:checkbox[id=required]').is(':checked')?'Sí':'No');
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 3).data($('#frmVariableEdit input:checkbox[id=visible]').is(':checked')?'Sí':'No');
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 4).data(buttons);
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 5).data($('#frmVariableEdit select[id=type]').val());
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 6).data(JSON.stringify(arrOptionsEdit));
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 7).data('');
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 8).data($('#frmVariableEdit input[id=id]').val());
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 9).data('');
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 10).data('');
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 11).data($('#frmVariableEdit input[id=filebase64]').val());
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 12).data('');
      $('#mdlVariableEdit').modal('hide');
    } // Upload Information
    else if($('#frmVariableEdit select[id=type]').val() === '4'){
      aplication = 1;
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 0).data($('#frmVariableEdit input[id=description]').val());
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 1).data(aplication);
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 2).data($('#frmVariableEdit input:checkbox[id=required]').is(':checked')?'Sí':'No');
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 3).data($('#frmVariableEdit input:checkbox[id=visible]').is(':checked')?'Sí':'No');
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 4).data(buttons);
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 5).data($('#frmVariableEdit select[id=type]').val());
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 6).data('');
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 7).data('');
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 8).data($('#frmVariableEdit input[id=id]').val());
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 9).data('');
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 10).data('');
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 11).data('');
      tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 12).data('');
      $('#mdlVariableEdit').modal('hide');
    }
    else if($('#frmVariableEdit select[id=type]').val() === '5'){
      if($('#frmVariableEdit textarea[name=text]').val() === ''){
        Core.showToast('error','Debe ingresar un texto');
      }
      else{
        aplication = 1;
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 0).data($('#frmVariableEdit input[id=description]').val());
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 1).data(aplication);
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 2).data($('#frmVariableEdit input:checkbox[id=required]').is(':checked')?'Sí':'No');
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 3).data($('#frmVariableEdit input:checkbox[id=visible]').is(':checked')?'Sí':'No');
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 4).data(buttons);
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 5).data($('#frmVariableEdit select[id=type]').val());
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 6).data('');
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 7).data($('#frmVariableEdit textarea[id=text]').val());
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 8).data($('#frmVariableEdit input[id=id]').val());
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 9).data($('#frmVariableEdit input[id=filebase64]').val());
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 10).data('');
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 11).data('');
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 12).data('');
        $('#mdlVariableEdit').modal('hide');
      } 
      
    }
    // Close window
}});

// Eliminar un item de diagnostico
$(document).on("click", "a.btnDelete", function(e){
    e.preventDefault();
    tableVariableDataNew.row($(this).parents("tr")).remove().draw();
});

$(document).on("click", "a.btnDeleteEdit", function(e){
    e.preventDefault();
    tableVariableDataEdit.row($(this).parents("tr")).remove().draw();
});

$('#frmVariableNew select[name=type]').on('change', function(){
    
    if($(this).val() !== ''){
        // Simple Option And Multiple Option
        if($(this).val() === '1' || $(this).val() === '2'){
            $('#frmVariableNew input[id=description]').val('');
            $('#frmVariableNew ul[id=arrOptionsContainer]').empty();
            $('#frmVariableNew input[id=fileDownload]').prop('required', false);
            $('#frmVariableNew input[id=option]').val('');
            $('#frmVariableNew div[id=view_selection]').removeClass('d-none');
            $('#frmVariableNew div[id=view_download]').addClass('d-none');
            $('#frmVariableNew div[id=view_text]').addClass('d-none');
            $('#mdlVariableNew textarea[name=text]').val('');
            $('#mdlVariableNew img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
        }
        // Download Information
        else if($(this).val() === '3'){
            $('#frmVariableNew input[id=description]').val('');
            $('#frmVariableNew input[id=fileDownload]').val('');
            $('#frmVariableNew input[id=fileDownload]').prop('required', true);
            $('#frmVariableNew div[id=view_download]').removeClass('d-none');
            $('#frmVariableNew div[id=view_selection]').addClass('d-none');
            $('#frmVariableNew div[id=view_text]').addClass('d-none');
            $('#mdlVariableNew textarea[name=text]').val('');
            $('#mdlVariableNew img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
            arrOptions = [];
        } // Upload Information
        else if($(this).val() === '4'){
            $('#frmVariableNew input[id=description]').val('');
            $('#frmVariableNew input[id=fileDownload]').prop('required', false);
            $('#frmVariableNew div[id=view_text]').addClass('d-none');
            $('#frmVariableNew div[id=view_download]').addClass('d-none');
            $('#frmVariableNew div[id=view_selection]').addClass('d-none');
            $('#mdlVariableNew textarea[name=text]').val('');
            $('#mdlVariableNew img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
            arrOptions = [];
        } 
        else if($(this).val() === '5'){
            $('#frmVariableNew input[id=description]').val('');
            $('#frmVariableNew input[id=fileDownload]').prop('required', false);
            //$('#frmVariableNew input[id=file]').prop('required', true);
            $('#frmVariableNew div[id=view_text]').removeClass('d-none');
            $('#frmVariableNew div[id=view_download]').addClass('d-none');
            $('#frmVariableNew div[id=view_selection]').addClass('d-none');
            $('#mdlVariableNew textarea[name=text]').val('');
            $('#mdlVariableNew img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
            arrOptions = [];
        }
    }
    else{
        $('#frmVariableNew input[id=description]').val('');
        $('#frmVariableNew input[id=fileDownload]').prop('required', false);
        $('#frmVariableNew div[id=view_text]').addClass('d-none');
        $('#frmVariableNew div[id=view_download]').addClass('d-none');
        $('#frmVariableNew div[id=view_selection]').addClass('d-none');
        $('#mdlVariableNew textarea[name=text]').val('');
        $('#mdlVariableNew img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
        arrOptions = [];
    }
});

$('#frmVariableEdit select[name=type]').on('change', function(){
    
    if($(this).val() !== ''){
        // Simple Option And Multiple Option
        if($(this).val() === '1' || $(this).val() === '2'){
            $('#frmVariableEdit input[id=description]').val('');
            $('#frmVariableEdit textarea[id=text]').val('');
            $('#frmVariableEdit ul[id=arrOptionsContainer]').empty();
            $('#frmVariableEdit input[id=option]').val('');
            $('#frmVariableEdit div[id=view_selection]').removeClass('d-none');
            $('#frmVariableEdit div[id=view_download]').addClass('d-none');
            $('#frmVariableEdit div[id=view_text]').addClass('d-none');
            $('#mdlVariableEdit textarea[name=text]').val('');
            $('#mdlVariableEdit img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
        }
        // Download
        else if($(this).val() === '3'){
            $('#frmVariableEdit input[id=description]').val('');
            $('#frmVariableEdit textarea[id=text]').val('');            
            $('#frmVariableEdit input[id=fileDownload]').val('');
            $('#frmVariableEdit div[id=view_download]').removeClass('d-none');
            $('#frmVariableEdit div[id=view_selection]').addClass('d-none');
            $('#frmVariableEdit div[id=view_text]').addClass('d-none');
            $('#mdlVariableEdit textarea[name=text]').val('');
            $('#mdlVariableEdit img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
            arrOptionsEdit = [];
        }
        else if($(this).val() === '4'){
            $('#frmVariableEdit input[id=description]').val('');
            $('#frmVariableEdit textarea[id=text]').val('');
            $('#frmVariableEdit div[id=view_text]').addClass('d-none');
            $('#frmVariableEdit div[id=view_download]').addClass('d-none');
            $('#frmVariableEdit div[id=view_selection]').addClass('d-none');
            $('#mdlVariableEdit textarea[name=text]').val('');
            $('#mdlVariableEdit img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
            arrOptionsEdit = [];
        } 
        else if($(this).val() === '5'){
            $('#frmVariableEdit input[id=description]').val('');
            $('#frmVariableEdit textarea[id=text]').val('');
            $('#frmVariableEdit div[id=view_text]').removeClass('d-none');
            $('#frmVariableEdit div[id=view_download]').addClass('d-none');
            $('#frmVariableEdit div[id=view_selection]').addClass('d-none');
            $('#mdlVariableEdit textarea[name=text]').val('');
            $('#mdlVariableEdit img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
            arrOptionsEdit = [];
        }
    }
    else{
        $('#frmVariableEdit input[id=description]').val('');
        $('#frmVariableEdit div[id=view_text]').addClass('d-none');
        $('#frmVariableEdit div[id=view_download]').addClass('d-none');
        $('#frmVariableEdit div[id=view_selection]').addClass('d-none');
        $('#mdlVariableEdit textarea[name=text]').val('');
        $('#mdlVariableEdit img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
        arrOptionsEdit = [];
    }
});

$('#frmNew').validate({
  submitHandler: function (form) {
  itemData = new FormData(form);
    let executionDate = ($('#frmNew input[name=execution_date]').val()).split('/');
    itemData.set('execution_date', executionDate[2] + '-' + executionDate[1] + '-' + executionDate[0]);

    if(tableVariableDataNew.rows().count() > 0){
        tableVariableDataNew.rows().data().each(function(value, index){
            console.log(value[11]);
            let required = value[2] === 'Sí' ? 1 : 0;
            let visible = value[3] === 'Sí' ? 1 : 0;
            itemData.append('descriptions[]', value[0]);
            itemData.append('requireds[]', required);
            itemData.append('visibles[]', visible);
            itemData.append('types[]', value[5]);
    
            if(value[6] !== ''){
                itemData.append('options[]', value[6]);
            }
            else{
                itemData.append('options[]', '');
            }
            if(value[7] !== ''){
                itemData.append('texts[]', value[7]);
            }
            else{
                itemData.append('texts[]', '');
            }
            if(value[9] !== ''){
                itemData.append('files[]', value[9]);
            }
            else{
                itemData.append('files[]', '');
            }
            if(value[11] !== ''){
                itemData.append('downloadFiles[]', value[11]);
            }
            else{
                itemData.append('downloadFiles[]', '');
            }
        });
        itemData.append('there_data', 1);
    }
    else{
        itemData.append('there_data', 0);
    }
    
    itemData.set('visible', $('#frmNew input:checkbox[id=visible]').is(':checked') ? 1 : 0);
    
    //Axios Http Post Request
    Core.post(route + '/store', itemData).then(function(res){
        Core.showToast('success', res.data.message);
        $('#mdlNew').modal('hide');
    }).catch(function(err){
        Core.showAlert('error', err.response.data.error.message);
    }).finally(() => {
        setTimeout(() => {
            window.location = urlWeb + route + '/' + $('#project_id').val();
        }, 2500);
    });
  //}
  
}});

$('#frmEdit').validate({
  submitHandler: function (form) {
  itemData = new FormData(form);
  
    let executionDate = ($('#frmEdit input[name=execution_date]').val()).split('/');
    itemData.set('execution_date', executionDate[2] + '-' + executionDate[1] + '-' + executionDate[0]);

    for (let index = 0; index < arrDeleteVariables.length; index++) {
        itemData.append('variables_delete[]', arrDeleteVariables[index]);
    }

    if(tableVariableDataEdit.rows().count() > 0){
        tableVariableDataEdit.rows().data().each(function(value, index){
            let required = value[2] === 'Sí' ? 1 : 0;
            let visible = value[3] === 'Sí' ? 1 : 0;
            itemData.append('descriptions[]', value[0]);
            itemData.append('requireds[]', required);
            itemData.append('visibles[]', visible);
            itemData.append('types[]', value[5]);
    
            if(value[6] !== ''){
                itemData.append('options[]', value[6]);
            }
            else{
                itemData.append('options[]', '');
            }
            if(value[7] !== ''){
                itemData.append('texts[]', value[7]);
            }
            else{
                itemData.append('texts[]', '');
            }
            if(value[8] !== ''){
                itemData.append('ids[]', value[8]);
            }
            else{
                itemData.append('ids[]', '');
            }
            if(value[9] !== ''){
                itemData.append('files[]', value[9]);
            }
            else{
                itemData.append('files[]', '');
            }
            if(value[11] !== ''){
                itemData.append('downloadFiles[]', value[11]);
            }
            else{
                itemData.append('downloadFiles[]', '');
            }
        });
        itemData.append('there_data', 1);
    }
    else{
        itemData.append('there_data', 0);
    }
    
    itemData.set('visible', $('#frmEdit input:checkbox[id=visible]').is(':checked') ? 1 : 0);
    itemData.set('complete', $('#frmEdit input:checkbox[id=complete]').is(':checked') ? 1 : 0);
    //Axios Http Post Request
    Core.post(route + '/update/' + $('#frmEdit input[id=id]').val(), itemData).then(function(res){
        Core.showToast('success', res.data.message);
        $('#mdlEdit').modal('hide');
    }).catch(function(err){
        Core.showAlert('error', err.response.data.error.message);
    }).finally(() => {
        setTimeout(() => {
            window.location = urlWeb + route + '/' + $('#project_id').val();
        }, 2000);
    });

  
}});

$('#frmVariableNew button[id=btnAddOption').on('click', function() {
    if ($('#frmVariableNew input[id=option]').val() != "") {
        arrOptions.push({
            'title': $('#frmVariableNew input[id=option]').val()
        });
        drawOptions();
    } else {
        Core.showAlert('El campo de requerimiento no debe estar vacío');
    }
});

//Draw Options
function drawOptions() {
    $('#frmVariableNew input[id=option]').val('');
    $('#frmVariableNew ul[id=arrOptionsContainer]').empty();
    arrOptions.map(function(option, index) {
        $('#frmVariableNew ul[id=arrOptionsContainer]').append(`<li class="list-group-item"><div class="row"><div class="col-sm-10 ml-2">${option.title}</div><div class="col-sm-1"><button type="button" data-index="${index}" id="btnRemoveOption" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button></div></div></li>`);
    });
}

//Remove
$(document).on('click', '#btnRemoveOption', function() {
    index = $(this).data('index');
    arrOptions.splice(index, 1);
    drawOptions();
});

$('#frmVariableEdit button[id=btnAddOption').on('click', function() {
    if ($('#frmVariableEdit input[id=option]').val() != "") {
        arrOptionsEdit.push({
            'title': $('#frmVariableEdit input[id=option]').val()
        });
        drawOptionsEdit();
    } else {
        Core.showAlert('El campo de requerimiento no debe estar vacío');
    }
});

//Draw Options
function drawOptionsEdit() {
    $('#frmVariableEdit input[id=option]').val('');
    $('#frmVariableEdit ul[id=arrOptionsContainer]').empty();
    arrOptionsEdit.map(function(option, index) {
        $('#frmVariableEdit ul[id=arrOptionsContainer]').append(`<li class="list-group-item"><div class="row"><div class="col-sm-10 ml-2">${option.title}</div><div class="col-sm-1"><button type="button" data-index="${index}" id="btnRemoveOptionEdit" class="btn btn-danger btn-sm"><i class="fa fa-times"></i></button></div></div></li>`);
    });
}

//Remove
$(document).on('click', '#btnRemoveOptionEdit', function() {
    index = $(this).data('index');
    arrOptionsEdit.splice(index, 1);
    drawOptionsEdit();
});

// Complete
$(document).on('click','.checkComplete', function(){
    if($(this).is(':checked')){
        data = $(this).data('id');
        checkComplete = $(this);
        bootbox.confirm({
            message: '¿Esta seguro que se ha completado la partida?',
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
                    itemData = new FormData();
                    itemData.append('project_id', $('#project_id').val());
                    //Axios Http Post Request
                    Core.post(route + '/completed/' +data,itemData).then(function(res){
                      Core.showToast('success', res.data.message);
                      setTimeout(() => {
                        window.location = res.data.redirect;
                      }, 1200);
                    }).catch(function(err){
                      console.log(err);
                    })
                }
                else{
                    checkComplete.prop('checked', false);
                }
            }
        });
    }
    else{

    }
});

$(document).on('click','#btnRefresh', function(e){
    e.preventDefault();
    window.location = urlWeb + route + '/' +$('#frmSearchPartidas input[id=project_id]').val();
});

function showDetail(departure_id){
    //Axios Http Get Request
    Core.get(route + '/find/' + departure_id).then(function(res){
      let status = res.data.data.status === '1' ? 'Pendiente' : 'Pagado';
      $('#mdlViewDetail span[id=code]').text(res.data.data.code);
      $('#mdlViewDetail span[id=quantity]').text(res.data.data.quantity);
      $('#mdlViewDetail span[id=dimensions]').text(res.data.data.dimension.name);
      $('#mdlViewDetail span[id=status]').text(status);  
      let html = '';
      //if(res.data.data.variables.length > 0){
        // html += `<div class="col-lg-12 col-md-12 mt-4">
        //             <span class="text-info"><b><i>Variable(s) suplementaria(s)</i></b></span>
        //        </div>`;
      //}
      
      res.data.data.variables.forEach((item) => {
        

        let simpleOptionIndex = 1;
        let multipleOptionIndex = 1;
        let options;
        if(item.options !== null && item.options.length){
            options = JSON.parse(item.options);
        }
        // Simple Options
        if(parseInt(item.type) === 1 && parseInt(item.visible) === 1){
            html += `<div class="col-lg-6 col-md-6 mb-3 mt-3">
                        <label for="">${item.description}</label>&nbsp;${parseInt(item.required) === 1 ? '<small class="text-red">Obligatorio</small>' : ''}<br>`;
                                options.map((element, index) => {
                                    html += `<label>${element.title}</label><br>`;
                                });
            html += `   </div>
                    </div>`;   
            simpleOptionIndex++;            
        }
        // Multiple Options
        else if(parseInt(item.type) === 2 && parseInt(item.visible) === 1){
            html += `<div class="col-lg-6 col-md-6 mb-3 mt-3">           
                        <label for="">${item.description}</label>&nbsp;${parseInt(item.required) === 1 ? '<small class="text-red">Obligatorio</small>' : ''}<br>`;
                            options.map((element, index) => {
                                    html += `<label>${element.title}</label><br>`;
                            });
            html += `   </div>
                    </div>`;
            multipleOptionIndex++;
        }
        // Donwload
        else if(parseInt(item.type) === 3 && parseInt(item.visible) === 1){
            html += `<div class="col-lg-6 col-md-6 mb-3">
                        <label for="">${item.description}</label><br>
                        <a href="${ urlWeb + '/storage/' + item.download_file}" download>${(item.download_file).split('/')[3]}</a>
                    </div>`;
        }
        // Upload Information
        else if(parseInt(item.type) === 4 && parseInt(item.visible) === 1){
                html += `<div class="col-lg-6 col-md-6 mb-3">
                            <label for="">${item.description}</label>&nbsp;${parseInt(item.required) === 1 ? '<small class="text-red">Obligatorio</small>' : ''}
                        </div>`;
        
       }
       // Text
       else if(parseInt(item.type) === 5 && parseInt(item.visible) === 1){
           html += `<div class="col-lg-8 col-md-8 mb-3">
                        <label>${item.description}</label>
                        <p class="text-justify">${item.text}</p>
                    </div>`;
            if(item.file !== null){
                let img = item.file;
                html += `<div class="col-lg-4 col-md-4 mb-3 text-center">
                            <img src="${urlWeb + '/storage/' + img }" alt="Imagen" style="width: 100%; height: 100%;"/>
                            <a href="${urlWeb + '/storage/' + img}" download style="text-decoration: none;">Descargar</a>
                        </div>`;
            }
        }

       });

      if(html!==''){
        $('#mdlViewDetail div[id=view_variables]').empty().append(html);
      }
      else{
        $('#mdlViewDetail div[id=view_variables]').empty().append('<div class="col-lg-12 col-md-12"><h6>No hay datos</h6></div>');
      }
      
        // <li class="list-group-item list-group-item-primary">A simple primary list group item</li>

      $('#mdlViewDetail').modal('show');
    }).catch(function(err){
      console.log(err);
    });
}

function editDeparture(departure_id){
    //Axios Http Get Request
    Core.get(route + '/find/' + departure_id).then(function(res){
      console.log(res.data);
      $('#frmEdit input[id=id]').val(res.data.data.id);
      $('#frmEdit input[id=code]').val(res.data.data.code);
      $('#frmEdit input[id=description]').val(res.data.data.description);
      $('#frmEdit input[id=quantity]').val(res.data.data.quantity);
      $('#frmEdit select[id=dimensions]').val(res.data.data.dimension_id);
      $('#frmEdit input[id=execution_date]').val(moment(res.data.data.execution_date).format('DD/MM/YYYY'));
      $('#frmEdit input:checkbox[id=visible]').attr('checked', parseInt(res.data.data.visible) == 1 ? true : false);
      $('#frmEdit input:checkbox[id=complete]').attr('checked', parseInt(res.data.data.complete) == 1 ? true : false);
      $('#frmEdit div[id=view_complete]').removeClass('d-none');
      //let table = $('#frmEdit table[id=tableVariableData]').DataTable();

      res.data.data.variables.forEach((item, index) => {
        let aplication = 1;
        let options = '';
        let text = '';
        let file = '';
        let downloadFile = '';

        if(parseInt(item.type) === 1 || parseInt(item.type) === 2){
            aplication = JSON.parse(item.options).length;
            if(JSON.parse(item.options)){
                options = item.options;
            }
        }
        else if(parseInt(item.type) === 3){
            downloadFile = item.download_file;
        }
        else if(parseInt(item.type) === 5){
            text = item.text;
            file = item.file;
        }
        
        if(parseInt(item.status) === 1){

            if(parseInt(item.visible) === 1){
                buttons = `<center>
                                <div class="d-flex">
                                    <div class="dropdown" style="width: 50%">
                                        <i class="fa fa-bars text-primary" aria-hidden="true" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false" style="cursor: pointer"></i>
                                        <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu2"
                                            style="width: 100%">
                                            <a class="dropdown-item btnEdit text-warning" onclick="editVariable(${item.id},${index})" style="cursor: pointer;"><i class="fa fa-pencil"></i>&nbsp;Editar</a>
                                            <a class="dropdown-item btnDelete text-danger" onclick="deleteVariable(${item.id},${index})" style="cursor: pointer;"><i class="fa fa-trash"></i>&nbsp;Eliminar</a>
                                        </div>
                                    </div>
                                    <div style="width: 50%">
                                        <i class="fa fa-eye text-primary" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </center>`;
            }
            else if(parseInt(item.visible) === 0){
                buttons = `<center>
                                <div class="d-flex">
                                    <div class="dropdown" style="width: 50%">
                                        <i class="fa fa-bars text-primary" aria-hidden="true" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false" style="cursor: pointer"></i>
                                        <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu2"
                                            style="width: 100%">
                                            <a class="dropdown-item btnEdit text-warning" onclick="editVariable(${item.id},${index})" style="cursor: pointer;"><i class="fa fa-pencil"></i>&nbsp;Editar</a>
                                            <a class="dropdown-item btnDelete text-danger" onclick="deleteVariable(${item.id},${index})" style="cursor: pointer;"><i class="fa fa-trash"></i>&nbsp;Eliminar</a>
                                        </div>
                                    </div>
                                    <div style="width: 50%">
                                        <i class="fa fa-eye-slash text-primary" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </center>`;
            }
            
        }else if(parseInt(item.status) === 2){

            if(parseInt(item.visible) === 1){
                buttons = `<center>
                            <div class="d-flex">
                                <div class="dropdown" style="width: 50%">
                                    <i class="fa fa-bars text-primary" aria-hidden="true" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" style="cursor: pointer"></i>
                                    <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu2"
                                        style="width: 100%">
                                        <a class="dropdown-item btnEdit text-warning" onclick="editVariable(${item.id},${index})" style="cursor: pointer;"><i class="fa fa-pencil"></i>&nbsp;Editar</button>
                                    </div>
                                </div>
                                <div style="width: 50%">
                                    <i class="fa fa-eye text-primary" aria-hidden="true"></i>
                                </div>
                            </div>
                       </center>`;
            }
            else if(parseInt(item.visible) === 0){
                buttons = `<center>
                            <div class="d-flex">
                                <div class="dropdown" style="width: 50%">
                                    <i class="fa fa-bars text-primary" aria-hidden="true" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" style="cursor: pointer"></i>
                                    <div class="dropdown-menu p-0" aria-labelledby="dropdownMenu2"
                                        style="width: 100%">
                                        <a class="dropdown-item btnEdit text-warning" onclick="editVariable(${item.id},${index})" style="cursor: pointer;"><i class="fa fa-pencil"></i>&nbsp;Editar</button>
                                    </div>
                                </div>
                                <div style="width: 50%">
                                    <i class="fa fa-eye-slash text-primary" aria-hidden="true"></i>
                                </div>                   
                            </div>
                       </center>`;
            }
            
        }

        tableVariableDataEdit.row.add([
            item.description,
            aplication,
            parseInt(item.required) === 1 ? 'Sí' : 'No',
            parseInt(item.visible) === 1 ? 'Sí' : 'No',
            buttons,
            item.type,
            options,
            text,
            item.id,
            '',
            file,
            '',
            downloadFile,
            ''
        ]).draw();
      });

      $('#mdlEdit').modal('show');
    }).catch(function(err){
      console.log(err);
    });
}

function deleteDeparture(departure_id){
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
                Core.get(route + '/destroy/' + departure_id).then(function(res) {
                    Core.showToast('success',res.data.message);
                }).catch(function(err) {
                    console.log(err);
                    //Core.showAlert('error', err.response.data.error.message);
                }).finally(() => {
                    setTimeout(() => {
                        window.location = urlWeb + '/client/projects/my-projects/departures/'+$('#project_id').val();    
                    }, 1500);
                });
            }
        }
    });
}

function editVariable(variable_id, index){
    let img = tableVariableDataEdit.cell(index, 10).data() !== '' && tableVariableDataEdit.cell(index, 9).data() !== null ? urlWeb + '/storage/' + tableVariableDataEdit.cell(index, 10).data() : urlWeb + '/images/image_default.jpeg';
    $('#frmVariableEdit input[id=index]').val(index);
    $('#frmVariableEdit select[id=type]').val(tableVariableDataEdit.cell(index, 5).data());
    $('#frmVariableEdit input[id=description]').val(tableVariableDataEdit.cell(index, 0).data());
    
    // Simple Options Or Multilple Options
    if(parseInt(tableVariableDataEdit.cell(index, 5).data()) === 1 || parseInt(tableVariableDataEdit.cell(index, 5).data()) === 2){
        $('#frmVariableEdit div[id=view_selection]').removeClass('d-none');
        let options = JSON.parse(tableVariableDataEdit.cell(index, 6).data());
        options.map((option, index) => {
            arrOptionsEdit.push({
                'title': option.title
            });
        }); 
        drawOptionsEdit();       
    } // Download Information
    else if(parseInt(tableVariableDataEdit.cell(index, 5).data()) === 3){
        $('#frmVariableEdit div[id=view_download]').removeClass('d-none');
        $('#frmVariableEdit div[id=view_downloadFile]').removeClass('d-none');
        $('#frmVariableEdit a[id=link_downloadFile]').attr('href', urlWeb + '/storage/' + tableVariableDataEdit.cell(index, 12).data()).text((tableVariableDataEdit.cell(index, 12).data()).split('/')[3]);
        $('#frmVariableEdit a[id=link_downloadFile]').attr('download', true);
    } // Upload Information
    else if(parseInt(tableVariableDataEdit.cell(index, 5).data()) === 4){
    } // Text and Image
    else if(parseInt(tableVariableDataEdit.cell(index, 5).data()) === 5){
        $('#frmVariableEdit div[id=view_text]').removeClass('d-none');
        $('#frmVariableEdit textarea[id=text]').val(tableVariableDataEdit.cell(index, 7).data());
        $('#frmVariableEdit img[id=view_image_file]').attr('src', img);
    }
    $('#frmVariableEdit input:checkbox[id=required]').prop('checked', tableVariableDataEdit.cell(index, 2).data() === 'Sí' ? true : false);
    $('#frmVariableEdit input:checkbox[id=visible]').prop('checked', tableVariableDataEdit.cell(index, 3).data() === 'Sí' ? true : false);
    
    //tableVariableDataEdit
    $('#mdlVariableEdit').modal('show');
}

function deleteVariable(variable_id, index){
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
                tableVariableDataEdit.row(index).remove().draw();
                arrDeleteVariables.push(variable_id);
            }
        }
    });
}

$('#mdlNew').on('show.bs.modal', function(){
    $('#frmVariableNew input[id=modality]').val('new');
    $('#frmNew input:checkbox[id=visible]').prop('checked', true);
});

$('#mdlEdit').on('show.bs.modal', function(){
    $('#frmVariableNew input[id=modality]').val('edit');
});

$('#mdlNew').on('hidden.bs.modal', function(){
    $('#frmNew')[0].reset();
    $('#frmNew').validate().resetForm();
    $('input,textarea,select').removeClass('is-invalid');
    tableVariableDataNew.clear().draw();
});

$('#mdlEdit').on('hidden.bs.modal', function(){
    $('#frmEdit')[0].reset();
    $('#frmEdit').validate().resetForm();
    $('input,textarea,select').removeClass('is-invalid');
    tableVariableDataEdit.clear().draw();
    arrDeleteVariables = [];
    $('#frmEdit div[id=view_complete]').addClass('d-none');
});

$('#mdlVariableNew').on('show.bs.modal', function(){
    $('#mdlVariableNew input:checkbox[id=visible]').prop('checked', true);
    $('#mdlVariableNew img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
});

$('#mdlVariableEdit').on('show.bs.modal', function(){
    arrDeleteVariables = [];
    //$('#frmVariableEdit input:checkbox[id=visible_variable]').attr('id', 'visible_variable_edit').attr('name', 'visible_variable_edit');
});

$('#mdlVariableNew').on('hidden.bs.modal', function(){
    $('#frmVariableNew')[0].reset();
    $('#frmVariableNew').validate().resetForm();
    $('input,textarea,select').removeClass('is-invalid');
    $('#frmVariableNew div[id=view_selection]').addClass('d-none');
    $('#frmVariableNew div[id=view_download]').addClass('d-none');
    $('#frmVariableNew div[id=view_downloadFile]').addClass('d-none');
    //$('#frmVariableNew div[id=upload]').addClass('d-none');
    $('#frmVariableNew div[id=view_text]').addClass('d-none');
    $('#frmVariableNew input[id=filebase64]').val('');
    $('#frmVariableNew input[id=file]').prop('required', false);
    $('#frmVariableNew input[id=fileDownload]').prop('required', false);
    $('#frmVariableNew textarea[id=text]').val('');
    $('#frmVariableNew img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');

    arrOptions = [];
    base64 = '';
    // Clear Table
    
});

$('#mdlVariableEdit').on('hidden.bs.modal', function(){
    $('#frmVariableEdit')[0].reset();
    $('#frmVariableEdit').validate().resetForm();
    $('input,textarea,select').removeClass('is-invalid');
    $('#frmVariableEdit div[id=view_selection]').addClass('d-none');
    $('#frmVariableEdit div[id=view_download]').addClass('d-none');
    $('#frmVariableEdit div[id=view_downloadFile]').addClass('d-none');
    //$('#frmVariableNew div[id=upload]').addClass('d-none');
    $('#frmVariableEdit div[id=view_text]').addClass('d-none');
    $('#frmVariableEdit input[id=filebase64]').val('');
    $('#frmVariableEdit input[id=file]').prop('required', false);
    $('#frmVariableEdit input[id=fileDownload]').prop('required', false);
    $('#frmVariableEdit textarea[id=text]').val('');
    $('#frmVariableEdit img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
    $('#frmVariableEdit a[id=link_downloadFile]').removeAttr('download');
    $('#frmVariableEdit a[id=link_downloadFile]').attr('href', '#');
    arrOptionsEdit = [];
    
    // Clear Table
});

function imageBase64(input) {
    if (input.files && input.files[0]) {
        let image = input.files[0];
        if(image['type'] != 'image/jpeg' && image['type'] != 'image/png'){
            input.value = '';
            Core.showToast('error','Extensión de imagen inválida, debe ser JPG o PNG');
        }
        else{
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#frmVariableNew input[id=filebase64]').val(reader.result);
                $('#frmVariableEdit input[id=filebase64]').val(reader.result);

                $('#frmVariableNew img[id=view_image_file]').attr('src', e.target.result);
                $('#frmVariableEdit img[id=view_image_file]').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
        
    }
}

function filesBase64(input) {
    if (input.files && input.files[0]) {
        let file = input.files[0];
        if(file['type'] != 'image/jpeg' && file['type'] != 'image/png' && file['type'] != 'application/pdf' 
                && file['type'] != 'application/vnd.ms-excel' && file['type'] != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' 
                && file['type'] != 'application/msword' && file['type'] != 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'){
            input.value = '';
            Core.showToast('error','Extensión de archivo inválida, debe ser jpg, jpeg, png, pdf, doc, xls');
        }
        else{
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#frmVariableNew input[id=filebase64]').val(reader.result);
                $('#frmVariableEdit input[id=filebase64]').val(reader.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
}

function init(){
    $('#frmNew input[name=execution_date]').daterangepicker({
        opens: 'right',
        singleDatePicker: true,
        showDropdowns: true,
        minDate: moment(),
        locale: {
            format: 'DD/MM/YYYY',
            separator: ' - ',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Custom',
            daysOfWeek: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
            monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiemmbre','Octubre','Noviembre','Diciembre'],
            firstDay: 1
        }    
    });

    $('#frmEdit input[name=execution_date]').daterangepicker({
        opens: 'right',
        singleDatePicker: true,
        showDropdowns: true,
        minDate: moment().add(1, 'day'),
        locale: {
            format: 'DD/MM/YYYY',
            separator: ' - ',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Custom',
            daysOfWeek: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
            monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiemmbre','Octubre','Noviembre','Diciembre'],
            firstDay: 1
        }
    });

    $('#frmVariableNew textarea[id=text]').ckeditor({
        toolbar:[
            { name: "basistyles", items: ['Bold', 'Italic']},
            { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] }
        ]
    });
    $('#frmVariableEdit textarea[id=text]').ckeditor({
        toolbar:[
            { name: "basistyles", items: ['Bold', 'Italic']},
            { name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] }
        ]
    });
}   

init();