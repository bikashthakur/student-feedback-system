<?php

    require("classes/class.dbconnection.php");
    require("classes/class.dbteacher.php");

    if(isset($_GET['field'], $_GET['subject'], $_GET['dept'], $_GET['sem'])) {
        
        $_subject = $_GET['subject'];
        $_dept = $_GET['dept'];
        $_sem = $_GET['sem'];
        
        $teacher = new DBTeacher();
        
        print_r(json_encode($teacher->getTeachers($_subject, $_dept, $_sem)));
        
    } else  if(isset($_GET['get-list'], $_GET['teacher']) && ($_GET['get-list'] === 'semester')) {
        
        $teachers = new DBTeacher();
        
        print_r(json_encode($teachers->getSemForTeacher($_GET['teacher'])));
        
    } else  if(isset($_GET['get-list'], $_GET['teacher']) && ($_GET['get-list'] === 'department')) {
        
        $teachers = new DBTeacher();
        
        print_r(json_encode($teachers->getDeptForTeacher($_GET['teacher'])));
        
    } else if(isset($_GET['get-list']) && $_GET['get-list'] === 'teachers') {
        
        $teachers = new DBTeacher();
        
        print_r(json_encode($teachers->getTeachers()));
        
    }

?>