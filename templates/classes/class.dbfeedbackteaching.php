<?php

    class DBFeedbackTeaching {
        
        private $db_handle;
        
        function __construct() {
            
            $db_connection = new DBConnection();
            $this->db_handle = $db_connection->connect();
            return $this->db_handle;
            
        }
        
        function getSubjects($teacher) {
            
            $data = array($teacher);
            
            $query = $this->db_handle->prepare("SELECT `feedback-teaching`.`subject_code`, `subject_name`, `department`, `semester` FROM `feedback-teaching` JOIN `subjects` ON  `feedback-teaching`.`subject_code` = `subjects`.`subject_code` WHERE `teacher` = (?) GROUP BY `department`, `semester`, `feedback-teaching`.`subject_code` ");
            
            if($query->execute($data)) {
                
                $result = array();
                
                $dept = array(
                
                    "1" =>  "CSE",
                    "2" =>  "ECE",
                    "3" =>  "EE",
                    "4" =>  "IT",
                
                );
                
                while($data = $query->fetch(PDO::FETCH_ASSOC)) {
                    
                    $result[$dept[$data['department']]."-SEM-".$data['semester']][$data['subject_code']] = $data['subject_name'];
                    
                }
                
                return $result;
                
            }
            
            return false;
            
        }

        function getFeedbackByTeacher($teacher) {
            
            $data = array($teacher);
            
            $query = $this->db_handle->prepare("SELECT * FROM `feedback-teaching` WHERE teacher = (?)");
            
            if($query->execute($data)) {
            
                return $query->fetch(PDO::FETCH_ASSOC);
            }
            
            return false;
            
        }
        
        function getFeedbackByQuestionAvg($qno, $teacher=null, $subject_code=null, $dept=null, $sem=null) {
            
            $qNoPoint = 'q'.$qno.'Point';
            
            if(isset($teacher, $dept, $sem)) {

                $data = array($teacher, $dept, $sem);

                $query = $this->db_handle->prepare("SELECT AVG(`".$qNoPoint."`) AS `AvgPoint` FROM `feedback-teaching` WHERE teacher = (?) AND department = (?) AND semester = (?)");

            } else if(isset($teacher, $dept)) {

                $data = array($teacher, $dept);

                $query = $this->db_handle->prepare("SELECT AVG(`".$qNoPoint."`) AS `AvgPoint` FROM `feedback-teaching` WHERE teacher = (?) AND department = (?)");

            } else if(isset($teacher, $sem)) {

                $data = array($teacher, $sem);

                $query = $this->db_handle->prepare("SELECT AVG(`".$qNoPoint."`) AS `AvgPoint` FROM `feedback-teaching` WHERE teacher = (?) AND semester = (?)");

            }  else if(isset($teacher, $subject_code)) {

                $data = array($teacher, $subject_code);

                $query = $this->db_handle->prepare("SELECT AVG(`".$qNoPoint."`) AS `AvgPoint` FROM `feedback-teaching` WHERE teacher = (?) AND subject_code = (?)");

            } else {
                
                $data = array($teacher);

                $query = $this->db_handle->prepare("SELECT AVG(`".$qNoPoint."`) AS `AvgPoint` FROM `feedback-teaching` WHERE teacher = (?)");
                
            }

            if($query->execute($data)) {

                return $query->fetch(PDO::FETCH_ASSOC)['AvgPoint'];
            }
            
            return false;
            
        }
        
        function getFeedbackByQuestion($qno, $teacher=null, $subject_code=null, $dept=null, $sem=null) {
            
            $qNoResponse = 'q'.$qno.'Response';
            $qNoPoint = 'q'.$qno.'Point';
            
            if(isset($teacher, $subject_code, $dept, $sem) && !empty($teacher) && !empty($subject_code) && !empty($dept) && !empty($sem)){
                
                $total_feedbacks = $this->totalFeedbacks($teacher, $subject_code, $dept, $sem);
                
                $data = array($teacher, $subject_code, $dept, $sem);
            
                $query = $this->db_handle->prepare("SELECT $qNoResponse, Count($qNoResponse) as 'count' FROM `feedback-teaching` WHERE teacher = (?) AND subject_code = (?) AND department = (?) AND semester = (?) GROUP BY $qNoResponse ORDER BY $qNoPoint DESC");
                
            } else if(isset($teacher, $dept, $sem)) {
                
                $total_feedbacks = $this->totalFeedbacks($teacher, null, $dept, $sem);
                
                $data = array($teacher, $dept, $sem);
            
                $query = $this->db_handle->prepare("SELECT $qNoResponse, Count($qNoResponse) as 'count' FROM `feedback-teaching` WHERE teacher = (?) AND department = (?) AND semester = (?) GROUP BY $qNoResponse ORDER BY $qNoPoint DESC");
                
            } else if(isset($dept)) {
                
                $total_feedbacks = $this->totalFeedbacks($teacher, null, $dept);
                
                $data = array($teacher, $dept);
            
                $query = $this->db_handle->prepare("SELECT $qNoResponse, Count($qNoResponse) as 'count' FROM `feedback-teaching` WHERE teacher = (?) AND department = (?) GROUP BY $qNoResponse ORDER BY $qNoPoint DESC");
                
            } else if(isset($sem)) {
                
                $total_feedbacks = $this->totalFeedbacks($teacher, null, null, $sem);
                
                $data = array($teacher, $sem);
            
                $query = $this->db_handle->prepare("SELECT $qNoResponse, Count($qNoResponse) as 'count' FROM `feedback-teaching` WHERE teacher = (?) AND semester = (?) GROUP BY $qNoResponse ORDER BY $qNoPoint DESC");
                
            } else if(isset($subject_code)) {
                
                $total_feedbacks = $this->totalFeedbacks($teacher, $subject_code);
                
                $data = array($teacher, $subject_code);
            
                $query = $this->db_handle->prepare("SELECT $qNoResponse, Count($qNoResponse) as 'count' FROM `feedback-teaching` WHERE teacher = (?) AND subject_code = (?) GROUP BY $qNoResponse ORDER BY $qNoPoint DESC");
                
            } else {
                
                $total_feedbacks = $this->totalFeedbacks($teacher);
                
                $data = array($teacher);
            
                $query = $this->db_handle->prepare("SELECT $qNoResponse, Count($qNoResponse) as 'count' FROM `feedback-teaching` WHERE teacher = (?) GROUP BY $qNoResponse ORDER BY $qNoPoint DESC");
                
            }
            
            if($query->execute($data)) {

                $result = array();

                $options = array();

                while($data = $query->fetch(PDO::FETCH_ASSOC)) {

                    $result[$data[$qNoResponse]] = round(($data['count']/$total_feedbacks)*100,2);
                    $options[] = $data[$qNoResponse];

                }

                //check if all options are present in the result array, if not then set that manually to 0

                $quesTeaching = new DBQuestionTeaching();

                $_options = $quesTeaching->getOptionsForQuestion($qno);

                foreach($_options as $arrIndx => $option) {

                    if(!in_array($option, $options)) {

                        $result[$option] = 0.00;

                    }

                }

                return $result;
            
            }
            
            return false;
                
        }
        
        /*function getFeedbackByQuestion2($teacher, $subject_code, $qno) {
            
            $qNoResponse = 'q'.$qno.'Response';
            $qNoPoint = 'q'.$qno.'Point';
            
            $data = array($teacher, $subject_code);
            
            $query = $this->db_handle->prepare("SELECT $qNoResponse, Count($qNoResponse) as 'count' FROM `feedback-teaching` WHERE teacher = (?) AND subject_code = (?) GROUP BY $qNoResponse ORDER BY $qNoPoint DESC");
            
            $query->execute($data);
            
            $total_feedbacks = $this->totalFeedbacks($teacher, $subject);
            
            $result = array();
            
            while($data = $query->fetch(PDO::FETCH_ASSOC)) {
                
                $result[$data[$qNoResponse]] = round(($data['count']/$total_feedbacks)*100,2);
                
            }
            
            return $result;
            
        }*/
        
        function getFeedbackByPoint($qno, $teacher=null, $subject_code=null, $dept=null, $sem=null) {

            $qNoPoint = 'q'.$qno.'Point';

            if(isset($teacher, $subject_code, $dept, $sem)) {
                
                $total_feedbacks = $this->totalFeedbacks($teacher, $subject_code, $dept, $sem);

                $data = array($teacher, $subject_code, $dept, $sem);

                $query = $this->db_handle->prepare("SELECT $qNoPoint, Count($qNoPoint) as 'count' FROM `feedback-teaching` WHERE teacher = (?) AND subject_code = (?) AND department = (?) AND semester = (?) GROUP BY $qNoPoint ORDER BY $qNoPoint DESC");

            } else if(isset($teacher, $dept, $sem)) {
                
                $total_feedbacks = $this->totalFeedbacks($teacher, null, $dept, $sem);

                $data = array($teacher, $dept, $sem);

                $query = $this->db_handle->prepare("SELECT $qNoPoint, Count($qNoPoint) as 'count' FROM `feedback-teaching` WHERE teacher = (?) AND department = (?) AND semester = (?) GROUP BY $qNoPoint ORDER BY $qNoPoint DESC");

            } else if(isset($teacher, $dept)) {
                
                $total_feedbacks = $this->totalFeedbacks($teacher, null, $dept, '');

                $data = array($teacher, $dept);

                $query = $this->db_handle->prepare("SELECT $qNoPoint, Count($qNoPoint) as 'count' FROM `feedback-teaching` WHERE teacher = (?) AND department = (?) GROUP BY $qNoPoint ORDER BY $qNoPoint DESC");

            } else if(isset($teacher, $sem)) {
                
                $total_feedbacks = $this->totalFeedbacks($teacher, '', '', $sem);

                $data = array($teacher, $sem);

                $query = $this->db_handle->prepare("SELECT $qNoPoint, Count($qNoPoint) as 'count' FROM `feedback-teaching` WHERE teacher = (?) AND semester = (?) GROUP BY $qNoPoint ORDER BY $qNoPoint DESC");

            } else if(isset($teacher, $subject_code)) {
                
                $total_feedbacks = $this->totalFeedbacks($teacher, $subject_code);
                
                $data = array($teacher, $subject_code);
            
                $query = $this->db_handle->prepare("SELECT $qNoPoint, Count($qNoPoint) as 'count' FROM `feedback-teaching` WHERE teacher = (?) AND subject_code = (?) GROUP BY $qNoPoint ORDER BY $qNoPoint DESC");
                
            } else {
                
                $total_feedbacks = $this->totalFeedbacks($teacher);
                
                $data = array($teacher);
            
                $query = $this->db_handle->prepare("SELECT $qNoPoint, Count($qNoPoint) as 'count' FROM `feedback-teaching` WHERE teacher = (?) GROUP BY $qNoPoint ORDER BY $qNoPoint DESC");
                
            }
            
            if($query->execute($data)) {
            
                $question = new DBQuestion('form-teaching');
                $options = $question->getOptions('question_'.$qno);
                
                $points = array();

                $result = array();

                while($_data = $query->fetch(PDO::FETCH_ASSOC)) {

                    $label = $_data[$qNoPoint];

                    $value = round(($_data['count']/$total_feedbacks)*100,2);
                    
                    if($label === '3') {
                        $label = 'less than 3';
                    }

                    if($label >= 8 && $label <= 10) {

                        $result[$options['option1']][] = ['label' => $label, 'value' => $value];
                        
                        $points[] = $label;

                    } else if($label >= 5 && $label <= 7) {

                        $result[$options['option2']][] = ['label' => $label, 'value' => $value];
                        
                        $points[] = $label;

                    } else {

                        $result[$options['option3']][] = ['label' => $label, 'value' => $value];
                        
                        $points[] = $label;

                    }

                }
                
                //check if all options/points are present in the result array, if not then set that manually to 0
                
                for($point = 3; $point <= 10; $point++) {
                    
                    if(!in_array($point, $points)) {
                        
                        if($point >= 8 && $point <= 10) {

                            $result[$options['option1']][] = ['label' => $point, 'value' => 0.00];

                        } else if($point >= 5 && $point <= 7) {

                            $result[$options['option2']][] = ['label' => $point, 'value' => 0.00];

                        } else {
                            
                            if($point != 3){
                                
                                $result[$options['option3']][] = ['label' => $point, 'value' => 0.00];
                                
                            } else if(!in_array('less than 3', $points)) {
                                
                                $result[$options['option3']][] = ['label' => 'less than 3', 'value' => 0.00];
                                
                            }
                            
                        }
                        
                    }
                    
                }

                return $result;
                
            }
            
            return false;
            
        }
        
        /*function getFeedbackByPoint2($teacher, $subject, $qno) {
            
            $qNoPoint = 'q'.$qno.'Point';
            $data = array($teacher, $subject);
            
            $query = $this->db_handle->prepare("SELECT $qNoPoint, Count($qNoPoint) as 'count' FROM `feedback-teaching` WHERE teacher = (?) AND subject_code = (?) GROUP BY $qNoPoint ORDER BY $qNoPoint DESC");
            
            $query->execute($data);
            
            $total_feedbacks = $this->totalFeedbacks($teacher, $subject);
            
            $question = new DBQuestion('form-teaching');
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
            
        }*/
        
        function totalFeedbacks($teacher=null, $subject_code=null, $dept=null, $sem=null) {
            
            
            if(isset($teacher, $subject_code, $dept, $sem)) {
                
                $data = array($teacher, $subject_code, $dept, $sem);
            
                $query = $this->db_handle->prepare("SELECT COUNT(`teacher`) AS 'total-feedbacks' FROM `feedback-teaching` WHERE teacher = (?) AND subject_code = (?) AND department = (?) AND semester = (?)");
                
            } else if(isset($teacher, $dept, $sem)) {
                
                $data = array($teacher, $dept, $sem);
            
                $query = $this->db_handle->prepare("SELECT COUNT(`teacher`) AS 'total-feedbacks' FROM `feedback-teaching` WHERE teacher = (?) AND department = (?) AND semester = (?)");
                
            } else if(isset($teacher,$dept)) {
                
                $data = array($teacher, $dept);
            
                $query = $this->db_handle->prepare("SELECT COUNT(`teacher`) AS 'total-feedbacks' FROM `feedback-teaching` WHERE teacher = (?) AND department = (?)");
                
            } else if(isset($teacher, $sem)) {
                
                $data = array($teacher, $sem);
            
                $query = $this->db_handle->prepare("SELECT COUNT(`teacher`) AS 'total-feedbacks' FROM `feedback-teaching` WHERE teacher = (?) AND semester = (?)");
                
            } else if(isset($teacher, $subject_code)) {
                
                $data = array($teacher, $subject_code);
            
                $query = $this->db_handle->prepare("SELECT COUNT(`teacher`) AS 'total-feedbacks' FROM `feedback-teaching` WHERE teacher = (?) AND subject_code = (?)");
                
            } else {
                
                $data = array($teacher);
            
                $query = $this->db_handle->prepare("SELECT COUNT(`teacher`) AS 'total-feedbacks' FROM `feedback-teaching` WHERE teacher = (?)");
                
            }
            
            if($query->execute($data)) {
            
                return $query->fetch(PDO::FETCH_ASSOC)['total-feedbacks'];
            }
            
            return false;
            
        }

        function addFeedback($data) {
            
            $query = $this->db_handle->prepare("INSERT INTO `feedback-teaching` (teacher, subject_code, department, semester, q1Response, q1Point, q2Response, q2Point, q3Response, q3Point, q4Response, q4Point, q5Response, q5Point, q6Response, q6Point, q7Response, q7Point, q8Response, q8Point) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            
            if($query->execute($data)) {
            
                return $query->rowCount();
            }
            
            return false;
            
        }
        
        function getFeedbackReportAvg($teacher, $subject_code=null, $dept=null, $sem=null) {
            
            $question = new DBQuestion('form-teaching');
            $questions = $question->getAllQuestions();
            $total_questions = $question->totalQuestions();
            
            $result = array();
            
            if(isset($teacher, $dept, $sem)) {

                for($i = 1; $i <= $total_questions; $i++) {

                    $result[$questions['question_'.$i]] = round($this->getFeedbackByQuestionAvg($i, $teacher, null, $dept, $sem), 2);

                }

            } else if(isset($teacher, $dept)) {

                for($i = 1; $i <= $total_questions; $i++) {

                    $result[$questions['question_'.$i]] = round($this->getFeedbackByQuestionAvg($i, $teacher, null, $dept), 2);

                }

            } else if(isset($teacher, $sem)) {

                for($i = 1; $i <= $total_questions; $i++) {

                    $result[$questions['question_'.$i]] = round($this->getFeedbackByQuestionAvg($i, $teacher, null, null, $sem), 2);

                }

            } else if(isset($teacher, $subject_code)) {

                for($i = 1; $i <= $total_questions; $i++) {

                    $result[$questions['question_'.$i]] = round($this->getFeedbackByQuestionAvg($i, $teacher, $subject_code), 2);

                }

            } else {

                for($i = 1; $i <= $total_questions; $i++) {

                    $result[$questions['question_'.$i]] = round($this->getFeedbackByQuestionAvg($i, $teacher), 2);

                }

            }
            
            return $result;
            
        }
        
        function getFeedbackReport($teacher=null, $subject_code=null, $dept=null, $sem=null) {
            
            $question = new DBQuestion('form-teaching');
            $questions = $question->getAllQuestions();
            $total_questions = $question->totalQuestions();
            
            $result = array();
            
            if(isset($teacher, $subject_code, $dept, $sem) && !empty($teacher) && !empty($subject_code) && !empty($dept) && !empty($sem)) {

                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByQuestion($i, $teacher, $subject_code, $dept, $sem);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }

            } else if(isset($teacher, $dept, $sem)) {

                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByQuestion($i, $teacher, null, $dept, $sem);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }

            } else if(isset($teacher, $dept)) {

                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByQuestion($i, $teacher, null, $dept);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }

            } else if(isset($teacher, $sem)) {

                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByQuestion($i, $teacher, null, null, $sem);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }

            } else if(isset($teacher, $subject_code)) {

                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByQuestion($i, $teacher, $subject_code);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }

            } else {

                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByQuestion($i, $teacher);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }
                
            }
            
            return $result;
            
        }
        
        /*function getFeedbackReport2($teacher, $subject) {
            
            $question = new DBQuestion('form-teaching');
            $questions = $question->getAllQuestions();
            $total_questions = $question->totalQuestions();
            
            $total_feedbacks = $this->totalFeedbacks($teacher, $subject);
            
            $result = array();
            
            for($i = 1; $i <= $total_questions; $i++) {
                
                $feedback_data = $this->getFeedbackByQuestion2($teacher, $subject, $i);
                
                foreach($feedback_data as $key=>$value) {
                    
                    $result[$questions['question_'.$i]][$key] = $value;
                    
                }
                
            }
            
            return $result;
            
        }*/
        
        function getFeedbackReportInPointScale($teacher, $subject_code=null, $dept=null, $sem=null) {
            
            $question = new DBQuestion('form-teaching');
            $questions = $question->getAllQuestions();
            $total_questions = $question->totalQuestions();
            
            $result = array();
            
            if(isset($teacher, $subject_code, $dept, $sem)) {
                
                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByPoint($i, $teacher, $subject_code, $dept, $sem);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }
                
            } else if(isset($teacher, $dept, $sem)) {
                
                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByPoint($i, $teacher, null, $dept, $sem);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }
                
            } else if(isset($teacher, $dept)) {
                
                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByPoint($i, $teacher, null, $dept);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }
                
            } else if(isset($teacher, $sem)) {
                
                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByPoint($i, $teacher, null, null, $sem);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }
                
            } else if(isset($teacher, $subject_code)) {
                
                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByPoint($i, $teacher, $subject_code);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }
                
            } else {
                
                for($i = 1; $i <= $total_questions; $i++) {

                    $feedback_data = $this->getFeedbackByPoint($i, $teacher);

                    foreach($feedback_data as $key=>$value) {

                        $result[$questions['question_'.$i]][$key] = $value;

                    }

                }
                
            }
            
            return $result;
            
        }
        
        function getFeedbackReportAll($teacher=null, $dept=null, $sem=null) {
            
            if(isset($teacher) && !is_null($teacher) && !empty($teacher)) {
                
                $data = array($teacher);
            
                $query = $this->db_handle->prepare("SELECT `feedback-teaching`.`subject_code`, `subject_name`, `department`, `semester`,  AVG(`q1Point`) AS 'q1Avg', AVG(`q2Point`) AS 'q2Avg', AVG(`q3Point`) AS 'q3Avg', AVG(`q4Point`) AS 'q4Avg', AVG(`q5Point`) AS 'q5Avg', AVG(`q6Point`) AS 'q6Avg', AVG(`q7Point`) AS 'q7Avg', AVG(`q8Point`) AS 'q8Avg' FROM `feedback-teaching` JOIN `subjects` ON  `feedback-teaching`.`subject_code` = `subjects`.`subject_code` WHERE `teacher` = (?) GROUP BY `department`, `semester`, `feedback-teaching`.`subject_code` ");
                
            } else if(isset($dept, $sem) && !is_null($dept) && !is_null($sem)) {
            
                $data = array($dept, $sem);
            
                $query = $this->db_handle->prepare("SELECT `teacher`, `feedback-teaching`.`subject_code`, `subject_name`, AVG(`q1Point`) AS 'q1Avg', AVG(`q2Point`) AS 'q2Avg', AVG(`q3Point`) AS 'q3Avg', AVG(`q4Point`) AS 'q4Avg', AVG(`q5Point`) AS 'q5Avg', AVG(`q6Point`) AS 'q6Avg', AVG(`q7Point`) AS 'q7Avg', AVG(`q8Point`) AS 'q8Avg' FROM `feedback-teaching` JOIN `subjects` ON  `feedback-teaching`.`subject_code` = `subjects`.`subject_code` WHERE `department` = (?) AND `semester` = (?) GROUP BY `teacher`, `feedback-teaching`.`subject_code`");
            
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
        
    }

?>