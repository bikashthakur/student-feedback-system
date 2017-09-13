<?php

    //require("class.dbconnection.php");

    class DBQuestionTeaching {
        private $db_handle;
        function __construct() {
            $db_connection = new DBConnection();
            $this->db_handle = $db_connection->connect();
            return $this->db_handle;
        }
        
        function getAllQuestions() {
            $query = $this->db_handle->prepare("SELECT question FROM `form-teaching`");
            $query->execute();
            $result = array();
            while($data = $query->fetch(PDO::FETCH_ASSOC)) {
                $result[] = $data['question'];
            }
            return $result;
        }
        
        function getOptionsForQuestion($qNo) {
            
            $ques_id = "question_".$qNo;
            
            $data = array($ques_id);
            
            $query = $this->db_handle->prepare("SELECT option1, option2, option3 FROM `form-teaching` WHERE ques_id = (?)");
            
            $query->execute($data);
            
            $result = array();
            
            while($data = $query->fetch(PDO::FETCH_ASSOC)) {
                
                $result = $data;
                
            }
            return $result;
        }
        
        function totalQuestions() {
            $query = $this->db_handle->prepare("SELECT COUNT(question) as 'total-questions' FROM `form-teaching`");
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC)['total-questions'];
        }
    }

    //$q = new DBQuestionTeaching();
    //print_r($q->getOptionsForQuestion(6));
    
?>