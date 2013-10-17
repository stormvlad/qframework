<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/object/qobject.class.php");

    define("QLOADER_DEFAULT_EXPIRATION", 24 * 60 * 60); // 24 hours of expiration
    define("QLOADER_DEFAULT_PREFIX", "asset{%d}-{%srv}");
    define("QLOADER_DEFAULT_BASE_FILE", APP_ROOT_PATH);
    define("QLOADER_DEFAULT_PATH", APP_ROOT_PATH . "tmp/loader/");
    
    /**
    * Add class info here
    */
    class qLoader extends qObject
    {
        var $_expiration;
        var $_prefix;
        var $_baseFile;
        var $_path;
        
        var $_loads;
        
        var $_urlPattern;
        
        /**
         * Constructor
         *
         * Params:
         *      - expiration:
         *          seconds to expire the generated file. 0 is no expiration. You can specify a date (or datetime) 
         *          with a string (p.ex. "2010-04-29" or "2010-04-29 10:00:00") and if generated file is previous
         *          to this date,it forces to generation.
         */
        function qLoader($expiration = QLOADER_DEFAULT_EXPIRATION, $prefix = QLOADER_DEFAULT_PREFIX, $baseFile = QLOADER_DEFAULT_BASE_FILE, $path = QLOADER_DEFAULT_PATH)
        {
            $this->qObject();
            
            $this->_expiration = $expiration;
            $this->_prefix     = $prefix;
            $this->_baseFile   = $baseFile;
            $this->_path       = $path;
            $this->_loads      = array();
            
            $this->autoSetUrlPattern();
        }

        /**
        * Add function here
        */
        function autoSetUrlPattern()
        {
            $path = str_replace("./", "", $this->_path);
            $this->_urlPattern = "{%proto}://" . $this->_prefix . ".{%domain}/{%path}" . $path . "{%file}";
        }
        
        /**
        * Add function here
        */
        function getFileName($name)
        {
            if (empty($this->_loads[$name]["file"]))
            {
                $path = substr($this->getUrl($name, false), 1);
                
                if (empty($this->_loads[$name]))
                {
                    $this->_loads[$name] = array();
                }
                
                $this->_loads[$name]["file"] = realpath(dirname($path)) . "/" . basename($path);
            }
            
            return $this->_loads[$name]["file"];
        }
        
        /**
        * Add function here
        */
        function getStatsFileName($name)
        {
            if (empty($this->_loads[$name]["sfile"]))
            {
                include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfile.class.php");
                    
                $fname = $this->getFileName($name);
                
                if (empty($this->_loads[$name]))
                {
                    $this->_loads[$name] = array();
                }
                
                $this->_loads[$name]["sfile"] = $fname . ".stats";
            }
            
            return $this->_loads[$name]["sfile"];
        }
        
        /**
        * Add function here
        */
        function getUrl($name, $absolute = true)
        {
            if (empty($this->_loads[$name]["url"]))
            {
                static $num;
                
                include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qurl.class.php");
                include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfile.class.php");
    
                $url     = new qUrl();
                $proto   = $url->getScheme();
                $d       = intVal($num);
                $server  = $url->getServer();
                $domain  = $url->getDomain();
                $path    = "";
                
                if (!$url->isSemantic())
                {
                    $path = $url->getPath();
                
                    if (substr($path, 0, 1) == "/")
                    {
                        $path = substr($path, 1);
                    }
                }

                $file    = qFile::getBaseName($name);
                $search  = array("{%proto}", "{%d}", "{%srv}", "{%domain}", "{%path}", "{%file}");
                $replace = array($proto, $d, $server, $domain, $path, $file);

                $num++;
                
                if (empty($this->_loads[$name]))
                {
                    $this->_loads[$name] = array();
                }

                $this->_loads[$name]["url"] = str_replace($search, $replace, $this->_urlPattern);
            }
            
            if (empty($absolute))
            {
                include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qurl.class.php");
                $url = new qUrl($this->_loads[$name]["url"]);
                return $url->getUri();
            }
            
            return $this->_loads[$name]["url"];
        }
        
        /**
        * Add function here
        */
        function getExpiration()
        {
            return $this->_expiration;
        }
        
        /**
        * Add function here
        */
        function setExpiration($expiration)
        {
            $this->_expiration = $expiration;
        }
        
        /**
        * Add function here
        */
        function getBaseFile()
        {
            return $this->_baseFile;
        }
        
        /**
        * Add function here
        */
        function setBaseFile($baseFile)
        {
            $this->_baseFile = $baseFile;
        }
        
        /**
        * Add function here
        */
        function getPrefix()
        {
            return $this->_prefix;
        }
        
        /**
        * Add function here
        */
        function setPrefix($prefix)
        {
            $this->_prefix = $prefix;
            $this->autoSetUrlPattern();
        }
        
        /**
        * Add function here
        */
        function isReloadRequired($name, $expiration = null)
        {
            $file = $this->getFileName($name);
            
            if (!is_file($file))
            {
                return true;
            }
            
            include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfile.class.php");
            $now  = time();
            $time = qFile::getTimeStamp($file);
            
            if ($expiration === null)
            {
                $expiration = $this->_expiration;
            }
            
            if (!empty($expiration))
            {
                // A date (or datetime) expiration
                if (is_string($expiration))
                {
                    $len = strlen($expiration);
                    
                    if ($len == 10)
                    {
                        $time = strftime("%Y-%m-%d", $time);
                    }
                    else if ($len == 19)
                    {
                        $time = strftime("%Y-%m-%d %H:%M:S", $time);
                    }
                    
                    return $time < $expiration;
                }
                // Expiration in seconds
                else if (($now - $time) > $expiration)
                {
                    return true;
                }
            }
            
            return false;
        }
        
        /**
        * Add function here
        */
        function pack($code, $type)
        {
            $className = "q" . ucFirst($type) . "Packer";
            include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/pack/" . strtolower($className) . ".class.php");
            $packer = new $className();
            
            return $packer->pack($code);
        }
        
        /**
        * Add function here
        */
        function load($name, $files, $pack = true, $stats = true, $expiration = null)
        {
            if ($this->isReloadRequired($name, $expiration))
            {
                if (empty($this->_loads[$name]))
                {
                    $this->_loads[$name] = array();
                }
                
                include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfile.class.php");
                include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qformat.class.php");
                include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/net/qclient.class.php");
                
                $this->_loads[$name]["loaded"] = strftime("%d/%m/%Y %H:%M:%S");
                $this->_loads[$name]["ip"]     = qClient::getIp();
                $this->_loads[$name]["params"] = array("files" => $files, "pack" => $pack);
                $this->_loads[$name]["files"]  = array();
                
                if (!is_array($files))
                {
                    $files = explode(",", $files);
                }
                
                $result = "";
                $fname  = $this->_path . $name;
                $fp     = new qFile($fname);
                $total  = 0;
                
                $fp->open("w");
                
                foreach ($files as $file)
                {
                    $file     = trim($file);
                    $packThis = $pack;
                    
                    if (substr($file, 0, 1) == "!")
                    {
                        $packThis = !$pack;
                        $file = substr($file, 1);
                    }
                    
                    if (!qFile::isAbsolutePath($file))
                    {
                        $file = realpath($this->_baseFile . $file);
                    }
                    
                    $size = qFile::getSize($file);
                    $this->_loads[$name]["files"][] = array(
                        "name" => $file,
                        "size" => qFormat::normalizeSize($size, 2)
                        );
                    
                    $total += $size;
                    $code   = file_get_contents($file);
                        
                    if (!empty($packThis))
                    {
                        $code = $this->pack($code, qFile::getExtension($file));
                    }
                    
                    $fp->write($code);
                }
                
                $fp->close();
                
                // And now calc the stats, if required
                $in    = qFormat::normalizeSize($total, 2);
                $out   = qFile::getNormalizedSize($fname, 2);;
                $ratio = number_format(1 - ($out / $in), 2);
                
                $this->_loads[$name]["stats"]  = array(
                    "in"    => $in,
                    "out"   => $out,
                    "ratio" => $ratio
                    );
                
                // Create stats file if required
                if (!empty($stats))
                {
                    $this->generateStatsFile($name);
                }
                
                $this->_loads[$name]["loaded"] = $this->_loads[$name]["loaded"] . " (in this request)";
            }
            //print "<pre>";print_r($this->_loads);print "</pre>";die;
            return $this->getUrl($name);
        }
        
        /**
        * Add function here
        */
        function getStats($name = null)
        {
            if (!empty($name))
            {
                if (empty($this->_loads[$name]) || empty($this->_loads[$name]["params"]))
                {
                    $this->readStatsFile($name);
                }
            
                return $this->_loads[$name];
            }
            
            foreach ($this->_loads as $name => $data)
            {
                $this->getStats($name);
            }
            
            return $this->_loads;
        }
        
        /**
        * Add function here
        */
        function readStatsFile($name)
        {
            $fname = $this->getStatsFileName($name);
            
            if (is_file($fname))
            {
                include($fname);
            }
            else
            {
                $this->_loads[$name]["sfile"] = "(not generated)";
            }
        }
        
        /**
        * Add function here
        */
        function generateStatsFile($name)
        {
            include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/file/qfile.class.php");
                
            $fname = $this->_path . baseName($this->getStatsFileName($name));
            $file  = new qFile($fname);
            $data  = "<?php" . PHP_EOL . "\$this->_loads['" . $name . "'] = " . var_export($this->_loads[$name], true) . ";" . PHP_EOL . "?>"; 
            
            $file->open("w");
            $file->write($data);
            $file->close();
        }
        
        /**
        * Add function here
        */
        function printStats($name = null)
        {
            print "<pre>";
            print_r($this->getStats($name));
            print "</pre>";
        }
        
        /**
        * Add function here
        */
        function &getInstance()
        {
            static $loaderInstance;

            if (!isset($loaderInstance))
            {
                $loaderInstance = new qLoader();
            }

            return $loaderInstance;
        }
    }
?>