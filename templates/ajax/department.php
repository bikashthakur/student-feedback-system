<?php

    require("classes/class.dbconnection.php");
    require("classes/class.dbsubject.php");
    require("classes/class.dbteacher.php");

    if(isset($_GET['teacher']) && !empty($_GET['teacher'])) {

        $_teacher = $_GET['teacher'];

        $teacher = new DBTeacher();

        if($teacher->hasTeacher($_teacher)) {

            print_r(json_encode($teacher->getDeptForTeacher($_teacher)));

        } else {

            print_r(json_encode(false));

        }

    }

?>