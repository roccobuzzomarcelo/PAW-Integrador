<?php include "parts/head.php" ?>

<body>
    <main class="register-main">
        <section class="container">
            <form class="form-box" action="/registro" method="post">
                <h1 class="subtitulo">Registrarse</h1>
                <div class="input-box">
                    <label for="inputNombre">Nombre Completo</label>
                    <input id="inputNombre" type="nombre" name="inputNombre" placeholder="Nombre" required>
                    <i class="fa-solid fa-user"></i>
                    </input>
                </div>
                <div class="input-box">
                    <label for="inputEmail">Dirección de correo electrónico</label>
                    <input id="inputEmail" type="email" name="inputEmail" placeholder="Email" required>
                    <i class="fa-solid fa-user"></i> </input>
                </div>
                <div class="input-box">
                    <label for="inputPassword">Contraseña</label>
                    <input id="inputPassword" type="password" name="inputPassword" placeholder="Contraseña" required>
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="input-box">
                    <label for="inputConfirmarPassword">Confirmar Contraseña</label>
                    <input id="inputConfirmarPassword" type="password" name="inputConfirmarPassword"
                        placeholder="Confirmar Contraseña" required>
                    <i class="fa-solid fa-lock"></i>
                </div>
                <p>Al registrarse, aceptas nuestros términos y condiciones</p>
                <button class="button-reg" type="submit">Registrarse</button>
                <p>¿Ya tienes cuenta creada? <strong><a class="login-link" href="/login">Iniciar Sesión</a></strong></a>
                </p>

            </form>
        </section>
    </main>
    <?php include "parts/footer.php" ?>
</body>