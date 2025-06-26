<form class="form-cargarCurso" action="/agregar-curso" method="POST" enctype="multipart/form-data">
    <section class="sec-cursos" aria-labelledby="datos-curso">
        <h2 id="datos-curso">Datos del curso</h2>

        <fieldset class="mb-3">
            <legend>Título</legend>
            <label for="titulo" class="form-label">Título del curso</label>
            <input type="text" class="form-control" id="titulo" name="titulo"
                placeholder="Ej: Introducción a la programación" required>
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
            <button class="btnCursoAdd" type="button" onclick="agregarTema()">Agregar otro tema</button>
        </fieldset>

        <fieldset class="mb-3">
            <input type="hidden" id="recomendaciones_json" name="recomendaciones_json">

            <legend>Recomendaciones IA</legend>
            <button type="button" class="btn btn-primary mb-2" onclick="consultarIA()">Obtener recomendaciones</button>

            <div id="cargandoIA" class="alert alert-warning d-flex align-items-center mt-2" style="display:none;">
                <svg class="spinner-border flex-shrink-0 me-2" role="status" style="width: 1.5rem; height: 1.5rem;" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle class="spinner-path" cx="8" cy="8" r="7" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <strong>Consultando IA...</strong> Por favor, espere.
            </div>

            <section id="recomendaciones" class="alert alert-info mt-2 p-3" style="display:none; max-height: 250px; overflow-y: auto; border-radius: .375rem;"></section>
        </fieldset>

    </section>

    <section class="sec-cursos" aria-labelledby="modulos-curso">
        <h2 id="modulos-curso">Módulos</h2>
        <article id="modulos-container" class="modulo" aria-label="Modulo 1">

        </article>
        <button class="btnCursoAdd" type="button" onclick="agregarModulo()">Agregar otro módulo</button>
    </section>

    <section class="sec-cursos" aria-labelledby="extras">
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
        <button class="btnCursoAdd" type="button" onclick="eliminarModulo(this)">Eliminar módulo</button>
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
        <button class="btnCursoAdd" type="button" onclick="eliminarTema(this)">Eliminar tema</button>
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

    function consultarIA() {
        const titulo = document.getElementById("titulo").value;
        const descripcion = document.getElementById("descripcion").value;
        const contenedor = document.getElementById("recomendaciones");
        const cargando = document.getElementById("cargandoIA");

        const temas = [];
        document.querySelectorAll('input[name="temario[]"]').forEach(input => {
            if (input.value.trim() !== "") temas.push(input.value.trim());
        });

        if (!titulo || !descripcion || temas.length === 0) {
            alert("Completá título, descripción y al menos un tema para obtener recomendaciones.");
            return;
        }

        // Mostrar mensaje de carga
        cargando.style.display = "block";
        contenedor.style.display = "none";

        fetch("modelo-ia", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                titulo,
                descripcion,
                temario: temas
            })
        })
        .then(res => res.json())
        .then(data => {
            cargando.style.display = "none";
            contenedor.style.display = "block";

            if (data.error) {
                contenedor.innerHTML = `<strong>Error:</strong> ${data.error}`;
                contenedor.classList.remove("alert-info");
                contenedor.classList.add("alert-danger");
            } else {
                const recomendaciones = data.recomendaciones;
                if (!Array.isArray(recomendaciones) || recomendaciones.length === 0) {
                    contenedor.innerHTML = "No se encontraron recomendaciones.";
                    return;
                }

                // Guardamos en global
                window.recomendacionesGlobal = recomendaciones;

                // Render UI
                contenedor.classList.remove("alert-danger");
                contenedor.classList.add("alert-info");

                contenedor.innerHTML = `
                    <strong>Recomendaciones IA:</strong>
                    <div id="recomendaciones-lista" class="mt-2 mb-3"></div>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="agregarRecomendacionManual()">Agregar recomendación</button>
                `;

                renderizarRecomendaciones();
            }
        });

    }

    function renderizarRecomendaciones() {
        const listaContenedor = document.getElementById("recomendaciones-lista");
        listaContenedor.innerHTML = "";

        window.recomendacionesGlobal.forEach((rec, index) => {
            const item = document.createElement("fieldset");
            item.className = "mb-3 p-3 border rounded";

            item.innerHTML = `
                <legend class="h6">Recomendación ${index + 1}</legend>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="rec-check-${index}" checked data-index="${index}" data-field="activo">
                    <label class="form-check-label" for="rec-check-${index}">Incluir esta recomendación</label>
                </div>

                <div class="mb-3">
                    <label for="rec-titulo-${index}" class="form-label">Título</label>
                    <input type="text" class="form-control form-control-sm" id="rec-titulo-${index}" data-index="${index}" data-field="titulo" placeholder="Título" value="${rec.titulo}">
                </div>

                <div class="mb-3">
                    <label for="rec-desc-${index}" class="form-label">Descripción</label>
                    <textarea class="form-control form-control-sm" id="rec-desc-${index}" data-index="${index}" data-field="descripcion" rows="2" placeholder="Descripción (opcional)">${rec.descripcion || ""}</textarea>
                </div>

                <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarRecomendacion(${index})">Eliminar</button>
            `;

            listaContenedor.appendChild(item);
        });

        actualizarRecomendacionesJSON();
    }


    function actualizarRecomendacionesJSON() {
        const lista = [];

        const inputs = document.querySelectorAll('[data-field="titulo"]');
        inputs.forEach(input => {
            const index = input.dataset.index;
            const check = document.querySelector(`[data-index="${index}"][data-field="activo"]`);
            if (check && !check.checked) return;

            const titulo = input.value.trim();
            const descripcion = document.querySelector(`[data-index="${index}"][data-field="descripcion"]`)?.value.trim();

            if (titulo !== "") {
                lista.push({
                    tipo: "Recomendación",
                    titulo,
                    descripcion
                });
            }
        });

        document.getElementById("recomendaciones_json").value = JSON.stringify(lista);
    }

    function eliminarRecomendacion(index) {
        if (!window.recomendacionesGlobal) return;
        window.recomendacionesGlobal.splice(index, 1);
        renderizarRecomendaciones();
    }

    function agregarRecomendacionManual() {
        if (!window.recomendacionesGlobal) window.recomendacionesGlobal = [];

        window.recomendacionesGlobal.push({
            tipo: "Recomendación",
            titulo: "",
            descripcion: ""
        });

        renderizarRecomendaciones();
    }

    // Escuchar cambios en inputs y textareas para sincronizar automáticamente
    document.addEventListener("input", function(e) {
        if (e.target.matches('[data-field]')) {
            actualizarRecomendacionesJSON();
        }
    });

</script>