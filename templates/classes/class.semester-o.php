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
            
            $time = time();
            $date = getdate($time);
            $month = $date['mon'];
            
            $semester = array();
                        
            if($month <= 6) {
                
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
    print_r($semesters);
    echo '<br>';
    foreach($semesters as $sem_no => $sem ) {
        
        echo $sem_no.'  '.$sem.'<br>';
        
    }*/

?>