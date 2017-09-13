<?php

    //require("class.dbconnection.php");

    class DBQuestion {
        
        private $db_handle;
        private $table;
        
        function __construct($_table) {
            $db_connection = new DBConnection();
            $this->db_handle = $db_connection->connect();
            $this->table = $_table;
            return $this->db_handle;
        }
        
        function getAllQuestions() {
            $query = $this->db_handle->prepare("SELECT ques_id, question FROM `$this->table`");
            $query->execute();
            $result = array();
            while($data = $query->fetch(PDO::FETCH_ASSOC)) {
                $result[$data['ques_id']] = $data['question'];
            }
            return $result;
        }
        
        function getOptions($ques_id) {
            $data = array($ques_id);
            $query = $this->db_handle->prepare("SELECT option1, option2, option3 FROM `$this->table` WHERE ques_id = (?)");
            $query->execute($data);
            return $query->fetch(PDO::FETCH_ASSOC);
        }
        
        function totalQuestions() {
            $query = $this->db_handle->prepare("SELECT COUNT(question) as 'total-questions' FROM `$this->table`");
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC)['total-questions'];
        }

        function getAllQuestionsWithOptions() {

            $questions = $this->getAllQuestions();

            $result = [];

            foreach($questions as $ques_no => $ques) {
                
                $result[$ques] = $this->getOptions($ques_no);

            }

            return $result;

        }

    }

    /*$q = new DBQuestion('form-subject');
    print_r($q->totalQuestions());
    print_r($q->getAllQuestions());

    echo "<br>";
    $q = new DBQuestion('form-teaching');
    print_r($q->totalQuestions());
    print_r($q->getAllQuestions());*/

    /*$q = new DBQuestion('form-subject');

    $questions = $q->getQuestionsWithResponse();

    foreach($questions as $ques => $options) {

        echo $ques;
        echo "<br>";

        foreach($options as $option_key => $option_val) {

            echo $option_key."  ".$option_val;
            echo "<br>";

        }

    }*/

    //print_r(json_encode($q->getQuestionsWithResponse()));

    /*$questions = $q->getAllQuestions();

    $result = [];
    
    $i = 1;
    foreach($questions as $ques_no => $ques) {
        $result[$ques] = $q->getOptions($i++);
    }
    
    print_r(json_encode($result));*/
    
    //$q = new DBQuestion('form-teaching');
    //print_r(json_encode($q->getAllQuestionsWithOptions()));
    
?>