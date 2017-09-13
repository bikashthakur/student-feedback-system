<?php

    require("classes/class.dbconnection.php");
    require("classes/class.dbquestion.php");
    require("classes/class.dbsubject.php");
    require("classes/class.dbteacher.php");
    require("classes/class.dbquestionsubject.php");
    require("classes/class.dbfeedbacksubject.php");
    require("classes/class.dbquestionteaching.php");
    require("classes/class.dbfeedbackteaching.php");
    
    //require("classes/class.managesession.php");

    if(isset($_GET['report-type']) && !empty($_GET['report-type'])) {
        
        $reportType = $_GET['report-type'];
        
        switch($reportType) {
                
            case "teacher":
                $teacher = $_GET['teacher'];
                $subject_code = $_GET['subject_code'];
                $dept = $_GET['dept'];
                $sem = $_GET['sem'];
                $report = new DBFeedbackTeaching();
                $reportData = $report->getFeedbackReport($teacher, $subject_code, $dept, $sem);
                break;

            case "teacher-sub":
                $teacher = $_GET['teacher'];
                $subject_code = $_GET['subject_code'];
                $report = new DBFeedbackTeaching();
                $reportData = $report->getFeedbackReport($teacher, $subject_code);
                break;
                
            case "teacher-dept":
                $teacher = $_GET['teacher'];
                $dept = $_GET['dept'];
                $report = new DBFeedbackTeaching();
                $reportData = $report->getFeedbackReport($teacher, null, $dept);
                break;
                
            case "teacher-sem":
                $teacher = $_GET['teacher'];
                $sem = $_GET['sem'];
                $report = new DBFeedbackTeaching();
                $reportData = $report->getFeedbackReport($teacher, null, null, $sem);
                break;
                
            case "teacher-dept-sem":
                $teacher = $_GET['teacher'];
                $subject_code = $_GET['subject_code'];
                $dept = $_GET['dept'];
                $sem = $_GET['sem'];
                $report = new DBFeedbackTeaching();
                $reportData = $report->getFeedbackReport($teacher, $subject_code, $dept, $sem);
                break;
                
            case "subject":
                $subject_code = $_GET['subject_code'];
                $report = new DBFeedbackSubject();
                $reportData = $report->getFeedbackReport($subject_code);
                break;
                
            case "subject-dept-sem":
                
                $subject_code = $_GET['subject_code'];
                $dept = $_GET['dept'];
                $sem = $_GET['sem'];
                
                $report = new DBFeedbackSubject();
                $reportData = $report->getFeedbackReport($subject_code, $dept, $sem);
                break;
                
        }
        
        print_r(json_encode($reportData));
        
    }

?>