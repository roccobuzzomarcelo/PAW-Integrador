<h2>Evaluación final</h2>

<form action="/agregar-evaluacion" method="post">

    <!-- Pregunta 1 -->
    <fieldset>
        <legend>Pregunta 1</legend>
        <label>Texto:</label>
        <input type="text" name="preguntas[0][pregunta]" placeholder="Ej: En Python, ¿que hace la funcion print()?" required>

        <label>Opciones:</label><br>
        <input type="text" name="preguntas[0][opciones][]" placeholder="Ej: Muestra texto o valores en la consola" required>
        <input type="text" name="preguntas[0][opciones][]" placeholder="Ej: Guarda datos en un archivo" required>
        <input type="text" name="preguntas[0][opciones][]" placeholder="Ej: Recibe datos del usuario" required>

        <label>Respuesta correcta:</label>
        <input type="text" name="preguntas[0][respuesta_correcta]" placeholder="Ej: Muestra texto o valores en la consola" required>
    </fieldset>

    <!-- Pregunta 2 -->
    <fieldset>
        <legend>Pregunta 2</legend>
        <label>Texto:</label>
        <input type="text" name="preguntas[1][pregunta]" required>

        <label>Opciones:</label><br>
        <input type="text" name="preguntas[1][opciones][]" required>
        <input type="text" name="preguntas[1][opciones][]" required>
        <input type="text" name="preguntas[1][opciones][]" required>

        <label>Respuesta correcta:</label>
        <input type="text" name="preguntas[1][respuesta_correcta]" required>
    </fieldset>

    <button type="submit">Guardar evaluación</button>
</form>