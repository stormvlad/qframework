<?php

    include_once("framework/class/net/request.class.php" );

    /**
     * This is the parameter that will be used in the pipeline, to communicate
     * with the filters that 'sit' in the pipeline
     */
    class PipelineRequest extends Request
    {
        function PipelineRequest($httpRequest)
        {
            $this->Request($httpRequest->getAsArray());
        }
    }
?>
