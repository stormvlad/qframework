<?php

    include_once("framework/class/object/object.class.php" );

    class BaseUnpacker extends Object {

        function BaseUnpacker()
        {
            $this->Object();
        }

        function unpack( $file, $destFolder )
        {
            throw( new Exception( "This method must be implemented by child classes!" ));

            die();
        }
    }

?>
