<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qrequest.class.php");

    /**
     * This is the parameter that will be used in the pipeline, to communicate
     * with the filters that 'sit' in the pipeline
     */
    class qPipelineRequest extends qRequest
    {
        function qPipelineRequest($httpRequest)
        {
            $this->qRequest($httpRequest->getAsArray());
        }
    }
?>
