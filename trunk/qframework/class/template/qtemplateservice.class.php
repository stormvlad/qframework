<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");
    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/template/qtemplate.class.php");

    /**
     * Generic template rendering service that takes care of providing the Template objects
     * whenever requested.
     *
     * The advantage of using this TemplateService class is that we can delegate on it things
     * like finding the folder where the template is, choosing the right template depending
     * on the client type (normal browser, wap-enabled device, etc)
     *
     * In order to find the most suitable template, it takes several things into account:
     *
     * <ul>
     * <li>Settings stored for the current blog</li>
     * <li>User agent of the client</li> (<b>NOTE: </b>will not be implemented yet)
     * <li>The default template specified in the server-wide configuration file</li>
     * </ul>
     *
     */
    class qTemplateService extends qObject
    {
        /**
         * Constructor
         */
        function qTemplateService()
        {
            $this->qObject();

        }

        /**
         * Generates a Template object for the given template name. This fuction does <b>not</b>
         * require the full path to the file!!
         *
         * @param templateName The name of the template we would like to get
         * @param layout A predefined layout style
         * @return A Template object representing the template file we asked for.
         */
        function Template($templateName, $layout = "default")
        {
            // build the file name
            $templateFileName = $layout . "/" . $templateName . ".template";

            $t = new qTemplate( $templateFileName);

            return $t;
        }
    }
?>
