<?php
    session_start();

    $_FORMS = array(
        'form1'  =>  false,
        'form2'  =>  false,
        'form3'  =>  false,
    );
    
    if(isset($_SESSION['form-1'])) {
        $_FORMS['form1'] = true;
    }

    if(isset($_SESSION['form-2'])) {
        $_FORMS['form2'] = true;
    }

    if(isset($_SESSION['form-3'])) {
        $_FORMS['form3'] = true;
    }
    
    print_r(json_encode($_FORMS));

?>