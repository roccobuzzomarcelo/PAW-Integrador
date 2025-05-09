<h2>Unidad <?= $actual ?> de <?= $max ?></h2>

<form class="form-cargarUnidades" action="/agregar-unidades" method="post">
    <fieldset class="mb-3">
        <label for="subtitulo" class="form-label">Titulo de la unidad</label>
        <input type="text" class="form-control" id="subtitulo" name="subtitulo" required>
    </fieldset>
    <fieldset class="mb-3">
        <label for="descripcion" class="form-label">Descripcion de la unidad</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="2" required></textarea>
    </fieldset>
    <fieldset class="mb-3">
        <label for="recurso" class="form-label">Link al recurso teorico</label>
        <input type="text" class="form-control" id="recurso" name="recurso" required>
    </fieldset>
    <button class= "boton-agregarUnidad" type="submit">Guardar unidad</button>
</form>