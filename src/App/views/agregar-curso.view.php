<?php include "parts/head.php" ?>

<body>
    <?php include "parts/header.php" ?>
    <main class="formulario-contenedor">
        <h1>Agregar nuevo curso</h1>

        <form action="/agregar-curso" method="POST" enctype="multipart/form-data">
        
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

        <!-- Recursos -->
        <fieldset class="mb-3">
            <label for="recursos" class="form-label">Recursos (uno por línea, formato: Título|URL)</label>
            <textarea class="form-control" id="recursos" name="recursos" rows="3"></textarea>
        </fieldset>

        <!-- Ejercicios -->
        <fieldset class="mb-3">
            <label for="ejercicios" class="form-label">Ejercicios (uno por línea, formato: Título|URL)</label>
            <textarea class="form-control" id="ejercicios" name="ejercicios" rows="3"></textarea>
        </fieldset>

        <!-- Evaluación -->
        <fieldset class="mb-3">
            <label for="evaluacion" class="form-label">Evaluación (puede ser un link o instrucciones)</label>
            <textarea class="form-control" id="evaluacion" name="evaluacion" rows="2"></textarea>
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

        <!-- Estado -->
        <fieldset class="mb-3">
            <label for="estado" class="form-label">Estado del curso</label>
            <select class="form-select" id="estado" name="estado">
                <option value="activo">Activo</option>
                <option value="borrador">Borrador</option>
            </select>
        </fieldset>

        <!-- Botón -->
        <button type="submit">Guardar curso</button>
    </form>
    </main>
    <?php include "parts/footer.php" ?>
</body>