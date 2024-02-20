<?php   
require_once("../constantes.php");
require_once("class.consulta.php");

$cn = conectar();
$v = new consulta($cn);

if(isset($_GET['q'])){
    $id_paciente = $_GET['q'];
    //echo $id_paciente;
} else {
    echo "No se ha seleccionado un paciente";
    exit;
}

$sql = "SELECT Genero FROM pacientes WHERE PacienteID = $id_paciente";
$res = $cn->query($sql);



if (!$res) {
    echo "Error en la consulta: " . $cn->error;
    exit;
}

$row = $res->fetch_assoc();


if($row['Genero'] == 'Femenino'){
    $html = '<select class="form-select" name="MedicoID" id="MedicoID">';
    $sql = "SELECT MedicoID,Nombre,Especialidad FROM medicos WHERE Especialidad = 'Ginecología';";
    $res = $cn->query($sql);
    while($row = $res->fetch_assoc()){
        $html .= '<option value="' . $row['MedicoID'] . '">' . $row['Nombre'] .' '. $row['Especialidad'] . '</option>';
    }
    $html .= '</select>'
    ;
    
    echo $html;
}
elseif($row['Genero'] == 'Masculino'){
    $html = '<select class="form-select" name="MedicoID" id="MedicoID">';
    $sql = "SELECT MedicoID,Nombre,Especialidad FROM medicos WHERE Especialidad != 'Ginecología';";
    $res = $cn->query($sql);
    while($row = $res->fetch_assoc()){
        $html .= '<option value="' . $row['MedicoID'] . '">' . $row['Nombre'] .' '. $row['Especialidad'] . '</option>';
    }
    $html .= '</select>';
    echo $html;
}
elseif($row['Genero'] == NULL){
    echo "No se ha seleccionado un paciente";
}


//*******************************************************
function conectar(){
    //echo "<br> CONEXION A LA BASE DE DATOS<br>";
    $c = new mysqli(SERVER,USER,PASS,BD);
    
    if($c->connect_errno) {
        die("Error de conexión: " . $c->mysqli_connect_errno() . ", " . $c->connect_error());
    }else{
        //echo "La conexión tuvo éxito .......<br><br>";
    }
    
    $c->set_charset("utf8");
    return $c;
}
//***
?>
