<?php include 'parts/head.php'?>
<body>
<?php include 'parts/header.php'; ?>
<main>
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a class="breadcrumb-link" href="../">Home</a></li>
            <li class="breadcrumb-item" aria-current="page">Inicio Sesion</li>
        </ul>
        <section class="iniciar-sesion">
            <form class="login-form" action="/mi-cuenta" method="post">
                <h2 class="subtitulo">Iniciar Sesión</h2>
                <label for="inputEmail">Dirección de correo electrónico</label>
                <input id="inputEmail" type="email" name="inputEmail" placeholder="Email" required>

                <label for="inputPassword">Contraseña</label>
                <input id="inputPassword" type="password" name="inputPassword" placeholder="Contraseña" required>
                <label for="inputRecuerdame">
                    <input type="checkbox" name="recuerdame">
                    Recuérdame
                </label>
                <button type="submit">Acceder</button>
                <a href="/recuperar-contraseña">¿Olvidaste tu contraseña?</a>
                <a href="/register">Registrarme</a>
            </form>
        </section>
    </main>

    <?php include 'parts/footer.php'; ?> 

</body>

</html>
