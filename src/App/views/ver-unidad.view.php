<?php include "parts/head.php"; ?>

<body>
    <?php include "parts/header.php"; ?>
    <main class="unidad-detalle">
        <h2><?= htmlspecialchars($modulo["titulo"]) ?></h2>

        <?php if (!empty($modulo['descripcion'])): ?>
            <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($modulo['descripcion'])) ?></p>
        <?php endif; ?>

        <p><strong>Recurso:</strong></p>
        <iframe width="560" height="315" src="<?= $modulo["url"] ?>" frameborder="0" allowfullscreen
            title="Video de Youtube"></iframe>

        <a href="/curso?titulo=<?= urlencode($cursoId) ?>">← Volver al curso</a>
    </main>
    <?php include "parts/footer.php"; ?>
</body>