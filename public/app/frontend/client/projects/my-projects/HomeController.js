var route = '/client/projects/my-projects';

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


$('#frmNew select[id=country]').on('change', function(){
    if($(this).val() !== ''){
        axios.get('/client/get-autonomous-community/' + $('#frmNew select[id=country]').val())
                .then(function (res) {
                    $('#frmNew select[id=autonomous_community]').empty().trigger('change');
                    res.data.data.forEach((item) => {
                        $('#frmNew select[id=autonomous_community]').append($('<option>',{value:item.id}).text(item.name)).trigger('change');
                    });
                })
                .catch(function (err) {
                    console.log(err);
                    Core.showAlert('error', err.response.data.error.message);
                });
    }
    else{
        $('#frmNew select[id=autonomous_community]').empty().trigger('change');
        $('#frmNew select[id=autonomous_community]').append($('<option>',{value:''}).text('Selecciona una opción')).trigger('change');
    }
});

$('#frmNew select[id=autonomous_community]').on('change', function(){
    if($(this).val() !== ''){
        axios.get('/client/get-province/' + $('#frmNew select[id=autonomous_community]').val())
                .then(function (res) {
                    $('#frmNew select[id=province_id]').empty().trigger('change');
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


$('#frmEdit select[id=country]').on('change', function(){
    if($(this).val() !== ''){
        axios.get('/client/get-autonomous-community/' + $('#frmEdit select[id=country]').val())
                .then(function (res) {
                    $('#frmEdit select[id=autonomous_community]').empty().trigger('change');
                    res.data.data.forEach((item) => {
                        $('#frmEdit select[id=autonomous_community]').append($('<option>',{value:item.id}).text(item.name)).trigger('change');
                    });
                })
                .catch(function (err) {
                    console.log(err);
                    Core.showAlert('error', err.response.data.error.message);
                });
    }
    else{
        $('#frmEdit select[id=autonomous_community]').empty().trigger('change');
        $('#frmEdit select[id=autonomous_community]').append($('<option>',{value:''}).text('Selecciona una opción')).trigger('change');
    }
});

$('#frmEdit select[id=autonomous_community]').on('change', function(){
    if($(this).val() !== ''){
        axios.get('/client/get-province/' + $('#frmEdit select[id=autonomous_community]').val())
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
    }
    else{
        $('#frmEdit select[id=province_id]').empty().trigger('change');
        $('#frmEdit select[id=province_id]').append($('<option>',{value:''}).text('Selecciona una opción')).trigger('change');
    }
});

$('#frmNew').validate({
  rules:{
    category:{
        required: true
    },
    payment_method:{
        required: true
    }
  },
  messages:{
    category:{
        required:'Debe seleccionar por lo menos una categoría'
    },
    payment_method:{
        required:'Debe seleccionar por lo menos una forma de pago'
    },
    image:{
        required: 'Debe subir una imagen'
    },
    autonomous_community:{
        required: 'Debe seleccionar una opción'
    },
    province_id:{
        required: 'Debe seleccionar una opción'
    }

  },
  submitHandler: function (form) {
    itemData = new FormData(form);
    let startDate = ($('#frmNew input[name=start_date]').val()).split('/');
    let finalDate = ($('#frmNew input[name=final_date]').val()).split('/');
    
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
    
    itemData.append('code', $('#frmNew span[id=field_code]').text());
    itemData.set('start_date', startDate[2] + '-' + startDate[1] + '-' + startDate[0]);
    itemData.set('final_date', finalDate[2] + '-' + finalDate[1] + '-' + finalDate[0]);
    itemData.delete('autonomous_community');
    itemData.delete('category[]');
    itemData.delete('payment_method[]');
    
    //Axios Http Post Request
    Core.post(route + '/store', itemData).then(function(res){
      Core.showToastPermanent('success', res.data.message);
      $('#mdlNew').modal('hide');  
    }).catch(function(err){
      console.log(err);
    }).finally(() => {
        setTimeout(() => {
            window.location = urlWeb + '/client/projects/my-projects';
        }, 1000);
    });
}});

$('#frmEdit').validate({
    rules:{
      category:{
          required: true
      },
      payment_method:{
          required: true
      }
    },
    messages:{
      category:{
          required:'Debe seleccionar por lo menos una categoría'
      },
      payment_method:{
          required:'Debe seleccionar por lo menos una forma de pago'
      },
      autonomous_community:{
          required: 'Debe seleccionar una opción'
      },
      province_id:{
          required: 'Debe seleccionar una opción'
      }
  
    },
    submitHandler: function (form) {
      itemData = new FormData(form);
      let startDate = ($('#frmEdit input[name=start_date]').val()).split('/');
      let finalDate = ($('#frmEdit input[name=final_date]').val()).split('/');
      
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
      
      itemData.set('start_date', startDate[2] + '-' + startDate[1] + '-' + startDate[0]);
      itemData.set('final_date', finalDate[2] + '-' + finalDate[1] + '-' + finalDate[0]);
      itemData.delete('autonomous_community');
      itemData.delete('category[]');
      itemData.delete('payment_method[]');

      //Axios Http Post Request
      Core.post(route + '/update/' + $('#frmEdit input[id=id]').val(), itemData).then(function(res){
        Core.showToast('success', res.data.message);
        $('#mdlEdit').modal('hide');  
      }).catch(function(err){
        console.log(err);
      }).finally(() => {
          setTimeout(() => {
              window.location = urlWeb + '/client/projects/my-projects';
          }, 1000);
      });
}});

$('#mdlNew').on('show.bs.modal', function(){
    const zIndex = 1040 + 10 * $('.modal:visible').length;
    $(this).css('z-index', zIndex);
    setTimeout(() => $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack'));
    let dateNow = moment().format('DD/MM/YYYY');
    $('#frmNew input[name=start_date]').val(dateNow);
    $('#frmNew img[id=imgProfile]').attr('src', urlWeb +'/images/image_default.jpeg');
});

$('#mdlEdit').on('show.bs.modal', function(){
    $('#frmEdit div[id=view_code]').removeClass('d-none');
});

$('#mdlNew').on('hidden.bs.modal', function(){
    $('#frmNew')[0].reset();
    $('#frmNew').validate().resetForm();
    $('input,textarea,select').removeClass('is-invalid');
    $('#frmNew span[id=field_code]').text('');
    $('#frmNew div[id=view_code]').addClass('d-none');
    $('#frmNew img[id=imgProfile]').attr('src', urlWeb +'/images/image_default.jpeg');
    $('#frmNew input[type=checkbox]').each(function(){
        if(this.checked){
            $(this).prop('checked', false);
        }
    });
});

$('#mdlEdit').on('hidden.bs.modal', function(){
    $('#frmEdit')[0].reset();
    $('#frmEdit').validate().resetForm();
    $('input,textarea,select').removeClass('is-invalid');
    $('#frmEdit span[id=field_code]').text('');
    $('#frmEdit div[id=view_code]').addClass('d-none');
    $('#frmEdit img[id=imgProfile]').attr('src', urlWeb +'/images/image_default.jpeg');
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

function editProject(project_id){
    //Axios Http Get Request
    Core.get(route + '/find/' + project_id).then(function(res){
      let project = res.data.data;
      let img = project.image !== null ? urlWeb + '/storage/' + project.image : urlWeb + '/images/image_default.jpeg';
      $('#frmEdit input[id=id]').val(project.id);
      $('#frmEdit span[id=field_code]').text(project.code);
      $('#frmEdit img[id=imgProfile]').attr('src', img);
      $('#frmEdit input[name=title]').val(project.title);
      $('#frmEdit input[name=short_description]').val(project.short_description);
      $('#frmEdit textarea[name=detailed_description]').val(project.detailed_description);  
      
      $('#frmEdit select[name=autonomous_community]').val(project.province.community.id).trigger('change');
      //Axios Http Get Request
      Core.get(route + '/get-province/' + project.province.community.id).then(function(res){
        $('#frmEdit select[id=province_id]').empty().trigger('change');
        res.data.data.forEach((item) => {
            $('#frmEdit select[id=province_id]').append($('<option>',{value:item.id}).text(item.name)).trigger('change');
        });
      }).catch(function(err){
        console.log(err);
      });
      $('#frmEdit select[name=province_id]').val(project.province_id).trigger('change');
      
      project.categories.forEach((item) => {
       $(`#frmEdit input:checkbox[name=category][value='${item.id}']`).prop('checked', true);
      });

      project.payment_methods.forEach((item) => {
        $(`#frmEdit input:checkbox[name=payment_method][value='${item.id}']`).prop('checked', true);
      });

      $('#frmEdit input[name=delivery_place]').val(project.delivery_place);

      $('#frmEdit input[name=start_date]').val(moment(project.start_date).format('DD/MM/YYYY'));
      $('#frmEdit input[name=start_date]').attr('readonly','readonly');
      $('#frmEdit input[name=final_date]').daterangepicker({
            drops: 'up',
            singleDatePicker: true,
            showDropdowns: true,
            minDate: moment(project.final_date).add(1, 'day'),
            locale: {
                format: 'DD/MM/YYYY'
            }
      });

      $('#frmEdit input[name=final_date]').val(moment(project.final_date).format('DD/MM/YYYY'));
      $('#frmEdit input[name=image]').prop('required', false);

      $('#mdlEdit').modal('show');

    }).catch(function(err){
      console.log(err);
    })
}
function addDeparture(project_id){
    window.location = urlWeb + '/client/projects/my-projects/departures/' + project_id;
}
function deleteProject(project_id){
    bootbox.confirm({
        message: '¿Estas seguro? Eso va a eliminar tanto el proyecto como las partidas asociadas',
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
                Core.get(route + '/destroy/' + project_id).then(function(res) {
                    Core.showToast('success',res.data.message);
                    setTimeout(() => {
                        window.location = res.data.redirect;
                    }, 1500);
                }).catch(function(err) {
                    console.log(err);
                    Core.showAlert('error', err.response.data.error.message);
                });
            }
        }
    });
}
function init(){
    $('#frmNew input[name=start_date]').val(moment().format('DD/MM/YYYY'));
    //$('#frmNew input[name=start_date]').attr('readonly', 'readonly');
    $('#frmNew input[name=start_date]').daterangepicker({
        drops: 'up',
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
    $('#frmNew input[name=final_date]').daterangepicker({
        drops: 'up',
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
    //loadProjects();    
    $('#frmEdit input[name=start_date]').daterangepicker({
        drops: 'up',
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
    $('#frmEdit input[name=final_date]').daterangepicker({
        drops: 'up',
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