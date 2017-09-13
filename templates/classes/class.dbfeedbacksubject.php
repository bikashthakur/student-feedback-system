<?php

    /*
        require("class.dbconnection.php");
        require("class.dbsubject.php");
        require("class.dbquestion.php");
        require("class.dbquestionsubject.php");
    */
    
    class DBFeedbackSubject {
        
        private $db_handle;
        
        function __construct() {
            
            $db_connection = new DBConnection();
            $this->db_handle = $db_connection->connect();
            
            return $this->db_handle;
            
        }

        function getFeedbackBySubjectCode($subject_code) {
            
            $data = array($subject_code);
            
            $query = $this->db_handle->prepare("SELECT * FROM `feedback-subject` WHERE subject_code = (?)");
            $query->execute($data);
            
            return $query->fetch(PDO::FETCH_ASSOC);
            
        }
        
        function getFeedbackByQuestion($qno, $subect_code=null, $dept=null, $sem=null) {
            
            $qno_response = 'q'.$qno.'Response';
            
            if(isset($subect_code, $dept, $sem)) {
                
                $total_feedbacks = $this->totalFeedbacks($subect_code, $dept, $sem);
                
                $data = array($subect_code, $dept, $sem);
            
                $query = $this->db_handle->prepare("SELECT $qno_response, Count($qno_response) as 'count' FROM `feedback-subject` WHERE subject_code = (?) AND department = (?) AND semester = (?) GROUP BY $qno_response");
                
            } else if(isset($dept, $sem)) {
                
                $total_feedbacks = $this->totalFeedbacks(null, $dept, $sem);
                
                $data = array($dept, $sem);
            
                $query = $this->db_handle->prepare("SELECT $qno_response, Count($qno_response) as 'count' FROM `feedback-subject` WHERE department = (?) AND semester = (?) GROUP BY $qno_response");
                
            } else if(isset($subect_code)) {
                
                $total_feedbacks = $this->totalFeedbacks($subect_code);
                
                $data = array($subect_code);
            
                $query = $this->db_handle->prepare("SELECT $qno_response, Count($qno_response) as 'count' FROM `feedback-subject` WHERE subject_code = (?) GROUP BY $qno_response");
                
                
            }
            
            $query->execute($data);
                        
            $result = array();
            $options = array();
            
            while($data = $query->fetch(PDO::FETCH_ASSOC)) {
                
                $result[$data[$qno_response]] = round(($data['count']/$total_feedbacks)*100,2);
                $options[] = $data[$qno_response];
                
            }
            
            $quesTeaching = new DBQuestionSubject();
            
            $_options = $quesTeaching->getOptionsForQuestion($qno);
            
            foreach($_options as $arrIndx => $option) {
                
                if(!in_array($option, $options)) {
                    
                    $result[$option] = 0.00;
                    
                }
                
            }
            
            return $result;
            
        }
        
        function totalFeedbacks($subect_code=null, $dept=null, $sem=null) {
            
            if (isset($subect_code, $dept, $sem)) {
                
                $data = array($subect_code, $dept, $sem);
            
                $query = $this->db_handle->prepare("SELECT COUNT(`subject_code`) AS 'total-feedbacks' FROM `feedback-subject` WHERE `subject_code` = (?) AND department = (?) AND semester = (?)");
                
            } else if (isset($dept, $sem)) {
                
                $data = array($dept, $sem);
            
                $query = $this->db_handle->prepare("SELECT COUNT(`subject_code`) AS 'total-feedbacks' FROM `feedback-subject` WHERE department = (?) AND semester = (?)");
                
            } else if (isset($dept)) {
                
                $data = array($dept);
            
                $query = $this->db_handle->prepare("SELECT COUNT(`subject_code`) AS 'total-feedbacks' FROM `feedback-subject` WHERE department = (?)");
                
            } else if (isset($sem)) {
                
                $data = array($sem);
            
                $query = $this->db_handle->prepare("SELECT COUNT(`subject_code`) AS 'total-feedbacks' FROM `feedback-subject` WHERE semester = (?)");
                
            } else if (isset($subect_code)) {
                
                $data = array($subect_code);
            
                $query = $this->db_handle->prepare("SELECT COUNT(`subject_code`) AS 'total-feedbacks' FROM `feedback-subject` WHERE `subject_code` = (?)");
                
            }
            
            $query->execute($data);
            
            return $query->fetch(PDO::FETCH_ASSOC)['total-feedbacks'];
            
        }

        /*function addFeedback($data) {
            
            $query = $this->db_handle->prepare("INSERT INTO `feedback-subject` (subject_code, department, semester, q1Response, q2Response, q3Response, q4Response, q5Response, q6Response, comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $query->execute($data);
            
            return $query->rowCount();
            
        }*/
        
        function addFeedback($data) {
            $query = $this->db_handle->prepare("INSERT INTO `feedback-subject` (subject_code, department, semester, q1Response, q1Point, q2Response, q2Point, q3Response, q3Point, q4Response, q4Point, q5Response, q5Point, q6Response, q6Point) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $query->execute($data);
            return $query->rowCount();
        }
        
        function getFeedbackByQuestionAvg($subject_code, $qno, $dept = 0, $sem = 0) {
            
            $qNoPoint = 'q'.$qno.'Point';
            
            if($dept && $sem) {

                $data = array($subject_code, $dept, $sem);

                $query = $this->db_handle->prepare("SELECT AVG(`".$qNoPoint."`) AS `AvgPoint` FROM `feedback-subject` WHERE subject_code = (?) AND department = (?) AND semester = (?)");

            } else if($dept) {

                $data = array($subject_code, $dept);

                $query = $this->db_handle->prepare("SELECT AVG(`".$qNoPoint."`) AS `AvgPoint` FROM `feedback-subject` WHERE subject_code = (?) AND department = (?)");

            } else if($sem) {

                $data = array($subject_code, $sem);

                $query = $this->db_handle->prepare("SELECT AVG(`".$qNoPoint."`) AS `AvgPoint` FROM `feedback-subject` WHERE subject_code = (?) AND semester = (?)");

            } else {
                
                $data = array($subject_code);

                $query = $this->db_handle->prepare("SELECT AVG(`".$qNoPoint."`) AS `AvgPoint` FROM `feedback-subject` WHERE subject_code = (?)");
                
            }

            $query->execute($data);

            return $query->fetch(PDO::FETCH_ASSOC)['AvgPoint'];
            
        }
        
        function getFeedbackByPoint($qno, $subject_code=null, $dept=null, $sem=null) {

            $qNoPoint = 'q'.$qno.'Point';

            if(isset($subject_code, $dept, $sem)) {
                
                $total_feedbacks = $this->totalFeedbacks($subject_code, $dept, $sem);

                $data = array($subject_code, $dept, $sem);

                $query = $this->db_handle->prepare("SELECT $qNoPoint, Count($qNoPoint) as 'count' FROM `feedback-subject` WHERE subject_code = (?) AND department = (?) AND semester = (?) GROUP BY $qNoPoint ORDER BY $qNoPoint DESC");

            } else if(isset($dept, $sem)) {
                
                $total_feedbacks = $this->totalFeedbacks(null, $dept, $sem);

                $data = array($dept, $sem);

                $query = $this->db_handle->prepare("SELECT $qNoPoint, Count($qNoPoint) as 'count' FROM `feedback-subject` WHERE department = (?) AND semester = (?) GROUP BY $qNoPoint ORDER BY $qNoPoint DESC");

            } else if(isset($dept)) {
                
                $total_feedbacks = $this->totalFeedbacks(null, $dept);

                $data = array($dept);

                $query = $this->db_handle->prepare("SELECT $qNoPoint, Count($qNoPoint) as 'count' FROM `feedback-subject` WHERE department = (?) GROUP BY $qNoPoint ORDER BY $qNoPoint DESC");

            } else if(isset($sem)) {
                
                $total_feedbacks = $this->totalFeedbacks(null, null, $sem);

                $data = array($sem);

                $query = $this->db_handle->prepare("SELECT $qNoPoint, Count($qNoPoint) as 'count' FROM `feedback-subject` WHERE semester = (?) GROUP BY $qNoPoint ORDER BY $qNoPoint DESC");

            } else if(isset($subject_code)) {
                
                $total_feedbacks = $this->totalFeedbacks($subject_code);
                
                $data = array($teacher, $subject_code);
            
                $query = $this->db_handle->prepare("SELECT $qNoPoint, Count($qNoPoint) as 'count' FROM `feedback-subject` WHERE subject_code = (?) GROUP BY $qNoPoint ORDER BY $qNoPoint DESC");
                
            }
            
            $query->execute($data);
            
            $question = new DBQuestion('form-subject');
            $options = $question->getOptions('question_'.$qno);
            
            $result = array();
            
            while($_data = $query->fetch(PDO::FETCH_ASSOC)) {
                
                $label = $_data[$qNoPoint];
                
                if($label === '3') {
                    $label = 'less than 3';
                }
                
                $value = round(($_data['count']/$total_feedbacks)*100,2);
                
                if($label <= 10 && $label >= 8) {
                    
                    $result[$options['option1']][] = ['label' => $label, 'value' => $value];
                    
                } else if($label <= 7 && $label >= 5) {
                    
                    $result[$options['option2']][] = ['label' => $label, 'value' => $value];
                    
                } else {
                    
                    $result[$options['option3']][] = ['label' => $label, 'value' => $value];
                    
                }
                
            }
            
            return $result;
            
        }
        
        function getFeedbackReportAvg($subject_code, $dept = 0, $sem = 0) {
            
            $question = new DBQuestion('form-subject');
            $questions = $question->getAllQuestions();
            $total_questions = $question->totalQuestions();
            
            $result = array();
            
            if($dept && $sem) {

                for($i = 1; $i <= $total_questions; $i++) {

                    $result[$questions['question_'.$i]] = round($this->getFeedbackByQuestionAvg($subject_code, $i, $dept, $sem), 2);

                }

            } else if($dept) {

                for($i = 1; $i <= $total_questions; $i++) {

                    $result[$questions['question_'.$i]] = round($this->getFeedbackByQuestionAvg($subject_code, $i, $dept, ''), 2);

                }

            } else if($sem) {

                for($i = 1; $i <= $total_questions; $i++) {

                    $result[$questions['question_'.$i]] = round($this->getFeedbackByQuestionAvg($subject_code, $i, '', $sem), 2);

                }

            } else {
                
                for($i = 1; $i <= $total_questions; $i++) {

                    $result[$questions['question_'.$i]] = round($this->getFeedbackByQuestionAvg($subject_code, $i), 2);

                }
                
            }
            
            return $result;
            
        }
        
        function getFeedbackReport($subect_code=null, $dept=null, $sem=null) {
            
            $question = new DBQuestion('form-subject');
            $questions = $question->getAllQuestions();
            $total_questions = $question->totalQuestions();
                        
            $result = array();
            
            if(isset($subect_code, $dept, $sem)) {
                
                for($i = 1; $i <= $total_questions; $i++) {
                
                    $feedback_data = $this->getFeedbackByQuestion($i, $subect_code, $dept, $sem);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value; 

                    }

                }
                
            } else if(isset($dept, $sem)) {
                
                for($i = 1; $i <= $total_questions; $i++) {
                
                    $feedback_data = $this->getFeedbackByQuestion($i, null, $dept, $sem);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value; 

                    }

                }
                
            } else if(isset($subect_code)) {
                
                for($i = 1; $i <= $total_questions; $i++) {
                
                    $feedback_data = $this->getFeedbackByQuestion($i, $subect_code);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value; 

                    }

                }
                
            }
            
            return $result;
            
        }
        
        function getFeedbackReportInPointScale($subject_code=null, $dept=null, $sem=null) {
            
            $question = new DBQuestion('form-subject');
            $questions = $question->getAllQuestions();
            $total_questions = $question->totalQuestions();
            
            $result = array();
            
            if(isset($subject_code, $dept, $sem)) {
                
                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByPoint($i, $subject_code, $dept, $sem);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }
                
            } else if(isset($dept, $sem)) {
                
                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByPoint($i, null, $dept, $sem);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }
                
            } else if(isset($dept)) {
                
                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByPoint($i, null, $dept);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }
                
            } else if(isset($sem)) {
                
                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByPoint($i, null, null, $sem);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }
                
            } else if(isset($subject_code)) {
                
                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByPoint($i, $subject_code);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }
                
            }
            
            return $result;
            
        }
        
        function getFeedbackReportAll($subject = null, $dept = null, $sem = null) {
            
            if(isset($subject, $dept, $sem) && !empty($subject)) {
                
                $data = array($subject, $dept, $sem);
            
                $query = $this->db_handle->prepare("SELECT `feedback-subject`.`subject_code`, `subject_name`, `department`, `semester`,  AVG(`q1Point`) AS 'q1Avg', AVG(`q2Point`) AS 'q2Avg', AVG(`q3Point`) AS 'q3Avg', AVG(`q4Point`) AS 'q4Avg', AVG(`q5Point`) AS 'q5Avg', AVG(`q6Point`) AS 'q6Avg' FROM `feedback-subject` JOIN `subjects` ON  `feedback-subject`.`subject_code` = `subjects`.`subject_code` WHERE `feedback-subject`.`subject_code` = (?) AND department = (?) AND semester = (?)  GROUP BY `department`, `semester`, `feedback-subject`.`subject_code`");
                
            } else if(isset($dept, $sem)) {
            
                $data = array($dept, $sem);
            
                $query = $this->db_handle->prepare("SELECT `feedback-subject`.`subject_code`, `subject_name`, `department`, `semester`,  AVG(`q1Point`) AS 'q1Avg', AVG(`q2Point`) AS 'q2Avg', AVG(`q3Point`) AS 'q3Avg', AVG(`q4Point`) AS 'q4Avg', AVG(`q5Point`) AS 'q5Avg', AVG(`q6Point`) AS 'q6Avg' FROM `feedback-subject` JOIN `subjects` ON  `feedback-subject`.`subject_code` = `subjects`.`subject_code` WHERE `feedback-subject`.`department` = (?) AND `feedback-subject`.`semester` = (?) GROUP BY `feedback-subject`.`subject_code`, `department`, `semester`");
            
            } else if(isset($subject)) {
            
                $data = array($subject);
            
                $query = $this->db_handle->prepare("SELECT `feedback-subject`.`subject_code`, `subject_name`, `department`, `semester`,  AVG(`q1Point`) AS 'q1Avg', AVG(`q2Point`) AS 'q2Avg', AVG(`q3Point`) AS 'q3Avg', AVG(`q4Point`) AS 'q4Avg', AVG(`q5Point`) AS 'q5Avg', AVG(`q6Point`) AS 'q6Avg' FROM `feedback-subject` JOIN `subjects` ON  `feedback-subject`.`subject_code` = `subjects`.`subject_code` WHERE `feedback-subject`.`subject_code` = (?) GROUP BY `department`, `semester`, `feedback-subject`.`subject_code`");
            
            } else {
                
                $data = array();
                
                $query = $this->db_handle->prepare("SELECT `feedback-subject`.`subject_code`, `subject_name`, `department`, `semester`,  AVG(`q1Point`) AS 'q1Avg', AVG(`q2Point`) AS 'q2Avg', AVG(`q3Point`) AS 'q3Avg', AVG(`q4Point`) AS 'q4Avg', AVG(`q5Point`) AS 'q5Avg', AVG(`q6Point`) AS 'q6Avg' FROM `feedback-subject` JOIN `subjects` ON  `feedback-subject`.`subject_code` = `subjects`.`subject_code` GROUP BY `department`, `semester`, `feedback-subject`.`subject_code`");
                
            }
            
            if($query->execute($data)) {
                
                $result = array();
                
                while($_data = $query->fetch(PDO::FETCH_ASSOC)) {
                    
                    $result[] = $_data;
                    
                }
                
                return $result;
                
            }
            
            return false;
            
        }
        
        /*function getComments($subect_code) {
            
            $data = array($subect_code);
            
            $query = $this->db_handle->prepare("SELECT comment FROM `feedback-subject` WHERE subject_code = (?)");
            $query->execute($data);
            
            $result = array();
            
            while($_data = $query->fetch(PDO::FETCH_ASSOC)) {
                
                if(!empty($_data['comment'])) {
                    $result[] = $_data['comment'];
                }
                
            }
            
            return $result;
            
        }*/
    }

    //$f = new DBFeedbackSubject();
    //echo "<pre>";
    //print_r($f->getFeedbackReportAll("M201"));
    //print_r($f->getFeedbackByQuestion(1, null, 1, 4));
    //print_r($f->getFeedbackByPoint(1, "CS601", 1, 6));
    //print_r($f->getFeedbackReportInPointScale("CS601", 1, 6));

?>