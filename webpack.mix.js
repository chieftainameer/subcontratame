const {scripts} = require('laravel-mix');
const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        // 
    ]).styles([
        // Backend CSS
        'public/dashboard/app-assets/vendors/css/vendors.min.css',
        'public/dashboard/app-assets/vendors/css/forms/wizard/bs-stepper.min.css',
        'public/dashboard/app-assets/vendors/css/forms/spinner/jquery.bootstrap-touchspin.css',
        'public/dashboard/app-assets/vendors/css/extensions/toastr.min.css',
        'public/dashboard/app-assets/css/bootstrap.css',
        'public/dashboard/app-assets/css/bootstrap-extended.css',
        'public/dashboard/app-assets/css/colors.css',
        'public/dashboard/app-assets/css/components.css',
        'public/dashboard/app-assets/css/themes/dark-layout.css',
        'public/dashboard/app-assets/css/themes/bordered-layout.css',
        'public/dashboard/app-assets/css/themes/semi-dark-layout.css',
        'public/dashboard/app-assets/css/core/menu/menu-types/vertical-menu.css',
        'public/dashboard/app-assets/css/pages/app-ecommerce.css',
        'public/dashboard/app-assets/css/plugins/forms/pickers/form-pickadate.css',
        'public/dashboard/app-assets/css/plugins/forms/form-wizard.css',
        'public/dashboard/app-assets/css/plugins/extensions/ext-component-toastr.css',
        'public/dashboard/app-assets/css/plugins/forms/form-number-input.css',
        'public/dashboard/vendor/nprogress/nprogress.min.css',
        'public/dashboard/vendor/select2/select2.min.css',
        'public/dashboard/app-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css',
        'public/dashboard/app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css',
        'public/dashboard/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css',
        'public/dashboard/app-assets/vendors/css/tables/datatable/rowGroup.bootstrap4.min.css',
        'public/dashboard/app-assets/css/core/menu/menu-types/vertical-menu.css',
        'public/dashboard/app-assets/css/plugins/fontawesome-free/css/fontawesome.min.css',
        'public/dashboard/app-assets/css/plugins/richtext/richtext.min.css',
        'public/dashboard/app-assets/css/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css',
        'public/dashboard/vendor/summernote/summernote-bs4.css',
        'public/dashboard/assets/css/style.css',
        'public/app/style.css'
    ], 'public/css/all.css')
    .styles([
        // Frontend CSS
        'public/frontend/css/plugins.css', 
        'public/frontend/css/style.css',
        'public/frontend/css/templete.css',
        'public/frontend/css/skin/skin-1.css',
        'public/frontend/css/dark-layout.css',
        'public/dashboard/vendor/toastr/toastr.min.css',
        'public/dashboard/app-assets/css/plugins/fontawesome-free/css/fontawesome.min.css'
    ], 'public/css/all-frontend.css')
    .scripts([
        // Backend Javascript
        'public/js/jquery.min.js',
        'public/dashboard/app-assets/vendors/js/vendors.min.js',
        'public/dashboard/app-assets/vendors/js/forms/wizard/bs-stepper.min.js',
        'public/dashboard/app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js',
        'public/dashboard/vendor/toastr/toastr.min.js',
        'public/dashboard/app-assets/js/core/app-menu.js',
        'public/dashboard/app-assets/js/core/app.min.js',
        'public/dashboard/app-assets/js/scripts/pages/app-ecommerce.js',
        'public/dashboard/app-assets/js/scripts/pages/app-ecommerce-checkout.js',
        'public/dashboard/vendor/axios/axios.min.js',
        'public/dashboard/vendor/axios/progress.bar.min.js',
        'public/dashboard/vendor/nprogress/nprogress.min.js',
        'public/dashboard/vendor/jquery.validate/jquery.validate.min.js',
        'public/dashboard/vendor/jquery.validate/messages_es.min.js',
        'public/dashboard/vendor/jquery.blockUI/jquery.blockUI.min.js',
        'public/dashboard/app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js',
        'public/dashboard/app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js',
        'public/dashboard/app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js',
        'public/dashboard/app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js',
        'public/dashboard/vendor/bootbox/bootbox.min.js',
        'public/dashboard/vendor/sweetalert2/sweetalert2.min.js',
        'public/dashboard/vendor/moment/moment.min.js',
        'public/dashboard/vendor/easy-autocomplete/easy-autocomplete.min.js',
        'public/dashboard/vendor/select2/select2.min.js',
        'public/dashboard/vendor/chart.js/Chart.min.js',
        'public/dashboard/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
        'public/dashboard/vendor/bootstrap-datepicker/dist/locales/bootstrap-datepicker.es.min.js',
        'public/dashboard/vendor/bootstrap-daterangepicker/daterangepicker.js',
        'public/dashboard/vendor/fontawesome-free/js/fontawesome.min.js',
        'public/dashboard/app-assets/js/scripts/richtext/richtext.min.js',
        'public/dashboard/app-assets/js/scripts/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'public/dashboard/vendor/summernote/summernote-bs4.js',
        'public/app/Config.js',
        'public/app/Core.js',
    ], 'public/js/all.js')
    scripts([
        // Frontend Javascript
        'public/frontend/js/jquery.min.js',
        'public/frontend/plugins/bootstrap/js/popper.min.js',
        'public/frontend/plugins/bootstrap/js/bootstrap.min.js',
        'public/frontend/plugins/bootstrap-select/bootstrap-select.min.js',
        'public/frontend/plugins/bootstrap-touchspin/jquery.bootstrap-touchspin.js',
        'public/frontend/plugins/magnific-popup/magnific-popup.js',
        'public/frontend/plugins/counter/waypoints-min.js',
        'public/frontend/plugins/counter/counterup.min.js',
        'public/frontend/plugins/imagesloaded/imagesloaded.js',
        'public/frontend/plugins/masonry/masonry-3.1.4.js',
        'public/frontend/plugins/masonry/masonry.filter.js',
        'public/frontend/plugins/owl-carousel/owl.carousel.js',
        'public/frontend/plugins/scroll/scrollbar.min.js',
        'public/frontend/js/custom.js',
        'public/frontend/js/dz.carousel.js',
        'public/frontend/js/dz.ajax.js',
        'public/dashboard/vendor/axios/axios.min.js',
        'public/dashboard/vendor/axios/progress.bar.min.js',
        'public/dashboard/vendor/jquery.validate/jquery.validate.min.js',
        'public/dashboard/vendor/jquery.validate/messages_es.min.js',
        'public/dashboard/vendor/moment/moment.min.js',
        'public/dashboard/vendor/sweetalert2/sweetalert2.min.js',
        'public/dashboard/vendor/jquery.blockUI/jquery.blockUI.min.js',
        'public/dashboard/vendor/toastr/toastr.min.js',
        'public/dashboard/app-assets/js/scripts/richtext/richtext.min.js',
        'public/dashboard/vendor/fontawesome-free/js/fontawesome.min.js',
        'public/app/Config.js',
        'public/app/Core.js',
        'public/app/removePage.js'
    ], 'public/js/all-frontend.js');

    if (mix.inProduction()) {
        mix.version();
    }
