<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/user/quserstorage.class.php");

    /**
     * @brief Servicio de almacenaje para los datos de usuario en sessión de PHP
     *
     * @author  qDevel - info@qdevel.com
     * @date    18/03/2005 20:42
     * @version 1.0
     * @ingroup core
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
            $formValues     = $session->getValue("formValues");
            $permissions    = $session->getValue("permissions");

            $history        = $session->getValue("history");
            $historyIndex   = $session->getValue("historyIndex");
            $historySize    = $session->getValue("historySize");

            if (empty($attributes))
            {
                 $attributes = array();
            }

            $user->setAuthenticated($auth);
            $user->setLoginName($loginName);
            $user->setLastActionTime($lastActionTime);
            $user->setAttributes($attributes);
            $user->setFormValues(null, $formValues);
            $user->setPermissions($permissions);

            $user->setHistory($history);
            $user->setHistoryIndex($historyIndex);
            $user->setHistorySize($historySize);
            
            return true;
        }

        /**
        * Add function info here
        */
        function store(&$user)
        {
            $session     = &qHttp::getSessionVars();
            $attributes  = &$user->getAttributes();
            $formValues  = &$user->getFormValues();
            $permissions = &$user->getPermissions();

            $session->setValue("auth", $user->isAuthenticated());
            $session->setValue("loginName", $user->getLoginName());
            $session->setValue("lastActionTime", $user->getLastActionTime());
            $session->setValue("attributes", $attributes);
            $session->setValue("formValues", $formValues);
            $session->setValue("permissions", $permissions);

            $session->setValue("history", $user->getHistory());
            $session->setValue("historyIndex", $user->getHistoryIndex());
            $session->setValue("historySize", $user->getHistorySize());
            
            $session->save();
        }
    }

?>