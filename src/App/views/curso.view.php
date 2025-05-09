<?php include "parts/head.php" ?>

<body>
    <?php include "parts/header.php" ?>
    <main class="curso-detalle">
        <h2><?= htmlspecialchars($curso['nombre']) ?></h2>
        <p><?= htmlspecialchars($curso['descripcion']) ?></p>
        <section>
            <h3>Unidades</h3>
            <ol>
                <?php foreach ($unidades as $unidad): ?>
                    <li>
                        <h4><?= htmlspecialchars($unidad['titulo']) ?></h4>
                        <p><?= htmlspecialchars($unidad['descripcion']) ?></p>
                    </li>
                <?php endforeach; ?>
            </ol>
        </section>
        <?php if ($usuarioCompleto): ?>
            <div class="examen-final">
                <a href="/examen?id=<?= $curso['id'] ?>" class="boton">¡Tomar examen final y obtener certificado!</a>
            </div>
        <?php else: ?>
            <p><em>Debes completar todas las unidades para habilitar el examen final.</em></p>
        <?php endif; ?>
    </main>
    <?php include "parts/footer.php" ?>
</body>