<?php

    require("classes/class.dbconnection.php");
    require("classes/class.dbsubject.php");

    if(isset($_GET['get-list'], $_GET['teacher']) && !empty($_GET['teacher'])) {

        $_teacher = $_GET['teacher'];

        $subjects = new DBSubject();

        print_r(json_encode($subjects->getSubjects($_teacher)));

    } else if(isset($_GET['get-list']) && ($_GET['get-list'] === 'subjects')) {

        $subjects = new DBSubject();

        print_r(json_encode($subjects->getSubjects()));

    } else if(isset($_GET['semester'], $_GET['department'])) {
        
        $semester = $_GET['semester'];
        $department = $_GET['department'];
        
        $subject = new DBSubject();
        
        print_r(json_encode($subject->getSubjects(null, $department, $semester)));
        
    }

?>