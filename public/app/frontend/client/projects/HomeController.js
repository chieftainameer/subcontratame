route = '/client/projects/departures';

arrVariablesSimpleOption = [];
arrVariablesMultipleOption = [];
arrFilesName = [];

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

$('#per-page').on('change',function(){
    $('#formPartidas').submit();
});

$.validator.addMethod('max_file', function(value, element){
    let max_file = true;
    console.log('value: ' +value)
    if(element.length > 5){
        max_file = false;
    }
    return this.optional(element) || max_file;
}, 'Solo se permite un máximo de 5 archivos.');

var tableVariantDataNew = $('#frmApplyNew table[id=tableVariantData]').DataTable({
    destroy: true,
    info: false,
    paging :false,
    searching: false,
    ordering: false,
    columnDefs:[
        { targets: [0], visible: true, searchable: true, orderable: false },   // Type
        { targets: [1], visible: true, searchable: true, orderable: false },   // Includes
        { targets: [2], visible: true, searchable: true, orderable: false },   // Quantity
        { targets: [3], visible: true, searchable: true, orderable: false },   // Price Unit
        { targets: [4], visible: true, searchable: false, orderable: false },  // Price Total
        { targets: [5], visible: true, searchable: false, orderable: false },  // Iva
        { targets: [6], visible: true, searchable: false, orderable: false },  // Acciones
        
        { targets: [7], visible: false, searchable: true, orderable: false }, // type
        { targets: [8], visible: false, searchable: false, orderable: false }, // options
        { targets: [9], visible: false, searchable: false, orderable: false }, // text
        { targets: [10], visible: false, searchable: false, orderable: false }, // id
    ],
});

var tableVariantDataEdit = $('#frmApplyEdit table[id=tableVariantData]').DataTable({
    destroy: true,
    info: false,
    paging :false,
    searching: false,
    ordering: false,
    columnDefs:[
        { targets: [0], visible: true, searchable: true, orderable: false },   // Type
        { targets: [1], visible: true, searchable: true, orderable: false },   // Includes
        { targets: [2], visible: true, searchable: true, orderable: false },   // Quantity
        { targets: [3], visible: true, searchable: true, orderable: false },   // Price Unit
        { targets: [4], visible: true, searchable: false, orderable: false },  // Price Total
        { targets: [5], visible: true, searchable: false, orderable: false },  // Iva
        { targets: [6], visible: true, searchable: false, orderable: false },  // Acciones
        
        { targets: [7], visible: false, searchable: true, orderable: false }, // type
        { targets: [8], visible: false, searchable: false, orderable: false }, // options
        { targets: [9], visible: false, searchable: false, orderable: false }, // text
        { targets: [10], visible: false, searchable: false, orderable: false }, // id
    ],
});

