route = '/client/projects/my-offers';

var arrVariablesSimpleOption = [];
var arrVariablesMultipleOption = [];
var arrFilesName = [];
var simple_option;
var multiple_option;
var num_files=0;

function showOffer(variant_id){
    //Axios Http Get Request
    
    Core.get(route + '/' + variant_id).then(function(res){
        console.log(res.data);
        $('#frmOfferShow input[id=project_id]').val(res.data.data.departure.project.id);
        $('#frmOfferShow span[id=date_of_delivery]').text(moment(res.data.data.departure.execution_date).format('DD/MM/YYYY'));
        $('#frmOfferShow span[id=view_title]').text(res.data.data.departure.code + ' ' + res.data.data.departure.description);
        $('#frmOfferShow input[id=expiration_date]').val(moment(res.data.data.expiration_date).format('DD/MM/YYYY'));
        $('#frmOfferShow select[id=type]').val(res.data.data.type);
        $('#frmOfferShow select[id=type]').prop('disabled', true);
        $('#frmOfferShow select[id=includes]').val(res.data.data.includes);
        $('#frmOfferShow select[id=includes]').prop('disabled', true);
        $('#frmOfferShow span[id=quantity]').text(res.data.data.departure.quantity);
        $('#frmOfferShow span[id=dimension]').text(res.data.data.departure.dimensions);
        $('#frmOfferShow input[id=quantity]').attr('max', res.data.data.departure.quantity);
        $('#frmOfferShow input[id=quantity]').val(res.data.data.quantity);
        $('#frmOfferShow input[id=price_unit]').val(res.data.data.price_unit);
        $('#frmOfferShow input[id=price_total]').val(res.data.data.price_total);
        $('#frmOfferShow input:checkbox[id=iva]').prop('checked', res.data.data.iva === 1 ? true : false);
        $('#frmOfferShow input:checkbox[id=iva]').prop('disabled', true);

        if(parseInt(res.data.data.iva) === 1){
            
            total = parseFloat($('#frmOfferShow input[id=quantity]').val() * $('#frmOfferShow input[id=price_unit]').val());
            iva = parseFloat(total / (($('#frmOfferShow input[id=percentage_iva]').val()+100) / 100));
            subTotal = total - iva;    
            
            $('#frmOfferShow input[id=price_iva]').val(parseFloat(iva).toFixed(2));
            $('#frmOfferShow input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
            $('#frmOfferShow input[id=price_total]').val(parseFloat(total).toFixed(2));
            
        }
        // Without IVA
        else{
            subTotal = parseFloat($('#frmOfferShow input[id=quantity]').val() * $('#frmOfferShow input[id=price_unit]').val());
            iva = parseFloat((subTotal * $('#frmOfferShow input[id=percentage_iva]').val()) / 100);
            total = subTotal + iva;    
            
            $('#frmOfferShow input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
            $('#frmOfferShow input[id=price_iva]').val(parseFloat(iva).toFixed(2));
            $('#frmOfferShow input[id=price_total]').val(parseFloat(total).toFixed(2));
    
        }

        let html = '';
        $('#frmOfferShow input[type=checkbox]').each(function(){
            $(this).prop('disabled', true);
        });

        $('#frmOfferShow div[id=view_payment_methods]').empty().append(html);

        if(res.data.data.payment_methods.length){
            res.data.data.payment_methods.forEach((item) => {
                $('#frmOfferShow input:checkbox[id=payment_method_'+item.id+']').prop('checked', true);
                $('#frmOfferShow input:checkbox[id=payment_method_'+item.id+']').prop('disabled', true);
            });
        }

        if(res.data.data.departure.variables.length){
            if(res.data.data.simple_option !== null){
                simple_option = JSON.parse(res.data.data.simple_option);
            }
            if(res.data.data.multiple_option !== null){
                multiple_option = JSON.parse(res.data.data.multiple_option);
            }
            let simpleOptionIndex = 1;
            let multipleOptionIndex = 1;
            let html = `<div class="row">`;
            res.data.data.departure.variables.forEach((item) => {
                let options;
                if(item.options !== null && item.options.length){
                    options = JSON.parse(item.options);
                }
                
                // Simple Options
                if(parseInt(item.type) === 1 && parseInt(item.visible) === 1){
                    html += `<div class="col-lg-6 col-md-6 mb-3">
                                <label for="">${item.description}</label>
                                <div class="form-check">`;
                                    options.map((element, index) => {
                                        html += `<input class="form-check-input simpleOption" type="radio" name="simple_option_${simpleOptionIndex}" id="simple_option_${element.title}_${item.id}" value="${element.title}" data-type="${item.type}" data-description="${item.description}" ${parseInt(item.required) === 1 ? "required":""}>
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
                    html += `<div class="col-lg-6 col-md-6 mb-3">           
                                <label for="">${item.description}</label>
                                <div class="form-check">`;
                                    options.map((element, index) => {
                                        html += `<input class="form-check-input multipleOption" type="checkbox" name="multiple_option_${multipleOptionIndex}" id="multiple_option_${element.title}_${item.id}" value="${element.title}" data-type="${item.type}" data-description="${item.description}" ${parseInt(item.required) === 1 ? "required":""}>
                                        <label class="form-check-label" for="">
                                            ${element.title}
                                        </label><br>`;
                                    });
                    html += `   </div>
                            </div>`;
                            
                    multipleOptionIndex++;
                }
                // Upload Information
                else if(parseInt(item.type) === 4 && parseInt(item.visible) === 1 && res.data.data.upload_information !== null){
                    html += `<div class="col-lg-6 col-md-6 mb-3">
                                <label for="">Documentos</label><br>`;
                                let upload_information = JSON.parse(res.data.data.upload_information);
                                upload_information.map((element, index) => {
                                   html+= `<a href="${ urlWeb + '/storage/' + element.file }" download>${ (element.file).split('/')[1] }</a><br>`;
                                   num_files++;
                                });
                    html += `</div>`;
                    
                    //arrFilesName.push('upload_'+item.id);
                    //console.log(arrFilesName);
                }
                // Text
                else if(parseInt(item.type) === 5 && parseInt(item.visible) === 1){
                    html += `<div class="col-lg-12 col-md-12 mb-3">
                                <label for="">${item.description}</label>
                                <p class="text-justify">${item.text}</p>
                            </div>`;
                }
            });
            html += `</div>`;
            
            $('#frmOfferShow div[id=view_variables]').empty().append(html);

            $('.uploadOption').each(function(){
                $(this).on('change', function(){
                    let images = $(this)[0].files;
                    let count_files = parseInt(images.length) + parseInt(num_files);
                    if(parseInt(count_files) > 5){
                        $(this).val('');
                        Core.showToast('error', 'Solo se permiten subir un máximo de 5 archivos.');
                    }
                });
            });
        }

        if(res.data.data.departure.variables.length){
            simple_option = JSON.parse(res.data.data.simple_option);
            multiple_option = JSON.parse(res.data.data.multiple_option);
            upload_information = JSON.parse(res.data.data.upload_information);

            // console.log(simple_option);
            // console.log("estrn " + res.data.data.departure.id);

            res.data.data.departure.variables.forEach((item) => {
                // Simple Options
                if(parseInt(item.type) === 1 && parseInt(item.visible) === 1){
                    if(simple_option !== null && simple_option.length){
                        simple_option.map((element, index) => {
                            $('#frmOfferShow input:radio[id=simple_option_' + element.option + '_' + item.id + ']').prop('checked', true);
                            $('#frmOfferShow input:radio[id=simple_option_' + element.option + '_' + item.id + ']').prop('disabled', true);
                        });
                    }
                }
                // Multiple Options
                else if(parseInt(item.type) === 2 && parseInt(item.visible) === 1){
                    if(multiple_option !== null && multiple_option.length){
                        multiple_option.map((element, index) => {
                            $('#frmOfferShow input:checkbox[id=multiple_option_' + element.option + '_' + item.id + ']').prop('checked', true);
                            $('#frmOfferShow input:checkbox[id=multiple_option_' + element.option + '_' + item.id + ']').prop('disabled', true);
                        });
                    }
                }

            });

        }

        $('#mdlOfferShow').modal('show');

    }).catch(function(err){
      console.log(err);
    })
}

function editOffer(variant_id){

    //Axios Http Get Request
    Core.get(route + '/' + variant_id).then(function(res){
        $('#frmOfferEdit input[id=variant_id]').val(res.data.data.id);
        $('#frmOfferEdit input[id=departure_id]').val(res.data.data.departure.id);
        $('#frmOfferEdit input[id=project_id]').val(res.data.data.departure.project.id);
        $('#frmOfferEdit span[id=date_of_delivery]').text(moment(res.data.data.departure.execution_date).format('DD/MM/YYYY'));
        $('#frmOfferEdit span[id=departure_name]').text('('+res.data.data.departure.code + ') ' + res.data.data.departure.description);
        $('#frmOfferEdit span[id=view_title]').text(res.data.data.departure.code + ' ' + res.data.data.departure.description);
        $('#frmOfferEdit input[id=expiration_date]').val(moment(res.data.data.expiration_date).format('DD/MM/YYYY'));
        $('#frmOfferEdit select[id=type]').val(res.data.data.type);
        $('#frmOfferEdit select[id=includes]').val(res.data.data.includes);
        $('#frmOfferEdit span[id=quantity]').text(res.data.data.departure.quantity);
        $('#frmOfferEdit span[id=dimension]').text(res.data.data.departure.dimensions);
        $('#frmOfferEdit input[id=quantity]').attr('max', res.data.data.departure.quantity);
        $('#frmOfferEdit input[id=quantity]').val(res.data.data.quantity);
        $('#frmOfferEdit input[id=price_unit]').val(res.data.data.price_unit);

        $('#frmOfferEdit input:checkbox[id=iva]').prop('checked', res.data.data.iva === 1 ? true : false);
         
            // With IVA
        if(parseInt(res.data.data.iva) === 1){
            
            total = parseFloat($('#frmOfferEdit input[id=quantity]').val() * $('#frmOfferEdit input[id=price_unit]').val());
            iva = parseFloat(total / (($('#frmOfferEdit input[id=percentage_iva]').val()+100) / 100));
            subTotal = total - iva;    
            
            $('#frmOfferEdit input[id=price_iva]').val(parseFloat(iva).toFixed(2));
            $('#frmOfferEdit input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
            $('#frmOfferEdit input[id=price_total]').val(parseFloat(total).toFixed(2));
            
        }
        // Without IVA
        else{
            subTotal = parseFloat($('#frmOfferEdit input[id=quantity]').val() * $('#frmOfferEdit input[id=price_unit]').val());
            iva = parseFloat((subTotal * $('#frmOfferEdit input[id=percentage_iva]').val()) / 100);
            total = subTotal + iva;    
            
            $('#frmOfferEdit input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
            $('#frmOfferEdit input[id=price_iva]').val(parseFloat(iva).toFixed(2));
            $('#frmOfferEdit input[id=price_total]').val(parseFloat(total).toFixed(2));
    
        }

        let html = '';
        if(res.data.data.departure.project.payment_methods.length){
            res.data.data.departure.project.payment_methods.forEach((item) => {
                html += `<span class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" class="form-check-input" id="payment_method_${item.id}"
                                name="payment_method" value="${item.id}">
                            <label class="form-check-label"
                                for="payment_method_${item.id}">${item.name}</label>
                        </span>`;
            });  
        }

        $('#frmOfferEdit div[id=view_payment_methods]').empty().append(html);
        
        if(res.data.data.payment_methods.length){
            res.data.data.payment_methods.forEach((item) => {
                $('#frmOfferEdit input:checkbox[id=payment_method_'+item.id+']').prop('checked', true);
            });
        }

        if(res.data.data.departure.variables.length){
            if(res.data.data.simple_option !== null){
                simple_option = JSON.parse(res.data.data.simple_option);
            }
            if(res.data.data.multiple_option !== null){
                multiple_option = JSON.parse(res.data.data.multiple_option);
            }
            let simpleOptionIndex = 1;
            let multipleOptionIndex = 1;
            let html = `<div class="row">`;
            res.data.data.departure.variables.forEach((item) => {
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
                // Upload Information
                else if(parseInt(item.type) === 4 && parseInt(item.visible) === 1){
                    html += `<div class="col-lg-6 col-md-6 mb-3">
                                <label for="">${item.description}</label>&nbsp;${parseInt(item.required) === 1 ? '<small class="text-red">Obligatorio</small>' : ''}
                                <div class="form-group">
                                    <input type="file" class="uploadOption" name="upload_${item.id}[]" id="upload_${item.id}" value="Seleccionar archivo" ${parseInt(item.required) === 1 ? "required" : ""} data-variable="${item.id}" multiple="multiple"/>
                                </div>`;
                            if(res.data.data.upload_information !== null){
                                let upload_information = JSON.parse(res.data.data.upload_information);
                                upload_information.map((element, index) => {
                                   html+= `<a href="${ urlWeb + '/storage/' + element.file }" download>${ (element.file).split('/')[1] }</a><br>`;
                                   num_files++;
                                });
                            }
                    html += `</div>`;
                    
                    arrFilesName.push('upload_'+item.id);
                    //console.log(arrFilesName);
                }
                // Text
                else if(parseInt(item.type) === 5 && parseInt(item.visible) === 1){
                    html += `<div class="col-lg-12 col-md-12 mb-3">
                                <label for="">${item.description}</label>
                                <p class="text-justify">${item.text}</p>
                            </div>`;
                }
            });
            html += `</div>`;
            
            $('#frmOfferEdit div[id=view_variables]').empty().append(html);

            $('.uploadOption').each(function(){
                $(this).on('change', function(){
                    let images = $(this)[0].files;
                    let count_files = parseInt(images.length) + parseInt(num_files);
                    if(parseInt(count_files) > 5){
                        $(this).val('');
                        Core.showToast('error', 'Solo se permiten subir un máximo de 5 archivos.');
                    }
                });
            });
        }

        if(res.data.data.departure.variables.length){
            simple_option = JSON.parse(res.data.data.simple_option);
            multiple_option = JSON.parse(res.data.data.multiple_option);
            upload_information = JSON.parse(res.data.data.upload_information);

            // console.log(simple_option);
            // console.log("estrn " + res.data.data.departure.id);

            res.data.data.departure.variables.forEach((item) => {
                // Simple Options
                if(parseInt(item.type) === 1 && parseInt(item.visible) === 1){
                    if(simple_option !== null && simple_option.length){
                        let counter = 1;
                        simple_option.map((element, index) => {
                            $('#frmOfferEdit input:radio[id=simple_option_' + counter + '_' + item.id + ']').prop('checked', true);
                            counter++;
                        });
                    }
                }
                // Multiple Options
                else if(parseInt(item.type) === 2 && parseInt(item.visible) === 1){
                    if(multiple_option !== null && multiple_option.length){
                        let counter = 1;
                        multiple_option.map((element, index) => {
                            $('#frmOfferEdit input:checkbox[id=multiple_option_' + counter + '_' + item.id + ']').prop('checked', true);
                            counter++;
                        });
                    }
                }

            });

        }

        $('#mdlOfferEdit').modal('show');
    }).catch(function(err){
      console.log(err);
    })

    
}

$('#frmOfferEdit input:checkbox[name=iva]').on('change',function(){
    
    if($(this).is(':checked')){
        let subTotal;
        let iva;
        let total;

        if($('#frmOfferEdit input[id=price_total]').val() !== ''){
            // With IVA
            if(parseFloat($('#frmOfferEdit input[id=percentage_iva]').val()) !== 0){

                total = parseFloat($('#frmOfferEdit input[id=quantity]').val() * $('#frmOfferEdit input[id=price_unit]').val());
                iva = parseFloat(total / (($('#frmOfferEdit input[id=percentage_iva]').val()+100) / 100));
                subTotal = total - iva;    
                
                $('#frmOfferEdit input[id=price_iva]').val(parseFloat(iva).toFixed(2));
                $('#frmOfferEdit input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
                $('#frmOfferEdit input[id=price_total]').val(parseFloat(total).toFixed(2));

            }
            // Without IVA
            else{
                subTotal = parseFloat($('#frmOfferEdit input[id=quantity]').val() * $('#frmOfferEdit input[id=price_unit]').val());
                iva = parseFloat((subTotal * $('#frmOfferEdit input[id=percentage_iva]').val()) / 100);
                total = subTotal + iva;    

                $('#frmOfferEdit input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
                $('#frmOfferEdit input[id=price_iva]').val(parseFloat(iva).toFixed(2));
                $('#frmOfferEdit input[id=price_total]').val(parseFloat(total).toFixed(2));
            }
        }   
        else{
            $(this).prop('checked', false);
        }
    }
    else{
        // Without IVA
        subTotal = parseFloat($('#frmOfferEdit input[id=quantity]').val() * $('#frmOfferEdit input[id=price_unit]').val());
        iva = parseFloat((subTotal * $('#frmOfferEdit input[id=percentage_iva]').val()) / 100);
        total = subTotal + iva;

        $('#frmOfferEdit input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
        $('#frmOfferEdit input[id=price_iva]').val(parseFloat(iva).toFixed(2));
        $('#frmOfferEdit input[id=price_total]').val(parseFloat(total).toFixed(2));
    }
});
 
$('#frmOfferEdit input[id=quantity]').on('blur', function(){
    if($('#frmOfferEdit input[id=quantity]').val() !== '' && $('#frmOfferEdit input[id=price_unit]').val()){
        let subTotal;
        let iva;
        let total;

        if(parseFloat($('#frmOfferEdit input[id=percentage_iva]').val()) !== 0){
            // With Iva
            if($('#frmOfferEdit input:checkbox[name=iva]').is(':checked')){
                
                total = parseFloat($('#frmOfferEdit input[id=quantity]').val() * $('#frmOfferEdit input[id=price_unit]').val());
                iva = parseFloat(total / (($('#frmOfferEdit input[id=percentage_iva]').val()+100) / 100));
                subTotal = total - iva;

                $('#frmOfferEdit input[id=price_iva]').val(parseFloat(iva).toFixed(2));
                $('#frmOfferEdit input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
                $('#frmOfferEdit input[id=price_total]').val(parseFloat(total).toFixed(2));
            }
            // Without Iva
            else{
                subTotal = parseFloat($('#frmOfferEdit input[id=quantity]').val() * $('#frmOfferEdit input[id=price_unit]').val());
                iva = parseFloat((subTotal * $('#frmOfferEdit input[id=percentage_iva]').val()) / 100);
                total = subTotal + iva;    

                $('#frmOfferEdit input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
                $('#frmOfferEdit input[id=price_iva]').val(parseFloat(iva).toFixed(2));
                $('#frmOfferEdit input[id=price_total]').val(parseFloat(total).toFixed(2));
                
            }
        }
        // Without IVA
        else{
            subTotal = parseFloat($('#frmOfferEdit input[id=quantity]').val() * $('#frmOfferEdit input[id=price_unit]').val());
            iva = parseFloat((subTotal * $('#frmOfferEdit input[id=percentage_iva]').val()) / 100);
            total = subTotal + iva;    

            $('#frmOfferEdit input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
            $('#frmOfferEdit input[id=price_iva]').val(parseFloat(iva).toFixed(2));
            $('#frmOfferEdit input[id=price_total]').val(parseFloat(total).toFixed(2));
        }
    }
});

$('#frmOfferEdit input[id=price_unit]').on('blur', function(){
    
    if($('#frmOfferEdit input[id=quantity]').val() !== '' && $('#frmOfferEdit input[id=price_unit]').val()){
        let subTotal;
        let iva;
        let total;

        if(parseFloat($('#frmOfferEdit input[id=percentage_iva]').val()) !== 0){
            // With Iva
            if($('#frmOfferEdit input:checkbox[name=iva]').is(':checked')){
                
                total = parseFloat($('#frmOfferEdit input[id=quantity]').val() * $('#frmOfferEdit input[id=price_unit]').val());
                iva = parseFloat(total / (($('#frmOfferEdit input[id=percentage_iva]').val()+100) / 100));
                subTotal = total - iva;

                $('#frmOfferEdit input[id=price_iva]').val(parseFloat(iva).toFixed(2));
                $('#frmOfferEdit input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
                $('#frmOfferEdit input[id=price_total]').val(parseFloat(total).toFixed(2));
            }
            // Without Iva
            else{
                subTotal = parseFloat($('#frmOfferEdit input[id=quantity]').val() * $('#frmOfferEdit input[id=price_unit]').val());
                iva = parseFloat((subTotal * $('#frmOfferEdit input[id=percentage_iva]').val()) / 100);
                total = subTotal + iva;    

                $('#frmOfferEdit input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
                $('#frmOfferEdit input[id=price_iva]').val(parseFloat(iva).toFixed(2));
                $('#frmOfferEdit input[id=price_total]').val(parseFloat(total).toFixed(2));
                
            }
        }
        // Without IVA
        else{
            subTotal = parseFloat($('#frmOfferEdit input[id=quantity]').val() * $('#frmOfferEdit input[id=price_unit]').val());
            iva = parseFloat((subTotal * $('#frmOfferEdit input[id=percentage_iva]').val()) / 100);
            total = subTotal + iva;    

            $('#frmOfferEdit input[id=sub_total]').val(parseFloat(subTotal).toFixed(2));
            $('#frmOfferEdit input[id=price_iva]').val(parseFloat(iva).toFixed(2));
            $('#frmOfferEdit input[id=price_total]').val(parseFloat(total).toFixed(2));
            
        }
       
    }
});


$(document).on('click','.simpleOption', function(){
    let type = $(this).data('type');
    let description = $(this).data('description');
    let option = $(this).val();
    
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

$('#frmOfferEdit select[id=type]').on('change',function(){
    $('#frmOfferEdit input[id=description]').val('');
    if($(this).val() !== ''){
        if($('#frmOfferEdit select[id=type] option:selected').text() === 'Alternativo'){
            $('#frmOfferEdit div[id=view_description]').removeClass('d-none');
            $('#frmOfferEdit input[id=description]').prop('required', true);
        }
        else{
            $('#frmOfferEdit div[id=view_description]').addClass('d-none');
            $('#frmOfferEdit input[id=description]').prop('required', false);
        }
    }
    else{
        $('#frmOfferEdit div[id=view_description]').addClass('d-none');
        $('#frmOfferEdit input[id=description]').prop('required', false);
    }
});

$('#frmOfferEdit').validate({
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
  let expirationDate = ($('#frmOfferEdit input[name=expiration_date]').val()).split('/');

  if(!$('#frmOfferEdit input:checkbox[id=iva]').is(':checked')){
    itemData.set('percentage_iva', '');
  }

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

  $('#frmOfferEdit input[type=checkbox]:checked').each(function() {
    if(this.checked){
        if($(this).attr('name') === 'payment_method'){
            itemData.append('payment_methods[]', $(this).val());
        }
    }
  });

  itemData.set('iva', $('#frmOfferEdit input:checkbox[id=iva]').is(':checked') ? 1 : 0);
  itemData.set('expiration_date', expirationDate[2] + '-' + expirationDate[1] + '-' + expirationDate[0]);
  itemData.delete('payment_method[]');

  if(arrFilesName.length){
    arrFilesName.forEach((item, index) => {
        itemData.append('upload_files[]', arrFilesName[index]);
        console.log(arrFilesName[index]);
    });
  }
  
  //Axios Http Post Request
  Core.post(route + '/update/' + $('#frmOfferEdit input[id=variant_id]').val(), itemData).then(function(res){
    Core.showToast('success', res.data.message);
    $('#mdlOfferEdit').modal('hide');
    setTimeout(() => {
        window.location = res.data.redirect;
    }, 1500);
  }).catch(function(err){
    Core.showAlert('error', err.response.data.error.message);
  });

}});

$('#mdlOfferEdit').on('hidden.bs.modal', function(){
    $('#frmOfferEdit div[id=view_payment_methods]').empty();
    $('#frmOfferEdit')[0].reset();
    $('#frmOfferEdit').validate().resetForm();
    $('input,textarea,select').removeClass('is-invalid');
    arrVariablesSimpleOption = [];
    arrVariablesMultipleOption = [];
    arrFilesName = [];
});

function init(){
    $('#frmOfferEdit input[id=expiration_date]').daterangepicker({
        drops: 'auto',
        singleDatePicker: true,
        showDropdowns: true,
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