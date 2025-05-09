<?php include "parts/head.php"; ?>
<body>
<?php include "parts/header.php"; ?>
<main class="unidad-detalle">
    <h2><?= htmlspecialchars($unidad['subtitulo']) ?></h2>

    <?php if (!empty($unidad['descripcion'])): ?>
        <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($unidad['descripcion'])) ?></p>
    <?php endif; ?>

    <p><strong>Recurso:</strong> <?= htmlspecialchars($unidad['recurso']) ?></p>

    <a href="/curso?titulo=<?= urlencode($curso['titulo']) ?>">← Volver al curso</a>
</main>
<?php include "parts/footer.php"; ?>
</body>