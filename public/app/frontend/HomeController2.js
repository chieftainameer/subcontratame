route = 'search';
routeFavoriteProject = 'client/projects/favorites';

var arrPaymentMethods = [];
var arrCategories = [];
var arrQueryFormad = [];
var queryF = [];

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

$('#frmSearch select[id=autonomous_community_search]').on('change',function(){
    if($(this).val() !== ''){
        Core.get(route + '/get-province/' + $('#frmSearch select[id=autonomous_community_search]').val())
                .then(function (res) {
                    $('#frmSearch select[id=province_id_search]').empty().trigger('change');
                    res.data.data.forEach((item) => {
                        $('#frmSearch select[id=province_id_search]').append($('<option>',{value:item.id}).text(item.name)).trigger('change');
                    });
                    $('#frmSearch select[id=province_id_search]').prop('required', true);
                })
                .catch(function (err) {
                    console.log(err);
                    Core.showAlert('error', err.response.data.error.message);
                });
    }
    else{
        $('#frmSearch select[id=province_id_search]').empty();
        $('#frmSearch select[id=province_id_search]').append($('<option>',{value:''}).text('Selecciona una provincia'));
    }
});

$('#btnSearch').on('click',function(e){
    e.preventDefault();
    let categories = JSON.stringify(arrCategories);
    let payment_methods = JSON.stringify(arrPaymentMethods);
    //let queryFormad = JSON.stringify(arrQueryFormad);
    let urlSearch = '';
    

    
    // if($('#frmSearch input[id=word]').val() !== '' || $('#frmSearch select[id=province_id]').val() !== '' || arrCategories.length > 0 || arrPaymentMethods.length > 0){

    //     if($('#frmSearch input[id=word]').val() !== '' && $('#frmSearch select[id=province_id]').val() === '' && arrCategories.length === 0 && arrPaymentMethods.length === 0){
    //         if(urlSearch == ''){
    //             urlSearch += '?word='+$('#frmSearch input[id=word]').val();
    //         }
    //         else{
    //             urlSearch += '&word='+$('#frmSearch input[id=word]').val();
    //         }
    //     }
    //     else if($('#frmSearch input[id=word]').val() !== '' && $('#frmSearch select[id=province_id]').val() !== '' && arrCategories.length === 0 && arrPaymentMethods.length === 0){
    //         if(urlSearch == ''){
    //             urlSearch += '?word='+$('#frmSearch input[id=word]').val()+'&province='+$('#frmSearch select[id=province_id]').val();
    //         }
    //         else{
    //             urlSearch += '&word='+$('#frmSearch input[id=word]').val()+'&province='+$('#frmSearch select[id=province_id]').val();
    //         }
    //     }
    //     else if($('#frmSearch input[id=word]').val() !== '' && $('#frmSearch select[id=province_id]').val() === '' && arrCategories.length > 0 && arrPaymentMethods.length === 0){
    //         if(urlSearch == ''){
    //             urlSearch += '?word='+$('#frmSearch input[id=word]').val()+'&categories='+categories;
    //         }
    //         else{
    //             urlSearch += '&word='+$('#frmSearch input[id=word]').val()+'&categories='+categories;
    //         }
    //     }
    //     else if($('#frmSearch input[id=word]').val() !== '' && $('#frmSearch select[id=province_id]').val() === '' && arrCategories.length === 0 && arrPaymentMethods.length > 0){
    //         if(urlSearch == ''){
    //             urlSearch += '?word='+$('#frmSearch input[id=word]').val()+'&payments='+payment_methods;
    //         }
    //         else{
    //             urlSearch += '&word='+$('#frmSearch input[id=word]').val()+'&payments='+payment_methods;
    //         }
    //     }
    //     else if($('#frmSearch input[id=word]').val() !== '' && $('#frmSearch select[id=province_id]').val() !== '' && arrCategories.length > 0 && arrPaymentMethods.length === 0){
    //         if(urlSearch == ''){
    //             urlSearch += '?word='+$('#frmSearch input[id=word]').val()+'&province='+$('#frmSearch select[id=province_id]').val()+'&categories='+categories;
    //         }
    //         else{
    //             urlSearch += '&word='+$('#frmSearch input[id=word]').val()+'&province='+$('#frmSearch select[id=province_id]').val()+'&categories='+categories;
    //         }    
    //     }
    //     else if($('#frmSearch input[id=word]').val() !== '' && $('#frmSearch select[id=province_id]').val() !== '' && arrCategories.length > 0 && arrPaymentMethods.length > 0){
    //         if(urlSearch == ''){
    //             urlSearch += '?word='+$('#frmSearch input[id=word]').val()+'&province='+$('#frmSearch select[id=province_id]').val()+'&categories='+categories+'&payments='+payment_methods;
    //         }
    //         else{
    //             urlSearch += '&word='+$('#frmSearch input[id=word]').val()+'&province='+$('#frmSearch select[id=province_id]').val()+'&categories='+categories+'&payments='+payment_methods;
    //         }
    //     }
    //     else if($('#frmSearch input[id=word]').val() === '' && $('#frmSearch select[id=province_id]').val() !== '' && arrCategories.length === 0 && arrPaymentMethods.length === 0){
    //         if(urlSearch == ''){
    //             urlSearch += '?province='+$('#frmSearch select[id=province_id]').val();
    //         }
    //         else{
    //             urlSearch += '&province='+$('#frmSearch select[id=province_id]').val();
    //         }
    //     }
    //     else if($('#frmSearch input[id=word]').val() === '' && $('#frmSearch select[id=province_id]').val() !== '' && arrCategories.length > 0 && arrPaymentMethods.length === 0){
    //         if(urlSearch == ''){
    //             urlSearch += '?province='+$('#frmSearch select[id=province_id]').val()+'&categories='+categories;
    //         }
    //         else{
    //             urlSearch += '&province='+$('#frmSearch select[id=province_id]').val()+'&categories='+categories;
    //         }
    //     }
    //     else if($('#frmSearch input[id=word]').val() === '' && $('#frmSearch select[id=province_id]').val() !== '' && arrCategories.length === 0 && arrPaymentMethods.length > 0){
    //         if(urlSearch == ''){
    //             urlSearch += '?province='+$('#frmSearch select[id=province_id]').val()+'&payments='+payment_methods;
    //         }
    //         else{
    //             urlSearch += '&province='+$('#frmSearch select[id=province_id]').val()+'&payments='+payment_methods;
    //         }
    //     }
    //     else if($('#frmSearch input[id=word]').val() === '' && $('#frmSearch select[id=province_id]').val() !== '' && arrCategories.length > 0 && arrPaymentMethods.length > 0){
    //         if(urlSearch == ''){
    //             urlSearch += '?province='+$('#frmSearch select[id=province_id]').val()+'&categories='+categories+'&payment='+payment_methods;
    //         }
    //         else{
    //             urlSearch += '&province='+$('#frmSearch select[id=province_id]').val()+'&categories='+categories+'&payment='+payment_methods;
    //         }
    //     }
    //     else if($('#frmSearch input[id=word]').val() === '' && $('#frmSearch select[id=province_id]').val() === '' && arrCategories.length > 0 && arrPaymentMethods.length === 0){
    //         if(urlSearch == ''){
    //             urlSearch += '?categories='+categories;
    //         }
    //         else{
    //             urlSearch += '&categories='+categories;
    //         }
    //     }
    //     else if($('#frmSearch input[id=word]').val() === '' && $('#frmSearch select[id=province_id]').val() === '' && arrCategories.length > 0 && arrPaymentMethods.length > 0){
    //         if(urlSearch == ''){
    //             urlSearch += '?categories='+categories+'&payment='+payment_methods;
    //         }
    //         else{
    //             urlSearch += '&categories='+categories+'&payment='+payment_methods;
    //         }
    //     }
    //     else if($('#frmSearch input[id=word]').val() === '' && $('#frmSearch select[id=province_id]').val() === '' && arrCategories.length === 0 && arrPaymentMethods.length > 0){
    //         if(urlSearch == ''){
    //             urlSearch += '?payment='+payment_methods;
    //         }
    //         else{
    //             urlSearch += '&payment='+payment_methods;
    //         }
    //     }
    // }
    // if(urlSearch.length){
    //     window.location = urlWeb + urlSearch;
    // }
    // else{
    //     window.location = urlWeb;
    // }
    
    if(arrQueryFormad.length > 0){
        let num_fiels = arrQueryFormad.length;
        let counter = 1;
        if(arrQueryFormad.length === 1){
            if(arrQueryFormad[0].search_for === 'title'){
                queryF.push({
                    field:'title',
                    value:arrQueryFormad[0].text,
                    condition:arrQueryFormad[0].condition
                });

                //queryF = 'title='+arrQueryFormad[0].text;
            }
            else if(arrQueryFormad[0].search_for === 'user'){
                queryF.push({
                    field:'user',
                    value:arrQueryFormad[0].text,
                    condition:arrQueryFormad[0].condition
                });
                //queryF = 'user='+arrQueryFormad[0].text;
            }
            else if(arrQueryFormad[0].search_for === 'company'){
                queryF.push({
                    field:'company',
                    value:arrQueryFormad[0].text,
                    condition:arrQueryFormad[0].condition
                });
                //queryF = 'company='+arrQueryFormad[0].text;
            }
            else if(arrQueryFormad[0].search_for === 'description_project'){
                queryF.push({
                    field:'description_project',
                    value:arrQueryFormad[0].text,
                    condition:arrQueryFormad[0].condition
                });
                //queryF = 'description_project='+arrQueryFormad[0].text;
            }
            else if(arrQueryFormad[0].search_for === 'description_departure'){
                queryF.push({
                    field:'description_departure',
                    value:arrQueryFormad[0].text,
                    condition:arrQueryFormad[0].condition
                });
                //queryF = 'description_departure='+arrQueryFormad[0].text;
            }
            else if(arrQueryFormad[0].search_for === 'execution_date'){
                queryF.push({
                    field:'execution_date',
                    value:arrQueryFormad[0].text,
                    condition:arrQueryFormad[0].condition
                });
                //queryF = 'execution_date='+arrQueryFormad[0].text;
            }
            else if(arrQueryFormad[0].search_for === 'location'){
                queryF.push({
                    field:'location',
                    value:arrQueryFormad[0].text,
                    condition:arrQueryFormad[0].condition
                });
                //queryF = 'location='+arrQueryFormad[0].value;
            }
        }
        else{
            arrQueryFormad.forEach((item) => {
                if(parseInt(counter) === parseInt(num_fiels)){
                    if(item.search_for === 'title'){
                        queryF.push({
                            field:'title',
                            value:item.text,
                            condition:item.condition
                        });
                        //queryF += 'title='+item.text;
                    }
                    else if(item.search_for === 'user'){
                        queryF.push({
                            field:'user',
                            value:item.text,
                            condition:item.condition
                        });
                        //queryF += 'user='+item.text;
                    }
                    else if(item.search_for === 'company'){
                        queryF.push({
                            field:'company',
                            value:item.text,
                            condition:item.condition
                        });
                        //queryF += 'company='+item.text;
                    }
                    else if(item.search_for === 'description_project'){
                        queryF.push({
                            field:'description_project',
                            value:item.text,
                            condition:item.condition
                        });
                        //queryF += 'description_project='+item.text;
                    }
                    else if(item.search_for === 'description_departure'){
                        queryF.push({
                            field:'description_departure',
                            value:item.text,
                            condition:item.condition
                        });
                        //queryF += 'description_departure='+item.text;
                    }
                    else if(item.search_for === 'execution_date'){
                        queryF.push({
                            field:'execution_date',
                            value:item.text,
                            condition:item.condition
                        });
                        //queryF += 'execution_date='+item.text;
                    }
                    else if(item.search_for === 'location'){
                        queryF.push({
                            field:'location',
                            value:item.text,
                            condition:item.condition
                        });
                        //queryF += 'location='+item.value;
                    }
                }
                else{
                    if(item.search_for === 'title'){
                        queryF.push({
                            field:'title',
                            value:item.text,
                            condition:item.condition
                        });
                        //queryF += 'title='+item.text+item.condition;
                    }
                    else if(item.search_for === 'user'){
                        queryF.push({
                            field:'user',
                            value:item.text,
                            condition:item.condition
                        });
                        //queryF += 'user='+item.text+item.condition;
                    }
                    else if(item.search_for === 'company'){
                        queryF.push({
                            field:'company',
                            value:item.text,
                            condition:item.condition
                        });
                        //queryF += 'company='+item.text+item.condition;
                    }
                    else if(item.search_for === 'description_project'){
                        queryF.push({
                            field:'description_project',
                            value:item.text,
                            condition:item.condition
                        });
                        //queryF += 'description_project='+item.text+item.condition;
                    }
                    else if(item.search_for === 'description_departure'){
                        queryF.push({
                            field:'description_departure',
                            value:item.text,
                            condition:item.condition
                        });
                        //queryF += 'description_departure='+item.text+item.condition;
                    }
                    else if(item.search_for === 'execution_date'){
                        queryF.push({
                            field:'execution_date',
                            value:item.text,
                            condition:item.condition
                        });
                        //console.log('paso');
                        //queryF += 'execution_date='+item.text+item.condition;
                    }
                    else if(item.search_for === 'location'){
                        queryF.push({
                            field:'location',
                            value:item.text,
                            condition:item.condition
                        });
                        //queryF += 'location='+item.value+item.condition;
                    }
                }
                counter++;
            });
        }
        
    }
   
    if(arrQueryFormad.length === 0 && arrCategories.length > 0 && arrPaymentMethods.length === 0){
        urlSearch += '?categories=' + categories;
    }
    else if(arrQueryFormad.length === 0 && arrCategories.length === 0 && arrPaymentMethods.length > 0){
        urlSearch += '?payment=' + payment_methods;
    }
    else if(arrQueryFormad.length > 0 && arrCategories.length === 0 && arrPaymentMethods.length === 0){
        urlSearch += '?query='+JSON.stringify(queryF);
    }
    else if(arrQueryFormad.length > 0 && arrCategories.length > 0 && arrPaymentMethods.length === 0){
        urlSearch += '?query='+JSON.stringify(queryF)+'&categories='+categories;
    }
    else if(arrQueryFormad.length > 0 && arrCategories.length === 0 && arrPaymentMethods.length > 0){
        urlSearch += '?query='+JSON.stringify(queryF)+'&payment='+payment_methods;
    }
    else if(arrQueryFormad.length > 0 && arrCategories.length > 0 && arrPaymentMethods.length > 0){
        urlSearch += '?query='+JSON.stringify(queryF)+'&categories='+categories+'&payment='+payment_methods;
    }
    console.log(urlSearch);
    if(urlSearch.length){
        window.location = urlWeb + urlSearch;
    }
    else{
        window.location = urlWeb;
    }
});

