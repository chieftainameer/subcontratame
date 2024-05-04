route = "dashboard/departures";
arrData = [];
var arrOptions = [];
var arrOptionsEdit = [];
var arrFiles = [];
var arrDeleteVariables = [];
var objectFiles = new Object();
var base64 = '';
var options = '';

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
        {data: 'id', name: 'id', orderable: true, searchable: true},
        {data: 'project.code', name: 'project.code', orderable: true, searchable: true},
        {data: 'code', name: 'code', orderable: true, searchable: true},
        {data: 'description', name: 'description', orderable: true, searchable: true},
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
            return row.project.code
        }},
        {
            targets: 3, render: function ( data, type, row, meta ) {
            return row.code;
        }},
        {
            targets: 4, render: function ( data, type, row, meta ) {
                return row.description
        }},
        {
            targets: 5, render: function ( data, type, row, meta ) {
            return `<center>${ row.status == '1' ? '<span class="text-warning">Pendiente</span>' : '<span class="text-success">Pagado</span>'}</center>`; 
        }},
        {
            targets: 6, render: function ( data, type, row, meta ) {
                   return `<center>
                            <div class="btn-group dropleft">
                                <i data-feather="grid" data-toggle="dropdown" id="btnOpciones" data-index='${meta.row}' data-toggle="modal" data-target="#mdlOpciones" style="cursor: pointer;"></i>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item" type="button" id="btnEditE" data-index='${meta.row}' onclick="editDeparture(${row.id})"><i class="fa fa-edit text-warning"></i> Editar</button>
                                    <button class="dropdown-item" type="button" id="btnDelete" data-index='${meta.row}'"><i class="fa fa-trash text-danger"></i> Eliminar</button>
                                </div>
                            </div>
                        </center>`;
        }},
        {
            targets: 0, render: function ( data, type, row, meta ) {
            return `<input type="checkbox" class="departures-checkboxes" name="delete_departures" value='${row.id}' />`; 
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
        { targets: [9], visible: false, searchable: false, orderable: false }, // file
        { targets: [10], visible: false, searchable: false, orderable: false }, // Aux
        { targets: [11], visible: false, searchable: false, orderable: false }, // Status
        { targets: [12], visible: false, searchable: false, orderable: false }, // Download file
        { targets: [13], visible: false, searchable: false, orderable: false }, // Aux download file
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
        { targets: [9], visible: false, searchable: false, orderable: false }, // file
        { targets: [10], visible: false, searchable: false, orderable: false }, // Aux
        { targets: [11], visible: false, searchable: false, orderable: false }, // Status
        { targets: [12], visible: false, searchable: false, orderable: false }, // Download file
        { targets: [13], visible: false, searchable: false, orderable: false }, // Aux download file
    ],      
});

window.getData = function() {
    //each reload datatable set data to arrData Array
    tblData.ajax.reload(function( json ){
        arrData = json.data;
    });
}

