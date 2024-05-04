route = 'search';
routeFavoriteProject = '/client/projects/favorites';

var arrPaymentMethods = [];
var arrCategories = [];
var arrProvinces = [];
var maxFields = 4;
var counter = 1;
var arrTerms = [];
var query = '';

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

function reset(){
    window.location = urlWeb;
}

function saveProject(project_id){
    itemData = new FormData();
    itemData.append('project_id', project_id);
    //Axios Http Post Request
    Core.post(routeFavoriteProject + '/store', itemData).then(function(res){
      if(res.data.type === 'success'){
        Core.showToast(res.data.type, res.data.message);
      }
      else if(res.data.type === 'info'){
        Core.showToast(res.data.type, res.data.message);
      }
    }).catch(function(err){
      Core.showAlert('error', err.response.data.error.message);
    })
}

$('#frmSearchAdvance select[id=autonomous_community]').on('change',function(){
    if($(this).val() !== ''){
        if($(this).val() !== 'all'){
            Core.get(route + '/get-province/' + $('#frmSearchAdvance select[id=autonomous_community]').val())
                .then(function (res) {
                    $('#frmSearchAdvance select[id=province_id]').empty().trigger('change');
                    $('#frmSearchAdvance select[id=province_id]').append($('<option>',{value:'all'}).text('Todas')).trigger('change');
                    res.data.data.forEach((item) => {
                        $('#frmSearchAdvance select[id=province_id]').append($('<option>',{value:item.id}).text(item.name)).trigger('change');
                    });
                    $('#frmSearchAdvance select[id=province_id]').prop('required', true);
                })
                .catch(function (err) {
                    console.log(err);
                    Core.showAlert('error', err.response.data.error.message);
                });
        }
        else{
            $('#frmSearchAdvance select[id=province_id]').empty();
            $('#frmSearchAdvance select[id=province_id]').append($('<option>',{value:''}).text('Selecciona una provincia'));    
        }
        
    }
    else{
        $('#frmSearchAdvance select[id=province_id]').empty();
        $('#frmSearchAdvance select[id=province_id]').append($('<option>',{value:''}).text('Selecciona una provincia'));
    }
});

$('#btnSearch').on('click',function(e){
    e.preventDefault();
    let categories = JSON.stringify(arrCategories);
    let payment_methods = JSON.stringify(arrPaymentMethods);
    let urlSearch = '';

    if($('#frmSearch input[id=word]').val() !== ''){
        urlSearch += '?query=term,'+$('#frmSearch input[id=word]').val();
    }

    // if($('#frmSearch input[id=word]').val() !== '' || $('#frmSearch select[id=province_id]').val() !== ''){

    //     if($('#frmSearch input[id=word]').val() !== '' && $('#frmSearch select[id=province_id]').val() === ''){
    //         urlSearch += '?query=word,'+$('#frmSearch input[id=word]').val()+'&op=1';
    //     }
    //     else if($('#frmSearch input[id=word]').val() !== '' && $('#frmSearch select[id=province_id]').val() !== ''){
    //         urlSearch += '?query=word,'+$('#frmSearch input[id=word]').val()+':prov,'+$('#frmSearch select[id=province_id]').val()+'&op=2';
    //     }
    //     else if($('#frmSearch input[id=word]').val() === '' && $('#frmSearch select[id=province_id]').val() !== ''){
    //         urlSearch += '?query=prov,'+$('#frmSearch select[id=province_id]').val()+'&op=3';
    //     }
        
    // }

    if(urlSearch.length){
        urlSearch += '&mode=simple';
        window.location = urlWeb + urlSearch;
    }
    else{
        window.location = urlWeb;
    }
});

