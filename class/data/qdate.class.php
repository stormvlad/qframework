<?php

    require_once 'Date.php'; // PEAR include

    class qDate extends Date
    {
        function qDate($date = null)
        {
            $this->Date($date);
        }
    }

?>