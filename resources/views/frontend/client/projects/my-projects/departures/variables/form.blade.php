<div class="row">
    <div class="col-lg-12 col-md-12">
        <label class="font-weight-700">Tipo</label><br>
        <div class="form-group">
            <select name="type" id="type" required class="form-control" style="padding: 5px;">
                <option value="">Seleccione una opción</option>
                <option value="1">Selección Simple</option>
                <option value="2">Selección Múltiple</option>
                <option value="3">Descargar información</option>
                <option value="4">Solicitar información</option>
                <option value="5">Texto</option>
            </select>
        </div>
    </div>
    <div class="col-lg-12 col-md-12">
        <div class="form-group">
            <label class="font-weight-700">Descripción</label>
            <input type="text" class="form-control" name="description" id="description" required />
        </div>
    </div>
</div>
<div id="view_selection" class="row d-none">

    <div class="col-lg-10 col-md-10">
        <div class="form-group">
            <label for="">Ingrese la opción</label>
            <input type="text" class="form-control" name="option" id="option" aria-describedby="helpId"
                placeholder="">
        </div>
    </div>
    <div class="col-lg-2 col-md-2">
        <button type="button" id="btnAddOption" class="btn btn-primary" style="margin-top: 33px;"><i
                class="fa fa-plus"></i>
        </button>
    </div>
    <ul class="list-group w-100" id="arrOptionsContainer"></ul>
</div>
<div id="view_download" class="row d-none">
    <div class="col-lg-12 col-md-12">
        <label for="">Cargar el archivo que será descargado por el postulante de la oferta (.jpeg,
            .jpg, .png, .pdf, .doc, .docx, .xls, .xlsx)</label><br>
        <input type="file" name="fileDownload" id="fileDownload" onchange="filesBase64(this)">
    </div>
    <div class="col-lg-12 col-md-12 d-none" id="view_downloadFile">
        <label for="">Descargar archivo</label><br>
        <a href="javascript:void(0)" id="link_downloadFile" name="link_downloadFile"></a>
    </div>
</div>
<div id="view_text" class="row d-none">
    <input type="hidden" name="filebase64" id="filebase64">
    <div class="col-lg-12 col-md-12 mb-3">
        <textarea class="form-control" name="text" id="text" cols="30" rows="5" required></textarea>
    </div>
    <div class="col-lg-8 col-md-8">
        <div class="form-group">
            <label for="">Cargar imagen adicional al texto</label>
            <input class="form-control" type="file" id="file" name="file" onchange="imageBase64(this)">
        </div>
    </div>
    <div class="col-lg-4 col-md-4">
        <img src="" id="view_image_file" name="view_image_file" alt="Imagen"
            style="width: 100%; height: 100%;">
    </div>
</div>
<div class="row mt-3">
    <div class="col-lg-6 col-md-6">
        <div class="form-group form-check">
            <span class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="form-check-input" id="required" name="required">
                <label class="form-check-label" for="required">Obligatorio</label>
            </span>
        </div>
    </div>

    <div class="col-lg-6 col-md-6">
        <div class="form-group form-check">
            <span class="custom-control custom-checkbox custom-control-inline">
                <input type="checkbox" class="form-check-input" id="visible" name="visible">
                <label class="form-check-label" for="visible">Visible</label>
            </span>
        </div>
    </div>
</div>
