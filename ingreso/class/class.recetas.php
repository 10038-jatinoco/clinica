<?php
class receta{
    private $RecetaID;
    private $ConsultaID;
    private $MedicamentoID;
    private $Cantidad;
    private $con;

    function __construct($cn){
        $this->con = $cn;
    }

    public function update_receta(){
        $this->RecetaID = $_POST['id'];
        $this->ConsultaID = $_POST['ConsultaID'];
        $this->MedicamentoID = $_POST['MedicamentoID'];
        $this->Cantidad = $_POST['Cantidad'];

        $sql = "UPDATE recetas SET ConsultaID = '$this->ConsultaID', 
                                     MedicamentoID = '$this->MedicamentoID',
                                     Cantidad = '$this->Cantidad' 
                                     WHERE RecetaID = '$this->RecetaID'";

        if($this->con->query($sql)){
            echo $this->_message_ok("modificó");
        }else{
            echo $this->_message_error("al modificar");
        }
    }

    public function save_receta(){
        $this->ConsultaID = $_POST['ConsultaID'];
        $this->MedicamentoID= $_POST['MedicamentoID'];
        $this->Cantidad = $_POST['Cantidad'];

        $sql = "INSERT INTO recetas VALUES (NULL,
                                             '$this->ConsultaID', 
                                             '$this->MedicamentoID',
                                             '$this->Cantidad')";