window.showItem = function() {
    let variables = itemData.variables;
    $('#frmEdit input[id=id]').val(itemData.id);
    $('#frmEdit input[id=code]').val(itemData.code);
    $('#frmEdit input[id=description]').val(itemData.description);
    $('#frmEdit input[id=quantity]').val(itemData.quantity);
    $('#frmEdit select[id=dimensions]').val(itemData.dimensions);
    $('#frmEdit input[id=execution_date]').val(moment(itemData.execution_date).format('DD/MM/YYYY'));
    $('#frmEdit input:checkbox[id=visible]').attr('checked', parseInt(itemData.visible) == 1 ? true : false);
    $('#frmEdit input:checkbox[id=complete]').attr('checked', parseInt(itemData.complete) == 1 ? true : false);
    $('#frmEdit select[id=status]').val(itemData.status);
    $('#frmEdit div[id=view_complete]').removeClass('d-none');

    
    //Variables
    itemData.variables.forEach((item, index) => {
        let aplication = 1;
        let options = '';
        let text = '';
        let file = '';
        if(parseInt(item.type) === 1 || parseInt(item.type) === 2){
            
            
            //options = item.options.replace('&quot;', '"');
            let cadena = JSON.stringify(item.options);
            let cadena2 = JSON.parse(cadena);
            //alert(cadena2);
            aplication = JSON.parse(cadena2).length;
            if(JSON.parse(cadena2)){
                options = cadena2;
            }
            
        }
        else if(parseInt(item.type) === 3){

        }
        else if(parseInt(item.type) === 5){
            text = item.text;
            file = item.file;
        }
        
        if(parseInt(item.status) === 1){
            buttons = `<center>
                        <button class="btn btn-warning btn-sm btnEdit" onclick="editVariable(${item.id},${index})" type="button"><i class="fa fa-pencil text-white"></i></button>
                        <button class="btn btn-danger btn-sm btnDelete" onclick="deleteVariable(${item.id},${index})" type="button"><i class="fa fa-trash text-white"></i></button>
                      </center>`;
        }else if(parseInt(item.status) === 2){
            buttons = `<center>
                        <button class="btn btn-warning btn-sm btnEdit" onclick="editVariable(${item.id},${index})" type="button"><i class="fa fa-pencil text-white"></i></button>
                      </center>`;
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
            item.status
        ]).draw();
    });
    for (let index = 0; index < variables.length; index++) {
        console.log(index);
        const element = variables[index];
        console.log(element);
    }

    //$('#mdlEdit').modal('show');
}

