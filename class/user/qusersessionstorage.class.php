<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/user/quserstorage.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qUserSessionStorage extends qUserStorage
    {
        /**
        * Add function info here
        */
        function qUserSessionStorage()
        {
            $this->qUserStorage();
        }

        /**
        * Add function info here
        */
        function load(&$user)
        {
            $session        = &qHttp::getSessionVars();
            $auth           = $session->getValue("auth");
            $loginName      = $session->getValue("loginName");
            $lastActionTime = $session->getValue("lastActionTime");
            $attributes     = $session->getValue("attributes");
            $permissions    = $session->getValue("permissions");

            if (empty($attributes))
            {
                 $attributes = array();
            }

            $user->setAuthenticated($auth);
            $user->setLoginName($loginName);
            $user->setLastActionTime($lastActionTime);
            $user->setAttributes($attributes);
            $user->setPermissions($permissions);

            return true;
        }

        /**
        * Add function info here
        */
        function store(&$user)
        {
            $session     = &qHttp::getSessionVars();
            $attributes  = &$user->getAttributes();
            $permissions = &$user->getPermissions();

            $session->setValue("auth", $user->isAuthenticated());
            $session->setValue("loginName", $user->getLoginName());
            $session->setValue("lastActionTime", $user->getLastActionTime());
            $session->setValue("attributes", $attributes);
            $session->setValue("permissions", $permissions);
            $session->save();
        }
    }

?>