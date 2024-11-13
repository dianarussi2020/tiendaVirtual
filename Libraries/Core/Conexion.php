<?php
class Conexion{
	protected $conect;# sea visible desde clase mysql

	public function __construct(){
		$connectionString = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
		try{
			$this->conect = new PDO($connectionString, DB_USER, DB_PASSWORD);
			$this->conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			//echo "Conexion exitosa";
		}catch(PDOException $e){
			$this->conect = null;
		    echo "ERROR: " . $e->getMessage();
		}
	}
	public function getConnect(){
		return $this->conect;
	}
}
?>