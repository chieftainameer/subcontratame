var urlWeb  = $('#domainHost').val();
var urlBase = $('#domainHost').val()+"/";
var route = "";
var arrData;
var tblData;
var itemData;

$.validator.setDefaults({
    errorElement: 'span',
     errorPlacement: function (error, element) {
         error.addClass('invalid-feedback');
         element.closest('.input-group').append(error);
     },
     highlight: function (element, errorClass, validClass) {
        $(element).addClass('is-invalid');
     },
     unhighlight: function (element, errorClass, validClass) {
        $(element).removeClass('is-invalid');
     },
 });

 $('#btnAbout').on('click',function(){
    $('#mdlShowAbout').modal('show');
});
$('#btnPrivacy').on('click',function(){
    $('#mdlShowPrivacy').modal('show');
});
$('#btnTerm').on('click',function(){
    $('#mdlShowTerm').modal('show');
});




