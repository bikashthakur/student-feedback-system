<?php
    session_start();
    $page = "report";

    //require("templates/classes/class.dbconnection.php");
    //require("templates/ajax/classes/class.dbteacher.php");

    if(isset($_SESSION['form-1'], $_SESSION['form-2'], $_SESSION['form-3'])) {
        
        unset($_SESSION['form-1'],$_SESSION['form-2'],$_SESSION['form-3']);
        
    }

    if(isset($_SESSION['feedback-report'])) {
        
        unset($_SESSION['feedback-report']);
        
    }

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
    <script src="static/js/highcharts.js"></script>
    <script src="static/js/highcharts-3d.js"></script>
    <script src="static/js/exporting.js"></script>
    <link type="text/css" href="static/css/jquery-ui.css" rel="stylesheet">
    <link type="text/css" href="static/css/jquery-ui.icon-font.css" rel="stylesheet">
    <link type="text/css" href="static/css/style.css" rel="stylesheet">
    <link type="text/css" href="static/css/reports.css" rel="stylesheet">
    <link type="text/css" href="static/css/header.css" rel="stylesheet">
    <link type="text/css" href="static/css/footer.css" rel="stylesheet">
    <link type="text/css" href="static/css/select2.min.css" rel="stylesheet">
    <script src="static/js/select2.min.js" type="text/javascript"></script>
    <!--script src="static/js/reports.js"></script-->
    <style>
            
    </style>
    
    <script type="text/javascript">
    
        $(document).ready(function () {
            
            $("#list-report-type").select2();
            
        });
    
    </script>
    
    <link href="DataTables/datatables.min.css" type="text/css" rel="stylesheet">
    <script src="DataTables/datatables.min.js" type="text/javascript"></script>
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
    <script type="text/javascript">

        $(document).ready(function () {

            $("select").select2();

            $("#form-subject-table").DataTable({
                dom: 'Bfrtip',
                buttons: ['copy',
                {
                    extend: 'pdf',
                    text: 'Save to PDF',
                    title: 'Feedback Report'
                },
                {
                    extend: 'print',
                    text: 'Print current page',
                    title: 'Feedback Report',
                    autoPrint: false,
                    exportOptions: {
                        columns: ':visible',
                    },
                    customize: function (win) {
                        $(win.document.body).find('table').addClass('display').css('font-size', '9px').css("border-collapse", 'collapse').css("border", '1px solid #ddd');
                        $(win.document.body).find('thead th').css("border-bottom-color", '#ddd !important');
                        /*$(win.document.body).find('tr:nth-child(odd) td').each(function(index){
                            $(this).css('background-color','#D0D0D0');
                        });*/
                        $(win.document).find('title').text("Student Feedback System").css("text-align", 'right');
                        //$(win.document).find('tr').css("border-bottom", '1px solid #000 !important');
                        //$(win.document.body).find('h1').css('display', 'none');
                        $("<img src='images/stcet.jpg'>").insertBefore($(win.document.body).find('h1:first-child'));
                        $("<h1>St. Thomas' College of Engineering & Technology</h1>").insertBefore($(win.document.body).find('h1'));
                        $(win.document.body).find('h1').css('text-align','center').css("font-size", '1.5em').css("font-family",'sans-serif').css("padding-bottom", '6px');
                    }
                },
                'excel']
            });

            function drawChart(question, _data, _i) {

                var _data1, _value1, _data2, _value2, _data3, _value3;
                var chart_displayID = 'question-' + _i + '-chart';
                var chartname = "Question-" + _i + "Response";
                var _question = "Question-" + _i + ": " + question;

                var i = 1;

                $.each(_data, function(key, value) {

                    switch(i) {

                        case 1:
                            _data1 = key;
                            _value1 = parseFloat(value);
                            break;
                        case 2:
                            _data2 = key;
                            _value2 = parseFloat(value);
                            break;
                        case 3:
                            _data3 = key;
                            _value3 = parseFloat(value);
                            break;
                    }

                    i++;

                });

                Highcharts.setOptions({colors: ['#f3a333', '#369', '#999']});

                Highcharts.chart(chart_displayID, {
                    chart: {
                        type: 'pie',
                        options3d: {
                            enabled: true,
                            alpha: 45,
                            beta: 0
                        }
                    },
                    title: {
                        text: _question
                    },
                    tooltip: {
                        pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>'
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            depth: 25,
                            dataLabels: {
                                enabled: true,
                                format: '{point.name}'
                            }
                        }
                    },
                    series: [{
                        type: 'pie',
                        name: ' ',
                        data: [
                            {
                                name: _data1 + ": " + _value1 + "%",
                                y: _value1,
                                sliced: true,
                                selected: true
                            },
                            [_data2 + ": " + _value2 + "%", _value2],
                            [_data3 + ": " + _value3 + "%", _value3]
                        ]
                    }]
                });

                //alert(qOp1 + " " + qOp1_count + " " + qOp2 + " " + qOp2_count + " " + qOp3 + " " + qOp3_count);
            }

        function createChart(_reportDetails) {

            $.ajax({
                url: "templates/ajax/chart.data.php",
                data: _reportDetails,
                method: 'GET',
                dataType: 'json',
                contentType: "application/json;charset=utf-8",
                success: function(reportData) {

                    if(reportData) {

                        var _reportChartUL = $("#fp-report-chart ul");

                        _reportChartUL.empty();

                        var i = 1;

                        $.each(reportData, function(question, response) {

                            var _chartData = {};

                            $.each(response, function(key, value) {

                                _chartData[key] = value;

                            });

                            _reportChartUL.append("<li id='question-" + i + "-chart' class='report-chart' style='height:300px;'></li>");
                            drawChart(question, _chartData, i++);

                        });

                    } else {

                        $("#fp-msg").addClass("no-feedback");
                        $("label#report-status").html("No Feedback Found!!");

                    }


                }

            });

        }

        $("#btn-tab-chart").click( function () {

            if(!$("#fp-report-chart ul").hasClass("chart-active")) {
                
                $("#fp-report-chart ul").addClass("chart-active");
                
                var deptId = {"CSE":"1", "ECE":"2", "EE":"3", "IT":"4"};
            
                $.ajax({

                    url: "templates/ajax/session.reportdetails.php",
                    method: 'GET',
                    data: {session:'report-details'},
                    dataType: 'json',
                    contentType: "application/json;charset=utf-8",
                    success: function (data) {

                        var reportDetails = data;

                        var reportType = reportDetails['report-type'];

                        switch(reportType) {

                            case "teacher":
                                var _subjectCode = $("select#drpdown-list-chart").val();
                                var chartDetails = $("select#drpdown-list-chart").find("option:selected").parent().prop("label").split("-");
                                console.log(chartDetails);
                                reportDetails['subject_code'] = _subjectCode;
                                reportDetails['dept'] = deptId[chartDetails[0]];
                                reportDetails['sem'] = chartDetails[2];
                                createChart(reportDetails);
                                break;

                            case "teacher-dept-sem":
                                var _subjectCode = $("select#drpdown-list-chart").val(),
                                    _teacher = $("select#drpdown-list-chart").find(":selected").parent().prop("label"); // get the selected optgroup label
                                reportDetails['teacher'] = _teacher;
                                reportDetails['subject_code'] = _subjectCode;
                                createChart(reportDetails);
                                break;

                            case "subject-dept-sem":
                                createChart(reportDetails);
                                break;

                        }

                    }

                });
                
            }

        });

        $("li.fp-tab").click( function () {

            //redirect to the appropriate form on tab click
            redirectTo($(this).attr("aria-controls"));

        });

        function redirectTo(reportId) {

            $(".fp-report[aria-expanded='true']").attr("aria-expanded", false)
                                                .removeClass("show-report");
            $("#" + reportId).attr("aria-expanded", 'true')
                            .addClass("show-report");
            //set tab active corresponding to the form
            setTabActive($(".fp-tab[aria-controls='"  + reportId +  "']").attr("id"));

        }

        function setTabActive(tabId) {

            $(".fp-tab[aria-expanded='true']").attr("aria-expanded", false)
                                                    .removeClass("active-tab");
            $("#" + tabId).attr("aria-expanded", 'true')
                            .addClass("active-tab");

        }
            
        $("#drpdown-list-chart").change( function () {
            
            var _this = $(this);
            
            var deptId = {"CSE":"1", "ECE":"2", "EE":"3", "IT":"4"};
            
            $.ajax({

                url: "templates/ajax/session.reportdetails.php",
                method: 'GET',
                data: {session:'report-details'},
                dataType: 'json',
                contentType: "application/json;charset=utf-8",
                success: function (data) {

                    var reportDetails = data;
                    
                    $reportType = reportDetails['report-type'];
                    
                    switch($reportType) {
                            
                        case "teacher":
                            var _subjectCode = _this.val();
                            //console.log(_this.find("option:selected").parent().prop("label"));
                            var chartDetails = _this.find("option:selected").parent().prop("label").split("-");
                            console.log(chartDetails);
                            reportDetails['subject_code'] = _subjectCode;
                            reportDetails['dept'] = deptId[chartDetails[0]];
                            reportDetails['sem'] = chartDetails[2];
                            break;
                            
                        case "teacher-dept-sem":
                            var _subjectCode = _this.val(),
                                _teacher = _this.find(":selected").parent().prop("label"); // get the selected optgroup label
                            reportDetails['teacher'] = _teacher;
                            reportDetails['subject_code'] = _subjectCode;
                            break;
                    }
                    
                    createChart(reportDetails);

                }

            });
        });

    });

