<?php include "parts/head.php"; ?>
<body>
<?php include "parts/header.php"; ?>
<main class="curso-detalle">
    <h2><?= htmlspecialchars($curso['titulo']) ?></h2>
    <?php if (!empty($curso['imagen'])): ?>
        <img src="/uploads/<?= htmlspecialchars($curso['imagen']) ?>" alt="Imagen del curso">
    <?php endif; ?>
    <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($curso['descripcion'])) ?></p>
    <p><strong>Temario:</strong> <?= nl2br(htmlspecialchars($curso['temario'])) ?></p>
    <p><strong>Nivel:</strong> <?= htmlspecialchars($curso['nivel']) ?></p>
    <p><strong>Duración:</strong> <?= htmlspecialchars($curso['duracion']) ?></p>

    <h3>Unidades (<?= $curso['cantidadUnidades'] ?>)</h3>
    <ul>
        <?php foreach ($curso['unidades'] as $i => $unidad): ?>
            <li>
                <a href="/ver-unidad?curso=<?= urlencode($curso['titulo']) ?>&unidad=<?= $i ?>">
                    <?= htmlspecialchars($unidad['subtitulo']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <h3>Evaluación final</h3>
    <a href="/resolver-evaluacion?curso=<?= urlencode($curso['titulo'])?>">Resolver Evaluación</a>
</main>
<?php include "parts/footer.php"; ?>
</body>
