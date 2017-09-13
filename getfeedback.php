<?php
    session_start();
    $page = "feedback";
    unset($_SESSION['form-1'], $_SESSION['form-2'], $_SESSION['form-3']);
    require("templates/classes/class.dbconnection.php");
    require("templates/classes/class.dbquestion.php");
    require("templates/classes/class.semester.php");
    require("templates/classes/class.department.php");
    //include "templates/form-validation.php";
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Write Feedback | Online Feedback System</title>
    <link href="favicon.ico" rel="icon" type="image/ico">
    <script src="static/js/jquery-3.1.0.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="static/js/jquery-ui.js"></script>
    <script type="text/javascript" src="static/js/select2.min.js"></script>
    <link type="text/css" href="static/css/jquery-ui.icon-font.css" rel="stylesheet">
    <link type="text/css" href="static/css/jquery-ui.css" rel="stylesheet">
    <link type="text/css" href="static/css/header.css" rel="stylesheet">
    <link type="text/css" href="static/css/footer.css" rel="stylesheet">
    <link type="text/css" href="static/css/select2.min.css" rel="stylesheet">
    <link type="text/css" href="static/css/getfeedback.css" rel="stylesheet">
    <script type="text/javascript" src="static/js/getfeedback.js"></script>
</head>
 <body class="fp-body">

    <section id="header-section">

        <?php include("templates/header.php"); ?>

    </section>
     
    <section id="fp-content-section">
        
        <main id="fp-main">
                                        
            <nav id="fp-form-nav">

                <ul>
                    
                    <li class="fp-tab active-tab" id="fp-tab-1" aria-controls="fp-form-1" aria-expanded="true">
                        <button type="button" class="btn-tab" id="btn-tab-1">
                            <label class="tab-title">
                                Feedback About&nbsp;<span class="ui-icon ui-icon-circle-check" id="icon-form-1-correct"></span>
                            </label>
                        </button>
                    </li>

                    <li class="fp-tab" id="fp-tab-2" aria-controls="fp-form-2" aria-expanded="false">
                        <button type="button" class="btn-tab" id="btn-tab-2">
                            <label class="tab-title">
                                Form&nbsp;&nbsp;:&nbsp; Subject&nbsp;<span class="ui-icon ui-icon-circle-check" id="icon-form-2-correct"></span>
                            </label>
                        </button>
                    </li>

                    <li class="fp-tab" id="fp-tab-3" aria-controls="fp-form-3" aria-expanded="false">
                        <button type="button" class="btn-tab" id="btn-tab-3">
                            <label class="tab-title">
                                Form&nbsp;: Teaching&nbsp;<span class="ui-icon ui-icon-circle-check" id="icon-form-3-correct"></span>
                            </label>
                        </button>
                    </li>

                </ul>

            </nav>
            
            <div id="fp-error-msg">
                <h5></h5>
                <span class="ui-icon ui-icon-closethick" title="click on it to dismiss the notification"></span>
            </div>
                
            <div class="fp-forms">
                
                <div class="fp-form active-form" id="fp-form-1" aria-expanded="true" data-form="false">
                    
                    <?php require("snippets/form.details.feedback.php"); ?>
                    
                </div>
                
                <div class="fp-form" id="fp-form-2" aria-expanded="false">
                    
                    <?php require("snippets/form.subject.feedback.php"); ?>
                    
                </div>
                
                <div class="fp-form" id="fp-form-3" aria-expanded="false">
                    
                    <?php require("snippets/form.teaching.feedback.php"); ?>
                    
                </div>
                
            </div>
        </main>
        
    </section>

    <section id="fp-footer-section">
        <?php include "templates/footer.php"; ?>
    </section>

   </body>
</html>