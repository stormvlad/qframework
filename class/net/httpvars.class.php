<?php
    include_once("framework/class/config/properties.class.php" );

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class HttpVars extends Properties {

        function HttpVars($params = null)
        {
            $this->Properties($params);
        }
    }
?>
