<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/libs/magpierss/rss_fetch.inc");

    /**
     * @brief Encapsula el acceso a una recurso RSS
     *
     * Esta clase es un simple enmascaramiento para qFramework de la 
     * libreria funciones <a href="http://magpierss.sourceforge.net/">MagpieRSS</a>.
     * 
     * <a href="http://es.wikipedia.org/wiki/RSS">RSS</a> es un acrónimo que tiene diferentes significados, 
     * pero el más aceptado es Really Simple Syndication (sindicación realmente simple). Es un formato XML indicado 
     * especialmente para sitios de noticias que cambien con relativa frecuencia, cuyos documentos
     * están estructurados en canales que a su vez se componen de artículos. Se ha popularizado
     * especialmente como un formato alternativo de difusión de weblogs.
     *
     * Mas información sobre la libreria MagpieRSS:
     * - http://magpierss.sourceforge.net/
     *
     * Mas información sobre RSS:
     * - http://es.wikipedia.org/wiki/RSS
     *
     * @author  qDevel - info@qdevel.com
     * @date    22/03/2005 18:00
     * @version 1.0
     * @ingroup net
     */
    class qRss extends qObject
    {
        var $_channel;
        var $_items;
        var $_image;
        var $_textInput;

        /**
        *    Add function info here
        */
        function qRss()
        {
            $this->qObject();

            $this->_channel   = null;
            $this->_items     = null;
            $this->_image     = null;
            $this->_textInput = null;
        }

        /**
        *    Add function info here
        */
        function getChannelInfo()
        {
            return $this->_channel;
        }

        /**
        *    Add function info here
        */
        function getImage()
        {
            return $this->_image;
        }

        /**
        *    Add function info here
        */
        function getItems()
        {
            return $this->_items;
        }

        /**
        *    Add function info here
        */
        function getTextinput()
        {
            return $this->_textInput;
        }

        /**
        *    Add function info here
        */
        function parse($url)
        {
            $rss             = @fetch_rss($url);

            if (empty($rss))
            {
                return false;
            }

            $this->_channel   = $rss->channel;
            $this->_items     = $rss->items;
            $this->_image     = $rss->image;
            $this->_textInput = $rss->textinput;

            return true;
        }
    }
?>
