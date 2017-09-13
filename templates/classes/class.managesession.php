<?php

    class ManageSession {
        
        private $session;
        private $session_value;
        
        function __construct($session, $session_value) {
            
            $this->session = $session;
            $this->session_value = $session_value;
            
        }
        
        function setSession() {
            
            $_SESSION[$this->session] = $this->session_value;
        }
        
        function unsetSession() {
            
            $_SESSION[$this->session] = null;
            unset($_SESSION[$this->session]);
            
        }
        
        function isSessionSet() {
            
            if(isset($_SESSION[$this->session])) {
                
                return true;
                
            } else {
                
                return false;
                
            }
            
        }
        
    }

?>