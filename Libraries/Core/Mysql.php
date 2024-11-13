<?php
    class Mysql extends Conexion{
        private $strquery;
        private $arrvalues;
        public function __construct(){
            # Llama al constructor de Conexion para inicializar la conexión
			parent::__construct();
		}
        // Insertar un registro
        public function insert(string $query, array $arrvalues) {
            $this->strquery = $query;
            $this->arrvalues = $arrvalues;
    
            if ($this->conect instanceof PDO) { // Verifica que la conexión es un objeto PDO
                $insert = $this->conect->prepare($this->strquery);
                $resInsert = $insert->execute($this->arrvalues);
                if ($resInsert) {
                    $lastInsert = $this->conect->lastInsertId();
                } else {
                    $lastInsert = 0;
                }
                return $lastInsert;
            } else {
                echo "Error: No se pudo establecer la conexión con la base de datos.";
                return 0;
            }
        }

        //Busca un registro
        public function select(string $query) {
            $this->strquery = $query;
            if ($this->conect instanceof PDO) {
                $result = $this->conect->prepare($this->strquery);
                $result->execute();
                $data = $result->fetch(PDO::FETCH_ASSOC);
                return $data;
            } else {
                echo "Error: No se pudo establecer la conexión con la base de datos.";
                return null;
            }
        }

        //Devuelve todos los registros
        public function select_all(string $query) {
            $this->strquery = $query;
            if ($this->conect instanceof PDO) {
                $result = $this->conect->prepare($this->strquery);
                $result->execute();
                $data = $result->fetchall(PDO::FETCH_ASSOC);
                return $data;
            } else {
                echo "Error: No se pudo establecer la conexión con la base de datos.";
                return null;
            }
        }

        //Actualiza registros
        public function update(string $query, array $arrvalues)
		{
			$this->strquery = $query;
            $this->arrvalues = $arrvalues;
            if ($this->conect instanceof PDO) {
			    $update = $this->conect->prepare($this->strquery);
			    $resExecute = $update->execute($this->arrvalues);
			    return $resExecute;
            } else {
                echo "Error: No se pudo establecer la conexión con la base de datos.";
                return null;
            }
		}

        //Eliminar registros
        public function delete(string $query,array $arrvalues = [])
        {
            $this->strquery = $query;
            if ($this->conect instanceof PDO) {
                $result = $this->conect->prepare($this->strquery);
                $del = $result->execute($arrvalues);
                return $del;
            } else {
                echo "Error: No se pudo establecer la conexión con la base de datos.";
                return null;
            }
        }
    }
?>