$('#btnFilterAdvance').on('click',function(e){
    e.preventDefault();
    let urlSearch = '';
    if(arrCategories.length > 0 || arrPaymentMethods.length > 0 || arrProvinces.length > 0){
        
        if(arrCategories.length > 0 && arrPaymentMethods.length === 0 && arrProvinces.length === 0){
            counter = 1;
            arrCategories.forEach((item) => {
                if(counter === 1){
                    urlSearch += '?query=cat,'+item.value;
                }
                else{
                    urlSearch += ':cat,'+item.value;
                }
                counter++;
            });
            urlSearch += '&op=1';
        }
        else if(arrCategories.length === 0 && arrPaymentMethods.length > 0 && arrProvinces.length === 0){
            counter = 1;
            arrPaymentMethods.forEach((item) => {
                if(counter === 1){
                    urlSearch += '?query=pay,'+item.value;
                }
                else{
                    urlSearch += ':pay,'+item.value;
                }
                counter++;
            });
            urlSearch += '&op=2';
        }
        else if(arrCategories.length > 0 && arrPaymentMethods.length > 0 && arrProvinces.length === 0){
            counter = 1;
            arrCategories.forEach((item) => {
                if(counter === 1){
                    urlSearch += '?query=cat,'+item.value;
                }
                else{
                    urlSearch += ':cat,'+item.value;
                }
                counter++;
            });
            arrPaymentMethods.forEach((item) => {
                urlSearch += ':pay,'+item.value;
            });
            urlSearch += '&op=3';
        }
        else if(arrCategories.length > 0 && arrPaymentMethods.length === 0 && arrProvinces.length > 0){
            counter = 1;
            arrCategories.forEach((item) => {
                if(counter === 1){
                    urlSearch += '?query=cat,'+item.value;
                }
                else{
                    urlSearch += ':cat,'+item.value;
                }
                counter++;
            });
            
            arrProvinces.forEach((item) => {
                urlSearch += ':prov,'+item.value;
            });
            urlSearch += '&op=4';
        }
        else if(arrCategories.length === 0 && arrPaymentMethods.length > 0 && arrProvinces.length > 0){
            counter = 1;
            arrPaymentMethods.forEach((item) => {
                if(counter === 1){
                    urlSearch += '?query=pay,'+item.value;
                }
                else{
                    urlSearch += ':pay,'+item.value;
                }
                counter++;
            });
            arrProvinces.forEach((item) => {
                urlSearch += ':prov,'+item.value;
            });
            urlSearch += '&op=5';
        }
        else if(arrCategories.length > 0 && arrPaymentMethods.length > 0 && arrProvinces.length > 0){
            counter = 1;
            arrCategories.forEach((item) => {
                if(counter === 1){
                    urlSearch += '?query=cat,'+item.value;
                }
                else{
                    urlSearch += ':cat,'+item.value;
                }
                counter++;
            });
            //urlSearch += '&';
            arrPaymentMethods.forEach((item) => {
                urlSearch += ':pay,'+item.value;
            });
            arrProvinces.forEach((item) => {
                urlSearch += ':prov,'+item.value;
            });
            urlSearch += '&op=6';
        }
        else if(arrCategories.length === 0 && arrPaymentMethods.length === 0 && arrProvinces.length > 0){
            counter = 1;
            arrProvinces.forEach((item) => {
                if(counter === 1){
                    urlSearch += '?query=prov,'+item.value;
                }
                else{
                    urlSearch += ':prov,'+item.value;
                }
                counter++;
                
            });
            urlSearch += '&op=7';
        }
    }
    if(urlSearch.length){
        urlSearch += '&mode=filter';
        window.location = urlWeb + urlSearch;
    }
    else{
        window.location = urlWeb;
    }
});

$('#frmSearchAdvance input:checkbox[name=checkbox-categories]').on('click', function(){
    if($(this).is(':checked')){
        arrCategories.push({
            value: $(this).val()  
        });
    }
    else{
        index = arrCategories.findIndex((element) => element.value === ($(this).attr('id')).split('_')[1]);
        if(index !== -1){
            arrCategories.splice(index, 1);
        }
    }
    
});

$('#frmSearchAdvance input:checkbox[name=checkbox-payments]').on('click', function(){
    if($(this).is(':checked')){
        arrPaymentMethods.push({
            value: $(this).val()  
        });
    }
    else{
        index = arrPaymentMethods.findIndex((element) => element.value === ($(this).attr('id')).split('_')[2]);
        if(index !== -1){
            arrPaymentMethods.splice(index, 1);
        }
    }
});

$('#frmSearchAdvance input:checkbox[name=checkbox-provinces]').on('click', function(){
    if($(this).is(':checked')){
        arrProvinces.push({
            value: $(this).val()  
        });
    }
    else{
        index = arrProvinces.findIndex((element) => element.value === ($(this).attr('id')).split('_')[1]);
        if(index !== -1){
            arrProvinces.splice(index, 1);
        }
    }
    console.log(arrProvinces);
});


