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

		public function resetPass(){
			if($_POST){
				error_reporting(0);
				if(empty($_POST['txtEmailReset'])) {
					$arrResponse = array("status" => false, "msg" => 'Error de datos.');
				}else{
					$token = token();//funcion esta en helpers
					$strEmail = strtolower(strClean($_POST['txtEmailReset']));
					$arrData = $this->model->getUserEmail($strEmail);
					if(empty($arrData)){
						$arrResponse = array("status" => false, "msg" => 'Usuario no existente.');
					}else{
						$idpersona = $arrData['idpersona'];
						$nombreUsuario = $arrData['nombres']. ' '.$arrData['apellidos'];
						$url_recovery = base_url().'/login/confirmUser'.$strEmail.'/'.$token;
						$requestUpdate = $this->model->setTokenUser($idpersona,$token);
						$dataUsuario = array(
							"nombreUsuario" => $nombreUsuario, 
							"email" => $strEmail,
							"asunto" => 'Recuperar cuenta - '. NOMBRE_REMITENTE,
							'url_recovery' => $url_recovery);
						if($requestUpdate){
							$sendEmail = sendEmail($dataUsuario,'email_cambioPassword');
							if($sendEmail){
								$arrResponse = array(
									"status" => true, 
									"msg" => 'Se ha enviado un email a tu cuenta de correo para cambiar tu contraseña.');
							}else{
								$arrResponse = array(
									"status" => false, 
									"msg" => 'No es posible realizar el proceso, intenta mas tarde.');	
							}
						}else{
							$arrResponse = array(
								"status" => false, 
								"msg" => 'No es posible realizar el proceso, intenta mas tarde.');
						}
					}
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function confirmUser(string $params){
			if(empty($params)){
				header('location: '.base_url());
			}else{
				$arrParams = explode(',',$params);
				//dep($arrParams);
				$strEmail = strClean($arrParams[0]);
				$strToken = strClean($arrParams[1]);
				$arrResponse = $this->model->getUser($strEmail,$strToken);
				if(empty($arrResponse)){
					header('location: '.base_url());
				}else{
					$data['page_tag'] = "Cambiar contraseña";
					$data['Page_name'] = "cambiar_contrasenia";
					$data['page_title'] = "Cambiar Contraseña";
					$data['email'] = $strEmail;
					$data['token'] = $strToken;
					$data['idpersona'] = $arrResponse['idpersona'];
					$data['page_functions_js'] = "functions_login.js";
					$this->views->getView($this,"cambiarPassView",$data);
				}
			}
			die();
		}

		public function setPassword (){
			if(empty($_POST['idUsuario']) || empty($_POST['txtEmail']) 
				|| empty($_POST['txtToken']) || empty($_POST['txtPassword'])
				|| empty($_POST['txtPasswordConfirm'])){
				$arrResponse = array(
					'status' => false,
					'msg' => 'Error  de datos.');
			}else{
				$intIdpersona = intval($_POST['idUsuario']);
				$strPassword = $_POST['txtPassword'];
				$strPasswordConfirm = $_POST['txtPasswordConfirm'];
				$strEmail = strClean($_POST['txtEmail']);
				$strToken = strClean($_POST['txtToken']);
				if($strPassword != $strPasswordConfirm){
					$arrResponse = array(
						'status' => false,
						'msg' => 'Las contraseñas no son iguales.');
				}else{
					$arrResponseUser = $this->model->getUser($strEmail,$strToken);
					if(empty($arrResponseUser)){
						$arrResponse = array(
							'status' => false,
							'msg' => 'Error de datos.');
					}else{
						$strPassword = hash("SHA256", $strPassword);
						$requestPass = $this->model->insertPassword($intIdpersona,$strPassword);
						if($requestPass){
							$arrResponse = array(
								'status' => true,
								'msg' => 'Contraseña actualizada con éxito.');
						}else{
							$arrResponse = array(
								'status' => false,
								'msg' => 'No es posible realizar el proceso, intente mas tarde.');
						}
					}
				}
			}
			echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
			die();
		}
	}
 ?>