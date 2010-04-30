<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    /**
    * Add class info here
    */
    class qCodePacker extends qObject
    {
        /**
         * Constructor
         */
        function qCodePacker()
        {
            $this->qObject();
        }

        /**
        * Add function here
        */
        function pack($code)
        {
            trigger_error("This function must be implemented by child classes.", E_USER_ERROR);
            return;
        }
    }
?>