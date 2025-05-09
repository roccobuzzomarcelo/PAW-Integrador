<?php include "parts/head.php" ?>

<body>
    <?php include "parts/header.php" ?>
    <main class="curso-detalle">
        <h2><?= htmlspecialchars($curso['titulo']) ?></h2>
        <?php if (!empty($curso['imagen'])): ?>
            <img src="/uploads/<?= htmlspecialchars($curso['imagen']) ?>" alt="Imagen del curso" class="curso-img">
        <?php endif; ?>
        <p><?= htmlspecialchars($curso['descripcion']) ?></p>
        
    </main>
    <?php include "parts/footer.php" ?>
</body>