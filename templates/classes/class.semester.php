<?php

    //require("class.dbconnection.php");

    class Semester {
        
        private $db_handle;
        
        function __construct() {
            
            $db_connection = new DBConnection();
            $this->db_handle = $db_connection->connect();
            return $this->db_handle;
            
        }
        
        function getSemester() {
            
            $month = date('m');
            
            $semester = array();
                        
            //ALWAYS RETURNS THE EVEN SEMESTER (2, 4, 6, 8), AS THE DATA FOR ONLY THESE SEMS ARE AVAILABLE IN DB
            
            //if($month <= 6) {
            
            if(true) {
                
                //$semester = ['2' => 'Two', '4' => 'Four', '6' => 'Six', '8' => 'Eight'];
                $query = $this->db_handle->prepare("SELECT semester_no, semester FROM semesters WHERE semester_no % 2 = 0 ORDER BY semester_no ASC");
                $query->execute();
                while($data = $query->fetch(PDO::FETCH_ASSOC)) {
                    
                    $semester[$data['semester_no']] = $data['semester'];
                    
                }
                
            } else {
                
                //$semester = ['1' => 'One', '3' => 'Three', '5' => 'Five', '7' => 'Seven'];
                $query = $this->db_handle->prepare("SELECT semester_no, semester FROM semesters WHERE semester_no % 2 = 1 ORDER BY semester_no ASC");
                $query->execute();
                while($data = $query->fetch(PDO::FETCH_ASSOC)) {
                    
                    $semester[$data['semester_no']] = $data['semester'];
                    
                }
                
            }
            
            return $semester;
            
        }
                    
    }
    
    /*$sem = new Semester();
    $semesters = $sem->getSemester();
    echo "<pre>";
    print_r($semesters);*/

?>