<?php
    class PermisosModel extends Mysql{
        public $intIdpermiso;
		public $intRolId;
		public $intModuloId;
		public $r;
		public $w;
		public $u;
		public $d;

        public function __construct()
        {
            parent::__construct();
        }

        public function selectModulos()
		{
			$sql = "SELECT * FROM modulo WHERE status != 0";
			$request = $this->select_all($sql);
			return $request;
		}	
		public function selectPermisosRol(int $idrol)
		{
			$this->intRolId = $idrol;
			$sql = "SELECT * FROM permisos WHERE rolid = $this->intRolId";
			$request = $this->select_all($sql);
			return $request;
		}

		public function deletePermisos(int $idrol){
			$this->intRolId = $idrol;
			$sql = "DELETE FROM permisos WHERE rolid = $this->intRolId";
			$request = $this->delete($sql);
			return $request;
		}

		public function insertPermisos(int $idrol, int $idmodulo, int $r, int $w, int $u, int $d){
			$this->intRolId = $idrol;
			$this->intModuloId = $idmodulo;
			$this->r = $r;
			$this->w = $w;
			$this->u = $u;
			$this->d = $d;
			$query_insert  = "INSERT INTO permisos(rolid,moduloid,r,w,u,d) VALUES(?,?,?,?,?,?)";
        	$arrData = array($this->intRolId, $this->intModuloId, $this->r, $this->w, $this->u, $this->d);
        	$request_insert = $this->insert($query_insert,$arrData);		
	        return $request_insert;
		}
    }
?>