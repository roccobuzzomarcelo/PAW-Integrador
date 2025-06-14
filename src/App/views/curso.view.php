<?php include "parts/head.php"; ?>

<body>
    <?php include "parts/header.php"; ?>
    <main class="curso-detalle">
        <?php if (isset($curso) && is_object($curso) && isset($curso->campos)): ?>
            <h2><?= htmlspecialchars($curso->campos['titulo']) ?></h2>
            <?php if (!empty($curso->campos['imagen'])): ?>
                <img src="<?= htmlspecialchars($curso->campos['imagen']) ?>" alt="Imagen del curso">
            <?php endif; ?>
            <section class="curso-box">
                <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($curso->campos['descripcion'])) ?></p>
                <p><strong>Temario:</strong></p>
                <ul>
                    <?php foreach ($temas as $tema): ?>
                        <li><?= htmlspecialchars($tema["titulo"]) ?></li>
                    <?php endforeach; ?>
                </ul>
                <p><strong>Nivel:</strong> <?= htmlspecialchars($curso->campos['nivel']) ?></p>
                <p><strong>Duración:</strong> <?= htmlspecialchars($curso->campos['duracion']) ?></p>
            </section>
            <section class="unidades-box">
                <h3 class="curso-subt">Unidades</h3>
                <ul>
                    <?php foreach ($modulos as $modulo): ?>
                        <li class="curso-card">
                            <a href="/ver-modulo?modulo=<?= urlencode($modulo['id']) ?>">
                                <?= htmlspecialchars($modulo['titulo']) ?>
                            </a>
                            <p><?= htmlspecialchars($modulo['descripcion']) ?></p>
                            <?php if (!empty($modulo['completado'])): ?>
                                <p class="estado-modulo completado">✅ Completado</p>
                            <?php else: ?>
                                <p class="estado-modulo incompleto">❌ No completado</p>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
            <section>
                <h3 class="curso-subt">Evaluación final</h3>
                <a class="btn-resolver" href="/resolver-evaluacion?curso=<?= urlencode($curso->campos['id']) ?>">Resolver
                    Evaluación</a>
                <a class="btn-resolver" href="/agregar-evaluacion?curso=<?= urlencode($curso->campos['id']) ?>">Agregar
                    Evaluación</a>
            </section>
        <?php else: ?>
            <p>No se encontró información del curso.</p>
        <?php endif; ?>
    </main>
    <?php include "parts/footer.php"; ?>
</body>