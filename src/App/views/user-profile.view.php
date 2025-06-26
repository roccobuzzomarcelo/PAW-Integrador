<?php include "parts/head.php" ?>

<body>
    <?php include "parts/header.php" ?>
    <main>
        <section class="seccion-perfil">
            <h2>USUARIO<br><small><?= htmlspecialchars($usuario["nombre"]) ?></small></h2>

            <section>
                <!-- Datos personales -->
                <div>
                    <h3>Datos Personales</h3>
                    <ul>
                        <li>
                            Nombre: <?= htmlspecialchars($usuario["nombre"]) ?>
                            <button title="Editar">✎</button>
                        </li>
                        <li>
                            Correo: <?= htmlspecialchars($usuario["correo"]) ?>
                            <button title="Editar">✎</button>
                        </li>
                        <li>
                            Contraseña: ********
                            <button title="Editar">✎</button>
                        </li>
                        <li>
                            Fecha de creación: <?= $fecha ?>
                        </li>
                        <li>
                            Rol: <?= htmlspecialchars($usuario["tipo_usuario"]) ?>
                        </li>
                    </ul>
                </div>

                <!-- Mi progreso (si lo añadís en el futuro) -->
                <div>
                    <h3>Mi progreso</h3>
                    <ul>
                        <li>Nivel: (próximamente)</li>
                        <li>Progreso: (próximamente)</li>
                    </ul>
                </div>

                <!-- Configuración -->
                <div>
                    <h3>Configuración</h3>
                    <ul>
                        <li>Notificaciones: (configurable)</li>
                    </ul>
                </div>

                <!-- Certificados -->
                <div>
                    <h3>Certificados</h3>
                    <ul>
                        <li>No hay certificados todavía.</li>
                    </ul>
                </div>
            </section>

            <!-- Logout -->
            <section>
                <h3>Salir</h3>
                <a href="/logout" class="btn btn-logout">Cerrar sesión</a>
            </section>
        </section>
    </main>
    <?php include "parts/footer.php" ?>
</body>