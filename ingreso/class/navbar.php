<?php   
    $html ='
            <nav class="navbar navbar-expand-lg navbar navbar-dark bg-dark">
            <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <a class="navbar-brand" href="#">Clinica Medica</a>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Pacientes.php">Paciente</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Medicos.php">Medico</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Medicamentos.php">Medicamentos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Consultas.php">Consultas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Recetas.php">Recetas</a>
                </li>
            </div>
            </div>
        </nav>
    ';

    echo $html;
?>