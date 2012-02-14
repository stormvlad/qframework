<?php

    require_once 'Date.php'; // PEAR include

    /**
     * @brief Encapsula el tipo qDate para fechas
     *
     * Esta clase es un simple enmascaramiento para qFramework de la 
     * libreria <a href="http://pear.php.net/package/Date">Date</a> 
     * de <a href="http://pear.php.net/">PEAR</a>.
     * 
     * qDate nos permite la manipulacion de fechas, horas y zonas horarias
     * sin la necesidad de timestamps -gran limitación en muchos programas PHP-.
     * No se rige en el sistema de 32 bits de los timestamps con lo que
     * podemos mostrar calendarios o comparar fechas anteriores a 1970 y 
     * posteriores a 2038. 
     * 
     * Mas información:
     * - http://pear.php.net/package/Date/
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 17:16
     * @version 1.0
     * @ingroup data
     */
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