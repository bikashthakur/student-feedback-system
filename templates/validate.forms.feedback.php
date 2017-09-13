<?php
    session_start();
    header("Content-type: text/javascript");
    
    $_FORMS = array(
        'form'      => null,
        'form1'    => false,
        'form2'    => false,
        'form3'    => false,
        'success'   => false
    );

    if(isset($_POST['form'])) {
        
        $form = $_POST['form'];
        
        $_FORMS['form'] = $form;
                
        $_FORMS['success'] = true;
        
        //$_ERROR = false;//set error to false i.e. start with no error
        
        foreach($_POST as $k => $v) {
            
            $_SESSION[$form][$k] = $v;
            
            if($v == '0') {
                
                //$_ERROR = true;//set error to true if any of the field has value '0' or '' i.e. <empty>
                
                $_FORMS['success'] = false;
                
                unset($_SESSION[$form]);
                
                break;
            }
        }
        
        if(isset($_SESSION['form-1'])) {
            $_FORMS['form1'] = true;
        }
        
        if(isset($_SESSION['form-2'])) {
            $_FORMS['form2'] = true;
        }
        
        if(isset($_SESSION['form-3'])) {
            $_FORMS['form3'] = true;
        }
        
    }

    /*if(isset($_SESSION['form-1'])) {
        $_FORMS['form-1'] = true;
    }
    if(isset($_SESSION['form-2'])) {
        $_FORMS['form-2'] = true;
    }
    if(isset($_SESSION['form-3'])) {
        $_FORMS['form-3'] = true;
    }
    if(isset($_SESSION['form-1'],$_SESSION['form-2'],$_SESSION['form-3'])) {
        unset($_SESSION['form-1'], $_SESSION['form-2'], $_SESSION['form-3']);
        $_SESSION['feedback'] = true;
    }*/

    print_r(json_encode($_FORMS));

?>