<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/Date/Date.php");

    class qDate extends Date
    {
        function qDate($date = null)
        {
            $this->Date($date);
        }
    }

?>