
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
                        echo "<th class='tbl-heading-subject'>Department</th>";
                        echo "<th class='tbl-heading-subject'>Semester</th>";

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