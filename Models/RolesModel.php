<?php
    class RolesModel extends Mysql{ 
        //propiedades de rol
        public $intIdRol;
        public $strRol;
        public $strDescripcon;
        public $intStatus;

        public function __construct()
        {
            parent::__construct();
        }

        public function selectRoles()
        {
            $sql = "SELECT * FROM rol WHERE status !=0";
            $request = $this->select_all($sql);
            return $request;
        }

        public function selectRol(int $idrol) {
            // Buscar rol
            $this->intIdRol = $idrol;
            $sql = "SELECT * FROM rol WHERE id = $this->intIdRol";
            $request = $this->select($sql);
            return $request;
        }

        public function insertRol(string $rol, string $descripcion, int $status){
            $return = "";
            $this->strRol = $rol; #propiedad->parametro
            $this->strDescripcon = $descripcion;
            $this->intStatus = $status;
            $sql = "SELECT * FROM rol WHERE nombrerol = '{$this->strRol}'";
            $request = $this->select_all($sql);
            if(empty($request)){
                $query_insert = "INSERT INTO rol(nombrerol,descripcion,status) 
                    VALUES(?,?,?)";
                $arrData = array($this->strRol, $this->strDescripcon, $this->intStatus);
                $request_insert = $this->insert($query_insert,$arrData);
                $return = $request_insert;
            }else{
                $return = "exist";
            }
            return $return;
        }

        public function updateRol(int $idrol, string $rol, string $descripcion, int $status){
			$this->intIdRol = $idrol;
			$this->strRol = $rol;
			$this->strDescripcon = $descripcion;
			$this->intStatus = $status;

			$sql = "SELECT * FROM rol WHERE nombrerol = '$this->strRol' AND id != $this->intIdRol";
			$request = $this->select_all($sql);

			if(empty($request))
			{
				$sql = "UPDATE rol SET nombrerol = ?, descripcion = ?, status = ? WHERE id = $this->intIdRol ";
				$arrData = array($this->strRol, $this->strDescripcon, $this->intStatus);
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
		    return $request;			
		}

        public function deleteRol(int $idrol)
		{
			$this->intIdRol = $idrol;
			$sql = "SELECT * FROM persona WHERE rolid = $this->intIdRol";
			$request = $this->select_all($sql);
			if(empty($request))
			{
				$sql = "UPDATE rol SET status = ? WHERE id = $this->intIdRol ";
				$arrData = array(0);
				$request = $this->update($sql,$arrData);
				if($request)
				{
					$request = 'ok';	
				}else{
					$request = 'error';
				}
			}else{
				$request = 'exist';
			}
			return $request;
		}
    }
?>