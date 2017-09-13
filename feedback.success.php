<?php
    session_start();
    require("templates/classes/class.dbconnection.php");
    require("templates/classes/class.dbfeedbacksubject.php");
    require("templates/classes/class.dbfeedbackteaching.php");

    if(isset($_SESSION['form-1'],$_SESSION['form-2'],$_SESSION['form-3'])) {
        
        //form-1 data
        
        $semester       =   $_SESSION['form-1']['listsem'];
        $department     =   $_SESSION['form-1']['listdept'];
        $subject_code   =   $_SESSION['form-1']['listsubject'];
        $teacher        =   $_SESSION['form-1']['listteacher'];
        
        //form-2 data
        
        $q1Response     =   $_SESSION['form-2']['question_1'];
        $q1Point        =   $_SESSION['form-2']['question_1_point'];
        $q2Response     =   $_SESSION['form-2']['question_2'];
        $q2Point        =   $_SESSION['form-2']['question_2_point'];
        $q3Response     =   $_SESSION['form-2']['question_3'];
        $q3Point        =   $_SESSION['form-2']['question_3_point'];
        $q4Response     =   $_SESSION['form-2']['question_4'];
        $q4Point        =   $_SESSION['form-2']['question_4_point'];
        $q5Response     =   $_SESSION['form-2']['question_5'];
        $q5Point        =   $_SESSION['form-2']['question_5_point'];
        $q6Response     =   $_SESSION['form-2']['question_6'];
        $q6Point        =   $_SESSION['form-2']['question_6_point'];
        
        $_formData = array($subject_code, $department, $semester, $q1Response, $q1Point, $q2Response, $q2Point, $q3Response, $q3Point, $q4Response, $q4Point, $q5Response, $q5Point, $q6Response, $q6Point);
        
        $feedbackSubject = new DBFeedbackSubject();
        $feedbackSubject->addFeedback($_formData);

        //form-3 data
        
        $q1Response     =   $_SESSION['form-3']['question_1'];
        $q1Point        =   $_SESSION['form-3']['question_1_point'];
        $q2Response     =   $_SESSION['form-3']['question_2'];
        $q2Point        =   $_SESSION['form-3']['question_2_point'];
        $q3Response     =   $_SESSION['form-3']['question_3'];
        $q3Point        =   $_SESSION['form-3']['question_3_point'];
        $q4Response     =   $_SESSION['form-3']['question_4'];
        $q4Point        =   $_SESSION['form-3']['question_4_point'];
        $q5Response     =   $_SESSION['form-3']['question_5'];
        $q5Point        =   $_SESSION['form-3']['question_5_point'];
        $q6Response     =   $_SESSION['form-3']['question_6'];
        $q6Point        =   $_SESSION['form-3']['question_6_point'];
        $q7Response     =   $_SESSION['form-3']['question_7'];
        $q7Point        =   $_SESSION['form-3']['question_7_point'];
        $q8Response     =   $_SESSION['form-3']['question_8'];
        $q8Point        =   $_SESSION['form-3']['question_8_point'];
        
        $_formData = array($teacher, $subject_code, $department, $semester, $q1Response, $q1Point, $q2Response, $q2Point, $q3Response, $q3Point, $q4Response, $q4Point, $q5Response, $q5Point, $q6Response, $q6Point, $q7Response, $q7Point, $q8Response, $q8Point);
        
        $feedbackTeaching = new DBFeedbackTeaching();
        $feedbackTeaching->addFeedback($_formData);

        unset($_SESSION['form-1'],$_SESSION['form-2'],$_SESSION['form-3']); ?>
        
        <!DOCTYPE html>
        <html>
        <head>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="static/css/bootstrap.min.css">
            <script src="static/js/jquery-3.1.0.min.js"></script>
            <script src="static/js/bootstrap.min.js"></script>
            <script type="text/javascript">
                $(document).ready(function() {
                    $("#feedback-success").modal({backdrop:'static'});
                    $("button").click(function(e) {
                        e.preventDefault(e);
                        window.location = "home";
                    });
                });
            </script>
        </head>
        <body>

            <div class="container">
              <div class="modal fade" id="feedback-success" role="dialog">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header" style="padding:15px; background:#369; color:#f3a333; text-align:center;">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h3 style="font-family:Vardana; font-weight:bold;"><span class="glyphicon glyphicon-like"></span>Thank You! ;) </h3>
                    </div>
                    <div class="modal-body" style="padding:40px 50px;">
                        <h4>Your Feedback Has Been Submitted Successfully</h4>
                    </div>
                    <div class="modal-footer" style="background:#ccc;">
                      <p>Have A Great Day Ahead</p>
                      <button type="submit" class="btn btn-success btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Ok</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>

        </body>
        </html>
        
 <?php } ?>