$('#btnViewSearchAdvance').on('click',function(){
    if($(this).attr('data-type') === 'advance'){
        $(this).text('Ocultar búsqueda avanzada');
        $('#frmSearch input[id=word]').val('');
        $('#frmSearch select[id=autonomous_community]').val('');
        $('#frmSearch select[id=province_id]').empty().append($('<option>',{value:''}).text('Selecciona una provincia'));
        $('#simpleSearch').addClass('d-none').stop().slideUp('slow');
        $('#advanceSearch').removeClass('d-none').stop().slideDown('slow');
        $(this).attr('data-type', 'simple');
    }
    else if($(this).attr('data-type') === 'simple'){
        let html = `<div class="col-lg-3 col-md-3">
        </div>
        <div class="col-lg-4 col-md-4">
            <select class="form-control" name="fields[]" style="margin-top: 13px;">
                <option value="title">Título de proyecto</option>
                <option value="user">Usuario</option>
                <option value="company">Empresa</option>
                <option value="des_project">Descripción de proyecto</option>
                <option value="des_departure">Descripción de partida</option>
            </select>
        </div>
        <div class="col-lg-5 col-md-5">
            <input class="form-control" type="text" name="terms[]" value=""
                placeholder="Ingrese el término para buscar">
        </div>
        <div class="col-lg-3 col-md-3">
            <select class="form-control" name="type[]" style="margin-top: 13px;">
                <option value="AND">Y</option>
                <option value="OR">O</option>
            </select>
        </div>
        <div class="col-lg-4 col-md-4">
            <select class="form-control" name="fields[]" id="field"
                style="margin-top: 13px;">
                <option value="title">Título de proyecto</option>
                <option value="user">Usuario</option>
                <option value="company">Empresa</option>
                <option value="des_project">Descripción de proyecto</option>
                <option value="des_departure">Descripción de partida</option>
            </select>
        </div>
        <div class="col-lg-5 col-md-5">
            <input class="form-control" type="text" name="terms[]" value=""
                placeholder="Ingrese el término para buscar">
        </div>`;
        $(this).text('Ver búsqueda avanzada');
        $('#advanceSearch').addClass('d-none').stop().slideUp('slow');
        $('#simpleSearch').removeClass('d-none').stop().slideDown('slow');
        $('#container_inputs').empty().append(html);
        $(this).attr('data-type', 'advance');
    }
});

$(document).on('click','.addField',function(){
  if(counter < maxFields){
    counter++;
    let html = `<div class="col-lg-3 col-md-3">
                    <select class="form-control" name="types[]" style="margin-top: 13px;">
                        <option value="AND">Y</option>
                        <option value="OR">O</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-4">
                    <select class="form-control" name="fields[]" id="field"
                    style="margin-top: 13px;">
                        <option value="title">Título de proyecto</option>
                        <option value="user">Usuario</option>
                        <option value="company">Empresa</option>
                        <option value="des_project">Descripción de proyecto</option>
                        <option value="des_departure">Descripción de partida</option>
                    </select>
                </div>
                <div class="col-lg-5 col-md-5">
                    <input class="form-control" type="text" name="terms[]" value=""
                            placeholder="Ingrese el término para buscar">
                </div>`;

    $('#container_inputs').append(html);
  }
});

$(document).on('click','.resetFields', function(){
    let html = `<div class="col-lg-3 col-md-3">
                    <select class="form-control d-none" name="types[]" style="margin-top: 13px;">
                        <option value=""></option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-4">
                    <select class="form-control" name="fields[]" style="margin-top: 13px;">
                        <option value="title">Título de proyecto</option>
                        <option value="user">Usuario</option>
                        <option value="company">Empresa</option>
                        <option value="des_project">Descripción de proyecto</option>
                        <option value="des_departure">Descripción de partida</option>
                    </select>
                </div>
                <div class="col-lg-5 col-md-5">
                    <input class="form-control" type="text" name="terms[]" value=""
                        placeholder="Ingrese el término para buscar">
                </div>
                <div class="col-lg-3 col-md-3">
                    <select class="form-control" name="types[]" style="margin-top: 13px;">
                        <option value="AND">Y</option>
                        <option value="OR">O</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-4">
                    <select class="form-control" name="fields[]" id="field"
                        style="margin-top: 13px;">
                        <option value="title">Título de proyecto</option>
                        <option value="user">Usuario</option>
                        <option value="company">Empresa</option>
                        <option value="des_project">Descripción de proyecto</option>
                        <option value="des_departure">Descripción de partida</option>
                    </select>
                </div>
                <div class="col-lg-5 col-md-5">
                    <input class="form-control" type="text" name="terms[]" value=""
                        placeholder="Ingrese el término para buscar">
                </div>`;
    counter = 1;
    $('#container_inputs').empty().append(html);
});

$('#btnSearchAdvance').on('click',function(){
    let urlSearch = '';
    for (let i = 0; i < $('select[name="types[]"]').length; i++) {
        //thisObj = objsType[i];
        if($($('input[name="terms[]"]')[i]).val() !== ''){
            console.log('entre');
            arrTerms.push({
                type: $($('select[name="types[]"]')[i]).val(),
                field: $($('select[name="fields[]"]')[i]).val(),
                term:  $($('input[name="terms[]"]')[i]).val()
            });
            if(i==0){
                urlSearch += '?query=type,nothing:field,'+$($('select[name="fields[]"]')[i]).val()+':term,'+$($('input[name="terms[]"]')[i]).val();
            }
            else{
                urlSearch += ':type,'+$($('select[name="types[]"]')[i]).val()+':field,'+$($('select[name="fields[]"]')[i]).val()+':term,'+$($('input[name="terms[]"]')[i]).val();
            }
        }
    }
    if(urlSearch.length){
        window.location = urlWeb + urlSearch + '&mode=advance';
    }
    else{
        window.location = urlWeb;
    }
    
});