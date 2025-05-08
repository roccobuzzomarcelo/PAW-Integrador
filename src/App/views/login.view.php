<?php include 'parts/head.php' ?>

<body>
    <main class="login-main">
        <section class="container">
            <form class="form-box" action="/login" method="post">
                <h1 class="subtitulo">Iniciar Sesión</h1>
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
                <label for="inputRecuerdame">
                    <input type="checkbox" name="recuerdame">
                    Recuérdame
                </label>
                <button class="button-log" type="submit">Acceder</button>
                <a href="/recuperar-contraseña">¿Olvidaste tu contraseña?</a>
                <a href="/register">Registrarme</a>
            </form>
        </section>
    </main>
    <?php include "parts/footer.php" ?>
</body>

</html>