function apply(departure_id){
    $('#frmApplyNew input[id=departure_id]').val(departure_id);
    let html = '';
    //Axios Http Get Request
    Core.get( route + '/' + departure_id).then(function(res){
      console.log(res.data.data, res.data.payment_methods);  
      $('#frmApplyNew input[id=project_id]').val(res.data.data.project_id);
      $('#frmApplyNew span[id=date_of_delivery]').text(moment(res.data.data.execution_date).format('DD/MM/YYYY'));
      $('#frmApplyNew span[id=departure_name]').text('('+res.data.data.code + ') ' + res.data.data.description);
      $('#frmApplyNew span[id=view_title]').text(res.data.data.description);
      $('#frmApplyNew span[id=dimension]').text(res.data.data.dimensions);
      $('#frmApplyNew span[id=quantity]').text(res.data.data.quantity);
      $('#frmApplyNew input[id=quantity]').attr('max', res.data.data.quantity);
      $('#frmApplyNew input[id=quantity]').val(res.data.data.quantity);
      $('#frmApplyNew input[id=expiration_date]').val(moment().add(1, 'day').format('DD/MM/YYYY'));
      $('#frmApplyNew input[id=price_unit]').val(parseFloat(0).toFixed(2));
      $('#frmApplyNew input[id=sub_total]').val(parseFloat(0).toFixed(2));
      $('#frmApplyNew input[id=price_iva]').val(parseFloat(0).toFixed(2));
      $('#frmApplyNew input[id=price_total]').val(parseFloat(0).toFixed(2));
      // if there variables
      if(res.data.data.variables.length){
        let simpleOptionIndex = 1;
        let multipleOptionIndex = 1;
        let html = `<div class="row">`;
        res.data.data.variables.forEach((item) => {
            let options;
            if(item.options !== null && item.options.length){
                options = JSON.parse(item.options);
            }
            // Simple Options
            if(parseInt(item.type) === 1 && parseInt(item.visible) === 1){
                let counter = 1;
                html += `<div class="col-lg-6 col-md-6 mb-3">
                            <label for="">${item.description}</label>&nbsp;${parseInt(item.required) === 1 ? '<small class="text-red">Obligatorio</small>' : ''}
                            <div class="form-check">`;
                                options.map((element, index) => {
                                    html += `<input class="form-check-input simpleOption" type="radio" name="simple_option_${simpleOptionIndex}" id="simple_option_${counter++}_${item.id}" value="${element.title}" data-type="${item.type}" data-description="${item.description}" ${parseInt(item.required) === 1 ? "required":""}>
                                    <label class="form-check-label" for="">
                                        ${element.title}
                                    </label><br>`;
                                });
                html += `   </div>
                        </div>`;   
                        
                simpleOptionIndex++;            
            }
            // Multiple Options
            else if(parseInt(item.type) === 2 && parseInt(item.visible) === 1){
                let counter = 1;
                html += `<div class="col-lg-6 col-md-6 mb-3">           
                            <label for="">${item.description}</label>&nbsp;${parseInt(item.required) === 1 ? '<small class="text-red">Obligatorio</small>' : ''}
                            <div class="form-check">`;
                                options.map((element, index) => {
                                    html += `<input class="form-check-input multipleOption" type="checkbox" name="multiple_option_${multipleOptionIndex}" id="multiple_option_${counter++}_${item.id}" value="${element.title}" data-type="${item.type}" data-description="${item.description}" ${parseInt(item.required) === 1 ? "required":""}>
                                    <label class="form-check-label" for="">
                                        ${element.title}
                                    </label><br>`;
                                });
                html += `   </div>
                        </div>`;
                        
                multipleOptionIndex++;
            }
            else if(parseInt(item.type) === 3 && parseInt(item.visible) === 1){
                html += `<div class="col-lg-6 col-md-6 mb-3">
                            <label for="">${item.description}</label><br>
                            <a href="${ urlWeb + '/storage/' + item.file}" download>${(item.file).split('/')[1]}</a>
                        </div>`;
            }
            // Upload Information
            else if(parseInt(item.type) === 4 && parseInt(item.visible) === 1){
                html += `<div class="col-lg-6 col-md-6 mb-3">
                            <label for="">${item.description}</label>&nbsp;${parseInt(item.required) === 1 ? '<small class="text-red">Obligatorio</small>' : ''}
                            <div class="form-group">
                                <input type="file" class="uploadOption" name="upload_${item.id}[]" id="upload_${item.id}" value="Seleccionar archivo" ${parseInt(item.required) === 1 ? "required" : ""} data-variable="${item.id}" multiple="multiple"/>
                            </div>
                        </div>`;
                arrFilesName.push('upload_'+item.id);
            }
            // Text
            else if(parseInt(item.type) === 5 && parseInt(item.visible) === 1){
                html += `<div class="col-lg-8 col-md-8 mb-3">
                            <label for="">${item.description}</label>
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
        html += `</div>`;
        $('#container_variables').removeClass('d-none');
        $('#frmApplyNew div[id=view_variables]').empty().append(html);

        $('.uploadOption').each(function(){
            $(this).on('change', function(){
                let images = $(this)[0].files;
                if(images.length > 5){
                    $(this).val('');
                    Core.showToast('error', 'Solo se permiten subir un máximo de 5 archivos.');
                }
            });
        });
      }
     
      if(res.data.payment_methods.length){
        res.data.payment_methods.forEach((item) => {
            $(`#frmApplyNew input:checkbox[name=payment_method][value='${item.id}']`).prop('checked', true);
        });
      }
      

      $('#mdlApplyNew').modal('show');
      //console.log(res.data.data);
    }).catch(function(err){
      console.log(err);
    })
}

function showDetailsWithSession(departure_id){

    console.log('/' + route+'/'+departure_id);
    //Axios Http Get Request
    Core.get(route + '/' + departure_id).then(function(res){
        let html = '';
        html += `<div class="col-lg-4 col-md-4 offset-lg-8 offset-8">
                    <span><b>Fecha de entrega:</b>&nbsp;${moment(res.data.data.execution).format('DD/MM/YYYY')}</span>
                </div>

                <div class="col-lg-4 col-md-4 mt-3">
                    <span><b>Código:</b>&nbsp;${res.data.data.code}</span>
                </div>
                <div class="col-lg-12 col-md-12">
                    <span><b>Descripción:</b>&nbsp;${res.data.data.description}</span>
                </div>
                <div class="col-lg-3 col-md-3">
                    <span><b>Cantidad:</b>&nbsp;${parseFloat(res.data.data.quantity).toFixed(2)}</span>
                </div>
                <div class="col-lg-3 col-md-3">
                    <span><b>Dimensión:</b>&nbsp;${res.data.data.dimension.name}</span>
                 </div>`;
         html += `<div class="col-lg-12 col-md-12 mt-4">
                 <span><b><i>Variable(s) suplementaria(s)</i></b></span>
             </div>`;
        
        if(res.data.data.variables.length){
            let counter = 0;
            res.data.data.variables.forEach((item) => {
                console.log(item.status);
                if(parseInt(item.status) === 2){
                    
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
                    // Download Information
                    else if(parseInt(item.type) === 3 && parseInt(item.visible) === 1){
                        html += `<div class="col-lg-6 col-md-6 mb-3">
                                <label for="">${item.description}</label><br>
                                <a href="${ urlWeb + '/storage/' + item.file}" download>${(item.file).split('/')[1]}</a>
                            </div>`;
                    }
                    // Upload Information
                    else if(parseInt(item.type) === 4 && parseInt(item.visible) === 1){
                        html += `<div class="col-lg-6 col-md-6 mb-3">
                                    <label for="">${item.description}</label>&nbsp;${parseInt(item.required) === 1 ? '<small class="text-red">Obligatorio</small>' : ''}
                                </div>`;
                        //arrFilesName.push('upload_'+item.id);
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
                    counter++;
                }
                
            });
            if(counter === 0){
                html += `<div class="col-lg-12 col-md-12 mt-4">
                        <span><b><i>No hay datos</i></b></span>
                    </div>`;     
            }
        }
        else{
            html += `<div class="col-lg-12 col-md-12 mt-4">
                        <span><b><i>No hay datos</i></b></span>
                    </div>`; 
        }
        

        // Payment methods
        
        $('#view_departure_details').empty().append(html);
        $('#mdlShowDetail').modal('show')
    }).catch(function(err){
      console.log(err);
    })
}

function showDetailsWithoutSession(departure_id){
    //Axios Http Get Request
    Core.get('/client/departures/' + departure_id).then(function(res){
      let html = '';
      html += `<div class="col-lg-4 col-md-4 offset-lg-8 offset-8">
                    <span><b>Fecha de entrega:</b>&nbsp;${moment(res.data.data.execution).format('DD/MM/YYYY')}</span>
                </div>

                <div class="col-lg-4 col-md-4 mt-3">
                    <span><b>Código:</b>&nbsp;${res.data.data.code}</span>
                </div>
                <div class="col-lg-12 col-md-12">
                    <span><b>Descripción:</b>&nbsp;${res.data.data.description}</span>
                </div>
                <div class="col-lg-3 col-md-3">
                    <span><b>Cantidad:</b>&nbsp;${parseFloat(res.data.data.quantity).toFixed(2)}</span>
                </div>
                <div class="col-lg-3 col-md-3">
                    <span><b>Dimensión:</b>&nbsp;${res.data.data.dimension.name}</span>
                </div>`;
      html += `<div class="col-lg-12 col-md-12 mt-4">
                <span><b><i>Variable(s) suplementaria(s)</i></b></span>
          </div>`;
        if(res.data.data.variables.length){
            let counter = 0;
            res.data.data.variables.forEach((item) => {

                if(parseInt(item.status) === 2){
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
                    // Download Information
                    else if(parseInt(item.type) === 3 && parseInt(item.visible) === 1){
                        html += `<div class="col-lg-6 col-md-6 mb-3">
                                <label for="">${item.description}</label><br>
                                <a href="${ urlWeb + '/storage/' + item.file}" download>${(item.file).split('/')[1]}</a>
                            </div>`;
                    }
                    // Upload Information
                    else if(parseInt(item.type) === 4 && parseInt(item.visible) === 1){
                        html += `<div class="col-lg-6 col-md-6 mb-3">
                                    <label for="">${item.description}</label>&nbsp;${parseInt(item.required) === 1 ? '<small class="text-red">Obligatorio</small>' : ''}
                                </div>`;
                        //arrFilesName.push('upload_'+item.id);
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
                    counter++;
                }
                
            });

            if(counter === 0){
                html += `<div class="col-lg-12 col-md-12 mt-4">
                        <span><b><i>No hay datos</i></b></span>
                    </div>`;     
            }
        }
        else{
            html += `<div class="col-lg-12 col-md-12 mt-4">
                        <span><b><i>No hay datos</i></b></span>
                    </div>`; 
        }

        // Payment methods
        

        $('#view_departure_details').empty().append(html);
        $('#mdlShowDetail').modal('show')
    }).catch(function(err){
      console.log(err);
    })
}

$('#frmApplyNew input:checkbox[name=iva]').on('change',function(){

    if($(this).is(':checked')){
        let subTotal;
        let iva;
        let total;
        
        if($('#frmApplyNew input[id=price_total]').val() !== ''){
            // With IVA
            if(parseFloat($('#frmApplyNew input[id=percentage_iva]').val()) !== 0){
                
                total = parseFloat($('#frmApplyNew input[id=quantity]').val() * $('#frmApplyNew input[id=price_unit]').val());
                iva = parseFloat(total / (($('#frmApplyNew input[id=percentage_iva]').val()+100) / 100));
                //alert(total-iva);
                subTotal = total - iva;    
                
                $('#frmApplyNew input[id=price_iva]').val(parseFloat(iva).toFixed(2));
                $('#frmApplyNew input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
                $('#frmApplyNew input[id=price_total]').val(parseFloat(total).toFixed(2));
            }
            // Without IVA
            else{
                subTotal = parseFloat($('#frmApplyNew input[id=quantity]').val() * $('#frmApplyNew input[id=price_unit]').val());
                iva = parseFloat((subTotal * $('#frmApplyNew input[id=percentage_iva]').val()) / 100);
                total = subTotal + iva;    
                $('#frmApplyNew input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
                $('#frmApplyNew input[id=price_iva]').val(parseFloat(iva).toFixed(2));
                $('#frmApplyNew input[id=price_total]').val(parseFloat(total).toFixed(2));

                //$('#frmApplyNew input[id=price_total]').val((parseFloat($('#frmApplyNew input[id=price_total]').val())).toFixed(2));
                //$('#frmApplyNew label[id=label_iva]').html('Precio total sin iva');
            }
        }   
        else{
            $(this).prop('checked', false);
            //$('#frmApplyNew label[id=label_iva]').html('Precio total sin iva');
        }
    }
    // Without IVA
    else{

        subTotal = parseFloat($('#frmApplyNew input[id=quantity]').val() * $('#frmApplyNew input[id=price_unit]').val());
        iva = parseFloat((subTotal * $('#frmApplyNew input[id=percentage_iva]').val()) / 100);
        total = subTotal + iva;    
        $('#frmApplyNew input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
        $('#frmApplyNew input[id=price_iva]').val(parseFloat(iva).toFixed(2));
        $('#frmApplyNew input[id=price_total]').val(parseFloat(total).toFixed(2));
        
    }
});
 
$('#frmApplyNew input[id=quantity]').on('blur', function(){
    let subTotal;
    let iva;
    let total;

    if($('#frmApplyNew input[id=quantity]').val() !== '' && $('#frmApplyNew input[id=price_unit]').val()){
        // With IVA
        if(parseFloat($('#frmApplyNew input[id=percentage_iva]').val()) !== 0){
            // With IVA
            if($('#frmApplyNew input:checkbox[name=iva]').is(':checked')){
                total = parseFloat($('#frmApplyNew input[id=quantity]').val() * $('#frmApplyNew input[id=price_unit]').val());
                iva = parseFloat(total / (($('#frmApplyNew input[id=percentage_iva]').val()+100) / 100));
                subTotal = total - iva;    
                
                $('#frmApplyNew input[id=price_iva]').val(parseFloat(iva).toFixed(2));
                $('#frmApplyNew input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
                $('#frmApplyNew input[id=price_total]').val(parseFloat(total).toFixed(2));
                
            }
            // Without IVA
            else{
                subTotal = parseFloat($('#frmApplyNew input[id=quantity]').val() * $('#frmApplyNew input[id=price_unit]').val());
                iva = parseFloat((subTotal * $('#frmApplyNew input[id=percentage_iva]').val()) / 100);
                total = subTotal + iva;    
                $('#frmApplyNew input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
                $('#frmApplyNew input[id=price_iva]').val(parseFloat(iva).toFixed(2));
                $('#frmApplyNew input[id=price_total]').val(parseFloat(total).toFixed(2));
            }
        }
        // Without IVA
        else{
            subTotal = parseFloat($('#frmApplyNew input[id=quantity]').val() * $('#frmApplyNew input[id=price_unit]').val());
            iva = parseFloat((subTotal * $('#frmApplyNew input[id=percentage_iva]').val()) / 100);
            total = subTotal + iva;
            $('#frmApplyNew input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
            $('#frmApplyNew input[id=price_iva]').val(parseFloat(iva).toFixed(2));
            $('#frmApplyNew input[id=price_total]').val(parseFloat(total).toFixed(2));
        }
    }
});

$('#frmApplyNew input[id=price_unit]').on('blur', function(){
    
    if($('#frmApplyNew input[id=quantity]').val() !== '' && $('#frmApplyNew input[id=price_unit]').val()){
        // With IVA
        if(parseFloat($('#frmApplyNew input[id=percentage_iva]').val()) !== 0){
            // With IVA
            if($('#frmApplyNew input:checkbox[name=iva]').is(':checked')){
                total = parseFloat($('#frmApplyNew input[id=quantity]').val() * $('#frmApplyNew input[id=price_unit]').val());
                iva = parseFloat(total / (($('#frmApplyNew input[id=percentage_iva]').val()+100) / 100));
                subTotal = total - iva;    
                
                $('#frmApplyNew input[id=price_iva]').val(parseFloat(iva).toFixed(2));
                $('#frmApplyNew input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
                $('#frmApplyNew input[id=price_total]').val(parseFloat(total).toFixed(2));
            }
            // Without IVA
            else{
                subTotal = parseFloat($('#frmApplyNew input[id=quantity]').val() * $('#frmApplyNew input[id=price_unit]').val());
                iva = parseFloat((subTotal * $('#frmApplyNew input[id=percentage_iva]').val()) / 100);
                total = subTotal + iva;   
                $('#frmApplyNew input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
                $('#frmApplyNew input[id=price_iva]').val(parseFloat(iva).toFixed(2));
                $('#frmApplyNew input[id=price_total]').val(parseFloat(total).toFixed(2));
            }
        }
        // Without IVA
        else{
            
            subTotal = parseFloat($('#frmApplyNew input[id=quantity]').val() * $('#frmApplyNew input[id=price_unit]').val());
            iva = parseFloat((subTotal * $('#frmApplyNew input[id=percentage_iva]').val()) / 100);
            total = subTotal + iva;
            $('#frmApplyNew input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
            $('#frmApplyNew input[id=price_iva]').val(parseFloat(iva).toFixed(2));
            $('#frmApplyNew input[id=price_total]').val(parseFloat(total).toFixed(2));
        }
    }

});

$(document).on('click','.simpleOption', function(){
    let type = $(this).data('type');
    let description = $(this).data('description');
    let option = $(this).val();
    //console.log(arrVariables.length, arrVariables)
    if(arrVariablesSimpleOption.length){
        let index = arrVariablesSimpleOption.findIndex((element) => element.description === description && element.option !== option);
        if(index === -1){
            arrVariablesSimpleOption.push({
                type: type,
                description: description,
                option: option
            });    
        }
        else{
            arrVariablesSimpleOption.splice(index, 1);
            arrVariablesSimpleOption.push({
                type: type,
                description: description,
                option: option
            });
        }
    }
    else{
        arrVariablesSimpleOption.push({
            type: type,
            description: description,
            option: option
        });
    }
});

$(document).on('click','.multipleOption', function(){
    let type = $(this).data('type');
    let description = $(this).data('description');
    let option = $(this).val();
    let index;
    if($(this).is(':checked')){
        //console.log(type, description, option);
        if(arrVariablesMultipleOption.length){
            index = arrVariablesMultipleOption.findIndex((element) => element.description === description && element.option === option);
            if(index === -1){
                arrVariablesMultipleOption.push({
                    type: type,
                    description: description,
                    option: option
                });    
            }
            else{
                arrVariablesMultipleOption.splice(index,1);
                arrVariablesMultipleOption.push({
                    type: type,
                    description: description,
                    option: option
                });
            }
        }
        else{
            arrVariablesMultipleOption.push({
                type: type,
                description: description,
                option: option
            });
        }
    }
    else{
        index = arrVariablesMultipleOption.findIndex((element) => element.description === description && element.option === option);
        if(index !== -1){
            arrVariablesMultipleOption.splice(index,1);
        }
    }
    //console.log(arrVariablesMultipleOption);
});

$('#frmApplyNew select[id=type]').on('change',function(){
    $('#frmApplyNew input[id=description]').val('');
    if($(this).val() !== ''){
        if($('#frmApplyNew select[id=type] option:selected').text() === 'Alternativo'){
            $('#frmApplyNew div[id=view_description]').removeClass('d-none');
            $('#frmApplyNew input[id=description]').prop('required', true);
        }
        else{
            $('#frmApplyNew div[id=view_description]').addClass('d-none');
            $('#frmApplyNew input[id=description]').prop('required', false);
        }
    }
    else{
        $('#frmApplyNew div[id=view_description]').addClass('d-none');
        $('#frmApplyNew input[id=description]').prop('required', false);
    }
});

$('#frmApplyNew').validate({
  rules:{
    payment_method:{
        required: true
    }
  }, 
  messages:{
    payment_method:{
        required: 'Debe seleccionar al menos uno.'
    }
  },
  submitHandler: function (form) {
  itemData = new FormData(form);
  let expirationDate = ($('#frmApplyNew input[name=expiration_date]').val()).split('/');

  //if(!$('#frmApplyNew input:checkbox[id=iva]').is(':checked')){
    itemData.set('percentage_iva', $('#frmApplyNew input[id=percentage_iva]').val());
  //}
  //alert("hola " + arrVariablesSimpleOption.length + ' ' + arrVariablesMultipleOption.length);

  if(arrVariablesSimpleOption.length){
    arrVariablesSimpleOption.forEach((item) => {
        itemData.append('simple_option[]', JSON.stringify(item));
        //console.log(item.type, item.description, item.option);
    });
  }

  if(arrVariablesMultipleOption.length){
    arrVariablesMultipleOption.forEach((item) => {
        //console.log(item.type, item.description, item.option);
        itemData.append('multiple_option[]', JSON.stringify(item));
    });
  }

  $('#frmApplyNew input[type=checkbox]:checked').each(function() {
    if(this.checked){
        if($(this).attr('name') === 'payment_method'){
            itemData.append('payment_methods[]', $(this).val());
        }
    }
  });

  itemData.set('iva', $('#frmApplyNew input:checkbox[id=iva]').is(':checked') ? 1 : 0);
  itemData.set('expiration_date', expirationDate[2] + '-' + expirationDate[1] + '-' + expirationDate[0]);
  itemData.delete('payment_method[]');

  if(arrFilesName.length){
    arrFilesName.forEach((item, index) => {
        itemData.append('upload_files[]', arrFilesName[index]);
    });
  }
  
  //Axios Http Post Request
  Core.post(route + '/apply/store', itemData).then(function(res){
    Core.showToast('success', res.data.message);
    $('#mdlApplyNew').modal('hide');
    setTimeout(() => {
        window.location = res.data.redirect;
    }, 1500);
  }).catch(function(err){
    Core.showAlert('error', err.response.data.error.message);
  });

}});

$('#frmApplyEdit').validate({
  submitHandler: function (form) {
  itemData = new FormData(form);
  alert("enviado");
}});

$('#frmVariantNew').validate({
  submitHandler: function (form) {
  itemData = new FormData(form);
  
  let type = $('#frmVariantNew select[id=type]').val() === '1' ? 'Original':'Alternativo';
  let includes = $('#frmVariantNew select[id=includes]').val() === '1' ? 'Suministro' : $('#frmVariantNew select[id=includes]').val() === '2' ? 'Instalación' : 'Suminitro + Instalación';
  tableVariantDataNew.row.add([
    type,
    includes,
    parseFloat($('#frmVariantNew input[id=quantity]').val()).toFixed(2),
    parseFloat($('#frmVariantNew input[id=price_unit]').val()).toFixed(2),
    parseFloat($('#frmVariantNew input[id=price_total]').val()).toFixed(2),
    $('#frmVariantNew input:checkbox[id=iva]').is(':checked') ? 'Sí' : 'No',
    `<center>
        <button class="btn btn-danger btn-sm btnDelete" type="button"><i class="fa fa-times text-white"></i></button>
    </center>`,
    $('#frmVariantNew select[id=type]').val(),
    $('#frmVariantNew select[id=includes]').val(),
    '',
    ''
  ]).draw();
  
  $('#mdlVariantNew').modal('hide');

}});

$('#frmApplyNew').on('hidden.bs.modal', function(){
    $('#frmApplyNew')[0].reset();
    $('#frmApplyNew').validate().resetForm();
    $('input,textarea,select').removeClass('is-invalid');
    $('#container_variables').addClass('d-none');
    tableVariantDataNew.clear().draw();
    arrVariablesSimpleOption = [];
    arrVariablesMultipleOption = [];
    arrFilesName = [];
});

$('#frmVariantNew').on('hidden.bs.modal', function(){
    $('#frmVariantNew')[0].reset();
    $('#frmVariantNew').validate().resetForm();
    $('input,textarea,select').removeClass('is-invalid');
    
});

$('#frmVariantEdit').on('hidden.bs.modal', function(){
    $('#frmVariantEdit')[0].reset();
    $('#frmVariantEdit').validate().resetForm();
    $('input,textarea,select').removeClass('is-invalid');
});

$(document).on('click','#btnCommentEdit', function(){
    data = $(this).data('id');
    //Axios Http Get Request
    Core.get(route+'/comments/'+data).then(function(res){
      $('#frmCommentEdit input[id=comment_id]').val(res.data.data.id);
      $('#frmCommentEdit input[id=description]').val(res.data.data.description);
      $('#mdlCommentEdit').modal('show');
    }).catch(function(err){
      console.log(err);
    });
});

$('#frmCommentEdit').validate({
    submitHandler: function (form) {
    itemData = new FormData(form);
    //Axios Http Post Request
    Core.post(route + '/comments/update/' + $('#frmCommentEdit input[id=comment_id]').val(), itemData).then(function(res){
        Core.showToast('success', res.data.message);
        $('#mdlCommentEdit').modal('hide');
        setTimeout(() => {
            window.location = res.data.redirect;
        }, 1200);
    }).catch(function(err){
      console.log(err);
    }).finally(() => {
        
    });
}});

$(document).on('click','#btnCommentReport', function(){
    data = $(this).data('id');
    $('#frmCommentReport input[id=comment_id]').val(data);
    bootbox.confirm({
        message: '¿Esta seguro(a) de reportar el comentario al administrador?',
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
                $('#mdlCommentReport').modal('show');
            }
        }
    });
});

$(document).on('click','#btnSearch', function(e){
    e.preventDefault();
    if($('#frmSearch input[id=search]').val() !== ''){
        window.location = urlWeb + '/client/projects?project='+$('#frmSearch input[id=project_id]').val()+'&search='+$('#frmSearch input[id=search]').val();
    }
    else{
        window.location = urlWeb + '/client/projects?project='+$('#frmSearch input[id=project_id]').val();
    }
    
});

$(document).on('click','#btnRefresh', function(e){
    e.preventDefault();
    window.location = urlWeb + '/client/projects?project='+$('#frmSearch input[id=project_id]').val();
});

$('#frmCommentReport').validate({
    submitHandler: function (form) {
    itemData = new FormData(form);
    //Axios Http Post Request
    Core.post(route + '/comments/report/' + $('#frmCommentReport input[id=comment_id]').val(), itemData).then(function(res){
        Core.showToast('success', res.data.message);
        $('#mdlCommentReport').modal('hide');
        setTimeout(() => {
            window.location = res.data.redirect;
        }, 1200);
    }).catch(function(err){
      console.log(err);
    }).finally(() => {
        
    });
}});

$(document).on('click','#btnCommentDelete', function(){
    data = $(this).data('id');
    bootbox.confirm({
        message: '¿Esta seguro(a) de eliminar el comentario?',
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
                //Axios Http Get Request
                Core.post(route+'/comments/destroy/'+data, itemData).then(function(res){
                  Core.showToast('success', res.data.message);
                  setTimeout(() => {
                    window.location = res.data.redirect;
                  }, 1200);
                }).catch(function(err){
                  console.log(err);
                })
            }
        }
    });
});

$(document).on('keypress',function(evt){
    var code = (evt.keyCode ? evt.keyCode : evt.which);
    if(code==13){
        evt.preventDefault();
        //alert("hola");
        //window.location = urlWeb + '/client/projects?project='+$('#frmSearch input[id=project_id]').val();
    }
});

$('#mdlCommentEdit').on('hidden.bs.modal', function(){
    $('#frmCommentEdit')[0].reset();
});

$('#mdlCommentReport').on('hidden.bs.modal', function(){
    $('#frmCommentReport')[0].reset();
});

function init(){
    $('#frmApplyNew input[name=expiration_date]').daterangepicker({
        drops: 'auto',
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
}

init();

