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
        
        function getDeptForTeacher($teacher) {
            
            $data = array($teacher);
            
            $query = $this->db_handle->prepare("SELECT DISTINCT `dept_id` FROM `subject-teacher` WHERE `subject-teacher`.`teacher` = (?) ORDER BY `dept_id` ASC");
            
            if($query->execute($data)) {
                
                $department = array(
                
                    "1" => "CSE",
                    "2" => "ECE",
                    "3" => "EE",
                    "4" => "IT"
                
                );
                
                $result = [];
                
                while($_data = $query->fetch(PDO::FETCH_ASSOC)) {
                    
                    $result[$_data['dept_id']] = $department[$_data['dept_id']];
                    
                }
                
                return $result;
                
            }
            
            return false;
            
        }
        
        function getSubjectsForTeacher($teacher) {

            $data = array($teacher);
            
            $query = $this->db_handle->prepare("SELECT subject_code FROM `subject-teacher` WHERE teacher = (?) ORDER BY subject_code ASC");
            
            if($query->execute($data)) {
                
                $result = array();
                
                while($_data = $query->fetch(PDO::FETCH_ASSOC)) {
                    
                    $result[] = $_data['subject_code'];
                    
                }
                
                return $result;
                
            }
            
            return false;
            
        }
        
        function getTeachersForSubject($subject) {
            
            $data = array($subject);
            
            $query = $this->db_handle->prepare("SELECT teacher FROM `subject-teacher` WHERE subject_code = (?) ORDER BY teacher ASC");
            
            if($query->execute($data)) {
                
                $result = array();
                
                while($_data = $query->fetch(PDO::FETCH_ASSOC)) {
                    
                    $result[] = $_data['teacher'];
                    
                }
                
                return $result;
                
            }
            
            return false;
            
        }
        
    }

    //$tsub = new DBTeacherSubject();
    
    //print_r($tsub->removeData('cs3402', 'ah'));

?>