<?php

    include_once("qframework/class/object/qobject.class.php" );

    class qBaseUnpacker extends qObject {

        function qBaseUnpacker()
        {
            $this->qObject();
        }

        function unpack( $file, $destFolder )
        {
            throw( new qException( "This method must be implemented by child classes!" ));

            die();
        }
    }

?>
