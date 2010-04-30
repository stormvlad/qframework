<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/pack/qcodepacker.class.php");
    
    /**
    * Add class info here
    */
    class qJsPacker extends qCodePacker
    {
        /**
         * Constructor
         */
        function qJsPacker()
        {
            $this->qCodePacker();
        }

        /**
        * Add function here
        */
        function pack($code)
        {
            include_once(QFRAMEWORK_CLASS_PATH . "qframework/libs/jsmin/jsmin.class.php");
            return JSMin::minify($code);
        }
    }
?>