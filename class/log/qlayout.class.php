<?php

    /**
     * @brief Proporciona una plantila personalizable para dar formato a los datos.
     *
     * @author  qDevel - info@qdevel.com
     * @date    13/03/2005 04:22
     * @version 1.0
     * @ingroup log
     */
    class qLayout extends qObject
    {
        /**
         * Constructor
         */
        function qLayout()
        {
            $this->qObject();
        }

        /**
         * Da formato a un mensaje
         *
         * @param message qMessage Una instancia de qMessage
         * @note Esta función debe llamarse manualmente
         */
        function format(&$message)
        {
            trigger_error("This method must be implemented by child classes.", E_USER_ERROR);
            return;
        }
    }

?>