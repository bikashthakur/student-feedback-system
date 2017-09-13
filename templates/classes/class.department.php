<?php

    //require("class.dbconnection.php");

    class Department {
        
        function __construct() {
            
            $db_connection = new DBConnection();
            $this->db_handle = $db_connection->connect();
            return $this->db_handle;
            
        }
        
        function getDepartment() {
            
            $department = array();
            
            $query = $this->db_handle->prepare("SELECT dept_id, dept_name FROM departments ORDER BY dept_id ASC");
            $query->execute();
            while($data = $query->fetch(PDO::FETCH_ASSOC)) {
                
                $department[$data['dept_id']] = $data['dept_name'];
                
            }
            
            return $department;
            
        }
                    
    }
    
    //$dept = new Department();
    //print_r($dept->getDepartment());

?>