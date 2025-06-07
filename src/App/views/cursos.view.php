<?php include "parts/head.php" ?>

<body>
    <?php include "parts/header.php" ?>
    <main>
        <section class="seccion-contenido">
            <h2>Aprenda y mejore habilidades de programación</h2>
            <section class="temas">
                <h3>Prepararse por temas</h3>
                <section class="temas-box">
                    <?php foreach ($cursos as $curso): ?>
                        <a href="/curso?id=<?= urlencode($curso->campos['id']) ?>" class="curso-card">
                            <h4><?= htmlspecialchars($curso->campos['titulo']) ?></h4>
                            <p><?= nl2br(htmlspecialchars($curso->campos['descripcion'])) ?></p>
                        </a>
                    <?php endforeach; ?>
                </section>
            </section>

            <section class="recursos">
                <h3>Recursos educativos</h3>
                <section class="temas-box">
                    <a href="/videos" class="curso-card">
                        <h4>Videos</h4>
                        <p>Explicaciones visuales para facilitar tu aprendizaje.</p>
                    </a>
                    <a href="/articulos" class="curso-card">
                        <h4>Artículos</h4>
                        <p>Explora conceptos en profundidad con nuestros artículos.</p>
                    </a>
                </section>
            </section>

            <h3>Cursos Activos</h3>
            <section class="temas-box">
                <p class="curso-card">Lo siento! No tienes ningun curso activo en este momento.</p>
                <!-- Aca deberia ir la logica que se encargue de mostrar cuales son los cursos activos del usuario 
                y en caso de que no tenga deberia avisar -->
            </section>
        
            <section>
                <?php if ($permiso): ?>
                    <a href="/agregar-curso" class="curso-card">
                        <h4>Agregar un nuevo curso</h4>
                    </a>
                <?php endif; ?>
                <!--
                Despues tenemos que implementar el boton para dar de baja un curso
                <a href="/eliminar-curso" class="curso-card">
                    <h4>Dar de baja un curso</h4>
                </a> 
                -->
            </section>

        </section>
    </main>
    <?php include "parts/footer.php" ?>
</body>