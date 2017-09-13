<?php

    class DBConnection {
        private $db_handle;
        private $db_host = 'localhost';
        private $db_name = 'studentfeedbacksystem';
        private $db_user = 'root';
        private $db_pass = '******';
        
        function connect() {
            try{
                $this->db_handle = new PDO("mysql:host=$this->db_host; dbname=$this->db_name", $this->db_user, $this->db_pass);
                return $this->db_handle;
            } catch(PDOException $e) {
                return $e->getMessage();
            }
        }
    }

?>