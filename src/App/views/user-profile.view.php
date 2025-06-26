<?php include "parts/head.php" ?>

<body>
    <?php include "parts/header.php" ?>
    <main>
        <section class="seccion-perfil">
            <h2>USUARIO<br><small>(nombre de usuario)</small></h2>

            <section>
                <!-- Datos personales -->
                <div>
                    <h3>Datos Personales</h3>
                    <ul>
                        <li>
                            Nombre
                            <button title="Editar">✎</button>
                        </li>
                        <li>
                            Contraseña
                            <button title="Editar">✎</button>
                        </li>
                    </ul>
                </div>

                <!-- Mi progreso -->
                <div>
                    <h3>Mi progreso</h3>
                    <ul>
                        <li>Nivel</li>
                        <li>Progreso</li>
                    </ul>
                </div>

                <!-- Configuración -->
                <div>
                    <h3>Configuración</h3>
                    <ul>
                        <li>Suscripción</li>
                        <li>Notificaciones</li>
                    </ul>
                </div>

                <!-- Certificados -->
                <div>
                    <h3>Certificados</h3>
                    <ul>
                        <li>Certificado 1</li>
                        <li>Certificado 2</li>
                    </ul>
                </div>

            </section>

        <!-- Logout -->
        <section>
            <h3>Salir</h3>
            <a href="/logout" class="btn btn-logout">Cerrar sesión</a>
        </section>
    </main>
    <?php include "parts/footer.php" ?>
</body>