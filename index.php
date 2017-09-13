<?php
    session_start();
    $page = "home";
    unset($_SESSION['form-1'],$_SESSION['form-2'],$_SESSION['form-3']);
?>    
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home | Student Feedback System</title>
    <link href="favicon.ico" rel="icon" type="image/ico">
    <script src="static/js/jquery-3.1.0.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="static/js/jquery-ui.js"></script>
    <link type="text/css" href="static/css/jquery-ui.css" rel="stylesheet">
    <link type="text/css" href="static/css/header.css" rel="stylesheet">
    <link type="text/css" href="static/css/footer.css" rel="stylesheet">
    <link type="text/css" href="static/css/style.css" rel="stylesheet">
    <link type="text/css" href="static/css/jquery-ui.icon-font.css" rel="stylesheet">
    <style>
    
        
    
    </style>
</head>
 <body class="fp-body">
     
     <section id="header-section">
     
        <?php include("templates/header.php"); ?>
     
     </section>
     
     <section id="fp-content-section">
         
        <main id="fp-main">
                
            <ul id="fp-goto">
                
                <li id="getfeedback">
                    <a href="feedback">Give Feedback</a>
                </li>
                
                <li id="getfeedbackreport">
                    <a href="feedback-report">View Report</a>
                </li>
                
            </ul>
                
        </main>         
    </section>
     
    <section id="fp-footer-section">
        <?php include "templates/footer.php"; ?>
    </section>

</body>
    

</html>