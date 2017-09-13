<?php
                                        
    switch($report_type) {
        
        case 'teacher':
            
            $subject = new DBSubject();

            $listSubjects = $subject->getSubjectsForTeacher($_teacher); ?>

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

            $listTeachers = $teachers->getTeachersForDeptAndSem($dept, $sem);
            
    ?>

            <select name="listTeacherChart" id="drpdown-list-chart">

                <?php   foreach($listTeachers as $teacher => $subjects) {   ?>

                            <optgroup label="<?php echo $teacher; ?>" >
                                
                                <?php   foreach($subjects as $subject_code => $subject) { ?>
                                    
                                            <option value="<?php echo $subject_code; ?>"><?php echo $subject; ?></option>
                                    
                                <?php   }   ?>
                                
                            </optgroup>

                <?php   }   ?>

            </select>

    <?php
            
            break;
            
        case 'subject-dept-sem': 

            $subjects = new DBSubject();

            $listSubjects = $subjects->getSubjects(null, $dept, $sem);

    ?>

            <select name="listSubjectChart" id="drpdown-list-chart">

                <?php   foreach($listSubjects as $subjectCode => $subject) { ?>
                                
                            <option value="<?php echo $subjectCode; ?>"><?php echo $subject; ?></option>

                <?php   }   ?>

            </select>

    <?php
            
            break;
        
    }

?>