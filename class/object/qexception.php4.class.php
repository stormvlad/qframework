<?php

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
