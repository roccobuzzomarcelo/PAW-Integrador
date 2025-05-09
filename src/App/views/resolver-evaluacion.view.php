<?php include "parts/head.php"?>
<body>
    <?php include "parts/header.php"?>
    <main>
        <form action="/resolver-evaluacion" method="POST">
            <input type="hidden" name="curso" value="<?= htmlspecialchars($curso['titulo']) ?>">
            <?php foreach ($evaluacion['preguntas'] as $index => $preg): ?>
                <fieldset class="mb-4">
                    <legend><strong>Pregunta <?= $index + 1 ?>:</strong> <?= htmlspecialchars($preg['pregunta']) ?></legend>
                    <?php foreach ($preg['opciones'] as $opcion): ?>
                        <label>
                            <input type="radio" name="respuestas[<?= $index ?>]" value="<?= htmlspecialchars($opcion) ?>" required>
                            <?= htmlspecialchars($opcion) ?>
                        </label>
                    <?php endforeach; ?>
                </fieldset>
            <?php endforeach; ?>

            <button type="submit">Enviar respuestas</button>
        </form>
    </main>
    <?php include "parts/footer.php"?>