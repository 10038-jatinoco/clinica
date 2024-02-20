<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="assets/fo.js"></script>
    <link rel="stylesheet" type="text/css" href="assets/login.css">
    <title>Clínica</title>
</head>
<body>
    <div class="padre">
        <img src="assets/img/fondo.jpg" alt="fondo 1" id="fondo">
        <div class="hijo">
            <div class="nieto">
                <form class="login">
                <h2 id="title">INICIAR SESIÓN</h2>
                <img src="assets/img/hospital.png" id="usuario">
                <div class="input-container">
                    <i class="fa-solid fa-user input-icon"></i>
                    <input type="text" id="user" name="usuario" placeholder="Usuario">
                </div>
                <div class="input-container">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input type="password" id="password" name="clave" placeholder="Contraseña">
                </div>
                <label class="alert" id="alerta"></label>
                <input value="INGRESAR" id="ingresar" onclick="redirigirALink()">
            </form>
            </div>
        </div>
    </div>
</body>

<script>
    function redirigirALink() {
        var enlaceEspecifico = "index2.php";
        window.location.href = enlaceEspecifico;
        return false;
    }
</script>

</html>