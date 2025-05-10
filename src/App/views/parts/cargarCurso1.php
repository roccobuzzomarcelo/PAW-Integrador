<form class="form-cargarCurso" action="/agregar-curso" method="POST" enctype="multipart/form-data">

    <!-- Título -->
    <fieldset class="mb-3">
        <label for="titulo" class="form-label">Título del curso</label>
        <input type="text" class="form-control" id="titulo" name="titulo" required>
    </fieldset>

    <!-- Descripción -->
    <fieldset class="mb-3">
        <label for="descripcion" class="form-label">Descripción corta</label>
        <textarea class="form-control" id="descripcion" name="descripcion" rows="2" required></textarea>
    </fieldset>

    <!-- Temario -->
    <fieldset class="mb-3">
        <label for="temario" class="form-label">Temario (uno por línea)</label>
        <textarea class="form-control" id="temario" name="temario" rows="4" required></textarea>
    </fieldset>

    <!-- Imagen -->
    <fieldset class="mb-3">
        <label for="imagen" class="form-label">Imagen del curso</label>
        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
    </fieldset>

    <!-- Cantidad de unidades -->
    <fieldset class="mb-3">
        <label for="cantidadUnidades" class="form-label">Cantidad de unidades</label>
        <input type="number" class="form-control" id="cantidadUnidades" name="cantidadUnidades" min="1" required>
    </fieldset>

    <!-- Nivel -->
    <fieldset class="mb-3">
        <label for="nivel" class="form-label">Nivel</label>
        <select class="form-select" id="nivel" name="nivel">
            <option value="básico">Básico</option>
            <option value="intermedio">Intermedio</option>
            <option value="avanzado">Avanzado</option>
        </select>
    </fieldset>

    <!-- Duración -->
    <fieldset class="mb-3">
        <label for="duracion" class="form-label">Duración estimada</label>
        <input type="text" class="form-control" id="duracion" name="duracion" placeholder="Ej: 4 semanas">
    </fieldset>

    <!-- Botón -->
    <button class="boton-agregarCurso" type="submit">Guardar curso</button>
</form>