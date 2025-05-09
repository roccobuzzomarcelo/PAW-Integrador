<?php include 'parts/head.php' ?>

<body>
    <?php include 'parts/header.php' ?>
    <?php
    session_start();
    $resultado = $_SESSION['resultado_evaluacion'] ?? null;
    ?>

    <main class="resultado-container">
        <?php if ($resultado): ?>
            <h2>Resultado de la Evaluación</h2>
            <p><strong>Curso:</strong> <?= htmlspecialchars($resultado['curso']) ?></p>
            <p><strong>Respuestas correctas:</strong> <?= $resultado['correctas'] ?> de <?= $resultado['total'] ?></p>
            <p><strong>Puntuación:</strong> <?= $resultado['puntuacion'] ?>/10</p>

            <?php if ($resultado['puntuacion'] > 6): ?>
                <p>🎉 ¡Felicitaciones! Has aprobado el curso y puedes obtener tu certificado.</p>
                <a class="btn" href="/descargar-certificado.php?curso=<?= urlencode($resultado['curso']) ?>">Descargar
                    Certificado</a>
            <?php else: ?>
                <p>😕 No alcanzaste la puntuación necesaria para aprobar.</p>
                <form action="/resolver-evaluacion" method="get">
                    <input type="hidden" name="curso" value="<?= htmlspecialchars($resultado['curso']) ?>">
                    <button type="submit" class="btn">Volver a intentar el examen</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <p>No hay resultados disponibles. Por favor, realiza una evaluación.</p>
        <?php endif; ?>
    </main>

    <?php include 'parts/footer.php'; ?>
</body>