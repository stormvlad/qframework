<?php

    /**
     * @brief Proporciona una forma personalizable para dar formato a los datos.
     *
     * @author  qDevel - info@qdevel.com
     * @date    13/03/2005 04:22
     * @version 1.0
     * @ingroup log
     */
    class qLayout extends qObject
    {
        /**
         * Create a new Layout instance.
         *
         * @public
         * @since  1.0
         */
        function &qLayout()
        {
            parent::qObject();
        }

        /**
         * Format a message.
         *
         * @note This should never be called manually.
         * @param message qMessage A qMessage instance.
         *
         * @public
         * @since  1.0
         */
        function &format (&$message)
        {
            throw(new qException("qLayout::format(&$message) must be overridden"));
            die();
        }
    }

?>