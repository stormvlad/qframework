<?php

    require_once 'Date.php'; // PEAR include

    class qDate extends Date
    {
        function qDate($date = null)
        {
            $this->Date($date);
        }
        
        /**
        * Returns the years from a date
        */
        function getAge()
        {
            $unixtime = $this->getDate(DATE_FORMAT_UNIXTIME);
            
            if ($unixtime <= 0)
            {
                return 0;
            }
            
            $dob      = date("Y-m-d", $unixtime);
            $ageparts = explode("-", $dob);
            
            // calculate age
            $age = date("Y-m-d") - $dob;
            
            // return their age (or their age minus one year if it's not their birthday yet, in current year
            return (date("nd") < $ageparts[1] . str_pad($ageparts[2], 2, '0', STR_PAD_LEFT)) ? $age -= 1 : $age;
        }        
    }

?>