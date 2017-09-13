<?php

    //require("class.dbconnection.php");
    
    class DBTeacherSubject {
        
        private $db_handle;
        
        function __construct() {
            
            $db_connection = new DBConnection();
            $this->db_handle = $db_connection->connect();
            return $this->db_handle;
            
        }
        
        function addData($subject, $teacher) {
            
            $data = array($subject, $teacher);
            
            $query = $this->db_handle->prepare("INSERT INTO `subject-teacher` (subject, teacher) VALUES (?, ?)");
            
            if($query->execute($data)) {
                
                return $query->rowCount();
                
            }
            
            return false; 
            
        }
        
        function removeData($subject, $teacher) {
            
            $data = array($subject, $teacher);
            
            $query = $this->db_handle->prepare("DELETE FROM `subject-teacher` WHERE subject = (?) AND teacher = (?)");
            
            if($query->execute($data)) {
                
                return $query->rowCount();
                
            }
            
            return false; 
            
        }
        
        function isDataCorrect($subject, $teacher) {
                        
            $data = array($subject, $teacher);
            
            $query = $this->db_handle->prepare("SELECT subject, teacher FROM `subject-teacher` WHERE subject = (?) AND teacher = (?)");
            
            if($query->execute($data)) {
                
                return $query->rowCount();
                
            }
            
            return false;            
            
        }
        
    }

    //$tsub = new DBTeacherSubject();
    
    //print_r($tsub->removeData('cs3402', 'ah'));

?>