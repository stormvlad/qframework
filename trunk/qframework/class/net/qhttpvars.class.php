<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/config/qproperties.class.php");

    /**
     * Inherits from Properties but just to add some default
     * values to some settings
     */
    class qHttpVars extends qProperties
    {
        /**
         * Add function info here
         */
        function qHttpVars($params = null)
        {
            $this->qProperties($params);
        }

        /**
         * Add function info here
         */
        function _save(&$vars, $values)
        {
            foreach ($values as $key => $value)
            {
                $vars[$key] = $value;
            }
        }

        /**
         * Add function info here
         */
        function save()
        {
            throw(new Exception("qHttpVars::save: This method must be implemented by child classes."));
            die();
        }
    }
?>
