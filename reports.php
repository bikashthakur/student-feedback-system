<?php
    session_start();
    $page = "report";

    if(isset($_SESSION['report-details'])) {
        unset($_SESSION['report-details']);
    }

?>    
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback Report | Student Feedback System</title>
    <link href="favicon.ico" rel="icon" type="image/ico">
    <script src="static/js/jquery-3.1.0.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="static/js/jquery-ui.js"></script>
    <link type="text/css" href="static/css/jquery-ui.css" rel="stylesheet">
    <link type="text/css" href="static/css/jquery-ui.icon-font.css" rel="stylesheet">
    <link type="text/css" href="static/css/style.css" rel="stylesheet">
    <link type="text/css" href="static/css/reports.css" rel="stylesheet">
    <link type="text/css" href="static/css/header.css" rel="stylesheet">
    <link type="text/css" href="static/css/footer.css" rel="stylesheet">
    <link type="text/css" href="static/css/select2.min.css" rel="stylesheet">
    <script src="static/js/select2.min.js" type="text/javascript"></script>
    <style>
            
    </style>
    
    <script type="text/javascript">
    
        $(document).ready(function () {
            
            $("#list-report-type").select2();
            
        });
    
    </script>
    
    <?php   if(isset($_POST['btnGetReport'])) { ?>
    
                <script src="static/js/highcharts.js"></script>
                <script src="static/js/highcharts-3d.js"></script>
                <script src="static/js/exporting.js"></script>
    
                <link href="DataTables/datatables.min.css" type="text/css" rel="stylesheet">
                <script src="DataTables/datatables.min.js" type="text/javascript"></script>
    
                <script src="static/js/reports.js" type="text/javascript"></script>
    
    <?php   }   ?>
    
    <style>

        div#fp-report-avg {
            padding: 15px 30px !important;
        }

        table td {
            text-align: center;
            border-bottom: 1px solid #ddd;
            border-right: 1px solid #ddd;
        }

    </style>

</head>

<body class="fp-body">
     
    <section id="header-section">

        <?php include("templates/header.php"); ?>

    </section>
     
     <section id="fp-content-section">
         
        <main id="fp-main">

            <?php 

                    if(!(isset($_POST['btnReportTypeSubmit']) || isset($_POST['btnGetReport']))) { ?>
            
                        <div id="fp-report-type">

                            <form action="" method="POST" id="fp-form-get-report">

                                <h3 style="text-align:center; font-family:Verdana, sans-serif; color:#369; padding-bottom:15px;">Report Details</h3>

                                <div class="fp-form-group" id="listReportType">

                                    <label for="listReportType">Report Type</label>

                                    <select id="list-report-type" name="listReportType">
                                        <option value="0" selected>Select Report Type</option>
                                        <option value="teacher">Teacher(all)</option>
                                        <option value="subject" disabled>Subject(all)</option>
                                        <option value="subject-dept-sem">Subject-Department-Semester</option>
                                        <option value="teacher-dept-sem">Teacher-Department-Semester</option>
                                    </select>

                                </div>

                                <div class="btn-form-submit">
                                    <input type="submit" name="btnReportTypeSubmit" id="btn-report-type-submit" value="Submit">
                                </div>

                            </form>

                        </div>
            
            <?php   } else if(isset($_POST['btnReportTypeSubmit']) && ($_POST['listReportType'] === "0")) { ?>
            
                        <div id="fp-report-type">

                            <form action="" method="POST" id="fp-form-get-report">

                                <h3 style="text-align:center; font-family:Verdana, sans-serif; color:#369; padding-bottom:15px;">Report Details</h3>

                                <div class="fp-form-group" id="listReportType">

                                    <label for="listReportType">Report Type</label>

                                    <select id="list-report-type" name="listReportType">
                                        <option value="0" selected>Select Report Type</option>
                                        <option value="teacher">Teacher(all)</option>
                                        <option value="subject" disabled>Subject(all)</option>
                                        <option value="subject-dept-sem">Subject-Department-Semester</option>
                                        <option value="teacher-dept-sem">Teacher-Department-Semester</option>
                                    </select>

                                </div>
                                
                                <div class="error-msg">

                                    <span>Please select a report type.</span>

                                </div>

                                <div class="btn-form-submit">
                                    <input type="submit" name="btnReportTypeSubmit" id="btn-report-type-submit" value="Submit">
                                </div>

                            </form>

                        </div>
            
            <?php    } else if(!isset($_POST['btnGetReport'])) {
                        
                        $report_type = $_POST['listReportType'];
                    
                        require("templates/reports.details.php"); 
            
                    }
            
                    if(isset($_POST['btnGetReport'])) {
                
                        $report_type = $_POST['report-type'];
                        
                        if (($report_type == "teacher" && $_POST['listTeachers'] === "0") || ($report_type == "subject" && $_POST['listSubject'] === "0") || ($report_type == "subject-dept-sem" && ($_POST['listDept'] === "0" || $_POST['listSem'] === "0")) || ($report_type == "teacher-dept-sem" && ($_POST['listDept'] === "0" || $_POST['listSem'] === "0"))) {
                            
                            $validation_error = true;
                            
                            require("templates/reports.details.php");
                            
                        } else {

                            require("snippets/report.overall.switch.php");

                            require("snippets/nav.reports.php"); ?>

                            <div id="fp-reports">

                                <div id="fp-report-heading" data-report-type="<?php echo $report_type; ?>" data-report="">

                                    <div id="fp-print-report">

                                        <div class="btn-form-submit">

                                            <input type="button" id="btn-print-report" name="btnPrintReport" value="Print">

                                            <?php require("snippets/selectlist.reports.php"); ?>

                                        </div>

                                    </div>

                                    <?php require("snippets/reports.heading.switch.php"); ?>

                                </div>

                                <div id="fp-report-data">

                                    <div id="fp-report-avg" class="fp-report show-report" aria-expanded="true">

                                        <ul>

                                            <div id="fp-form-subject">

                                                <?php require("snippets/content.report.general.php"); ?>

                                            </div>

                                        </ul> 

                                    </div>

                                    <div id="fp-report-chart" class="fp-report" aria-expanded="false">

                                        <ul>    </ul>

                                    </div>

                                    <div  id="fp-report-point-scale" class="fp-report" aria-expanded="false">

                                        <ul>    </ul>

                                    </div>

                                </div>

                            </div>

            <?php      }
                        
                    }   ?>

        </main>

    </section>

    <section id="fp-footer-section">
        
        <?php require("templates/footer.php"); ?>
        
    </section>

</body>

</html>