// Submits
$('#frmVariableNew').validate({
    messages:{
      type:{
          required: 'Debe seleccionar un tipo de variable'
      }
    },
    submitHandler: function (form) {
        let aplication;
        
        // options
        if($('#frmVariableNew select[id=type]').val() === '1' || $('#frmVariableNew select[id=type]').val() === '2'){
      
            aplication = arrOptions.length;
            if($('#frmVariableNew input[id=modality]').val() === 'new'){
          
                tableVariableDataNew.row.add([
                    $('#frmVariableNew input[id=description]').val(),
                    aplication,
                    $('#frmVariableNew input:checkbox[id=required]').is(':checked') ? 'Sí': 'No',
                    $('#frmVariableNew input:checkbox[id=visible]').is(':checked') ? 'Sí' : 'No',
                    // `<center>
                    //     <button class="btn btn-danger btn-sm btnDelete" type="button"><i class="fa fa-times text-white"></i></button>
                    // </center>`,
                    `<center>
                            <div class="btn-group dropleft">
                                <i data-feather="grid" data-toggle="dropdown" id="btnOpciones" data-toggle="modal" data-target="#mdlOpciones" style="cursor: pointer;"></i>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item btnDelete" type="button" data-index="${tableVariableDataNew.rows().count()}"><i class="fa fa-trash text-danger"></i> Eliminar</button>
                                </div>
                            </div>
                      </center>`,


                    $('#frmVariableNew select[id=type]').val(), // type
                    JSON.stringify(arrOptions), // options
                    '', // text
                    '', // id
                    '', // file
                    '', // aux file
                    $('#frmVariableNew select[id=status]').val(), // status
                    '', // download file
                    '' // aux download file
                ]).draw();
                feather.replace();
                $('#mdlVariableNew').modal('hide');
            }
            else if($('#frmVariableNew input[id=modality]').val() === 'edit'){
                tableVariableDataEdit.row.add([
                    $('#frmVariableNew input[id=description]').val(),
                    aplication,
                    $('#frmVariableNew input:checkbox[id=required]').is(':checked') ? 'Sí': 'No',
                    $('#frmVariableNew input:checkbox[id=visible]').is(':checked') ? 'Sí' : 'No',
                    // `<center>
                    //     <button class="btn btn-danger btn-sm btnDeleteEdit" type="button"><i class="fa fa-times text-white"></i></button>
                    // </center>`,

                    `<center>
                            <div class="btn-group dropleft">
                                <i data-feather="grid" data-toggle="dropdown" id="btnOpciones" data-toggle="modal" data-target="#mdlOpciones" style="cursor: pointer;"></i>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item btnDeleteEdit" type="button" data-index="${tableVariableDataEdit.rows().count()}"><i class="fa fa-trash text-danger"></i> Eliminar</button>
                                </div>
                            </div>
                      </center>`,
            
                    $('#frmVariableNew select[id=type]').val(), // type
                    JSON.stringify(arrOptions), // options
                    '', // text
                    '', // id
                    '', // file
                    '', // aux file
                    $('#frmVariableNew select[id=status]').val(), // status
                    '', // download file
                    '' // aux download file
                ]).draw();
                feather.replace();
                $('#mdlVariableNew').modal('hide');
            }
        } // Download
        else if($('#frmVariableNew select[id=type]').val() === '3'){
            aplication = 1;
            
            if($('#frmVariableNew input[id=modality]').val() === 'new'){
                console.log(tableVariableDataNew.rows().count());
                tableVariableDataNew.row.add([
                    $('#frmVariableNew input[id=description]').val(),
                    aplication,
                    $('#frmVariableNew input:checkbox[id=required]').is(':checked') ? 'Sí': 'No',
                    $('#frmVariableNew input:checkbox[id=visible]').is(':checked') ? 'Sí' : 'No',
                    // `<center>
                    //     <button class="btn btn-danger btn-sm btnDelete" type="button"><i class="fa fa-times text-white"></i></button>
                    // </center>`,

                    `<center>
                            <div class="btn-group dropleft">
                                <i data-feather="grid" data-toggle="dropdown" id="btnOpciones" data-toggle="modal" data-target="#mdlOpciones" style="cursor: pointer;"></i>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item btnDelete" type="button" data-index="${tableVariableDataNew.rows().count()}"><i class="fa fa-trash text-danger"></i> Eliminar</button>
                                </div>
                            </div>
                     </center>`,
            
                    $('#frmVariableNew select[id=type]').val(), // type
                    '', // options
                    '', // text
                    '', // id
                    '', // file,
                    '',  // aux file
                    $('#frmVariableNew select[id=status]').val(), // status
                    $('#frmVariableNew input[id=filebase64]').val(), // download file
                    '' // aux download file
                ]).draw();
                //feather.replace();
                $('#mdlVariableNew').modal('hide');
            }
            else if($('#frmVariableNew input[id=modality]').val() === 'edit'){
                //alert("entro");
                tableVariableDataEdit.row.add([
                    $('#frmVariableNew input[id=description]').val(),
                    aplication,
                    $('#frmVariableNew input:checkbox[id=required]').is(':checked') ? 'Sí': 'No',
                    $('#frmVariableNew input:checkbox[id=visible]').is(':checked') ? 'Sí' : 'No',
                    // `<center>
                    //     <button class="btn btn-danger btn-sm btnDeleteEdit" type="button"><i class="fa fa-times text-white"></i></button>
                    // </center>`,

                    `<center>
                            <div class="btn-group dropleft">
                                <i data-feather="grid" data-toggle="dropdown" id="btnOpciones" data-toggle="modal" data-target="#mdlOpciones" style="cursor: pointer;"></i>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item btnDeleteEdit" type="button" data-index="${tableVariableDataEdit.rows().count()}"><i class="fa fa-trash text-danger"></i> Eliminar</button>
                                </div>
                            </div>
                     </center>`,
            
                    $('#frmVariableNew select[id=type]').val(), // type
                    '', // options
                    '', // text
                    '', // id
                    '', // file
                    '', // aux file
                    $('#frmVariableNew select[id=status]').val(), // status
                    $('#frmVariableNew input[id=filebase64]').val(), // download file
                    '' // aux download file
                ]).draw();
                feather.replace();
                $('#mdlVariableNew').modal('hide');
            } 
        } // 
        else if($('#frmVariableNew select[id=type]').val() === '4'){
            aplication = 1;
            if($('#frmVariableNew input[id=modality]').val() === 'new'){
                tableVariableDataNew.row.add([
                    $('#frmVariableNew input[id=description]').val(),
                    aplication,
                    $('#frmVariableNew input:checkbox[id=required]').is(':checked') ? 'Sí': 'No',
                    $('#frmVariableNew input:checkbox[id=visible]').is(':checked') ? 'Sí' : 'No',
                    // `<center>
                    //     <button class="btn btn-danger btn-sm btnDelete" type="button"><i class="fa fa-times text-white"></i></button>
                    // </center>`,

                    `<center>
                            <div class="btn-group dropleft">
                                <i data-feather="grid" data-toggle="dropdown" id="btnOpciones" data-toggle="modal" data-target="#mdlOpciones" style="cursor: pointer;"></i>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item btnDelete" type="button" data-index="${tableVariableDataNew.rows().count()}"><i class="fa fa-trash text-danger"></i> Eliminar</button>
                                </div>
                            </div>
                      </center>`,

                    $('#frmVariableNew select[id=type]').val(), // type
                    '', // options 
                    '', // text
                    '', // id
                    '', // file
                    '', // aux file
                    $('#frmVariableNew select[id=status]').val(), // status
                    '', // download file
                    '' // aux download file
                ]).draw();
                feather.replace();
                $('#mdlVariableNew').modal('hide');
            }
            else if($('#frmVariableNew input[id=modality]').val() === 'edit'){
                tableVariableDataEdit.row.add([
                    $('#frmVariableNew input[id=description]').val(),
                    aplication,
                    $('#frmVariableNew input:checkbox[id=required]').is(':checked') ? 'Sí': 'No',
                    $('#frmVariableNew input:checkbox[id=visible]').is(':checked') ? 'Sí' : 'No',
                    // `<center>
                    //     <button class="btn btn-danger btn-sm btnDeleteEdit" type="button"><i class="fa fa-times text-white"></i></button>
                    // </center>`,

                    `<center>
                            <div class="btn-group dropleft">
                                <i data-feather="grid" data-toggle="dropdown" id="btnOpciones" data-toggle="modal" data-target="#mdlOpciones" style="cursor: pointer;"></i>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item btnDeleteEdit" type="button" data-index="${tableVariableDataEdit.rows().count()}"><i class="fa fa-trash text-danger"></i> Eliminar</button>
                                </div>
                            </div>
                      </center>`,

                    $('#frmVariableNew select[id=type]').val(), // type
                    '', // options
                    '', // text
                    '', // id
                    '', // file
                    '', // aux file 
                    $('#frmVariableNew select[id=status]').val(), // status
                    '', // download file
                    '' // aux download file
                ]).draw();
                feather.replace();
                $('#mdlVariableNew').modal('hide');
            } 
        }
        else if($('#frmVariableNew select[id=type]').val() === '5'){
            aplication = 1;
            if($('#frmVariableNew input[id=modality]').val() === 'new'){
                //alert($('#frmVariableNew textarea[id=text]').val());
                if($('#frmVariableNew textarea[name=text]').val() === ''){
                    Core.showToast('error','Debe agregar un texto');
                }
                else{
                    tableVariableDataNew.row.add([
                        $('#frmVariableNew input[id=description]').val(),
                        aplication,
                        $('#frmVariableNew input:checkbox[id=required]').is(':checked') ? 'Sí': 'No',
                        $('#frmVariableNew input:checkbox[id=visible]').is(':checked') ? 'Sí' : 'No',
                        // `<center>
                        //     <button class="btn btn-danger btn-sm btnDelete" type="button"><i class="fa fa-times text-white"></i></button>
                        // </center>`,

                        `<center>
                            <div class="btn-group dropleft">
                                <i data-feather="grid" data-toggle="dropdown" id="btnOpciones" data-toggle="modal" data-target="#mdlOpciones" style="cursor: pointer;"></i>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item btnDelete" type="button" data-index="${tableVariableDataNew.rows().count()}"><i class="fa fa-trash text-danger"></i> Eliminar</button>
                                </div>
                            </div>
                      </center>`,

                        $('#frmVariableNew select[id=type]').val(), // type
                        '', // options
                        $('#frmVariableNew textarea[id=text]').summernote('code'), // text
                        '', // id
                        $('#frmVariableNew input[id=filebase64]').val(), // file
                        '', // aux file
                        $('#frmVariableNew select[id=status]').val(), // status
                        '', // download file
                        '' // aux download file
                    ]).draw();
                    feather.replace();
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
                        // `<center>
                        //     <button class="btn btn-danger btn-sm btnDeleteEdit" type="button"><i class="fa fa-times text-white"></i></button>
                        // </center>`,

                        `<center>
                            <div class="btn-group dropleft">
                                <i data-feather="grid" data-toggle="dropdown" id="btnOpciones" data-toggle="modal" data-target="#mdlOpciones" style="cursor: pointer;"></i>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item btnDeleteEdit" type="button" data-index="${tableVariableDataEdit.rows().count()}"><i class="fa fa-trash text-danger"></i> Eliminar</button>
                                </div>
                            </div>
                      </center>`,

                        $('#frmVariableNew select[id=type]').val(), // type
                        '', // options
                        $('#frmVariableNew textarea[id=text]').summernote('code'), // text
                        '', // id
                        $('#frmVariableNew input[id=filebase64]').val(), // file
                        '', // aux file
                        $('#frmVariableNew select[id=status]').val(), // status
                        '', // download file
                        '' // aux download file
                    ]).draw();
                    feather.replace();
                    $('#mdlVariableNew').modal('hide');
                }
            } 
        }
}});
  