</script>

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
                                        <option value="subject">Subject(all)</option>
                                        <option value="subject-dept-sem">Subject-Department-Semester</option>
                                        <option value="teacher-dept-sem">Teacher-Department-Semester</option>
                                        <option value="teacher-sub" disabled>Teacher-Subject</option>
                                        <option value="teacher-dept" disabled>Teacher-Department</option>
                                        <option value="teacher-sem" disabled>Teacher-Semester</option>
                                    </select>

                                </div>

                                <div class="btn-form-submit">
                                    <input type="submit" name="btnReportTypeSubmit" id="btn-report-type-submit" value="Submit">
                                </div>

                            </form>

                        </div>
            
            <?php   }   ?>
            
            <?php 
                if(isset($_POST['btnReportTypeSubmit'])) {
                    include("templates/reports.details.php"); 
                }
            ?>
            
            <?php   if(isset($_POST['btnGetReport'])) {
    
                        require("templates/classes/class.dbconnection.php");
                        require("templates/classes/class.dbquestion.php");
                        require("templates/classes/class.dbsubject.php");
                        require("templates/classes/class.dbteacher.php");
                        require("templates/classes/class.dbfeedbacksubject.php");
                        require("templates/classes/class.dbfeedbackteaching.php");
                        require("templates/classes/class.managesession.php");

                        //if(isset($_GET['feedback'],$_GET['report']) && !empty($_GET['feedback']) && !empty($_GET['report'])) {
    
                            function generateReportAvg($reportAvg) {
                                    
                                foreach($reportAvg as $question => $response) {

                                    echo "<li><label>".$question." : ".$response."</label></li>";
                                    
                                }
                                
                            }

                            function generateReportGeneral($reportGeneral) {
                                    
                                foreach($reportGeneral as $question => $responses) {

                                    echo "<li><label>".$question."</label>";
                                    echo "<ul>";

                                    foreach($responses as $option => $value) {

                                        echo "<li>".$option." : ".$value."%</li>";

                                    }

                                    echo "</ul>";

                                }

                                echo "</li><br>";
                                
                            }
    
                            function generateReportPointScale($reportPointScale) {
                                    
                                foreach($reportPointScale as $question => $responses) {

                                    echo "<li><label>".$question."</label>";
                                    echo "<ul>";

                                    foreach($responses as $option => $option_val) {
                                        
                                        echo "<li><label>".$option."</label>";
                                        echo "<ul>";
                                        
                                            foreach($option_val as $key => $val) {
                                                
                                                echo "<li><span class='ui-icon ui-icon-shield'></span> ".$val['label']." <span class='point-scale-bar'></span> ".$val['value']."</li>";
                                                
                                            }

                                        echo "</ul>";
                                        echo "</li>";

                                    }
                                    
                                    echo "</ul>";
                                    echo "</li><br>";

                                }
                                
                            }

                            $report_type = $_POST['report-type'];
                            $reportAvg = array();
                            $reportPointScale = array();
                            $report_on = '';
                            $total_feedbacks = '';
    
                            unset($_SESSION['report-details']);
    
                            $_SESSION['report-details']['report-type'] = $report_type;
    
                            //echo "report_type $report_type";
    
                            //$report_on = $_POST['report'];

                            switch($report_type) {

                                case "teacher":

                                    $_teacher = $_POST['listTeachers'];
                                    
                                    $_SESSION['report-details']['teacher'] = $_teacher;
                                    
                                    $report_on = $_teacher;

                                    $reportTeaching = new DBFeedbackTeaching();
                                    $reportAvg = $reportTeaching->getFeedbackReportAVG($_teacher);
                                    $reportPointScale = $reportTeaching->getFeedbackReportInPointScale($_teacher);
                                    
                                    //$total_feedbacks = $reportTeaching->totalFeedbacks($_teacher);
                                    
                                    $reportAll = $reportTeaching->getFeedbackReportAll($report_on, '', '');
                                                                        
                                    //generateReportGeneral($reportGeneral, $_Teacher);

                                    break;

                                case "teacher-sub":

                                    $_teacher = $_POST['listTeachers'];
                                    $_subject_code = $_POST['listSubjects'];
                                    
                                    $_SESSION['report-details']['teacher'] = $_teacher;
                                    
                                    $report_on = $_teacher;

                                    $subject = new DBSubject();

                                    $_Subject = $subject->getSubjectByCode($_subject_code)." (".$_subject_code.")";
                                    
                                    $_SESSION['report-details']['subject_code'] = $_subject_code;
                                    $_SESSION['report-details']['subject'] = $_Subject;

                                    $reportTeaching = new DBFeedbackTeaching();
                                    $reportAvg = $reportTeaching->getFeedbackReportAVG($_teacher, $_subject_code);
                                    $reportPointScale = $reportTeaching->getFeedbackReportInPointScale($_teacher, $_subject_code);
                                    
                                    $total_feedbacks = $reportTeaching->totalFeedbacks($_teacher, $_subject_code);

                                    break;

                                case "teacher-dept":

                                    $_teacher = $_POST['listTeachers'];
                                    $_dept = $_POST['listDept'];
                                    
                                    $_SESSION['report-details']['teacher'] = $_teacher;
                                    $_SESSION['report-details']['dept'] = $_dept;
                                    
                                    $report_on = $_teacher;

                                    $reportTeaching = new DBFeedbackTeaching();
                                    $reportAvg = $reportTeaching->getFeedbackReportAVG($_teacher, '', $_dept);
                                    $reportGeneral = $reportTeaching->getFeedbackReport($_teacher, '', $_dept);
                                    $reportPointScale = $reportTeaching->getFeedbackReportInPointScale($_teacher, '', $_dept, '');
                                    
                                    $total_feedbacks = $reportTeaching->totalFeedbacks($_teacher, '', $_dept);

                                    break;

                                case "teacher-sem":

                                    $_teacher = $_POST['listTeachers'];
                                    $_sem = $_POST['listSem'];
                                    
                                    $_SESSION['report-details']['teacher'] = $_teacher;
                                    $_SESSION['report-details']['sem'] = $_sem;
                                    
                                    $report_on = $_teacher;

                                    $reportTeaching = new DBFeedbackTeaching();
                                    $reportAvg = $reportTeaching->getFeedbackReportAVG($_teacher, '', '', $_sem);
                                    $reportPointScale = $reportTeaching->getFeedbackReportInPointScale($_teacher, '', '', $_sem);
                                    
                                    $total_feedbacks = $reportTeaching->totalFeedbacks($_teacher, '', '', $_sem);

                                    break;

                                case "subject":

                                    /*$subject_code = $_POST['listSubjects'];
                                    
                                    $subject = new DBSubject();
                                    
                                    $subject_name = $subject->getSubjectByCode($subject_code);
                                    
                                    $report_on = $subject_name." (".$subject_code.")";
                                    
                                    $_SESSION['report-details']['subject_code'] = $subject_code;
                                    $_SESSION['report-details']['subject'] = $subject_name;

                                    $reportSubject = new DBFeedbackSubject();
                                    $reportAvg = $reportSubject->getFeedbackReportAvg($subject_code);
                                    $total_feedbacks = $reportSubject->totalFeedbacks($subject_code);*/
                                    
                                    //$_SESSION['report-details']['listDept'] = $dept;
                                    //$_SESSION['report-details']['listSem'] = $sem;

                                    $reportTeaching = new DBFeedbackSubject();
                                    $reportAll = $reportTeaching->getFeedbackReportAll();
                                    
                                    break;
                                    
                                case "teacher-dept-sem":
                                    
                                    $dept = $_POST['listDept'];
                                    $sem = $_POST['listSem'];
                                    
                                    $_SESSION['report-details']['report-type'] = "teacher-dept-sem";
                                    $_SESSION['report-details']['listDept'] = $dept;
                                    $_SESSION['report-details']['listSem'] = $sem;

                                    $reportTeaching = new DBFeedbackTeaching();
                                    $reportAll = $reportTeaching->getFeedbackReportAll(null, $dept, $sem);
                                    
                                    break;
                                    
                                case "subject-dept-sem":
                                    
                                    $dept = $_POST['listDept'];
                                    $sem = $_POST['listSem'];
                                    
                                    $_SESSION['report-details']['report-type'] = "subject-dept-sem";
                                    $_SESSION['report-details']['listDept'] = $dept;
                                    $_SESSION['report-details']['listSem'] = $sem;

                                    $reportTeaching = new DBFeedbackSubject();
                                    $reportAll = $reportTeaching->getFeedbackReportAll(null, $dept, $sem);
                                    
                                    break;

                            }
            
            ?>
            
                        <nav id="fp-report-nav">

                            <ul>

                                <li class="fp-tab active-tab" id="fp-tab-1" aria-controls="fp-report-avg" aria-expanded="true">
                                    <button type="button" class="btn-tab" id="btn-tab-1">
                                        <label class="tab-title">
                                            Report : Overall
                                        </label>
                                    </button>
                                </li>

                                <li class="fp-tab" id="fp-tab-2" aria-controls="fp-report-chart" aria-expanded="false">
                                    <button type="button" class="btn-tab" id="btn-tab-chart">
                                        <label class="tab-title">
                                            Report : Chart
                                        </label>
                                    </button>
                                </li>

                                <li class="fp-tab" id="fp-tab-3" aria-controls="fp-report-point-scale" aria-expanded="false">
                                    <button type="button" class="btn-tab" id="btn-tab-3">
                                        <label class="tab-title">
                                            Report : Point Scale
                                        </label>
                                    </button>
                                </li>

                            </ul>

                        </nav>

                        <div id="fp-reports">
                            
                            <div id="fp-report-heading" data-report-type="<?php echo $report_type; ?>" data-report="">
                                
                                <div id="fp-print-report">
                                    
                                    <div class="btn-form-submit">

                                        <input type="button" id="btn-print-report" name="btnPrintReport" value="Print">
                                        
                                        <?php
                                        
                                            switch($report_type) {
                                                
                                                case 'teacher':
                                                    
                                                    $feedbackTeaching = new DBFeedbackTeaching();

                                                    $listSubjects = $feedbackTeaching->getSubjects($_teacher); ?>

                                                    <select name="listSubjectChart" id="drpdown-list-chart">

                                                        <?php 

                                                                foreach($listSubjects as $subjectIdx => $subjects) { ?>

                                                                    <optgroup label="<?php echo $subjectIdx; ?>" >
                                                                        
                                                                        <?php 
                                                                        
                                                                             foreach($subjects as $subject_code => $subject) { ?>
                                                                        
                                                                                <option value="<?php echo $subject_code; ?>"><?php echo $subject; ?></option>
                                                                        
                                                                        <?php } ?>
                                                                        
                                                                    </optgroup>

                                                        <?php   }   ?>

                                                    </select>
                                        
                                        <?php
                                                    
                                                    break;
                                                
                                                case 'teacher-dept-sem': 
                                        
                                                    $teachers = new DBTeacher();

                                                    $listTeachers = $teachers->getTeachersForDeptAndSem($dept, $sem); ?>

                                                    <select name="listTeacherChart" id="drpdown-list-chart">

                                                        <?php 
                                                    
                                                                foreach($listTeachers as $teacher => $subjects) { ?>

                                                                    <optgroup label="<?php echo $teacher; ?>" >
                                                                        
                                                                        <?php 
                                                                        
                                                                             foreach($subjects as $subject_code => $subject) { ?>
                                                                        
                                                                                <option value="<?php echo $subject_code; ?>"><?php echo $subject; ?></option>
                                                                        
                                                                        <?php } ?>
                                                                        
                                                                    </optgroup>

                                                        <?php   }   ?>

                                                    </select>
                                        
                                        <?php
                                                    
                                                    break;
                                                
                                            }
                                        
                                        ?>

                                    </div>
                            
                                </div>
                                
                                <h3> <?php echo "Feedback Report : ".$report_on; ?> </h3>
                                
                                <?php
                                    
                                    switch($report_type) {
                                            
                                        case "teacher":
                                        
                                            echo "<h5> Report Type : Teacher(all) </h5>";
                                            
                                            break;
                                            
                                        case "teacher-sub":
                                        
                                            echo "<h4> Subject : ".$_Subject."</h4>";
                                            echo "<h5> Report Type : Teacher-in-Subject </h5>";
                                            
                                            break;
                                            
                                        case "teacher-dept":
                                            
                                            $department = array(
                                            
                                                "1" => "CSE",
                                                "2" => "ECE",
                                                "3" => "EE",
                                                "4" => "IT"
                                                
                                            );
                                            
                                            echo "<h4> Department : ".$department[$_dept]."</h4>";
                                            echo "<h5> Report Type : Teacher-in-Department </h5>";
                                            
                                            break;
                                            
                                        case "teacher-sem":
                                            
                                            echo "<h4> Semester : ".$_sem."</h4>";
                                            echo "<h5> Report Type : Teacher-in-Semester</h5>";
                                            
                                            break;
                                            
                                        case "subject":
                                            
                                            echo "<h5> Report Type : Subject </h5>";
                                            
                                            break;
                                            
                                        case "subject-dept-sem":
                                        
                                            $department = array(
                                            
                                                "1" => "CSE",
                                                "2" => "ECE",
                                                "3" => "EE",
                                                "4" => "IT"
                                                
                                            );
                                            
                                            echo "<h5> Report Type : Subjects (Department:$department[$dept] | Semester:$sem) </h5>";
                                            
                                            break;
                                            
                                        case "teacher-dept-sem":
                                            
                                            $department = array(
                                            
                                                "1" => "CSE",
                                                "2" => "ECE",
                                                "3" => "EE",
                                                "4" => "IT"
                                                
                                            );
                                            
                                            echo "<h5> Report Type : Teachers (Department:$department[$dept] | Semester:$sem) </h5>";
                                            
                                            break;
                                            
                                    }
                                ?>
                            
                            </div>

                            <div id="fp-report-data">
                                
                                <div id="fp-report-avg" class="fp-report show-report" aria-expanded="true">
                                
                                    <ul>
                                        
                                        
                                        <!-- if($report_type === 'teacher-dept-sem' || $report_type === 'teacher') { ? -->
                                            
                                        <div id="fp-form-subject">

                                            <table class="fp-form-table" id="form-subject-table">

                                                <thead class="tbl-heading">
                                                
                                                    <tr>

                                                        <?php
                                                        
                                                            switch($report_type) {
                                                        
                                                                case "teacher":

                                                                    require("templates/classes/class.dbquestionteaching.php");

                                                                    $Question = new DBQuestionTeaching();

                                                                    $questions = $Question->getAllQuestions();

                                                                    echo "<th class='tbl-heading-subject'>Subject</th>";
                                                                    echo "<th class='tbl-heading-subject'>Department</th>";
                                                                    echo "<th class='tbl-heading-subject'>Semester</th>";

                                                                    foreach($questions as $qIdx => $question) {

                                                                        echo "<th class='tbl-heading-q'>".$question."</th>";

                                                                    }
                                                                    break;
                                                            
                                                                case "teacher-dept-sem":
                                                                    
                                                                    require("templates/classes/class.dbquestionteaching.php");

                                                                    $Question = new DBQuestionTeaching();

                                                                    $questions = $Question->getAllQuestions();

                                                                    echo "<th class='tbl-heading-subject'>Teacher</th>";
                                                                    echo "<th class='tbl-heading-subject'>Subject</th>";

                                                                    foreach($questions as $qIdx => $question) {

                                                                        echo "<th class='tbl-heading-q'>".$question."</th>";

                                                                    }
                                                                    break;
                                                                    
                                                                case "subject":
                                                                    
                                                                    require("templates/classes/class.dbquestionsubject.php");

                                                                    $Question = new DBQuestionSubject();

                                                                    $questions = $Question->getAllQuestions();

                                                                    echo "<th class='tbl-heading-subject'>Subject</th>";
                                                                    echo "<th class='tbl-heading-subject'>Department</th>";
                                                                    echo "<th class='tbl-heading-subject'>Semester</th>";

                                                                    foreach($questions as $qIdx => $question) {

                                                                        echo "<th class='tbl-heading-q'>".$question."</th>";

                                                                    }
                                                                    break;
                                                                    
                                                                case "subject-dept-sem":
                                                                    
                                                                    require("templates/classes/class.dbquestionsubject.php");

                                                                    $Question = new DBQuestionSubject();

                                                                    $questions = $Question->getAllQuestions();

                                                                    echo "<th class='tbl-heading-subject'>Subject</th>";
                                                                    echo "<th class='tbl-heading-subject'>Subject</th>";
                                                                    echo "<th class='tbl-heading-subject'>Department</th>";

                                                                    foreach($questions as $qIdx => $question) {

                                                                        echo "<th class='tbl-heading-q'>".$question."</th>";

                                                                    }
                                                                    break;
                                                            }

                                                        ?>
                                                        
                                                    </tr>
                                                    
                                                </thead>

                                                <tbody class="tbl-content">

                                                    <?php

                                                        //require("templates/classes/class.dbfeedbackteaching.php");

                                                        //$TeachingReport = new DBFeedbackTeaching();

                                                        //$report = $TeachingReport->getFeedbackReportForDeptAndSem(1, 6);
                                        
                                                        $department = array(

                                                            "1" => "CSE",
                                                            "2" => "ECE",
                                                            "3" => "EE",
                                                            "4" => "IT"

                                                        );

                                                        foreach($reportAll as $rIdx => $rData) {

                                                            echo "<tr>";

                                                                if(isset($rData['teacher'])){
                                                                    echo "<td>".$rData['teacher']."</td>";
                                                                }
                                                            
                                                                if(isset($rData['subject_code'], $rData['subject_name'])){
                                                                    echo "<td>".$rData['subject_name']."(".$rData['subject_code'].")</td>";
                                                                }
                                                                if(isset($rData['department'])){
                                                                    echo "<td>".$department[$rData['department']]."</td>";
                                                                }
                                                                if(isset($rData['semester'])){
                                                                    echo "<td>".$rData['semester']."</td>";
                                                                }
                                                                echo "<td>".round($rData['q1Avg'], 2)."</td>";
                                                                echo "<td>".round($rData['q2Avg'], 2)."</td>";
                                                                echo "<td>".round($rData['q3Avg'], 2)."</td>";
                                                                echo "<td>".round($rData['q4Avg'], 2)."</td>";
                                                                echo "<td>".round($rData['q5Avg'], 2)."</td>";
                                                                echo "<td>".round($rData['q6Avg'], 2)."</td>";
                                                                
                                                                if(isset($rData['q7Avg'])){
                                                                    echo "<td>".round($rData['q7Avg'], 2)."</td>";
                                                                }
                                                                
                                                                if(isset($rData['q8Avg'])){
                                                                    echo "<td>".round($rData['q8Avg'], 2)."</td>";
                                                                }

                                                            echo "</tr>";

                                                        }

                                                    ?>

                                                </tbody>

                                            </table>

                                        </div>
                                                
                                        <!--?--php  } else {
                                            
                                            generateReportAvg($reportAvg);
                                            
                                        }  ? -->

                                    </ul> 
                                
                                </div>
                                
                                <div id="fp-report-chart" class="fp-report" aria-expanded="false">

                                    <ul>
                                        <?php 
    
                                            if($report_type === 'dept-sem') {
                                                
                                                echo "<h3>No Data to Display!";
                                                
                                            }
    
                                        ?>
                                    </ul>
                                    
                                </div>

                                <div  id="fp-report-point-scale" class="fp-report" aria-expanded="false">
                                    
                                    <ul>
                                        
                                        <?php 
    
                                            if($report_type === 'dept-sem') {
                                                
                                                echo "<h3>No Data to Display!";
                                                
                                            } else {
                                                
                                                generateReportPointScale($reportPointScale);
                                                
                                            }
    
                                        ?>

                                    </ul>
                                    
                                </div>
                                
                            </div>

                        </div>

            <?php   }   ?>
            
            <?php
            
            
            ?>

        </main>

    </section>

    <section id="fp-footer-section">
        
        <?php include "templates/footer.php"; ?>
        
    </section>

</body>

</html>