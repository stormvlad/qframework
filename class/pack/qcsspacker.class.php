<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/pack/qcodepacker.class.php");
    
    /**
    * Add class info here
    */
    class qCssPacker extends qCodePacker
    {
        /**
         * Constructor
         */
        function qCssPacker()
        {
            $this->qCodePacker();
        }

        /**
        * Add function here
        */
        function pack($code)
        {
            if (is_file($code))
            {
                $code = file_get_contents($code);
            }
            
            include_once(QFRAMEWORK_CLASS_PATH . "qframework/libs/csstidy/class.csstidy.php");
            $packer = new csstidy();
    
            $packer->settings["remove_last_;"] = true;
            $packer->parse($code);
            $packer->load_template("highest_compression");
            
            return $packer->print->plain();
        }
    }
?>