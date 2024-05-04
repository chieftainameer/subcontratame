route = '/client/projects/my-projects/applications';

var variables_simple_option = [];
var variables_multiple_option = [];
var variables_upload_information = [];
var variables = [];
var optionsA = [];
var itemTemporal;
var rating=0;

function showVariables(variant_id) {
    //Axios Http Get Request
    Core.get(route + '/find/' + variant_id).then(function(res) {
        let simple_option = JSON.parse(res.data.data.simple_option);
        let multiple_option = JSON.parse(res.data.data.multiple_option);
        let upload_information = JSON.parse(res.data.data.upload_information);
        let html = '';

        // Simple Option
        if (simple_option !== null) {
            simple_option.forEach((item) => {
                variables_simple_option.push({
                    description: item.description,
                    option: item.option
                });
            });
        }

        // Multimple Option
        if (multiple_option !== null) {
            let index;
            multiple_option.forEach((item) => {
                if (optionsA.length) {
                    index = optionsA.findIndex((element) => element.description === item.description);
                    if (index === -1) {
                        optionsA.push({
                            description: item.description
                        });
                    }
                } else {
                    optionsA.push({
                        description: item.description
                    });
                }
            });

            if (optionsA.length) {
                let index2;
                optionsA.forEach((item) => {
                    itemTemporal = item;
                    multiple_option.forEach((item2) => {
                        if (itemTemporal.description === item2.description) {
                            index2 = variables_multiple_option.findIndex((element) => element.description === item2.description);
                            if (index2 === -1) {
                                variables_multiple_option.push({
                                    description: item2.description,
                                    options: [{
                                        option: item2.option
                                    }]
                                });
                            } else {
                                variables_multiple_option[index2].options.splice(0, 0, {
                                    option: item2.option
                                });
                            }
                        }
                    });
                });
            }


        }

        // Upload Information
        if (upload_information !== null) {
            upload_information.forEach((item) => {
                variables_upload_information.push({
                    file: item.file
                });
            });
        }

        // Html template
        if (variables_simple_option.length) {
            variables_simple_option.forEach((item) => {
                html += `<div class="col-lg-12 col-md-12">
                        <span><b><i>${item.description}</b></span>
                     </div>
                     <div class="col-lg-12 col-md-12">
                        <span class="ml-5"> - <i>${item.option}</span>
                     </div>`;
            });
        }

        if (variables_multiple_option.length) {
            variables_multiple_option.forEach((item) => {
                html += `<div class="col-lg-12 col-md-12">
                        <span><b><i>${item.description}</i></b></span>
                     </div><div class="col-lg-12 col-md-12"><ul>`;
                item.options.forEach((item2) => {
                    html += `<li class="ml-5">
                            <span>- <i>${item2.option}</i></span>
                        </li>`;

                });
                html += `</ul></div>`;
            });
        }

        if (variables_upload_information.length) {
            html += `<div class="col-lg-12 col-md-12 text-center">
                    <span><b><i>Documentos suministrados por el postulante</i></b></span>       
                 </div>`;
            variables_upload_information.forEach((item) => {
                html += `<div class="col-lg-6 col-md-6 text-center">
                        <a href="${ urlWeb + '/storage/' + item.file}" download>${(item.file).split('/')[1]}</a>
                    </div>`;
            });

        }

        if(res.data.data.text !== null){
            
        }

        if (variables_simple_option.length || variables_multiple_option.length || variables_upload_information.length) {
            $('#view_variables').empty().append(html);
        } else {
            $('#view_variables').empty().append('<div class="col-lg-12 col-md-12"><p class="text-center">Sin variables</p></div>');
        }


        $('#mdlShowVariables').modal('show');

    }).catch(function(err) {
        console.log(err);
    });
}

function qualify(variant_id, user_id) {
    $('#frmQualify input[id=variant_id]').val(variant_id);
    $('#frmQualify input[id=user_id]').val(user_id);
    $('#mdlQualify').modal('show');
}

$('.star').on('starrr:change', function(e, value){
    rating = value;
    $('#frmQualify button[type=submit]').prop('disabled', false);
});

$('#frmQualify').validate({
  submitHandler: function (form) {
  itemData = new FormData(form);
  itemData.append('rating', rating);
  //Axios Http Post Request
  Core.post(route + '/rating/store', itemData).then(function(res){
    Core.showToast('success', res.data.message);
    $('#mdlQualify').modal('hide');
    setTimeout(() => {
        window.location = res.data.redirect;
    }, 1500);
  }).catch(function(err){
    console.log(err);
  })
}});

$('#mdlShowVariables').on('hidden.bs.modal', function() {
    variables_simple_option = [];
    variables_multiple_option = [];
    variables_upload_information = [];
});

$('#mdlQualify').on('hidden.bs.modal', function() {
    rating = 0;
    $('#frmQualify button[type=submit]').prop('disabled', true);
});

function init(){
    var ratings = document.getElementsByClassName('ratings');
    for(a = 0; a < ratings.length; a++){
        $(ratings[a]).starrr({
            rating: ratings[a].getAttribute('data-rating')
        });
    }

    // $('.ratings').starrr({
    //     readOnly: true,
    //     rating: 3
    // });

    $('.star').starrr();
}

init();
