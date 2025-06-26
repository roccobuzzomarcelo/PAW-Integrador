<?php include "parts/head.php" ?>
<body>
    <?php include "parts/header.php" ?>
    <main>
        <h2>Listado de inscriptos</h2>
        <?php if (empty($inscriptos)): ?>
            <p>No hay inscriptos en este curso.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($inscriptos as $ins): ?>
                    <li>Usuario ID: <?= htmlspecialchars($ins['usuario_id']) ?> - Nombre: <?=htmlspecialchars($ins['nombre']) ?> - Fecha: <?= htmlspecialchars($ins['fecha_inscripcion']) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>
    <?php include "parts/footer.php" ?>
</body>