        if($this->con->query($sql)){
            echo $this->_message_ok("guardó");
        }else{
            echo $this->_message_error("al guardar");
        }
    }

    public function delete_receta($id){
        $sql = "DELETE FROM recetas WHERE RecetaID =$id;";

        if($this->con->query($sql)){
            echo $this->_message_ok("eliminó");
        }else{
            echo $this->_message_error("al eliminar");
        }
    }

    public function get_list(){
        $d_new = "new/0";
        $d_new_final = base64_encode($d_new);
        $html = '
        <div class = "container-fluid ">
            <div class = "table-responsive">
                <table class = "table table-bordered">
                    <thead class = "table-dark">
                        <tr>
                            <th scope = "col" colspan = "6" class = "text-center">Lista de Recestas</th>
                        </tr>
                        <tr>
                            <th scope = "col" colspan = "6" class = "text-center"><a href="Recetas.php?d=' . $d_new_final . '">Nuevo</a></th>
                        </tr>
                    </thead>
                    
                    <tbody>
                     <tr class = "text-center table-primary">
                            <th>RecetaID</th>
                            <th>Paciente</th>
                            <th>Medico</th>
                            <th colspan="3">Acciones</th>
                    </tr>
                    
                    ';
        $sql = "SELECT r.RecetaID, p.Nombre AS NombrePaciente , m.Nombre AS NombreMedico 
                FROM recetas r, pacientes p, medicos m , consultas c
                WHERE c.MedicoID = m.MedicoID AND r.ConsultaID = c.ConsultaID AND c.PacienteID = p.PacienteID"
                ;

        $rs = $this->con->query($sql);
        while($row = $rs->fetch_assoc()){
            $d_det = "det/".$row['RecetaID'];
            $d_det_final = base64_encode($d_det);
            $d_act = "act/".$row['RecetaID'];
            $d_act_final = base64_encode($d_act);
            $d_del = "del/".$row['RecetaID'];
            $d_del_final = base64_encode($d_del);
            $html .= '
            <tr>
                <td>'.$row['RecetaID'].'</td>
                <td>'.$row['NombrePaciente'].'</td>
                <td>'.$row['NombreMedico'].'</td>
                <td class="text-center" ><a href="Recetas.php?d=' . $d_del_final . '" class = "bi bi-x-circle btn btn-success btn-sm"> Borrar</a></td>
                <td class="text-center"><a href="Recetas.php?d=' . $d_act_final . '" class = "bi bi-database btn btn-info btn-sm"> Actualizar</a></td>
                <td class="text-center"><a href="Recetas.php?d=' . $d_det_final . '" class = "bi bi-card-list btn btn-warning btn-sm"> Detalle</a></td>
            </tr>';
        }
        $html .= '
                    </tbody>
                </table>
            </div>
        </div>';
        return $html;
    }

    public function get_detail_receta($id){
        $sql = "SELECT r.RecetaID, p.Nombre AS NombrePaciente , m.Nombre AS NombreMedico, r.Cantidad AS Cantidad, me.Nombre AS NombreMedicamento, me.Tipo AS TipoMedicamento , r.cantidad AS Cantidad 
        FROM recetas r, pacientes p, medicos m , consultas c, medicamentos me
        WHERE c.MedicoID = m.MedicoID AND r.ConsultaID = c.ConsultaID AND c.PacienteID = p.PacienteID AND r.MedicamentoID = me.MedicamentoID AND r.RecetaID = $id";
        $res = $this->con->query($sql);
        $row = $res->fetch_assoc()
        ;

        $html = '
        <div class="container-fluid container-sm">
			<div class="table-responsive">
			<table class="table table-bordered bordered-primary table-striped">
                    <thead class = "table-dark">
                        <tr>
                            <th scope = "col" colspan = "2" class = "text-center">Detalle de Receta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>RecetaID</th>
                            <td>' . $row['RecetaID'] . '</td>
                        </tr>
                        <tr>
                            <th>Paciente</th>
                            <td>' . $row['NombrePaciente'] . '</td>
                        </tr>
                        <tr>
                            <th>Medico</th>
                            <td>' . $row['NombreMedico'] . '</td>
                        </tr>
                        <tr>
                            <th>Medicamento</th>
                            <td>' . $row['NombreMedicamento'] . '</td>
                        </tr>
                        <tr>
                            <th>Tipo</th>
                            <td>' . $row['TipoMedicamento'] . '</td>
                        </tr>
                        <tr>
                            <th>Cantidad</th>
                            <td>' . $row['Cantidad'] . '</td>
                        </tr>
                        <tr>
                            <th scope = "col" colspan = "2"><a class="btn btn-primary" href="Recetas.php">Regresar</a></th>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div>
        ';

        return $html;
    }



    public function get_form($id=NULL){
        if($id == NULL){
            $this->ConsultaID = NULL;
            $this->MedicamentoID = NULL;
            $this->Cantidad = NULL;

            $flag = "enabled";
            $op = "new";
        }else{
            $sql = "SELECT * FROM recetas WHERE RecetaID = $id";
            $res = $this->con->query($sql);
            $row = $res->fetch_assoc();

            $num = $res->num_rows;
            if($num == 0){
                $mensaje = "No existe el registro";
                echo $this->_message_error($mensaje);
            }else{

                //************ TUPLA ENCONTRADA  */
                echo "<br>TUPLA <br>";
                echo "<pre>";
                    print_r($row);
                echo "</pre>";
                
                $this->ConsultaID = $row['ConsultaID'];
                $this->MedicamentoID = $row['MedicamentoID'];
                $this->Cantidad = $row['Cantidad'];

                $flag = "disabled";
                $op = "update";
            }
        }

        $html = '
        <form name="Recetas" method="POST" action="Recetas.php" enctype="multipart/form-data">
		
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">
		
        <div class="container-fluid container-md">
			<div class="table-responsive">
            <table class="table table-bordered bordered-primary table-striped">

				<tr>
                <th colspan="2" class="table-dark text-center">DATOS RECETA</th>
				</tr>
				<tr>
					<td>Consulta:</td>
                    <td>' . $this->_get_combo_consulta("consultas","ConsultaID","Nombre","ConsultaID",$this->ConsultaID) . '</td>
				</tr>
				<tr>
					<td>Medicamento:</td>
                    <td>' . $this->_get_combo_db("medicamentos","MedicamentoID","Nombre","MedicamentoID",$this->MedicamentoID) . '</td>
				</tr>
				<tr>
					<td>Cantidad:</td>
                    <td><input type="text" class="form-control" size="8" name="Cantidad" value="' . $this->Cantidad . '"  required></td>
				</tr>	
				<tr>
					<th colspan="2"><input type="submit" class="btn btn-primary d-grid gap-2  mx-auto" name="Guardar" value="GUARDAR"></th>
				</tr>												
			</table>';
		return $html;
    }

    private function _get_combo_consulta($tabla,$valor,$etiqueta,$nombre,$defecto){
		$html = '<select class="form-select" name="' . $nombre . '">';
		$sql = "SELECT c.$valor,p.$etiqueta AS nombrePaciente, m.Nombre AS nombreMedico, c.FechaConsulta AS fechaConsulta FROM $tabla c, pacientes p, medicos m
                WHERE c.PacienteID = p.PacienteID AND m.MedicoID = c.MedicoID;";
		$res = $this->con->query($sql);
		while($row = $res->fetch_assoc()){
			//ImpResultQuery($row);
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>'.'Paciente: '.$row['nombrePaciente'] .' || Medico: '. $row['nombreMedico'] .' || Fecha Consulta: '.$row['fechaConsulta'].   '</option>' . "\n" : '<option value="' . $row[$valor] . '">'.$row['nombrePaciente'] .' || Medico: '. $row['nombreMedico'] .' || Fecha Consulta: '.$row['fechaConsulta'].  '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}

    private function _get_combo_db($tabla,$valor,$etiqueta,$nombre,$defecto){
		$html = '<select class="form-select" name="' . $nombre . '">';
		$sql = "SELECT $valor,$etiqueta  FROM $tabla;";
		$res = $this->con->query($sql);
		while($row = $res->fetch_assoc()){
			//ImpResultQuery($row);
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta] . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta] . '</option>' . "\n";
		}
		$html .= '</select>';
		return $html;
	}


     /********************************************************* */
     private function _message_error($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . '. Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a href="Recetas.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
	
	
	private function _message_ok($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>El registro se  ' . $tipo . ' correctamente</th>
			</tr>
			<tr>
				<th><a href="Recetas.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}
}
?>