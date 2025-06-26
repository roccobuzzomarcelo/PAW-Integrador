<?php include "parts/head.php" ?>
<body>
    <?php include "parts/header.php" ?>
    <main>
        <h2>Total de inscriptos</h2>
        <p>Hay <?= $total ?> inscriptos en este curso.</p>
        <a class= "btn-inscriptos" href="/listar-inscriptos?id=<?= $_GET['curso'] ?>">Ver detalle</a>
    </main>
    <?php include "parts/footer.php" ?>
</body>
