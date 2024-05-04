<div class="row">
    <div class="col-md-12 col-12">
        <h3>Acerca de </h3>
    </div>
    <div class="col-md-12 col-12 text-justify">
        {!! isset($setting) && $setting->about ? strip_tags($setting->about, '<b><i><u><font><a><img><br>') : '' !!}
    </div>
</div>
