<div class="row">
    <div class="col-md-12 col-12">
        <h3>Términos y Condiciones</h3>
    </div>
    <div class="col-md-12 col-12">
        {!! isset($setting) && $setting->terms_conditions
            ? strip_tags($setting->terms_conditions, '<b><i><u><font><a><img><br>')
            : '' !!}
    </div>
</div>
