<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/request/qrequestparser.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qhttp.class.php");

    /**
     * @brief Analizador mejorado de cadenas de petición HTTP GET
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 18:19
     * @version 1.0
     * @ingroup request
     */
    class qRawRequestParser extends qRequestParser
    {
        function qRawRequestParser()
        {
            $this->qRequestParser("");
        }

        function parse(&$request)
        {
            $request->setValuesByRef(qHttp::getRequestVars()->getAsArray());
        }
    }
?>
