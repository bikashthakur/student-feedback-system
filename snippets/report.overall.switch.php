<?php

    require("templates/classes/class.dbconnection.php");
    require("templates/classes/class.dbquestion.php");
    require("templates/classes/class.dbsubject.php");
    require("templates/classes/class.dbteacher.php");
    require("templates/classes/class.dbfeedbacksubject.php");
    require("templates/classes/class.dbfeedbackteaching.php");
    require("templates/classes/class.managesession.php");

    //if(isset($_GET['feedback'],$_GET['report']) && !empty($_GET['feedback']) && !empty($_GET['report'])) {

    $reportAvg = array();
    $reportPointScale = array();
    $report_on = '';
    $total_feedbacks = '';

    unset($_SESSION['report-details']);

    $_SESSION['report-details']['report-type'] = $report_type;

    switch($report_type) {

        case "teacher":

            $_teacher = $_POST['listTeachers'];
            
            $_SESSION['report-details']['teacher'] = $_teacher;
            
            $report_on = $_teacher;

            $reportTeaching = new DBFeedbackTeaching();
            
            $reportAll = $reportTeaching->getFeedbackReportAll($_teacher);

            break;
            
        case "teacher-dept-sem":
            
            $dept = $_POST['listDept'];
            $sem = $_POST['listSem'];
            
            $_SESSION['report-details']['report-type'] = "teacher-dept-sem";
            $_SESSION['report-details']['dept'] = $dept;
            $_SESSION['report-details']['sem'] = $sem;

            $reportTeaching = new DBFeedbackTeaching();
            $reportAll = $reportTeaching->getFeedbackReportAll(null, $dept, $sem);
            
            break;

        case "subject":

            $_SESSION['report-details']['report-type'] = "subject";
            
            $reportTeaching = new DBFeedbackSubject();
            $reportAll = $reportTeaching->getFeedbackReportAll();
            
            break;
            
        case "subject-dept-sem":
            
            $dept = $_POST['listDept'];
            $sem = $_POST['listSem'];
            
            $_SESSION['report-details']['report-type'] = "subject-dept-sem";
            $_SESSION['report-details']['dept'] = $dept;
            $_SESSION['report-details']['sem'] = $sem;

            $reportTeaching = new DBFeedbackSubject();
            $reportAll = $reportTeaching->getFeedbackReportAll(null, $dept, $sem);
            
            break;

    }

?>