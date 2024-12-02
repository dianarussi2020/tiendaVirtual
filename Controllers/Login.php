<?php 
	class Login extends Controllers{
		public function __construct()
		{
			// necesario para que las variables de sesion creadas funcione
			session_start();
			if(isset($_SESSION['login'])){ // si existe la var session
				header('location: '.base_url().'/dashboard');
			}
			parent::__construct();
		}
		public function login()
		{
			$data['page_tag'] = "Login - Tienda Virtual";
			$data['page_title'] = "Tienda Virtual";
			$data['page_name'] = "login";
			$data['page_functions_js'] = "functions_login.js";
			$this->views->getView($this,"loginView",$data);
		}
		
		public function loginUser(){
			if($_POST){
				if(empty($_POST['txtEmail']) || empty($_POST['txtPassword'])) {
					$arrResponse = array("status" => false, "msg" => 'Error de datos.');
				}else{ 
					$strUsuario = strtolower(strClean($_POST['txtEmail']));
					$strPassword = hash("SHA256",$_POST['txtPassword']);
					$requestUser = $this->model->loginUser($strUsuario,$strPassword);
					if(empty($requestUser)){
						$arrResponse = array(
							'status'=>false, 
							'msg'=> 'El usuario o la contraseña es incorrecto'
						);
					}else{
						$arrData = $requestUser;
						if($arrData['status'] == 1){
							//usuario activo crear variable sesion
							$_SESSION['idUser'] = $arrData['idpersona'];
							$_SESSION['login'] = true;
							// Crear var de sesion y almacenar datos de usuario en arrdata
							$arrData = $this->model->sessionLogin($_SESSION['idUser']);
							$_SESSION['userData'] = $arrData;
							$arrResponse = array(
								'status'=>true, 
								'msg'=> 'ok'
							);
						}else{
							//usuario inactivo
							$arrResponse = array(
								'status'=>false, 
								'msg'=> 'Usuario inactivo'
							);
						}
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die(); // detiene el proceso
		}
	}
 ?>