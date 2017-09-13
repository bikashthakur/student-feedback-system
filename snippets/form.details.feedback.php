<form action="" method="POST" name="form-1" id="form-1">
                        
    <table class="fp-form-table" id="fp-tbl-form-1">

        <thead class="tbl-heading">
            <tr>
                <th>Feedback Details</th>
            </tr>
        </thead>

        <tbody class="tbl-content">

            <tr>
                <td>
                    <div id="fp-details-edit">
                        <button type="button"><span class="ui-icon ui-icon-pencil"></span> Edit </button>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="fp-details" id="fp-details-sem">

                        <label for="semester">Semester</label>

                        <!--span class="ui-icon ui-icon-caret-1-s active-caret"></span-->
                        <div id="sem-list">

                            <select name="listsem" id="list-sem" class="active-select-menu">
                                <option value="0">--Select--</option>
                                <?php
                                        $semesters = new Semester();

                                        $sem_list = $semesters->getSemester();

                                        foreach($sem_list as $sem_no => $sem)  { ?>

                                            <option value="<?php echo $sem_no;?>">
                                                <?php echo $sem; ?>
                                            </option>

                                <?php   }   ?>
                            </select>

                        </div>

                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="fp-details" id="fp-details-dept">

                        <label for="stream">Department</label>

                        <!--span class="ui-icon ui-icon-caret-1-s"></span-->
                        <div id="dept-list">
                            <select name="listdept" id="list-dept" disabled="true">
                                <option value="0">--Select--</option>
                                <?php
                                        $departments = new Department();

                                        $dept_list = $departments->getDepartment();

                                        foreach($dept_list as $dept_id => $dept)  { ?>

                                            <option value="<?php echo $dept_id;?>">
                                                <?php echo $dept; ?>
                                            </option>

                                <?php   }   ?>
                            </select>
                        </div>

                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="fp-details" id="fp-details-subject">

                        <label for="subject">Subject</label>

                        <!--span class="ui-icon ui-icon-caret-1-s"></span-->
                        <div id="subject-list">

                            <select name="listsubject" id="list-subject" disabled="true">

                                <option value="0">--Select--</option>

                            </select>

                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <div class="fp-details" id="fp-details-teacher">

                        <label for="teacher">Teacher</label>

                        <div id="teacher-list">

                            <select name="listteacher" id="list-teacher" disabled="true">

                                <option value="0">--Select--</option>

                            </select>

                        </div>
                    </div>
                </td>
            </tr>

        </tbody>

    </table>

    <div class="btn-form-submit">
        <input type="text" name="form" value="form-1" hidden="hidden">
        <input type="submit" name="btn-form-1-submit" id="btn-form-1-submit" value="Next">
    </div>

</form>