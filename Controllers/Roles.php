<?php
    class Roles extends Controllers {
        public function __construct()
        {
            parent::__construct();
        }
    
        public function roles() {
            $data['page_id'] = 3;
            $data['page_tag'] = "Roles Usuario";
            $data['page_title'] = "Roles";
            $data['page_name'] = "rol_usuario";
            $this->views->getView($this, "rolesView", $data);
        }

        public function getRoles(){
            $arrData = $this->model->selectRoles();
            #dep($arrData[0]['status']);exit; #obtiene valor 1 - activo
            for ($i=0; $i < count($arrData); $i++) {
                if($arrData[$i]['status'] == 1)
                {
                    $arrData[$i]['status'] = '<span class="badge badge-success">Activo</span>';
                }else{
                    $arrData[$i]['status'] = '<span class="badge badge-danger">Inactivo</span>';
                }

                $arrData[$i]['options'] = 
                '<div class="text-center">
                    <button class="btn btn-secondary btn-sm btnPermisosRol" rl="'.$arrData[$i]['id'].'" title="Permisos"><i class="fas fa-key"></i></button>
                    <button class="btn btn-primary btn-sm btnEditRol" rl="'.$arrData[$i]['id'].'" title="Editar"><i class="fas fa-pencil-alt"></i></button>
                    <button class="btn btn-danger btn-sm btnDelRol" rl="'.$arrData[$i]['id'].'" title="Eliminar"><i class="far fa-trash-alt"></i></button>
                </div>';
                }
            echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
            die();#finalizar procesos
        }

        public function getRol(int $idrol){
            $intIdRol = intval(strClean($idrol));
            if($intIdRol > 0){
                $arrData = $this->model->selectRol($intIdRol);
                if (empty($arrData)) {
                    $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados.');
                } else {
                    $arrResponse = array('status' => true, 'data' => $arrData);
                }
                echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            }
            die();
        }

        public function setRol(){
            $intIdRol = intval($_POST['idRol']);
            $strRol = strClean($_POST['txtNombre']);
            $strDescripcion = strClean($_POST['txtDescripcion']);
            $intStatus = intval($_POST['listStatus']);

            if($intIdRol == 0){
                //crear
                $request_rol = $this->model->inseertRol($strRol, $strDescripcion,$intStatus);
                $option = 1;
            }else{
                //actualizar
                $request_rol = $this->model->updateRol($intIdRol, $strRol, $strDescripcion,$intStatus);
                $option = 2;
            }

            if($request_rol > 0){
                if($option == 1){
                    $arrResponse = array('status'=>true, 'msg'=>'Datos guardados correctamente.');
                }else{
                    $arrResponse = array('status'=>true, 'msg'=>'Datos actualizados correctamente.');
                }
            }else if($request_rol =='exist'){
                $arrResponse = array('status'=>false, 'msg'=>'¡Atención! El rol ya existe.');
            }else{
                $arrResponse = array('status'=>false, 'msg'=>'No es posible guardar los datos.');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            die();
        }

        public function delRol()
		{
			if($_POST){
				$intIdrol = intval($_POST['id']);
				$requestDelete = $this->model->deleteRol($intIdrol);
				if($requestDelete == 'ok')
				{
					$arrResponse = array('status' => true, 'msg' => 'Se ha eliminado el Rol');
				}else if($requestDelete == 'exist'){
					$arrResponse = array('status' => false, 'msg' => 'No es posible eliminar un Rol asociado a usuarios.');
				}else{
					$arrResponse = array('status' => false, 'msg' => 'Error al eliminar el Rol.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}
    }
?>