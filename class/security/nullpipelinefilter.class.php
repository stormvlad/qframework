<?php

    include_once("framework/class/security/pipelinefilter.class.php" );

    /**
     * This is the simplest and fastest filter ever: it does nothing :)
     */
    class NullPipelineFilter extends PipelineFilter
    {
        function NullPipelineFilter($pipelineRequest)
        {
            $this->PipelineFilter($pipelineRequest);
        }

        function filter()
        {
            return new PipelineResult(true);
        }
    }
?>