$('#frmSearchAvance input:checkbox[name=checkbox-categories]').on('click', function(){
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

$('#frmSearchAvance input:checkbox[name=checkbox-payments]').on('click', function(){
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


// desde aqui nuevo
$('#frmSearch select[id=search_for').on('change',function(){
    if($(this).val() !== ''){
        if($(this).val() === 'title' || $(this).val() === 'user' || $(this).val() === 'company' || $(this).val() === 'description_project' || $(this).val() === 'description_departure'){
            $('#box_word_search').removeClass('d-none');
            $('#box_date_search').addClass('d-none');
            $('#box_payment_search').addClass('d-none');
            $('#box_location_search').addClass('d-none');

            $('#frmSearch input[id=text_word_search]').val('');
            $('#frmSearch input[id=execution_date_search]').val(moment().format('DD/MM/YYYY'));
            $('#frmSearch select[id=payment_search]').val('');
            $('#frmSearch select[id=province_id_search]').empty().append($('<option>', {value:''}).text('Selecciona una provincia'));
            $('#frmSearch select[id=autonomous_community_search]').val('');
            $('#frmSearch select[id=type_date_search]').val('OR');
            //$('#frmSearch select[id=type_payment_search]').val('OR');
            $('#frmSearch select[id=type_location_search]').val('OR');
            $('#frmSearch select[id=type_word_search]').val('OR');

        }
        else if($(this).val() === 'execution_date'){
            $('#box_date_search').removeClass('d-none');
            $('#box_word_search').addClass('d-none');
            $('#box_payment_search').addClass('d-none');
            $('#box_location_search').addClass('d-none');

            $('#frmSearch input[id=text_word_search]').val('');
            $('#frmSearch input[id=execution_date_search]').val(moment().format('DD/MM/YYYY'));
            $('#frmSearch select[id=payment_search]').val('');
            $('#frmSearch select[id=province_id_search]').empty().append($('<option>', {value:''}).text('Selecciona una provincia'));
            $('#frmSearch select[id=autonomous_community_search]').val('');
            $('#frmSearch select[id=type_date_search]').val('OR');
            //$('#frmSearch select[id=type_payment_search]').val('OR');
            $('#frmSearch select[id=type_location_search]').val('OR');
            $('#frmSearch select[id=type_word_search]').val('OR');
        }
        else if($(this).val() === 'payment_method'){
            $('#box_payment_search').removeClass('d-none');
            $('#box_date_search').addClass('d-none');
            $('#box_word_search').addClass('d-none');
            $('#box_location_search').addClass('d-none');

            $('#frmSearch input[id=text_word_search]').val('');
            $('#frmSearch input[id=execution_date_search]').val(moment().format('DD/MM/YYYY'));
            $('#frmSearch select[id=payment_search]').val('');
            $('#frmSearch select[id=province_id_search]').empty().append($('<option>', {value:''}).text('Selecciona una provincia'));
            $('#frmSearch select[id=autonomous_community_search]').val('');
            $('#frmSearch select[id=type_date_search]').val('OR');
            //$('#frmSearch select[id=type_payment_search]').val('OR');
            $('#frmSearch select[id=type_location_search]').val('OR');
            $('#frmSearch select[id=type_word_search]').val('OR');
        }
        else if($(this).val() === 'location'){
            $('#box_location_search').removeClass('d-none');
            $('#box_payment_search').addClass('d-none');
            $('#box_date_search').addClass('d-none');
            $('#box_word_search').addClass('d-none');

            $('#frmSearch input[id=text_word_search]').val('');
            $('#frmSearch input[id=execution_date_search]').val(moment().format('DD/MM/YYYY'));
            $('#frmSearch select[id=province_id_search]').empty().append($('<option>', {value:''}).text('Selecciona una provincia'));
            $('#frmSearch select[id=autonomous_community_search]').val('');
            $('#frmSearch select[id=payment_search]').val('');
            $('#frmSearch select[id=type_date_search]').val('OR');
            //$('#frmSearch select[id=type_payment_search]').val('OR');
            $('#frmSearch select[id=type_location_search]').val('OR');
            $('#frmSearch select[id=type_word_search]').val('OR');
        }
    }
    else{
        $('#box_word_search').addClass('d-none');
        $('#box_location_search').addClass('d-none');
        $('#box_payment_search').addClass('d-none');
        $('#box_date_search').addClass('d-none');

        $('#frmSearch input[id=text_word_search]').val('');
        $('#frmSearch input[id=execution_date_search]').val(moment().format('DD/MM/YYYY'));
        $('#frmSearch select[id=payment_search]').val('');
        $('#frmSearch select[id=province_id_search]').empty().append($('<option>', {value:''}).text('Selecciona una provincia'));
        $('#frmSearch select[id=autonomous_community_search]').val('');
        $('#frmSearch select[id=payment_search]').val('');
        $('#frmSearch select[id=type_date_search]').val('OR');
        //$('#frmSearch select[id=type_payment_search]').val('OR');
        $('#frmSearch select[id=type_location_search]').val('OR');
        $('#frmSearch select[id=type_word_search]').val('OR');
    }
  });

  function drawWords(){
    //$('#frmSearch ')
    $('#frmSearch div[id=arrWordsContainer]').empty();
    arrQueryFormad.map(function(option, index) {
        $('#frmSearch div[id=arrWordsContainer]').append(`<span class="ml-2 badge badge-info p-2 m-2 font-12">${option.text} - ${option.condition} <a href="javascript:void(0)" data-index="${index}" id="btnRemoveWord" class="text-white p-2"><i class="fa fa-times"></i></a></span>`);
    });
  }

  $(document).on('click', '#btnRemoveWord', function() {
    index = $(this).data('index');
    arrQueryFormad.splice(index, 1);
    drawWords();
  });

  $('#frmSearch button[id=btnWordSearch]').on('click',function(e){
    e.preventDefault();
    if($('#frmSearch select[id=search_for]').val() === 'title'){
        if($('#frmSearch input[id=text_word_search]').val() !== ''){
            if(arrQueryFormad.length < 7){
                arrQueryFormad.push({
                    search_for: 'title',
                    text: $('#frmSearch input[id=text_word_search]').val(),
                    condition: $('#frmSearch select[id=type_word_search]').val()
                });
                drawWords();
            }
            else{
                Core.showToast('error','Ya abarco el máximo de campos permitidos');
            }
            $('#frmSearch input[id=text_word_search]').val('');

        }
        else{
            Core.showToast('error','Debe ingresar el titulo del proyecto');
        }     
    }
    else if($('#frmSearch select[id=search_for]').val() === 'user'){
        if($('#frmSearch input[id=text_word_search]').val() !== ''){
            if(arrQueryFormad.length < 7){
                arrQueryFormad.push({
                    search_for: 'user',
                    text: $('#frmSearch input[id=text_word_search]').val(),
                    condition: $('#frmSearch select[id=type_word_search]').val()
                });
                drawWords();
            }
            else{
                Core.showToast('error','Ya abarco el máximo de campos permitidos');
            }
            $('#frmSearch input[id=text_word_search]').val('');
        }
        else{
            Core.showToast('error','Debe ingresar el nombre del usuario');
        }     
    }
    else if($('#frmSearch select[id=search_for]').val() === 'company'){
        if($('#frmSearch input[id=text_word_search]').val() !== ''){
            if(arrQueryFormad.length < 7){
                arrQueryFormad.push({
                    search_for: 'company',
                    text: $('#frmSearch input[id=text_word_search]').val(),
                    condition: $('#frmSearch select[id=type_word_search]').val()
                });
                drawWords();
            }
            else{
                Core.showToast('error','Ya abarco el máximo de campos permitidos');
            }
            $('#frmSearch input[id=text_word_search]').val('');
        }
        else{
            Core.showToast('error','Debe ingresar el nombre de la empresa');
        }
    }
    else if($('#frmSearch select[id=search_for]').val() === 'description_project'){
        if($('#frmSearch input[id=text_word_search]').val() !== ''){
            if(arrQueryFormad.length < 7){
                arrQueryFormad.push({
                    search_for: 'description_project',
                    text: $('#frmSearch input[id=text_word_search]').val(),
                    condition: $('#frmSearch select[id=type_word_search]').val()
                });
                drawWords();
            }
            else{
                Core.showToast('error','Ya abarco el máximo de campos permitidos');
            }
            $('#frmSearch input[id=text_word_search]').val('');
        }
        else{
            Core.showToast('error','Debe ingresar la descripción del proyecto');
        }
    }
    else if($('#frmSearch select[id=search_for]').val() === 'description_departure'){
        if($('#frmSearch input[id=text_word_search]').val() !== ''){
            if(arrQueryFormad.length < 7){
                arrQueryFormad.push({
                    search_for: 'description_departure',
                    text: $('#frmSearch input[id=text_word_search]').val(),
                    condition: $('#frmSearch select[id=type_word_search]').val()
                });
                drawWords();
            }
            else{
                Core.showToast('error','Ya abarco el máximo de campos permitidos');
            }
            $('#frmSearch input[id=text_word_search]').val('');
        }
        else{
            Core.showToast('error','Debe ingresar la descripción de la partida');
        }
    }
  });

  $('#frmSearch button[id=btnDateSearch]').on('click',function(e){
    e.preventDefault();
    if($('#frmSearch input[id=execution_date_search]').val() !== ''){
        if(arrQueryFormad.length < 7){
            arrQueryFormad.push({
                search_for: 'execution_date',
                text: $('#frmSearch input[id=execution_date_search]').val(),
                condition: $('#frmSearch select[id=type_date_search]').val()
            });
            drawWords();
        }
        else{
            Core.showToast('error','Ya abarco el máximo de campos permitidos');
        }
        $('#frmSearch input[id=execution_date_search]').val(moment().format('DD/MM/YYYY'));
    }
    else{
        Core.showToast('error','Debe ingresar la fecha de inicio del proyecto');
    }
  });

//   $('#frmSearch button[id=btnPaymentSearch]').on('click',function(e){
//     e.preventDefault();
//     if($('#frmSearch select[id=payment_search]').val() !== ''){
//         if(arrQueryFormad.length > 6){
//             arrQueryFormad.push({
//                 search_for: 'payment_method',
//                 value: $('#frmSearch select[id=payment_search]').val(), 
//                 text: $('#frmSearch select[id=payment_search] option:selected').text(),
//                 condition: $('#frmSearch select[id=type_payment_search]').val()
//             });
//             drawWords();
//         }
//         else{
//             Core.showToast('error','Ya abarco el máximo de campos permitidos');
//         }
//         $('#frmSearch select[id=payment_search]').val('');
//     }
//     else{
//         Core.showToast('error','Debe seleccionar una forma de pago');
//     }
//   });

  $('#frmSearch button[id=btnLocationSearch]').on('click',function(e){
    e.preventDefault();
    if($('#frmSearch select[id=location_search]').val() !== ''){
        if(arrQueryFormad.length < 7){
            arrQueryFormad.push({
                search_for: 'location',
                value: $('#frmSearch select[id=province_id_search]').val(),
                text: $('#frmSearch select[id=autonomous_community_search] option:selected').text()+', '+$('#frmSearch select[id=province_id_search] option:selected').text(),
                condition: $('#frmSearch select[id=type_location_search]').val()
            });
            drawWords();
        }
        else{
            Core.showToast('error','Ya abarco el máximo de campos permitidos');
        }
        $('#frmSearch select[id=autonomous_community_search]').val('');
        $('#frmSearch select[id=province_id_search]').empty().append($('<option>', {value:''}).text('Selecciona una provincia'))
    }
    else{
        Core.showToast('error','Debe seleccionar una forma de pago');
    }
  });

  function init(){
    $('#frmSearch input[id=execution_date_search]').daterangepicker({
        drops: 'auto',
        singleDatePicker: true,
        showDropdowns: true,
        locale: {
            format: 'DD/MM/YYYY'
        }
    })
  }

  init();