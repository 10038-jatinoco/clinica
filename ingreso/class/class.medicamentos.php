<?php

class medicamento{
    private $MedicamentoID;
    private $Nombre;
    private $Tipo;

    private $con;

    function __construct($cn){
        $this->con = $cn;
    }

    public function update_medicamento(){
        $this->MedicamentoID = $_POST['id'];
        $this->Nombre = $_POST['Nombre'];
        $this->Tipo = $_POST['Tipo'];

        $sql = "UPDATE medicamentos SET Nombre = '$this->Nombre', 
                                     Tipo = '$this->Tipo' 
                                     WHERE MedicamentoID = '$this->MedicamentoID'";

        if($this->con->query($sql)){
            echo $this->_message_ok("modificó");
        }else{
            echo $this->_message_error("al modificar");
        }
    }

        public function save_medicamento(){
            $this->Nombre = $_POST['Nombre'];
            $this->Tipo = $_POST['Tipo'];

            $sql = "INSERT INTO medicamentos VALUES (NULL,
                                                '$this->Nombre', 
                                                '$this->Tipo')";

            if($this->con->query($sql)){
                echo $this->_message_ok("guardó");
            }else{
                echo $this->_message_error("al guardar");
            }
        }

        public function delete_medicamento($id){
            $sql = "DELETE FROM medicamentos WHERE MedicamentoID =$id;";

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
                                <th scope = "col" colspan = "5" class = "text-center">Lista de Medicamentos</th>
                            </tr>
                            <tr>
                                <th scope = "col" colspan = "5" class = "text-center"><a href="Medicamentos.php?d=' . $d_new_final . '">Nuevo</a></th>
                            </tr>
                        </thead>
                        <tbody>
                         <tr class = "text-center table-primary">
                            <th>MedicamentoID</th>
                            <th>Nombre</th>
                            <th colspan="3">Acciones</th>
                        </tr>
                ';
            //consulta para mostrar el nombre del paciente y el nombre del medico en la tabla de consultas
            $sql = "SELECT * FROM medicamentos";	
            $res = $this->con->query($sql);
            // Sin codificar <td><a href="index.php?op=del&id=' . $row['id'] . '">Borrar</a></td>
            while($row = $res->fetch_assoc()){
                $d_del = "del/" . $row['MedicamentoID'];
                $d_del_final = base64_encode($d_del);
                $d_act = "act/" . $row['MedicamentoID'];
                $d_act_final = base64_encode($d_act);
                $d_det = "det/" . $row['MedicamentoID'];
                $d_det_final = base64_encode($d_det);					
                $html .= '
                    <tr>
                        <td>' . $row['MedicamentoID'] . '</td>
                        <td>' . $row['Nombre'] . '</td>
                        <td class="text-center" ><a href="Medicamentos.php?d=' . $d_del_final . '" class = "bi bi-x-circle btn btn-success btn-sm"> Borrar</a></td>
                        <td class="text-center"><a href="Medicamentos.php?d=' . $d_act_final . '" class = "bi bi-database btn btn-info btn-sm"> Actualizar</a></td>
                        <td class="text-center"><a href="Medicamentos.php?d=' . $d_det_final . '" class = "bi bi-card-list btn btn-warning btn-sm"> Detalle</a></td>
                    </tr>';
            }
            $html .= '</tbody>
                    </table>
                </div>
            </div>';
            
            return $html;
        }

        public function get_detail_medicamento($id){
            $sql = "SELECT * FROM medicamentos WHERE MedicamentoID = $id";
            $res = $this->con->query($sql);
            $row = $res->fetch_assoc();

            $html = '

                        <div class="container-fluid container-md">
			            <div class="table-responsive">
                        <table class="table table-bordered bordered-primary table-striped">
                        <thead class = "table-dark">
                            <tr>
                                <th scope = "col" colspan = "2" class = "text-center">Detalle de Medicamento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>MedicamentoID</th>
                                <td>' . $row['MedicamentoID'] . '</td>
                            </tr>
                            <tr>
                                <th>Nombre</th>
                                <td>' . $row['Nombre'] . '</td>
                            </tr>
                            <tr>
                                <th>Tipo</th>
                                <td>' . $row['Tipo'] . '</td>
                            </tr>
                            <tr>
                                  <th scope = "col" colspan = "2"><a class="btn btn-primary"  href="Medicamentos.php">Regresar</a></th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>';
            return $html;

        }

        public function get_form($id=NULL){
            if($id==NULL){
                $this->MedicamentoID = NULL;
                $this->Nombre = NULL;
                $this->Tipo = NULL;
                
                $flag = "enabled";
                $op = "new";
            }else{
                $sql = "SELECT * FROM medicamentos WHERE MedicamentoID = $id";
                $res = $this->con->query($sql);
                $row = $res->fetch_assoc();
    
                $num = $res->num_rows;
                if($num==0){
                    $mensaje = "tratar de modificar id= ".$id;
                    echo $this->_message_error($mensaje);
                 }else{
                    $this->MedicamentoID = $row['MedicamentoID'];
                    $this->Nombre = $row['Nombre'];
                    $this->Tipo = $row['Tipo'];
                    
                    $flag = "disabled";
                    $op = "update";
                 }
                }
    
                $html = '
                        <form name="consulta" method="POST" action="Medicamentos.php" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="' . $id  . '">
                        <input type="hidden" name="op" value="' . $op  . '">
                        
                        <div class="container-fluid container-md">
			            <div class="table-responsive">
                        <table class="table table-bordered bordered-primary table-striped">
                        <thead class = "table-dark">
                                <tr>
                                    <th colspan="2" class="text-center">DATOS MEDICAMENTOS</th>
                                </tr>
                        </thead>
                                <tr>
                                    <td>Medicamento:</td>
                                    <td><input type="text" class=" form-control" name="Nombre" value="' . $this->Nombre . '" ></td>
                                </tr>
                                <tr>
                                    <td>Tipo:</td>
                                    <td><input  type="text"  class=" form-control" name="Tipo" value="' . $this->Tipo . '" ></td>
                                </tr>
                                <tr>
                                    <th colspan="2"><input type="submit" class="btn btn-primary d-grid gap-2  mx-auto"  name="Guardar" value="GUARDAR"></th>
                                </tr>		
                                </div>
                                </div>										
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
				<th><a href="Medicamentos.php">Regresar</a></th>
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
				<th><a href="Medicamentos.php">Regresar</a></th>
			</tr>
		</table>';
		return $html;
	}


    }


?>