$('#frmVariableEdit').validate({
      messages:{
        type:{
            required: 'Debe seleccionar un tipo de variable'
        }
      },
      submitHandler: function (form) {
        let aplication;
      // options
      if($('#frmVariableEdit select[id=type]').val() === '1' || $('#frmVariableEdit select[id=type]').val() === '2'){
          aplication = arrOptionsEdit.length;
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 0).data($('#frmVariableEdit input[id=description]').val());
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 1).data(aplication);
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 2).data($('#frmVariableEdit input:checkbox[id=required]').is(':checked')?'Sí':'No');
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 3).data($('#frmVariableEdit input:checkbox[id=visible]').is(':checked')?'Sí':'No');
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 5).data($('#frmVariableEdit select[id=type]').val()); // type
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 6).data(JSON.stringify(arrOptionsEdit)); // options
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 7).data(''); // text
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 8).data($('#frmVariableEdit input[id=id]').val()); // id
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 9).data(''); // file
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 10).data(''); // aux file
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 11).data($('#frmVariableEdit select[id=status]').val()); // status
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 12).data(''); // download file
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 13).data(''); // aux download file
          $('#mdlVariableEdit').modal('hide');
      } // Download Information
      else if($('#frmVariableEdit select[id=type]').val() === '3'){
        aplication = 1;
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 0).data($('#frmVariableEdit input[id=description]').val());
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 1).data(aplication);
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 2).data($('#frmVariableEdit input:checkbox[id=required]').is(':checked')?'Sí':'No');
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 3).data($('#frmVariableEdit input:checkbox[id=visible]').is(':checked')?'Sí':'No');
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 5).data($('#frmVariableEdit select[id=type]').val()); // type
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 6).data(''); // options
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 7).data(''); // text
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 8).data($('#frmVariableEdit input[id=id]').val()); // id
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 9).data(''); // file
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 10).data(''); // aux file
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 11).data($('#frmVariableEdit select[id=status]').val()); //status
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 12).data($('#frmVariableEdit input[id=filebase64]').val()); // download file
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 13).data(''); // aux download file
        $('#mdlVariableEdit').modal('hide');
      } // Upload Information
      else if($('#frmVariableEdit select[id=type]').val() === '4'){
        aplication = 1;
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 0).data($('#frmVariableEdit input[id=description]').val());
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 1).data(aplication);
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 2).data($('#frmVariableEdit input:checkbox[id=required]').is(':checked')?'Sí':'No');
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 3).data($('#frmVariableEdit input:checkbox[id=visible]').is(':checked')?'Sí':'No');
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 5).data($('#frmVariableEdit select[id=type]').val()); // type
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 6).data(''); // options 
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 7).data(''); // text
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 8).data($('#frmVariableEdit input[id=id]').val()); // id
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 9).data(''); // file
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 10).data(''); // aux file
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 11).data($('#frmVariableEdit select[id=status]').val()); // status
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 12).data(''); // download
        tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 13).data(''); // aux download
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
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 5).data($('#frmVariableEdit select[id=type]').val()); // type
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 6).data(''); // options
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 7).data($('#frmVariableEdit textarea[id=text]').summernote('code')); // text
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 8).data($('#frmVariableEdit input[id=id]').val()); // id
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 9).data($('#frmVariableEdit input[id=filebase64]').val()); // file
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 10).data(''); // aux file
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 11).data($('#frmVariableEdit select[id=status]').val());// status
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 12).data(''); // download file
          tableVariableDataEdit.cell($('#frmVariableEdit input[id=index]').val(), 13).data(''); // aux download file
          $('#mdlVariableEdit').modal('hide');
        } 
        
      }
      // Close window
      //$('#mdlVariableEdit').modal('hide');
}});

