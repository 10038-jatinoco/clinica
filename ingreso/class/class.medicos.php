<?php

class medico{
    private $MedicoID;
    private $Nombre;
    private $Especialidad;
    private $con;

    function __construct($cn){
        $this->con = $cn;
    }

    public function update_medico(){
        $this->MedicoID = $_POST['id'];
        $this->Nombre = $_POST['Nombre'];
        $this->Especialidad = $_POST['Especialidad'];

        $sql = "UPDATE medicos SET Nombre = '$this->Nombre', 
                                     Especialidad = '$this->Especialidad' 
                                     WHERE MedicoID = '$this->MedicoID'";

        if($this->con->query($sql)){
            echo $this->_message_ok("modificó");
        }else{
            echo $this->_message_error("al modificar");
        }
    }

    public function save_medico(){
        $this->Nombre = $_POST['Nombre'];
        $this->Especialidad = $_POST['Especialidad'];

        $sql = "INSERT INTO medicos VALUES (NULL,
                                             '$this->Nombre', 
                                             '$this->Especialidad')";

        if($this->con->query($sql)){
            echo $this->_message_ok("guardó");
        }else{
            echo $this->_message_error("al guardar");
        }
    }

    public function delete_medico($id){
        $sql = "DELETE FROM medicos WHERE MedicoID =$id;";

        if($this->con->query($sql)){
            echo $this->_message_ok("eliminó");
        }else{
            echo $this->_message_error("al eliminar");
        }
    }

    public function get_list(){//tabla para mostrar los datos de la consulta en la base de datos
        $d_new = "new/0";
		$d_new_final = base64_encode($d_new);
		$html = '
		<div class = "container-fluid ">
			<div class = "table-responsive">
				<table class = "table table-bordered">
					<thead class = "table-dark">
						<tr>
							<th scope = "col" colspan = "5" class = "text-center">Lista de Medicos</th>
						</tr>
						<tr>
							<th scope = "col" colspan = "5" class = "text-center"><a href="Medicos.php?d=' . $d_new_final . '">Nuevo</a></th>
						</tr>
					</thead>
					<tbody>
					 <tr class = "text-center table-primary">
                        <th>MedicoID</th>
                        <th>Nombre</th>
						<th colspan="3">Acciones</th>
					</tr>
			';
        //consulta para mostrar el nombre del paciente y el nombre del medico en la tabla de consultas
        $sql = "SELECT * FROM medicos";	
		$res = $this->con->query($sql);
		// Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
		while($row = $res->fetch_assoc()){
			$d_del = "del/" . $row['MedicoID'];
			$d_del_final = base64_encode($d_del);
			$d_act = "act/" . $row['MedicoID'];
			$d_act_final = base64_encode($d_act);
			$d_det = "det/" . $row['MedicoID'];
			$d_det_final = base64_encode($d_det);					
			$html .= '
				<tr>
                    <td>' . $row['MedicoID'] . '</td>
                    <td>' . $row['Nombre'] . '</td>
					<td class="text-center" ><a href="Medicos.php?d=' . $d_del_final . '" class = "bi bi-x-circle btn btn-success btn-sm"> Borrar</a></td>
					<td class="text-center"><a href="Medicos.php?d=' . $d_act_final . '" class = "bi bi-database btn btn-info btn-sm"> Actualizar</a></td>
					<td class="text-center"><a href="Medicos.php?d=' . $d_det_final . '" class = "bi bi-card-list btn btn-warning btn-sm"> Detalle</a></td>
				</tr>';
		}
		$html .= '</tbody>
				</table>
			</div>
		</div>';
		
		return $html;
		
	}

    public function get_detail_medico($id){
        $sql = "SELECT * FROM medicos WHERE MedicoID = $id";
        $res = $this->con->query($sql);
        $row = $res->fetch_assoc();
        $num = $res->num_rows;
        if($num==0){
            $mensaje = "tratar de visualizar id= ".$id;
            echo $this->_message_error($mensaje);
        }else{

            $html = '
            <div class="container-fluid container-md">
			<div class="table-responsive">
            <table class="table table-bordered bordered-primary table-striped">
                                    <thead class = "table-dark">
                                        <tr>
                                            <th scope = "col" colspan = "2" class = "text-center">Detalle de Paciente</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th scope = "col">MedicoID</th>
                                            <td>' . $row['MedicoID'] . '</td>
                                        </tr>
                                        <tr>
                                            <th scope = "col">Nombre</th>
                                            <td>' . $row['Nombre'] . '</td>
                                        </tr>
                                        <tr>
                                            <th scope = "col">Especialidad</th>
                                            <td>' . $row['Especialidad'] . '</td>
                                        </tr>
                                        <tr>
                                            <th scope = "col" colspan = "2"><a class="btn btn-primary" href="Medicos.php">Regresar</a></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>';

                    return $html;
        }
        
    }

    public function get_form($id=NULL){
        if($id==NULL){
            $this->MedicoID = NULL;
            $this->Nombre = NULL;
            $this->Especialidad = NULL;
            
            $flag = "enabled";
            $op = "new";
        }else{
            $sql = "SELECT * FROM medicos WHERE MedicoID = $id";
            $res = $this->con->query($sql);
            $row = $res->fetch_assoc();

            $num = $res->num_rows;
            if($num==0){
                $mensaje = "tratar de modificar id= ".$id;
                echo $this->_message_error($mensaje);
             }else{
                $this->MedicoID = $row['MedicoID'];
                $this->Nombre = $row['Nombre'];
                $this->Especialidad = $row['Especialidad'];
                
                $flag = "disabled";
                $op = "act";
             }
            }

            $html = '
                    <form name="consulta" method="POST" action="Medicos.php" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="' . $id  . '">
                    <input type="hidden" name="op" value="' . $op  . '">
                    
    
                    <div class="container-fluid container-sm">
                    <div class="table-responsive">
                    <table class="table table-bordered bordered-primary table-striped">
                    <thead class = "table-dark">
                            <tr>
                                <th colspan="2" class="text-center">DATOS MEDICOS</th>
                            </tr>
                    </thead>
                            <tr>
                                <td>Medico:</td>
                                <td><input type="text" class="form-control" name="Nombre" value="' . $this->Nombre . '" ></td>
                            </tr>
                            <tr>
                                <td>Especialidad:</td>
                                <td><input type="text" class="form-control" name="Especialidad" value="' . $this->Especialidad . '" ></td>
                            </tr>
                            <tr>
                                <th colspan="2"><input type="submit" class="btn btn-primary d-grid gap-2  mx-auto" name="Guardar" value="GUARDAR"></th>
                            </tr>												
                        </table>
                    </form>
                    ';
    
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
				<th><a href="Medicos.php">Regresar</a></th>
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
				<th><a href="Medicos.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}


    
}

?>