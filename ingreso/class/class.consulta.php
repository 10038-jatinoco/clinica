<?php

class consulta{
    private $ConsultaID;
    private $PacienteID;
    private $MedicoID;
    private $FechaConsulta;
    private $Diagnostico;
    private $Foto;
    private $con;

    //CONTRUCTOR

    function __construct($cn){
		$this->con = $cn;
	}

    //PARTES DEL CRUD

    public function update_consultas(){
        $this->ConsultaID = $_POST['id'];
        $this->PacienteID = $_POST['PacienteID'];
        $this->MedicoID = $_POST['MedicoID'];
        $this->FechaConsulta = $_POST['FechaConsulta'];
        $this->Diagnostico = $_POST['Diagnostico'];
        $this->Foto = $_FILES['foto']['name'];

        $sql = "UPDATE consultas SET PacienteID = '$this->PacienteID', 
                                     MedicoID = '$this->MedicoID', 
                                     FechaConsulta = '$this->FechaConsulta',
                                     Diagnostico = '$this->Diagnostico', 
                                     Foto = '$this->Foto' 
                                     WHERE ConsultaID = '$this->ConsultaID'";

        if($this->con->query($sql)){
			echo $this->_message_ok("modificó");
		}else{
			echo $this->_message_error("al modificar");
		}
    }

    public function save_consultas(){
        $this->PacienteID = $_POST['PacienteID'];
        $this->MedicoID = $_POST['MedicoID'];
        $this->FechaConsulta = $_POST['FechaConsulta'];
        $this->Diagnostico = $_POST['Diagnostico'];
        $this->Foto = $_FILES['foto']['name'];

        $this->Foto = $this->_get_name_file($_FILES['foto']['name'],12);
		
		$path = PATH . $this->Foto;
		
		//exit;
		if(!move_uploaded_file($_FILES['foto']['tmp_name'],$path)){
			$mensaje = "Cargar la imagen";
			echo $this->_message_error($mensaje);
			exit;
		}

        $sql = "INSERT INTO consultas VALUES (NULL, 
                                              '$this->PacienteID', 
                                              '$this->MedicoID', 
                                              '$this->FechaConsulta', 
                                              '$this->Diagnostico', 
                                              '$this->Foto')";

