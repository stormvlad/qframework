<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qview.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/view/qsmartyrenderer.class.php");

    /**
     * @brief Vista usando el motor de renderizado con plantillas Smarty
     *
     * Smarty http://smarty.php.net
     *
     * @author  qDevel - info@qdevel.com
     * @date    06/03/2005 19:39
     * @version 1.0
     * @ingroup view
     * @see qSmartyRenderer
     */
          
    class qSmartyView extends qView
    {
        var $_templateName;
        var $_layout;

        /**
        *    Add function info here
        */
        function qSmartyView($templateName, $layout = null)
        {
            $this->qView(new qSmartyRenderer());

            $this->_templateName = $templateName;
            $this->_layout       = $layout;
        }

        /**
        *    Add function info here
        */
        function getTemplateName()
        {
            return $this->_templateName;
        }

        /**
        *    Add function info here
        */
        function setTemplateName($templateName)
        {
            $this->_templateName = $templateName;
        }

        /**
        *    Add function info here
        */
        function getLayout()
        {
            return $this->_layout;
        }

        /**
        *    Add function info here
        */
        function setLayout($layout)
        {
            $this->_layout = $layout;
        }
    }
?>
