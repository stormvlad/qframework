<?php

    define ("E_STRICT", 2048);
    
    /**
     * Throws an exception
     */
    function throw($exception)
    {
        $exception->qthrow();
    }

    function catch($exception)
    {
        print("Exception catched!");
    }

?>
