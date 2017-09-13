<?php

        require("classes/class.dbconnection.php");

        //$report_type = $_POST['listReportType'];

        switch($report_type) {

            case "teacher": ?>

                <script type="text/javascript">

                    $(document).ready( function () {
                        
                        $("#list-teachers").select2();
                        
                    });

                </script>

                <div id="fp-reports-details">

                    <form action="" method="POST" class="report-details">
                        
                        <h3 style="text-align:center; font-family:Verdana, sans-serif; color:#369; padding-bottom:15px;">Report Details</h3>
                        
                        <div class="fp-form-group">
                        
                            <label for="listTeachers">Teacher</label>
                            <select name="listTeachers" id="list-teachers">
                            
                                <?php
                
                                    require("classes/class.dbteacher.php");
                                
                                    $teacher = new DBTeacher();
                
                                    $listTeachers = $teacher->getTeachers();
                
                                    echo "<option value='0' selected>Select</option>";
                
                                    foreach($listTeachers as $key => $val) {
                                        
                                        echo "<option value='".$val."'>".$val."</option>";
                                        
                                    }
                                
                                ?>
                            
                            </select>
                        
                        </div>
                        
                        <?php   if(isset($validation_error) && $validation_error == true) { ?>
                        
                                    <div class="error-msg">

                                        <span>Please select a teacher.</span>

                                    </div>
                        
                        <?php   }   ?>
                        
                        <div class="btn-form-submit">
                            
                            <input type="hidden" name="report-type" value="teacher" hidden="hidden">

                            <input type="submit" id="btn-get-report" name="btnGetReport" value="Get Report">
                            
                        </div>

                    </form>

                </div>

            <?php break;

            case "subject": ?>

                <script type="text/javascript">

                    $(document).ready( function () {
                                                                
                        var _listSubjects = $("#list-subjects");

                        _listSubjects.select2();
                        
                    });

                </script>

                <div id="fp-reports-details">

                    <form action="" method="POST" class="report-details">
                        
                        <h3 style="text-align:center; font-family:Verdana, sans-serif; color:#369; padding-bottom:15px;">Report Details</h3>
                        
                        <div class="fp-form-group">

                            <label for="listSubjects">Subject</label>
                            
                            <select name="listSubjects" id="list-subjects">
                            
                                <?php
                
                                    require("classes/class.dbsubject.php");
                                
                                    $subject = new DBSubject();
                
                                    $listSubjects = $subject->getSubjects();
                
                                    echo "<option value='0' selected>Select</option>";
                
                                    foreach($listSubjects as $subject_code => $subject) {
                                        
                                        echo "<option value='".$subject_code."'>".$subject."</option>";
                                        
                                    }
                                
                                ?>
                            
                            </select>

                        </div>
                        
                        <?php   if(isset($validation_error) && $validation_error == true) { ?>
                        
                                    <div class="error-msg">

                                        <span>Please select a subject.</span>

                                    </div>
                        
                        <?php   }   ?>

                        <div class="btn-form-submit">
                            
                            <input type="hidden" name="report-type" value="subject" hidden="hidden">

                            <input type="submit" id="btn-get-report" name="btnGetReport" value="Get Report">
                            
                        </div>

                    </form>

                </div>

            <?php break;
                
            case "subject-dept-sem": ?>

                <script type="text/javascript">

                    $(document).ready( function () { 

                        $("#list-dept").select2();
                        $("#list-sem").select2();
                        
                    });

                </script>

                <div id="fp-reports-details">

                    <form action="" method="POST" class="report-details">
                        
                        <h3 style="text-align:center; font-family:Verdana, sans-serif; color:#369; padding-bottom:15px;">Report Details</h3>
                        
                        <div class="fp-form-group">

                            <label for="listDept">Department</label>
                            
                            <select name="listDept" id="list-dept">
                            
                                <?php
                
                                    require("classes/class.department.php");
                                
                                    $dept = new Department();
                
                                    $listDept = $dept->getDepartment();
                
                                    echo "<option value='0' selected>Select</option>";
                
                                    foreach($listDept as $dept_id => $dept) {
                                        
                                        echo "<option value='".$dept_id."'>".$dept."</option>";
                                        
                                    }
                                
                                ?>
                            
                            </select>

                        </div>
                        
                        <div class="fp-form-group">

                            <label for="listSem">Semester</label>
                            
                            <select name="listSem" id="list-sem">
                            
                                <?php
                
                                    require("classes/class.semester.php");
                                
                                    $sem = new Semester();
                
                                    $listSem = $sem->getSemester();
                
                                    echo "<option value='0' selected>Select</option>";
                
                                    foreach($listSem as $sem_no => $sem) {
                                        
                                        echo "<option value='".$sem_no."'>".$sem."</option>";
                                        
                                    }
                                
                                ?>
                            
                            </select>

                        </div>
                        
                        <?php   if(isset($validation_error) && $validation_error == true) { ?>
                        
                                    <div class="error-msg">

                                        <span>Please select all the fields.</span>

                                    </div>
                        
                        <?php   }   ?>

                        <div class="btn-form-submit">
                            
                            <input type="hidden" name="report-type" value="subject-dept-sem" hidden="hidden">

                            <input type="submit" id="btn-get-report" name="btnGetReport" value="Get Report">
                            
                        </div>

                    </form>

                </div>

            <?php break;
            
            case "teacher-dept-sem": ?>

                <script type="text/javascript">

                    $(document).ready( function () { 

                        $("#list-dept").select2();
                        $("#list-sem").select2();
                        
                    });

                </script>

                <div id="fp-reports-details">

                    <form action="" method="POST" class="report-details">
                        
                        <h3 style="text-align:center; font-family:Verdana, sans-serif; color:#369; padding-bottom:15px;">Report Details</h3>
                        
                        <div class="fp-form-group">

                            <label for="listDept">Department</label>
                            
                            <select name="listDept" id="list-dept">
                            
                                <?php
                
                                    require("classes/class.department.php");
                                
                                    $dept = new Department();
                
                                    $listDept = $dept->getDepartment();
                
                                    echo "<option value='0' selected>Select</option>";
                
                                    foreach($listDept as $dept_id => $dept) {
                                        
                                        echo "<option value='".$dept_id."'>".$dept."</option>";
                                        
                                    }
                                
                                ?>
                            
                            </select>

                        </div>
                        
                        <div class="fp-form-group">

                            <label for="listSem">Semester</label>
                            
                            <select name="listSem" id="list-sem">
                            
                                <?php
                
                                    require("classes/class.semester.php");
                                
                                    $sem = new Semester();
                
                                    $listSem = $sem->getSemester();
                
                                    echo "<option value='0' selected>Select</option>";
                
                                    foreach($listSem as $sem_no => $sem) {
                                        
                                        echo "<option value='".$sem_no."'>".$sem."</option>";
                                        
                                    }
                                
                                ?>
                            
                            </select>

                        </div>
                        
                        <?php   if(isset($validation_error) && $validation_error == true) { ?>
                        
                                    <div class="error-msg">

                                        <span>Please select all the fields.</span>

                                    </div>
                        
                        <?php   }   ?>

                        <div class="btn-form-submit">
                            
                            <input type="hidden" name="report-type" value="teacher-dept-sem" hidden="hidden">

                            <input type="submit" id="btn-get-report" name="btnGetReport" value="Get Report">
                            
                        </div>

                    </form>

                </div>

            <?php break;

        }

?>