<?php
    class Dashboard extends Controllers {
        public function __construct()
        {
            parent::__construct();
        }
    
        public function dashboard() {
            $data['page_id'] = 2;
            $data['page_tag'] = "Tienda Virtual";
            $data['page_title'] = "Inicio";
            $data['page_name'] = "dashboard";
            $this->views->getView($this, "dashboardView", $data);
        }
    }
?>