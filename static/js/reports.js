$(document).ready(function () {

    $("select").select2();

    $("#form-subject-table").DataTable({
        dom: 'Bfrtip',
        buttons: ['copy',
        {
            extend: 'print',
            text: 'Print Report',
            title: 'Feedback Report',
            autoPrint: false,
            exportOptions: {
                columns: ':visible',
            },
            customize: function (win) {

                var _body = $(win.document.body);

                _body.find('table').addClass('display').css('font-size', '9px').css("border-collapse", 'collapse').css("border", '1px solid #ddd');
                $(win.document.body).find('thead th').css("border-bottom-color", '#ddd !important');
                /*$(win.document.body).find('tr:nth-child(odd) td').each(function(index){
                    $(this).css('background-color','#D0D0D0');
                });*/
                $(win.document).find('title').text("Student Feedback System").css("text-align", 'right');
                //$(win.document).find('tr').css("border-bottom", '1px solid #000 !important');
                //$(win.document.body).find('h1').css('display', 'none');
                $("<img src='images/stcet.jpg'>").insertBefore($(win.document.body).filter('h1:first-child'));
                $("<h1>St. Thomas' College of Engineering & Technology</h1>").insertBefore($(win.document.body).find('h1'));
                _body.find('h1').css('text-align','center').css("font-size", '1.5em').css("font-family",'sans-serif').css("padding-bottom", '6px');
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

    function createPointScaleReport(_reportDetails) {

        $.ajax({

            url: "templates/ajax/report.pointscale.php",
            method: "GET",
            data: _reportDetails,
            dataType: "json",
            contentType: "application/json;charset=utf-8",
            success: function (reportData) {

                if(reportData) {

                    var pointScaleReportUL = $("#fp-report-point-scale ul");

                    pointScaleReportUL.hide();

                    pointScaleReportUL.empty();

                    var contentHTML = '';

                    $.each(reportData, function (question, options) {

                        contentHTML = contentHTML + "<li><label>" + question + "</label>";
                        contentHTML += "<ul>"; 
                        //console.log(response);

                        $.each(options, function(option, response) {

                            contentHTML += "<li><label>" + option + "</label>";

                            contentHTML += "<ul>"; 

                            $.each(response, function (responseIndx, responseValue) {

                                contentHTML += "<li><span class='ui-icon ui-icon-shield'></span> " + responseValue.label + " <span class='point-scale-bar'></span> " + responseValue.value + "</li>"

                            });

                            contentHTML += "</ul></li>";

                        });

                        contentHTML += "</ul></li>"; 

                    });

                    pointScaleReportUL.html(contentHTML);
                    pointScaleReportUL.slideDown("slow");

                }

            }

        });

    }

    $("#btn-tab-2").click( function () {

        var deptId = {"CSE":"1", "ECE":"2", "EE":"3", "IT":"4"};

        $.ajax({

            url: "templates/ajax/session.reportdetails.php",
            method: 'GET',
            data: {session:'report-details'},
            dataType: 'json',
            contentType: "application/json;charset=utf-8",
            success: function (reportDetails) {

                $("#fp-report-point-scale ul").addClass("report-active");

                reportDetails['report'] = "pointscale";
                var reportType = reportDetails['report-type'];

                switch(reportType) {

                    case "teacher":
                        var _subjectCode = $("select#drpdown-list-chart").val();
                        var chartDetails = $("select#drpdown-list-chart").find("option:selected").parent().prop("label").split("-");
                        //console.log(chartDetails);
                        reportDetails['subject_code'] = _subjectCode;
                        reportDetails['dept'] = deptId[chartDetails[0]];
                        reportDetails['sem'] = chartDetails[2];
                        createChart(reportDetails);
                        createPointScaleReport(reportDetails);
                        break;

                    case "teacher-dept-sem":
                        var _subjectCode = $("select#drpdown-list-chart").val(),
                            _teacher = $("select#drpdown-list-chart").find(":selected").parent().prop("label"); // get the selected optgroup label
                        reportDetails['teacher'] = _teacher;
                        reportDetails['subject_code'] = _subjectCode;
                        createChart(reportDetails);
                        createPointScaleReport(reportDetails);
                        break;

                    case "subject-dept-sem":
                        var _subjectCode = $("select#drpdown-list-chart").val();
                        reportDetails['subject_code'] = _subjectCode;
                        createChart(reportDetails);
                        createPointScaleReport(reportDetails);
                        break;

                    case "subject ":
                        var _subjectCode = $("select#drpdown-list-chart").val();
                        reportDetails['subject_code'] = _subjectCode;
                        createChart(reportDetails);
                        createPointScaleReport(reportDetails);
                        break;

                }

            }

        });

    });

    $("#btn-tab-3").click( function () {

        if(!$("#fp-report-point-scale ul").hasClass("report-active")) {

            $("#fp-report-point-scale ul").addClass("report-active");

            var deptId = {"CSE":"1", "ECE":"2", "EE":"3", "IT":"4"};

            $.ajax({

                url: "templates/ajax/session.reportdetails.php",
                method: 'GET',
                data: {session:'report-details'},
                dataType: 'json',
                contentType: "application/json;charset=utf-8",
                success: function (reportDetails) {

                    reportDetails['report'] = 'pointscale';
                    var reportType = reportDetails['report-type'];

                    switch(reportType) {

                        case "teacher":
                            var _subjectCode = $("select#drpdown-list-chart").val();
                            var chartDetails = $("select#drpdown-list-chart").find("option:selected").parent().prop("label").split("-");
                            //console.log(chartDetails);
                            reportDetails['subject_code'] = _subjectCode;
                            reportDetails['dept'] = deptId[chartDetails[0]];
                            reportDetails['sem'] = chartDetails[2];
                            createPointScaleReport(reportDetails);
                            createChart(reportDetails);
                            break;

                        case "teacher-dept-sem":
                            var _subjectCode = $("select#drpdown-list-chart").val(),
                                _teacher = $("select#drpdown-list-chart").find(":selected").parent().prop("label"); // get the selected optgroup label
                            reportDetails['teacher'] = _teacher;
                            reportDetails['subject_code'] = _subjectCode;
                            createPointScaleReport(reportDetails);
                            createChart(reportDetails);
                            break;

                        case "subject-dept-sem":
                            var _subjectCode = $("select#drpdown-list-chart").val();
                            reportDetails['subject_code'] = _subjectCode;
                            createPointScaleReport(reportDetails);
                            createChart(reportDetails);
                            break;

                        case "subject":
                            var _subjectCode = $("select#drpdown-list-chart").val();
                            reportDetails['subject_code'] = _subjectCode;
                            createPointScaleReport(reportDetails);
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

        if(tabId == 'fp-tab-1') {
            $("div#fp-print-report span.select2-container").css("visibility", "hidden");
        } else {
            $("div#fp-print-report span.select2-container").css("visibility", "visible");
        }

    }

    $("#drpdown-list-chart").change( function () {

        var activeTabId = $("li.fp-tab").filter(".active-tab").attr("id");

        var _this = $(this);

        switch(activeTabId) {

            case "fp-tab-2":

                var deptId = {"CSE":"1", "ECE":"2", "EE":"3", "IT":"4"};

                $.ajax({

                    url: "templates/ajax/session.reportdetails.php",
                    method: 'GET',
                    data: {session:'report-details'},
                    dataType: 'json',
                    contentType: "application/json;charset=utf-8",
                    success: function (reportDetails) {

                        reportDetails['report'] = 'pointscale';
                        $reportType = reportDetails['report-type'];

                        switch($reportType) {

                            case "teacher":
                                var _subjectCode = _this.val();
                                //console.log(_this.find("option:selected").parent().prop("label"));
                                var chartDetails = _this.find("option:selected").parent().prop("label").split("-");
                                //console.log(chartDetails);
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

                            case "subject-dept-sem":
                                var _subjectCode = _this.val();
                                reportDetails['subject_code'] = _subjectCode;
                                break;
                        }

                        createChart(reportDetails);
                        createPointScaleReport(reportDetails);

                    }

                });

                break;

            case "fp-tab-3":

                var deptId = {"CSE":"1", "ECE":"2", "EE":"3", "IT":"4"};

                $.ajax({

                    url: "templates/ajax/session.reportdetails.php",
                    method: 'GET',
                    data: {session:'report-details'},
                    dataType: 'json',
                    contentType: "application/json;charset=utf-8",
                    success: function (reportDetails) {

                        reportDetails['report'] = 'pointscale';

                        $reportType = reportDetails['report-type'];

                        switch($reportType) {

                            case "teacher":
                                var _subjectCode = _this.val();
                                //console.log(_this.find("option:selected").parent().prop("label"));
                                var chartDetails = _this.find("option:selected").parent().prop("label").split("-");
                                //console.log(chartDetails);
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

                            case "subject-dept-sem":
                                var _subjectCode = $("select#drpdown-list-chart").val();
                                reportDetails['subject_code'] = _subjectCode;
                                createPointScaleReport(reportDetails);
                                createChart(reportDetails);
                                break;

                            case "subject":
                                var _subjectCode = $("select#drpdown-list-chart").val();
                                reportDetails['subject_code'] = _subjectCode;
                                createPointScaleReport(reportDetails);
                                createChart(reportDetails);
                                break;
                        }

                        createPointScaleReport(reportDetails);
                        $("#fp-report-chart ul").empty();

                    }

                });

                break;

        }


    });

});