<?php include 'parts/head.php' ?>

<body>
    <?php include 'parts/header.php' ?>

    <main class="curso-detalle">
        <section class="curso-box">
            <?php if ($resultado): ?>
                <h2>Resultado de la Evaluación</h2>
                <p><strong>Curso:</strong> <?= htmlspecialchars($resultado['curso']) ?></p>
                <p><strong>Respuestas correctas:</strong> <?= $resultado['correctas'] ?> de <?= $resultado['total'] ?></p>
                <p><strong>Puntuación:</strong> <?= $resultado['puntuacion'] ?>/10</p>

                <?php if ($resultado['puntuacion'] > 6): ?>
                    <p>🎉 ¡Felicitaciones! Has aprobado el curso y puedes obtener tu certificado.</p>
                    <a class=" btn" href="/descargar-certificado.php?curso=<?= urlencode($resultado['curso']) ?>">Descargar
                        Certificado</a>
                <?php else: ?>
                    <section>
                        <p>😕 No alcanzaste la puntuación necesaria para aprobar.</p>
                        <form action="/resolver-evaluacion" method="get">
                            <input class="curso-subt" type="hidden" name="curso"
                                value="<?= htmlspecialchars($resultado['curso']) ?>">
                            <button class="btn-resolver" type="submit" class="btn">Volver a intentar el examen</button>
                        </form>
                    </section>
                <?php endif; ?>
            <?php else: ?>
                <p>No hay resultados disponibles. Por favor, realiza una evaluación.</p>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'parts/footer.php'; ?>
</body>