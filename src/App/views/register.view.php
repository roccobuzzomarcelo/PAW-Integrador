<?php include "parts/head.php" ?>

<body>
    <main>
        <section class="registrarse">
            <form class="registro-form" action="/registro" method="post">
                <h3 class="subtitulo">Registrarse</h3>

                <label for="inputNombre">Nombre Completo</label>
                <input id="inputNombre" type="nombre" name="inputNombre" placeholder="Nombre" required>

                <label for="inputEmail">Dirección de correo electrónico</label>
                <input id="inputEmail" type="email" name="inputEmail" placeholder="Email" required>

                <label for="inputPassword">Contraseña</label>
                <input id="inputPassword" type="password" name="inputPassword" placeholder="Contraseña" required>

                <label for="inputConfirmarPassword">Confirmar Contraseña</label>
                <input id="inputConfirmarPassword" type="password" name="inputConfirmarPassword"
                    placeholder="Confirmar Contraseña" required>
                <p>Al registrarse, aceptas nuestros términos y condiciones </p>

                <input type="submit" name="submit" value="Registrarme">
                <p>¿Ya tienes cuenta creada?</p>
                <a class="login-link" href="/login">Iniciar Sesión</a>
            </form>

        </section>
    </main>
</body>