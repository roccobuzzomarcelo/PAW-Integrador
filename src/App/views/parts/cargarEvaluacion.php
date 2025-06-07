<h2>Evaluación final</h2>

<form class="form-cargarEvaluacion" action="/agregar-evaluacion" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id_curso" value="<?= htmlspecialchars($_GET['curso']) ?>"> <label
        for="titulo-eval">Título de la evaluación:</label>
    <input type="text" name="titulo" id="titulo-eval" required>

    <section id="preguntas-container"></section>

    <button type="button" onclick="agregarPregunta()">Agregar pregunta</button>
    <button type="submit">Guardar evaluación</button>
</form>

<script>
    let preguntaIndex = 0;

    function agregarPregunta() {
        const container = document.getElementById('preguntas-container');

        const section = document.createElement('section');
        section.classList.add('pregunta');
        section.innerHTML = `
            <hr>
            <label>Enunciado:</label>
            <input type="text" name="preguntas[${preguntaIndex}][enunciado]" required>

            <label>Tipo:</label>
            <select name="preguntas[${preguntaIndex}][tipo]" onchange="mostrarOpciones(this, ${preguntaIndex})">
                <option value="multiple-choice">Multiple Choice</option>
                <option value="ordenar">Ordenar</option>
                <option value="completar">Completar</option>
            </select>

            <section id="opciones-${preguntaIndex}" class="opciones-container"></section>
        `;

        container.appendChild(section);
        mostrarOpciones(section.querySelector('select'), preguntaIndex);
        preguntaIndex++;
    }

    function mostrarOpciones(select, index) {
        const tipo = select.value;
        const container = document.getElementById(`opciones-${index}`);
        container.innerHTML = "";

        if (tipo === "multiple-choice") {
            container.innerHTML = `
                <label>Opción A:</label>
                <input type="text" name="preguntas[${index}][opciones][0][texto]" required>
                <input type="radio" name="preguntas[${index}][correcta]" value="0" required> Correcta<br>

                <label>Opción B:</label>
                <input type="text" name="preguntas[${index}][opciones][1][texto]" required>
                <input type="radio" name="preguntas[${index}][correcta]" value="1"> Correcta<br>

                <label>Opción C:</label>
                <input type="text" name="preguntas[${index}][opciones][2][texto]" required>
                <input type="radio" name="preguntas[${index}][correcta]" value="2"> Correcta<br>

                <label>Opción D:</label>
                <input type="text" name="preguntas[${index}][opciones][3][texto]" required>
                <input type="radio" name="preguntas[${index}][correcta]" value="3"> Correcta<br>
            `;
        } else if (tipo === "ordenar") {
            container.innerHTML = `
                <label>Ítem 1:</label>
                <input type="text" name="preguntas[${index}][opciones][0][texto]" required>
                <input type="hidden" name="preguntas[${index}][opciones][0][posicion]" value="1"><br>

                <label>Ítem 2:</label>
                <input type="text" name="preguntas[${index}][opciones][1][texto]" required>
                <input type="hidden" name="preguntas[${index}][opciones][1][posicion]" value="2"><br>

                <label>Ítem 3:</label>
                <input type="text" name="preguntas[${index}][opciones][2][texto]" required>
                <input type="hidden" name="preguntas[${index}][opciones][2][posicion]" value="3"><br>

                <label>Ítem 4:</label>
                <input type="text" name="preguntas[${index}][opciones][3][texto]" required>
                <input type="hidden" name="preguntas[${index}][opciones][3][posicion]" value="4"><br>
            `;
        } else if (tipo === "completar") {
            container.innerHTML = `
                <label>Texto con espacios en blanco:</label>
                <input type="text" name="preguntas[${index}][opciones][0][enunciado]" placeholder="" required>
                <input type="text" name="preguntas[${index}][opciones][0][respuesta_correcta]" placeholder="Respuesta esperada" required>
            `;
        }
    }
</script>