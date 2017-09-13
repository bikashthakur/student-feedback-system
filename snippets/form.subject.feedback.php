<form action="" method="POST" name="form-2" id="form-2">
                        
    <table class="fp-form-table">

        <thead class="tbl-heading">
            <tr>
                <th class="tbl-heading-q">Question</th>
                <th class="tbl-heading-p">Select Option</th>
                <th class="tbl-heading-o">Point Scale</th>
            </tr>
        </thead>

        <tbody class="tbl-content">

            <?php 

                $_question = new DBQuestion('form-subject');

                $questions = $_question->getAllQuestionsWithOptions();

                $i = 1;

                    foreach($questions as $question => $options) { ?>

                        <tr>
                            <td>
                                <div class="fp-question">
                                    <label>
                                        <?php echo $question ;?>
                                    </label>
                                    <span class="fp-require">*</span>
                                </div>
                            </td>

                            <td>
                                <div class="fp-options">
                                    <?php 
                                            $ques_id = 'question_'.$i++;
                                            $option1 = $options['option1'];
                                            $option2 = $options['option2'];
                                            $option3 = $options['option3'];
                                    ?>
                                    <select class="select-op" name="<?php echo $ques_id ;?>" value="" style="width:120px">
                                            <option value="0" selected="selected">Select</option>
                                            <option value="<?php echo $option1; ?>"> <?php echo $option1;?> </option>
                                            <option value="<?php echo $option2; ?>"> <?php echo $option2;?> </option>
                                            <option value="<?php echo $option3; ?>"> <?php echo $option3;?> </option>
                                    </select>
                                </div>
                            </td>

                            <td>
                                <div class="fp-options option-points">
                                    <!--input list="fp-point-scale-<--?php echo $ques_id; ?>"-->
                                    <select class="fp-point-scale" name="<?php echo $ques_id; ?>_point" style="width:120px">
                                        <!--optgroup label="default"-->
                                            <option value="0" selected="selected">Select</option>
                                        <!--/optgroup-->
                                        <optgroup label="<?php echo $option1; ?>">
                                            <option value="10" disabled="disabled">10</option>
                                            <option value="9" disabled="disabled">9</option>
                                            <option value="8" disabled="disabled">8</option>
                                        </optgroup>
                                        <optgroup label="<?php echo $option2; ?>">
                                            <option value="7" disabled="disabled">7</option>
                                            <option value="6" disabled="disabled">6</option>
                                            <option value="5" disabled="disabled">5</option>
                                        </optgroup>
                                        <optgroup label="<?php echo $option3; ?>">
                                            <option value="4" disabled="disabled">4</option>
                                            <option value="3" disabled="disabled">less than 3</option>
                                        </optgroup>
                                    </select>
                                </div>
                            </td>

                        </tr>

            <?php   }   ?>

        </tbody>

    </table>

    <div class="btn-form-submit">
        <input type="text" name="form" value="form-2" hidden="hidden">
        <input type="submit" name="btn-form-2-submit" id="btn-form-2-submit" value="Next" disabled>
    </div>

</form>