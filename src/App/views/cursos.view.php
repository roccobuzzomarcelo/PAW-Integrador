<?php include "parts/head.php" ?>

<body>
    <?php include "parts/header.php" ?>
    <main>
        <section class="seccion-contenido">
            <h2>Aprenda y mejore habilidades de programación</h2>
            <section class="temas">
                <h3>Prepararse por temas</h3>
                <section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <a href="/algoritmos" class="curso-card">
                        <h4>Algoritmos</h4>
                        <p>Descubre los fundamentos de la lógica de programación.</p>
                    </a>
                    <a href="/estructuras-de-datos" class="curso-card">
                        <h4>Estructuras de datos</h4>
                        <p>Organiza y gestiona información de manera eficiente.</p>
                    </a>
                    <a href="/c++" class="curso-card">
                        <h4>C++</h4>
                        <p>Domina un lenguaje de programación potente y versátil.</p>
                    </a>
                    <a href="/java" class="curso-card">
                        <h4>Java</h4>
                        <p>Aprende a desarrollar aplicaciones robustas y escalables.</p>
                    </a>
                    <a href="/sql" class="curso-card">
                        <h4>SQL</h4>
                        <p>Consulta y manipula bases de datos de manera efectiva.</p>
                    </a>
                </section>
            </sec>

            <section class="recursos">
                <h3>Recursos educativos</h3>
                <section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
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
            <section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                <a href="cursos-activos" class="curso-card">
                    <h4>Java</h4>
                    <p>¡El curso de Java está activo! Inscríbete ahora.</p>
                </a>
            </section>

        </section>
    </main>
    <?php include "parts/footer.php" ?>
</body>