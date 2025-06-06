<form class="form-cargarCurso" action="/agregar-curso" method="POST" enctype="multipart/form-data">
    <section aria-labelledby="datos-curso">
        <h2 id="datos-curso">Datos del curso</h2>

        <fieldset class="mb-3">
            <legend>Título</legend>
            <label for="titulo" class="form-label">Título del curso</label>
            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ej: Introducción a la programación" required>
        </fieldset>

        <fieldset class="mb-3">
            <legend>Descripción</legend>
            <label for="descripcion" class="form-label">Descripción corta</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="2" required></textarea>
        </fieldset>

        <fieldset class="mb-3" id="temario-container">
            <legend>Temario</legend>
            <label for="tema-0" class="form-label">Tema 1</label>
            <input type="text" class="form-control mb-2" id="tema-0" name="temario[]" required>
            <button type="button" onclick="agregarTema()">Agregar otro tema</button>
        </fieldset>
    </section>

    <section aria-labelledby="modulos-curso">
        <h2 id="modulos-curso">Módulos</h2>

        <article id="modulos-container" class="modulo" aria-label="Modulo 1">
            
        </article>

        <button type="button" onclick="agregarModulo()">Agregar otro módulo</button>
    </section>

    <section aria-labelledby="extras">
        <h2 id="extras">Datos adicionales</h2>

        <fieldset class="mb-3">
            <legend>Imagen</legend>
            <label for="imagen" class="form-label">Imagen del curso</label>
            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
        </fieldset>

        <fieldset class="mb-3">
            <legend>Nivel</legend>
            <label for="nivel" class="form-label">Nivel</label>
            <select class="form-select" id="nivel" name="nivel">
                <option value="básico">Básico</option>
                <option value="intermedio">Intermedio</option>
                <option value="avanzado">Avanzado</option>
            </select>
        </fieldset>

        <fieldset class="mb-3">
            <legend>Duración</legend>
            <label for="duracion" class="form-label">Duración estimada (Horas)</label>
            <input type="number" class="form-control" id="duracion" name="duracion" placeholder="Ej: 4">
        </fieldset>
    </section>

    <button class="boton-agregarCurso" type="submit">Guardar curso</button>
</form>

<script>
let moduloIndex = 1;
let temaIndex = 1;

function agregarModulo() {
    const container = document.getElementById('modulos-container');
    const art = document.createElement('article');
    art.classList.add('modulo');
    art.setAttribute('aria-label', `Módulo ${moduloIndex + 1}`);
    art.innerHTML = `
        <hr>
        <label for="modulo-titulo-${moduloIndex}">Título del módulo</label>
        <input type="text" id="modulo-titulo-${moduloIndex}" name="modulos[${moduloIndex}][titulo]" required><br>

        <label for="modulo-descripcion-${moduloIndex}">Descripción</label>
        <textarea id="modulo-descripcion-${moduloIndex}" name="modulos[${moduloIndex}][descripcion]" required></textarea><br>

        <div class="contenido-unico-container mb-3">
            <h4>Contenido del módulo</h4>

            <div class="mb-2">
                <label for="contenido-link-${moduloIndex}" class="form-label">Link al contenido (opcional)</label>
                <input type="url" class="form-control" id="contenido-link-${moduloIndex}" name="modulos[${moduloIndex}][link]" oninput="handleLinkInput(${moduloIndex})">
            </div>

            <div class="mb-2">
                <label for="contenido-archivo-${moduloIndex}" class="form-label">Subir archivo (opcional)</label>
                <input type="file" class="form-control" id="contenido-archivo-${moduloIndex}" name="modulos[${moduloIndex}][archivo]" onchange="handleArchivoInput(${moduloIndex})">
            </div>
        </div>

        <br>
        <button type="button" onclick="eliminarModulo(this)">Eliminar módulo</button>
    `;
    container.appendChild(art);
    moduloIndex++;
}



function eliminarModulo(boton) {
    const modulo = boton.closest('.modulo');
    modulo.remove();
    moduloIndex--;
}

// TEMARIO
function agregarTema() {
    const container = document.getElementById('temario-container');
    const nuevoInput = document.createElement('div');
    nuevoInput.classList.add('tema-item');
    nuevoInput.innerHTML = `
        <label for="tema-${temaIndex}" class="form-label">Tema ${temaIndex + 1}</label>
        <input type="text" class="form-control mb-2" id="tema-${temaIndex}" name="temario[]" required>
        <button type="button" onclick="eliminarTema(this)">Eliminar tema</button>
    `;
    container.appendChild(nuevoInput);
    temaIndex++;
}

function eliminarTema(boton) {
    const tema = boton.closest('.tema-item');
    tema.remove();
    temaIndex--;
}

function agregarContenido(modIndex) {
    if (!contenidoIndex[modIndex]) contenidoIndex[modIndex] = 1;
    else contenidoIndex[modIndex]++;

    const contContainer = document.getElementById(`contenidos-container-${modIndex}`);
    const nuevoContenido = document.createElement('div');
    nuevoContenido.innerHTML = crearContenidoHTML(modIndex, contenidoIndex[modIndex]);
    contContainer.appendChild(nuevoContenido);
}

function eliminarContenido(boton) {
    const cont = boton.closest('.contenido-item');
    cont.remove();
}

function handleLinkInput(index) {
    const linkInput = document.getElementById(`contenido-link-${index}`);
    const fileInput = document.getElementById(`contenido-archivo-${index}`);

    if (linkInput.value.trim() !== "") {
        fileInput.disabled = true;
    } else {
        fileInput.disabled = false;
    }
}

function handleArchivoInput(index) {
    const linkInput = document.getElementById(`contenido-link-${index}`);
    const fileInput = document.getElementById(`contenido-archivo-${index}`);

    if (fileInput.files.length > 0) {
        linkInput.disabled = true;
    } else {
        linkInput.disabled = false;
    }
}


</script>

