
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