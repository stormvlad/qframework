<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    if (PHP_VERSION < 5) include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qexception.php4.class.php");

    class qException extends Exception
    {
    }

?>
