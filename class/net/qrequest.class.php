<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");

    /**
     * Represents a request in our system. Doing so we can in the future
     * change the format requests are recognized since all the dirty
     * stuff would be done here. After that, using an interface of the type
     * getValue( "param" ) would be enough to access those values, regardless
     * if the request was /index.php?op=Default&articleId=10 or
     * /index.php/op/Default/articleId/10.
     */
    class qRequest extends qProperties
    {
        function qRequest($values = null)
        {
            $this->qProperties($values);
        }
    }
?>