        if($this->con->query($sql)){
            echo $this->_message_ok("guardó");
        }else{
            echo $this->_message_error("al guardar");
        }
    }

    public function delete_consulta($id){
		$sql = "DELETE FROM consultas WHERE ConsultaID=$id;";
			if($this->con->query($sql)){
			echo $this->_message_ok("ELIMINÓ");
		}else{
			echo $this->_message_error("eliminar");
		}	
	}

    //FUNCION PARA LA TABLA DE CONSULTAS EN LA BASE DE DATOS
    public function get_list(){//tabla para mostrar los datos de la consulta en la base de datos
        $d_new = "new/0";
		$d_new_final = base64_encode($d_new);
		$html = '
		<div class = "container-fluid ">
			<div class = "table-responsive">
				<table class = "table table-bordered">
					<thead class = "table-dark">
						<tr>
							<th scope = "col" colspan = "9" class = "text-center">Lista de Consultas</th>
						</tr>
						<tr>
							<th scope = "col" colspan = "9" class = "text-center"><a href="Consultas.php?d=' . $d_new_final . '">Nuevo</a></th>
						</tr>
					</thead>
					<tbody>
					 <tr class = "text-center table-primary">
					 	<td>ConsultaID</td>
                        <td>PacienteID</td>
                        <td>MedicoID</td>
                        <td>FechaConsulta</td>
                        <td>Diagnostico</td>
                        <td>Foto</td>   
						<th colspan="3">Acciones</th>
					</tr>
			';
        //consulta para mostrar el nombre del paciente y el nombre del medico en la tabla de consultas
        $sql = "SELECT c.ConsultaID, p.Nombre as PacienteID, m.Nombre as MedicoID, c.FechaConsulta, c.Diagnostico, c.Foto FROM consultas c, pacientes p, medicos m WHERE c.PacienteID=p.PacienteID AND c.MedicoID=m.MedicoID;";	
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			$d_del = "del/" . $row['ConsultaID'];
			$d_del_final = base64_encode($d_del);
			$d_act = "act/" . $row['ConsultaID'];
			$d_act_final = base64_encode($d_act);
			$d_det = "det/" . $row['ConsultaID'];
			$d_det_final = base64_encode($d_det);					
			$html .= '
				<tr>
					<td>' . $row['ConsultaID'] . '</td>
                    <td>' . $row['PacienteID'] . '</td>
                    <td>' . $row['MedicoID'] . '</td>
                    <td>' . $row['FechaConsulta'] . '</td>
                    <td>' . $row['Diagnostico'] . '</td>
                    <td>' . $row['Foto'] . '</td>
					<td class="text-center" ><a href="Consultas.php?d=' . $d_del_final . '" class = "bi bi-x-circle btn btn-success btn-sm"> Borrar</a></td>
					<td class="text-center"><a href="Consultas.php?d=' . $d_act_final . '" class = "bi bi-database btn btn-info btn-sm"> Actualizar</a></td>
					<td class="text-center"><a href="Consultas.php?d=' . $d_det_final . '" class = "bi bi-card-list btn btn-warning btn-sm"> Detalle</a></td>
				</tr>';
		}
		$html .= '</tbody>
				</table>
			</div>
		</div>';
		
		return $html;
		
	}

    public function get_detail_consultas($id){
        $sql = "SELECT c.ConsultaID, p.Nombre as PacienteID,p.Genero as Genero, m.Nombre as MedicoID, c.FechaConsulta as Consulta, c.Diagnostico as Descripcion, c.Foto as Imagen FROM consultas c, pacientes p, medicos m WHERE c.PacienteID=p.PacienteID AND c.MedicoID=m.MedicoID AND c.ConsultaID = $id;";	
		$res = $this->con->query($sql);
        $row = $res->fetch_assoc();

        $num = $res->num_rows;
        if($num==0){
            $mensaje = "tratar de visualizar id= ".$id;
            echo $this->_message_error($mensaje);
        }else{
            $html = '
			<div class="container-fluid container-sm">
			<div class="table-responsive">
			<table class="table table-bordered bordered-primary table-striped">
					<tr>
						<th colspan="2" class="table-dark text-center">DATOS DE LA CONSULTA</th>
					</tr>
					<tr>
						<td>Paciente: </td>
						<td>'. $row['PacienteID'] .'</td>
					</tr>}
					<tr>
						<td>Genero: </td>
						<td>'. $row['Genero'] .'</td>
					</tr>
					<tr>
						<td>Medico: </td>
						<td>'. $row['MedicoID'] .'</td>
					</tr>
					<tr>
						<td>Fecha: </td>
						<td>'. $row['Consulta'] .'</td>
					</tr>
					<tr>
						<td>Diagnostico: </td>
						<td>'. $row['Descripcion'] .'</td>
					</tr>			
					<tr>
					<td>Imagen: </td>
						<th colspan="2"><img src="' .PATH .'' . $row['Imagen'] . '" width="300px"/></th>
					</tr>	
					<tr>
						<th colspan="2" ><a class="btn btn-primary" href="Consultas.php">Regresar</a></th>
					</tr>		
					</div>
					</div>
				</table>';
				
				return $html;
        }

    }
    //FUNCION PARA EL FORMULARIO DE CONSULTAS

    public function get_form($id=NULL) {
        if ($id == NULL) {
            $this->PacienteID = NULL;
            $this->MedicoID = NULL;
            $this->FechaConsulta = NULL;
            $this->Diagnostico = NULL;
            $this->Foto = NULL;

            $flag = "enabled";
			$op = "new";

        } else {
            $sql = "SELECT * FROM consultas WHERE ConsultaID=$id;";
            $res = $this->con->query($sql);
            $row = $res->fetch_assoc();
            
            $num = $res->num_rows;
            if ($num == 0) {
                $mensaje = "tratar de actualizar la consulta con ConsultaID= ".$id;
                echo $this->_message_error($mensaje);
            } else {

                // ***** TUPLA ENCONTRADA *****
				echo "<br>TUPLA <br>";
				echo "<pre>";
					print_r($row);
				echo "</pre>";
                
				$this->PacienteID = $row['PacienteID'];
                $this->MedicoID = $row['MedicoID'];
                $this->FechaConsulta = $row['FechaConsulta'];
                $this->Diagnostico = $row['Diagnostico'];
                $this->Foto = $row['Foto'];
				
				$flag = "disabled";
				$op = "update";
            }
        }
    
        $html = '
        <form name="consulta" method="POST" action="Consultas.php" enctype="multipart/form-data">
		<input type="hidden" name="id" value="' . $id  . '">
		<input type="hidden" name="op" value="' . $op  . '">

		<div class="container-fluid container-md">
			<div class="table-responsive">
            <table class="table table-bordered bordered-primary table-striped">
                <tr>
                    <th colspan="2" class="table-dark text-center" >DATOS CONSULTA</th>
                </tr>
                <tr>
                    <td>Paciente:</td>
                    <td>' . $this->_get_combo_paciente("pacientes", "PacienteID", "Nombre", "PacienteID", $this->PacienteID) . '</td>
                </tr>
				<tr>
					<td>Medico:</td>
					<td><div class="mb-3" id="div"></div></td>
				</tr>
				<tr>
					<td>Fecha de Consulta:</td>
					<td><input type="date" class="form-control" name="FechaConsulta" id="fechaConsulta" value="'. $this->FechaConsulta.'" onchange="validarFechaConsulta()"></td>
				</tr>
							
                            <tr>
                            <td>Diagnostico:</td>
                            <td><input type="text" class=" form-control" name="Diagnostico" value="' . $this->Diagnostico . '"></td>
                            </tr>
                <tr>
                    <td>Foto:</td>
                    <td><input class="form-control" type="file" name="foto"></td>
                </tr>
                <tr>
                    <th colspan="2"><input type="submit" class="btn btn-primary d-grid gap-2  mx-auto" name="Guardar" value="GUARDAR"></th>
                </tr>												
            </table>
			</div>
		</div>
        </form>
		

		<script>
		function validarFechaConsulta() {
			var medicoSelect = document.getElementById("MedicoID");
			var fechaInput = document.getElementById("fechaConsulta");
		
			// Obtener el valor seleccionado del médico
			var medicoValue = medicoSelect.options[medicoSelect.selectedIndex].text;
		
			// Obtener el día de la semana de la fecha seleccionada
			var fecha = new Date(fechaInput.value);
			var diaSemana = fecha.getDay();
		
			if (medicoValue.includes("Ginecología")) {
				if (!(diaSemana === 1 || diaSemana === 3 || diaSemana === 5)) {
					
				}else{
					alert("Para Ginecología, solo se pueden seleccionar los días lunes, miércoles y viernes.");
					fechaInput.value = ""; // Limpiar la fecha
				}
			}
			
			
			
			
		}
		</script>
		
		
		';
    
        return $html;
    }

    private function _get_combo_paciente($tabla,$valor,$etiqueta,$nombre,$defecto){
		$html = '<select class="form-select" name="' . $nombre . '"  onclick="muestraselect(this.value)" >';
		$html.= '<option value="" >Seleccione un paciente</option>';
		$sql = "SELECT $valor,$etiqueta FROM $tabla;";
		$res = $this->con->query($sql);
		while($row = $res->fetch_assoc()){
			//ImpResultQuery($row);
			$html .= ($defecto == $row[$valor])?'<option value="' . $row[$valor] . '" selected>' . $row[$etiqueta]  . '</option>' . "\n" : '<option value="' . $row[$valor] . '">' . $row[$etiqueta]  .'</option>' . "\n";
		}
		$html .= '</select>
		        ';
		$html .= '<script>
					function muestraselect(str){
						var conexion;
						if(str==""){
							document.getElementById("txtHint").innerHTML="";
							return;
						}

						if(window.XMLHttpRequest){
							conexion = new XMLHttpRequest();
						}

						conexion.onreadystatechange = function(){
							if(conexion.readyState==4 && conexion.status==200){
								document.getElementById("div").innerHTML=conexion.responseText;
							}
						}

						conexion.open("GET","class/compartir.php?q="+str,true);
						conexion.send();
					}

						
				</script>';
		return $html;
	}

    

    //************************************************************************** 
    //FUNCION PARA LA EXTRACCION DEL NOMBRE DE LA IMAGEN 
    private function _get_name_file($nombre_original, $tamanio){
		$tmp = explode(".",$nombre_original); //Divido el nombre por el punto y guardo en un arreglo
		$numElm = count($tmp); //cuento el número de elemetos del arreglo
		$ext = $tmp[$numElm-1]; //Extraer la última posición del arreglo.
		$cadena = "";
			for($i=1;$i<=$tamanio;$i++){
				$c = rand(65,122);
				if(($c >= 91) && ($c <=96)){
					$c = NULL;
					 $i--;
				 }else{
					$cadena .= chr($c);
				}
			}
		return $cadena . "." . $ext;
	}

    //*************************************************************************	
	//FUNCIONES PARA LOS MENSAJES
	private function _message_error($tipo){
		$html = '
		<table border="0" align="center">
			<tr>
				<th>Error al ' . $tipo . '. Favor contactar a .................... </th>
			</tr>
			<tr>
				<th><a href="Consultas.php">Regresar</a></th>
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
				<th><a href="Consultas.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}

	

	

}

?>