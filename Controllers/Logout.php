<?php 
	class Logout extends Controllers{
		public function __construct()
		{
			session_start();
            session_unset(); //limpiar var sesion
            session_destroy();// destruir var sesion
            header('location: '.base_url().'/login');
		}
    }
?>