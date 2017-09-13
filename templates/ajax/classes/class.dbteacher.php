<?php

    //require("class.dbconnection.php");

    class DBTeacher {
        
        private $db_handle;
        
        function __construct() {
            
            $db_connection = new DBConnection();
            $this->db_handle = $db_connection->connect();
            return $this->db_handle;
            
        }
        
        function getTeachers($subject_code=null, $dept=null, $sem=null) {
            
            if(isset($subject_code, $dept, $sem)) {
                
                $data = array($subject_code, $dept, $sem);
                
                $query = $this->db_handle->prepare("SELECT teacher FROM `subject-teacher` WHERE subject_code = (?) AND dept_id = (?) AND semester = (?) ORDER BY teacher ASC");
                
            } else if(isset($subject_code, $dept)) {
                
                $data = array($subject_code, $dept);
                
                $query = $this->db_handle->prepare("SELECT DISTINCT teacher FROM `subject-teacher` WHERE subject_code = (?) AND dept_id = (?) ORDER BY teacher ASC");
                
            } else if(isset($subject_code, $sem)) {
                
                $data = array($subject_code, $sem);
                
                $query = $this->db_handle->prepare("SELECT DISTINCT teacher FROM `subject-teacher` WHERE subject_code = (?) AND semester = (?) ORDER BY teacher ASC");
                
            } else if(isset($dept, $sem)) {
                
                $data = array($dept, $sem);
                
                $query = $this->db_handle->prepare("SELECT DISTINCT teacher FROM `subject-teacher` WHERE dept_id = (?) AND semester = (?) ORDER BY teacher ASC");
                
            } else if(isset($dept)) {
                
                $data = array($dept);
                
                $query = $this->db_handle->prepare("SELECT DISTINCT teacher FROM `subject-teacher` WHERE dept_id = (?) ORDER BY teacher ASC");
                
            } else if(isset($sem)) {
                
                $data = array($sem);
                
                $query = $this->db_handle->prepare("SELECT DISTINCT teacher FROM `subject-teacher` WHERE semester = (?) ORDER BY teacher ASC");
                
            } else {
                
                $data = array($dept);
                
                $query = $this->db_handle->prepare("SELECT DISTINCT teacher FROM `subject-teacher` ORDER BY teacher ASC");
                
            }
            
            if($query->execute($data)) {
                
                $result = array();
                
                while($_data = $query->fetch(PDO::FETCH_ASSOC)) {
                    
                    $result[$_data["teacher"]] = $_data['teacher'];
                    
                }
                
                return $result;
                
            }
            
            return false;
            
        }
        
        function getTeacherByName($teacher) {
            
            $data = array($teacher);
            
            $query = $this->db_handle->prepare("SELECT `name-abbr` FROM teachers WHERE `name-abbr` = (?)");
            
            $query->execute($data);
            
            return $query->fetch(PDO::FETCH_ASSOC)['name-abbr'];
            
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
        
        function getTeachersForDeptAndSem($dept=null, $sem=null) {
            
            if(isset($dept) && isset($sem)) {
                
                $data = array($dept, $sem);
                $query = $this->db_handle->prepare("SELECT `teacher`, `subjects`.`subject_code`, `subject_name` FROM `subject-teacher` JOIN `subjects` ON `subject-teacher`.`subject_code` = `subjects`.`subject_code` WHERE `subject-teacher`.`dept_id` = (?) AND `subject-teacher`.`semester` = (?) GROUP BY `subject_code`, `teacher`");
                
            }
            
            if($query->execute($data)) {
                
                $result = array();
                
                while($_data = $query->fetch(PDO::FETCH_ASSOC)) {
                    
                    $result[$_data['teacher']][$_data['subject_code']] = $_data['subject_name'];
                    
                }
                
                return $result;
                
            }
            
            return false;
            
        }
        
        function hasTeacher($teacher) {
            
            $data = array($teacher);
            
            $query = $this->db_handle->prepare("SELECT `name-abbr` FROM teachers WHERE `name-abbr` = (?)");
            
            if($query->execute($data)) {
                
                return $query->rowCount();
                
            }
            
            return false;
            
        }
        
        function searchTeacher($term) {
            
            //$data = array($term);
            
            $query = $this->db_handle->prepare("SELECT `id`, `name-abbr` FROM `teachers` WHERE `name-abbr` LIKE '%".$term."%'");
            
            //$execute = $query->execute();
            
            if($query->execute()) {
                
                $result = array();
                
                while($_data = $query->fetch(PDO::FETCH_ASSOC)) {
                    
                    $result[$_data['id']] = $_data['name-abbr'];
                    
                }
                
                return $result;
                
            }
            
            return false;
            
        }
        
        function addTeacher($teacher) {
            
            $data = array($teacher);
            
            $query = $this->db_handle->prepare("INSERT INTO teachers (`name-abbr`) VALUES (?)");
            
            $query->execute($data);
            
            return $query->rowCount();
            
        }
        
        function removeTeacher($teacher) {
            
            $data = array($teacher);
            
            $query = $this->db_handle->prepare("DELETE FROM teachers WHERE `name-abbr` = (?)");
            
            $query->execute($data);
            
            return $query->rowCount();
            
        }
    }

?>