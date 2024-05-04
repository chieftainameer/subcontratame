<div class="row">
    <div class="col-md-12 col-12">
        <h3>Políticas de Privacidad</h3>
    </div>
    <div class="col-md-12 col-12">
        {!! isset($setting) && $setting->privacy_policies
            ? strip_tags($setting->privacy_policies, '<b><i><u><font><a><img><br>')
            : '' !!}
    </div>
</div>
