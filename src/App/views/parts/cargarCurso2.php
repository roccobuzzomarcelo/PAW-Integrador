<h2>Unidad <?= $actual ?> de <?= $max ?></h2>

<form class="form-cargarUnidades" action="/agregar-unidades" method="post">
    <fieldset class="mb-3">
        <label for="subtitulo" class="form-label">Titulo de la unidad</label>
        <input type="text" class="form-control" id="subtitulo" name="subtitulo" required placeholder="Ej: Vectores y Matrices">
    </fieldset>
    <fieldset class="mb-3">
        <label for="descripcion" class="form-label">Descripcion de la unidad</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="2" required></textarea>
    </fieldset>
    <fieldset class="mb-3">
        <label class="form-label">Contenido adjunto</label>
        <div id="dropzone" class="dropzone">
            Soltá un archivo aquí o hacé clic para subirlo
        </div>
        <button type="button" id="botonSeleccionarArchivo" class="btn btn-secondary mt-2">Seleccionar archivo</button>
        <input type="file" class= "archivo" id="recursoArchivo" name="recursoArchivo" />
        <div id="preview" class= "preview"></div>
        <div class="form-check mt-3">
            <input class="form-check-input" type="checkbox" id="usarEnlace" name="usarEnlace">
            <label class="form-check-label" for="usarEnlace">Usar un enlace en lugar de un archivo</label>
        </div>
        <div id="zonaEnlace" style="display: none;" class="zonaEnlace mt-2">
            <input type="url" id="recursoLink" name="recursoLink" class="form-control" placeholder="https://ejemplo.com/archivo.pdf">
        </div>
        <div id="previewUrl" class="preview mt-2"></div>
    </fieldset>

    <button class="boton-agregarUnidad" type="submit">Guardar unidad</button>
</form>


