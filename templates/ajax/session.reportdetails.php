<?php

    session_start();

    if(isset($_GET['session']) && ($_GET['session'] === 'report-details')) {
        
        $reportDetails = $_SESSION['report-details'];
        print_r(json_encode($reportDetails));
        
    }

?>