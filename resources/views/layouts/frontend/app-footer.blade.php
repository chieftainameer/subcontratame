<footer class="site-footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                @php
                    $setting = \App\Models\Setting::where('id', 1)->first();
                @endphp
                <div class="col-md-6 col-12">
                    <div class="widget border-0">
                        <h5 class="m-b30 text-white">Información de contacto</h5>
                        Email:<a
                            href="{{ isset($setting) && $setting->contact_email ? 'mailto:' . $setting->contact_email : '#' }}">
                            {{ isset($setting) && $setting->contact_email ? $setting->contact_email : '' }}</a><br>
                        Télefono Celular:<span>
                            {{ isset($setting) && $setting->contact_cellphone ? $setting->contact_cellphone : '' }}
                        </span><br>
                        Linkedin:<a
                            href="{{ isset($setting) && $setting->contact_linkedin ? $setting->contact_linkedin : '#' }}">
                            {{ isset($setting) && $setting->contact_linkedin ? $setting->contact_linkedin : '' }}
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="widget border-0">
                        <h5 class="m-b30 text-white">Información de la empresa</h5>
                        <a href="javascript:void(0);" id="btnAbout">Acerca de</a><br>
                        <a href="javascript:void(0);" id="btnPrivacy">Políticas
                            de Privacidad</a><br>
                        <a href="javascript:void(0);" id="btnTerm">Términos y Condiciones</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- footer bottom part -->
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <span> © Copyright {{ date('Y') }} by <i class="fa fa-heart m-lr5 text-red heart"></i>
                        <a href="https://4megatech.com/" target="_blank">4megatech </a> All rights
                        reserved.</span>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Modal -->
<div class="modal fade browse-job modal-bx-info editor" id="mdlShowAbout" tabindex="-1" role="dialog"
    aria-labelledby="ProfilenameModalLongTitle2" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ProfilenameModalLongTitle2">Acerca de</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('frontend.client.partials.data-about')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade browse-job modal-bx-info editor" id="mdlShowPrivacy" tabindex="-1" role="dialog"
    aria-labelledby="ProfilenameModalLongTitle2" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ProfilenameModalLongTitle2">Políticas de Privacidad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('frontend.client.partials.data-privacy')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade browse-job modal-bx-info editor" id="mdlShowTerm" tabindex="-1" role="dialog"
    aria-labelledby="ProfilenameModalLongTitle2" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ProfilenameModalLongTitle2">Términos y Condiciones</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @include('frontend.client.partials.data-term')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal End -->
