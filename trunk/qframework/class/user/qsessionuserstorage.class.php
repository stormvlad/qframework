<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/user/quserstorage.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qSessionUserStorage extends qUserStorage
    {
        /**
        * Add function info here
        */
        function qSessionUserStorage()
        {
            $this->qUserStorage();
        }

        /**
        * Add function info here
        */
        function load(&$user)
        {
            $session = &qHttp::getSessionVars();
            $auth       = $session->getValue("auth");
            $attributes = $session->getValue("attributes");

            if (empty($attributes))
            {
                 $attributes = array();
            }

            $user->setAuthenticated($auth);
            $user->setAttributes($attributes);

            return true;
        }

        /**
        * Add function info here
        */
        function store(&$user)
        {
            $session    = &qHttp::getSessionVars();
            $attributes = &$user->getAttributes();

            $session->setValue("auth", $user->isAuthenticated());
            $session->setValue("attributes", $attributes);
            $session->save();
        }
    }

?>