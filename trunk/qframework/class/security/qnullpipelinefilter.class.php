<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/security/qpipelinefilter.class.php");

    /**
     * This is the simplest and fastest filter ever: it does nothing :)
     */
    class qNullPipelineFilter extends qPipelineFilter
    {
        function qNullPipelineFilter($pipelineRequest)
        {
            $this->qPipelineFilter($pipelineRequest);
        }

        function filter()
        {
            return new qPipelineResult(true);
        }
    }
?>