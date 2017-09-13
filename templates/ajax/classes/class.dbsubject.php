<?php

    //require("class.dbconnection.php");
    
    class DBSubject {
        
        private $db_handle;
        
        function __construct() {
            
            $db_connection = new DBConnection();
            $this->db_handle = $db_connection->connect();
            return $this->db_handle;
            
        }
        
        function hasSubject($subject_code) {
            $data = array($subject_code);
            $query = $this->db_handle->prepare("SELECT subject_name FROM subjects WHERE subject_code = (?)");
            if($query->execute($data)) {
                //$query->fetch(PDO::FETCH_ASSOC)['subject_name'];
                return $query->rowCount();
            }
            return false;
        }
        
        function hasSubjectInDept($subject_code, $dept) {
            $data = array($subject_code, $dept);
            $query = $this->db_handle->prepare("SELECT subject_name FROM subjects WHERE subject_code = (?) AND dept_id = (?)");
            if($query->execute($data)) {
                return $query->rowCount();
            }
            return false;
        }
        
        function hasSubjectInSem($subject_code, $sem) {
            $data = array($subject_code, $sem);
            $query = $this->db_handle->prepare("SELECT subject_name FROM subjects WHERE subject_code = (?) AND semester_no = (?)");
            if($query->execute($data)) {
                return $query->rowCount();
            }
            return false;
        }
        
        function hasSubjectInDeptAndSem($subject_code, $dept, $sem) {
            $data = array($subject_code, $dept, $sem);
            $query = $this->db_handle->prepare("SELECT subject_name FROM subjects WHERE subject_code = (?) AND dept_id = (?) AND semester_no = (?)");
            if($query->execute($data)) {
                return $query->rowCount();
            }
            return false;
        }
        
        function addSubject($subject_data) {
            //$data = array($subject_code, $dept, $subject, $semester, $credits, $total_review);
            $query = $this->db_handle->prepare("INSERT INTO subjects (subject_code, dept_id, subject_name, semester_no, credits, total_review) VALUES (?, ?, ?, ?, ?, ?)");
            if($query->execute($subject_data)) {
                return $query->rowCount();
            }
            return false;
        }
        
        function getSubjectByCode($subject_code) {
            $data = array($subject_code);
            $query = $this->db_handle->prepare("SELECT subject_name FROM subjects WHERE subject_code = (?)");
            if($query->execute($data)) {
                return $query->fetch(PDO::FETCH_ASSOC)['subject_name'];
            }
            return false;
        }
        
        function getSubjectsForTeacher($teacher=null) {
                        
            if(isset($teacher)) {
                
                $data = array($teacher);
                
                $query = $this->db_handle->prepare("SELECT `subjects`.`subject_code`, `subjects`.`subject_name`, `subjects`.`dept_id`, `subjects`.`semester_no` FROM `subjects` JOIN `subject-teacher` ON `subjects`.`subject_code` = `subject-teacher`.`subject_code` WHERE `subject-teacher`.`teacher` = (?) ORDER BY subject_name ASC");
                
                if($query->execute($data)) {
                
                    $result = array();
                    
                    $department = array("1" => "CSE", "2" => "ECE", "3" => "EE", "4" => "IT");

                    while($_data = $query->fetch(PDO::FETCH_ASSOC)) {

                        $result[$department[$_data['dept_id']]."-SEM-".$_data['semester_no']][$_data['subject_code']] = $_data['subject_name'];

                    }

                    return $result;

                }
                
            }
            return false;
        }
        
        function getSubjects($teacher=null, $dept=null, $sem=null) {
                        
            if(isset($teacher, $dept, $sem)) {
                
                $data = array($teacher, $dept, $sem);
                
                $query = $this->db_handle->prepare("SELECT `subjects`.`subject_code`, `subjects`.`subject_name` FROM `subjects` JOIN `subject-teacher` ON `subjects`.`subject_code` = `subject-teacher`.`subject_code` WHERE `subject-teacher`.`teacher` = (?) AND `subject-teacher`.`dept_id` = (?) AND `subject-teacher`.`semester` = (?) ORDER BY subject_name ASC");
                
            } else if(isset($dept, $sem)) {
                
                $data = array($dept, $sem);
            
                $query = $this->db_handle->prepare("SELECT subject_code, subject_name FROM subjects WHERE dept_id = (?) AND semester_no = (?) ORDER BY subject_name ASC");
                
            } else if(isset($teacher)) {
                
                $data = array($teacher);
                
                $query = $this->db_handle->prepare("SELECT `subjects`.`subject_code`, `subjects`.`subject_name` FROM `subjects` JOIN `subject-teacher` ON `subjects`.`subject_code` = `subject-teacher`.`subject_code` WHERE `subject-teacher`.`teacher` = (?) ORDER BY subject_name ASC");
                
            } else if(isset($dept)) {
                
                $data = array($dept);
            
                $query = $this->db_handle->prepare("SELECT subject_code, subject_name FROM subjects WHERE dept_id = (?) ORDER BY subject_name ASC");
                
            } else if(isset($sem)) {
                
                $data = array($sem);
            
                $query = $this->db_handle->prepare("SELECT subject_code, subject_name FROM subjects WHERE semester_no = (?) ORDER BY subject_name ASC");
                
            } else {
                
                $data = array();
                
                $query = $this->db_handle->prepare("SELECT DISTINCT `subject_code`, `subject_name` FROM `subjects` ORDER BY `subject_name` ASC");
                
            }
            
            if($query->execute($data)) {
                
                $result = array();
                
                while($_data = $query->fetch(PDO::FETCH_ASSOC)) {
                    
                    $result[$_data['subject_code']] = $_data['subject_name'];
                    
                }
                
                return $result;
                
            }
            return false;
        }
        
        function getMatchedSubjects($subject_term) {
            
            //$data = array($term);
            
            $query = $this->db_handle->prepare("SELECT subject_code, subject_name FROM `subjects` WHERE `subject_name` LIKE '%".$subject_term."%' ORDER BY `subject_name` ASC");
            
            //$execute = $query->execute();
            
            if($query->execute()) {
                
                $result = array();
                
                while($_data = $query->fetch(PDO::FETCH_ASSOC)) {
                    
                    $result[] = $_data;
                    
                }
                
                return $result;
                
            }
            
            return false;
            
        }
        
        
        function removeSubject($subject_code) {
            $data = array($subject_code);
            $query = $this->db_handle->prepare("DELETE FROM subjects WHERE subject_code = (?)");
            if($query->execute($data)) {
                return $query->rowCount();
            }
            return false;
        }
        
    }

    //$s = new DBSubject();
    //echo "<pre>";
    //print_r($s->getSubjects(null, 1, 8));
    //print_r($s->hasSubjectForSem('CS302',3));

?>