$('#frmEdit').validate({
    submitHandler: function (form) {
    itemData = new FormData(form);
    
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
              itemData.append('statuses[]', value[11]);
      
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
                  console.log(value[9]);  
                  itemData.append('files[]', value[9]);
              }
              else{
                  itemData.append('files[]', '');
              }
              if(value[12] !== ''){
                itemData.append('downloadFiles[]', value[12]);
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
      itemData.set('text', $('#frmEdit textarea[id=text]').summernote('code'));
      //Axios Http Post Request
      Core.post(route + '/update/' + $('#frmEdit input[id=id]').val(), itemData).then(function(res){
          Core.showToast('success', res.data.message);
          $('#mdlEdit').modal('hide');
      }).catch(function(err){
          Core.showAlert('error', err.response.data.error.message);
      }).finally(() => {
          setTimeout(() => {
            getData();
          }, 1200);
      });
  
    
}});

// Eliminar un item de diagnostico
$(document).on("click", "button.btnDelete", function(e){
    e.preventDefault();
    //var tabVariableData = $("#frmNew table[id=tableVariableData]").DataTable();
    tableVariableDataNew.row($(this).parents("tr")).remove().draw();
});

$(document).on("click", "button.btnDeleteEdit", function(e){
    e.preventDefault();
    //var tabVariableData = $("#frmNew table[id=tableVariableData]").DataTable();
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
        // Download
        else if($(this).val() === '3'){
            $('#frmVariableNew input[id=description]').val('');
            $('#frmVariableNew input[id=fileDownload]').val('');
            $('#frmVariableNew div[id=view_download]').removeClass('d-none');
            $('#frmVariableNew input[id=fileDownload]').prop('required', true);
            $('#frmVariableNew div[id=view_selection]').addClass('d-none');
            $('#frmVariableNew div[id=view_text]').addClass('d-none');
            $('#mdlVariableNew textarea[name=text]').val('');
            $('#mdlVariableNew img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
            arrOptions = [];
        }
        else if($(this).val() === '4'){
            $('#frmVariableNew input[id=description]').val('');
            $('#frmVariableNew div[id=view_text]').addClass('d-none');
            $('#frmVariableNew div[id=view_download]').addClass('d-none');
            $('#frmVariableNew input[id=fileDownload]').prop('required', false);
            $('#frmVariableNew div[id=view_selection]').addClass('d-none');
            $('#mdlVariableNew textarea[name=text]').val('');
            $('#mdlVariableNew img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
            arrOptions = [];
        } 
        else if($(this).val() === '5'){
            $('#frmVariableNew input[id=description]').val('');
            //$('#frmVariableNew input[id=file]').prop('required', true);
            $('#frmVariableNew div[id=view_text]').removeClass('d-none');
            $('#frmVariableNew div[id=view_download]').addClass('d-none');
            $('#frmVariableNew input[id=fileDownload]').prop('required', false);
            $('#frmVariableNew div[id=view_selection]').addClass('d-none');
            $('#mdlVariableNew textarea[name=text]').val('');
            $('#mdlVariableNew img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
            arrOptions = [];
        }
    }
    else{
        $('#frmVariableNew input[id=description]').val('');
        $('#frmVariableNew div[id=view_text]').addClass('d-none');
        $('#frmVariableNew div[id=view_download]').addClass('d-none');
        $('#frmVariableNew input[id=fileDownload]').prop('required', false);
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

$('#frmVariableNew button[id=btnAddOption').on('click', function() {
    console.log("entre");
    if ($('#frmVariableNew input[id=option]').val() != "") {
        arrOptions.push({
            'title': $('#frmVariableNew input[id=option]').val()
        });
        drawOptions();
    } else {
        Core.showToast('error', 'El campo de requerimiento no debe estar vacío');
    }
    console.log(arrOptions);
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

function showDetail(departure_id){
    //Axios Http Get Request
    Core.get(route + '/find/' + departure_id).then(function(res){
      let status = res.data.data.status === '1' ? 'Pendiente' : 'Pagado';
      $('#mdlViewDetail span[id=code]').text(res.data.data.code);
      $('#mdlViewDetail span[id=quantity]').text(res.data.data.quantity);
      $('#mdlViewDetail span[id=dimensions]').text(res.data.data.dimensions);
      $('#mdlViewDetail span[id=status]').text(status);  
      let html = '';
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
        // Donwload Information
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
       // Text and Image
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
    Core.get(route + '/' + departure_id).then(function(res){
      let dateServer = (res.data.data.execution_date).split('-');
      let dateNew = dateServer[2]+'/'+dateServer[1]+'/'+dateServer[0];
      console.log(dateNew);
      $('#frmEdit input[id=id]').val(res.data.data.id);
      $('#frmEdit input[id=code]').val(res.data.data.code);
      $('#frmEdit input[id=description]').val(res.data.data.description);
      $('#frmEdit input[id=quantity]').val(res.data.data.quantity);
      $('#frmEdit select[id=dimensions]').val(res.data.data.dimensions);
      $('#frmEdit select[id=status]').val(res.data.data.status);
      $('#frmEdit input[id=execution_date]').val(res.data.data.execution_date);
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
            // buttons = `<center>
            //             <button class="btn btn-warning btn-sm btnEdit" onclick="editVariable(${item.id},${index})" type="button"><i class="fa fa-pencil text-white"></i></button>
            //             <button class="btn btn-danger btn-sm btnDelete" onclick="deleteVariable(${item.id},${index})" type="button"><i class="fa fa-trash text-white"></i></button>
            //           </center>`;

            buttons = `<center><div class="btn-group dropleft">
                                <i data-feather="grid" data-toggle="dropdown" id="btnOpciones" data-index='${index}' data-toggle="modal" data-target="#mdlOpciones" style="cursor: pointer;"></i>
                                <div class="dropdown-menu">
                                    <button class="dropdown-item btnEdit" type="button" data-index='${index}' onclick="editVariable(${item.id},${index})"><i class="fa fa-edit text-warning"></i> Editar</button>
                                    <button class="dropdown-item btnDelete" type="button" data-index='${index}'" onclick="deleteVariable(${item.id},${index})"><i class="fa fa-trash text-danger"></i>Eliminar</button>
                                </div>
                       </div></center>`
        }else if(parseInt(item.status) === 2){
            // buttons = `<center>
            //             <button class="btn btn-warning btn-sm btnEdit" onclick="editVariable(${item.id},${index})" type="button"><i class="fa fa-pencil text-white"></i></button>
            //           </center>`;

            buttons = `<center><div class="btn-group dropleft">
                            <i data-feather="grid" data-toggle="dropdown" id="btnOpciones" data-index='${index}' data-toggle="modal" data-target="#mdlOpciones" style="cursor: pointer;"></i>
                            <div class="dropdown-menu">
                                <button class="dropdown-item btnEdit" type="button" data-index='${index}' onclick="editVariable(${item.id},${index})"><i class="fa fa-edit text-warning"></i> Editar</button>
                            </div>
                    </div></center>`
        }

        tableVariableDataEdit.row.add([
            item.description,
            aplication,
            parseInt(item.required) === 1 ? 'Sí' : 'No',
            parseInt(item.visible) === 1 ? 'Sí' : 'No',
            buttons, 
            item.type, // type
            options, // options
            text, // text
            item.id, // id
            '', // file
            file, // aux file
            item.status, // status
            '', // download file
            downloadFile // aux download file
        ]).draw();
      });
      feather.replace();
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
                        window.location = urlWeb + '/'+route;    
                    }, 1500);
                });
            }
        }
    });
}

