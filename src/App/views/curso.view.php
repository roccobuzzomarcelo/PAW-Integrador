<?php include "parts/head.php"; ?>

<body>
    <?php include "parts/header.php"; ?>
    <main class="curso-detalle">
        <h2><?= htmlspecialchars($curso['titulo']) ?></h2>
        <?php if (!empty($curso['imagen'])): ?>
            <img src="/uploads/<?= htmlspecialchars($curso['imagen']) ?>" alt="Imagen del curso">
        <?php endif; ?>
        <section class="curso-box">
            <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($curso['descripcion'])) ?></p>
            <p><strong>Temario:</strong></p>
            <ul>
                <?php foreach (explode("\n", $curso['temario']) as $item): ?>
                    <li><?= htmlspecialchars(trim($item)) ?></li>
                <?php endforeach; ?>
            </ul>
            <p><strong>Nivel:</strong> <?= htmlspecialchars($curso['nivel']) ?></p>
            <p><strong>Duración:</strong> <?= htmlspecialchars($curso['duracion']) ?></p>
        </section>
        <section class="unidades-box">
            <h3 class="curso-subt">Unidades</h3>
            <ul>
                <?php foreach ($curso['unidades'] as $i => $unidad): ?>
                    <li class="curso-card">
                        <a href="/ver-unidad?curso=<?= urlencode($curso['titulo']) ?>&unidad=<?= $i ?>">
                            <?= htmlspecialchars($unidad['subtitulo']) ?>
                        </a>
                        <p><?= htmlspecialchars($unidad['descripcion']) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
        <section >
            <h3 class="curso-subt">Evaluación final</h3>
            <a class="btn-resolver" href="/resolver-evaluacion?curso=<?= urlencode($curso['titulo']) ?>">Resolver Evaluación</a>
        </section>
    </main>
    <?php include "parts/footer.php"; ?>
</body>