function editVariable(variable_id, index){
    console.log(tableVariableDataEdit.cell(index, 10).data());
    let img = tableVariableDataEdit.cell(index, 10).data() !== '' && tableVariableDataEdit.cell(index, 9).data() !== null ? urlWeb + '/storage/' + tableVariableDataEdit.cell(index, 10).data() : urlWeb + '/images/image_default.jpeg';
    $('#frmVariableEdit input[id=index]').val(index);
    $('#frmVariableEdit select[id=type]').val(tableVariableDataEdit.cell(index, 5).data());
    $('#frmVariableEdit input[id=description]').val(tableVariableDataEdit.cell(index, 0).data());

    if(parseInt(tableVariableDataEdit.cell(index, 5).data()) === 1 || parseInt(tableVariableDataEdit.cell(index, 5).data()) === 2){
        $('#frmVariableEdit div[id=view_selection]').removeClass('d-none');
        let options = JSON.parse(tableVariableDataEdit.cell(index, 6).data());
        options.map((option, index) => {
            arrOptionsEdit.push({
                'title': option.title
            });
        }); 
        drawOptionsEdit();       
    }
    else if(parseInt(tableVariableDataEdit.cell(index, 5).data()) === 3){
        $('#frmVariableEdit div[id=view_download]').removeClass('d-none');
        $('#frmVariableEdit div[id=view_downloadFile]').removeClass('d-none');
        $('#frmVariableEdit a[id=link_downloadFile]').attr('href', urlWeb + '/storage/' + tableVariableDataEdit.cell(index, 13).data()).text((tableVariableDataEdit.cell(index, 13).data()).split('/')[3]);
        $('#frmVariableEdit a[id=link_downloadFile]').attr('download', true);
    }
    else if(parseInt(tableVariableDataEdit.cell(index, 5).data()) === 4){
    }
    else if(parseInt(tableVariableDataEdit.cell(index, 5).data()) === 5){
        $('#frmVariableEdit div[id=view_text]').removeClass('d-none');
        //$('#frmVariableEdit textarea[id=text]').val(tableVariableDataEdit.cell(index, 7).data());
        $('#frmVariableEdit textarea[id=text]').summernote('code', tableVariableDataEdit.cell(index, 7).data());
    }
    $('#frmVariableEdit input:checkbox[id=required]').prop('checked', tableVariableDataEdit.cell(index, 2).data() === 'Sí' ? true : false);
    $('#frmVariableEdit input:checkbox[id=visible]').prop('checked', tableVariableDataEdit.cell(index, 3).data() === 'Sí' ? true : false);
    $('#frmVariableEdit select[id=status]').val(tableVariableDataEdit.cell(index, 11).data());
    $('#frmVariableEdit img[id=view_image_file]').attr('src', img);
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
    //$('#frmVariableNew div[id=upload]').addClass('d-none');
    $('#frmVariableNew div[id=view_text]').addClass('d-none');
    $('#frmVariableNew input[id=filebase64]').val('');
    $('#frmVariableNew input[id=file]').prop('required', false);
    $('#frmVariableNew input[id=fileDownload]').prop('required', false);
    $('#frmVariableNew textarea[id=text]').val('');
    $('#frmVariableNew img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
    $('#frmVariableEdit textarea[id=text]').summernote('code', '');

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
    //$('#frmVariableNew div[id=upload]').addClass('d-none');
    $('#frmVariableEdit div[id=view_text]').addClass('d-none');
    $('#frmVariableEdit input[id=filebase64]').val('');
    $('#frmVariableEdit input[id=file]').prop('required', false);
    $('#frmVariableEdit input[id=fileDownload]').prop('required', false);
    $('#frmVariableEdit textarea[id=text]').val('');
    $('#frmVariableEdit img[id=view_image_file]').attr('src', urlWeb + '/images/image_default.jpeg');
    $('#frmVariableEdit textarea[id=text]').summernote('code', '');
    arrOptionsEdit = [];
    
    // Clear Table
});

function init(){
    $('.view_other').fadeOut();
    $('.show_field').fadeOut();
    $('#frmVariableNew textarea[id=text]').summernote();
    $('#frmVariableEdit textarea[id=text]').summernote();
}

init();