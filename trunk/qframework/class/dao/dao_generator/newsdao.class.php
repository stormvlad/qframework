<?php

    include_once(QFRAMEWORK_CLASS_PATH . "qframework/class/data/qdate.class.php");
    include_once("class/dao/dao.class.php");
    include_once("class/config/config.class.php");

    /**
    * Dao base class.
    * Your dao classes should inherit from this.
    */
    class NewsDao extends Dao
    {
        /**
        * Constructor.
        * It will work with global db connection.
        */
        function NewsDao()
        {
            $this->Dao("news");
        }

        /**
        * Add function here
        */
        function getWhereClauseFromHttpRequest($httpRequest)
        {
            $searchText = $httpRequest->getValue("search_text");
            $startDate  = $httpRequest->getValue("start_date");
            $endDate    = $httpRequest->getValue("end_date");
            $where      = "id > 0";

            if (!empty($searchText))
            {
                $where .= " AND title LIKE '%" . $searchText . "%' OR subtitle LIKE '%" . $searchText . "%' OR text LIKE '%" . $searchText . "%'";
            }

            if (!empty($startDate))
            {
                $date = new qDate();
                $date->setDay(substr($startDate, 0, 2));
                $date->setMonth(substr($startDate, 3, 2));
                $date->setYear(substr($startDate, 6, 4));
                $date->setHour(0);
                $date->setMinute(0);
                $date->setSecond(0);
                $timeStamp = $date->getDate(DATE_FORMAT_TIMESTAMP);

                $where .= " AND date >= " . $timeStamp;
            }

            if (!empty($endDate))
            {
                $date = new qDate();
                $date->setDay(substr($endDate, 0, 2));
                $date->setMonth(substr($endDate, 3, 2));
                $date->setYear(substr($endDate, 6, 4));
                $date->setHour(0);
                $date->setMinute(0);
                $date->setSecond(0);
                $timeStamp = $date->getDate(DATE_FORMAT_TIMESTAMP);

                $where .= " AND date <= " . $timeStamp;
            }

            return $where;
        }

        /**
        * Add function here
        */
        function selectCountFromHttpRequest($httpRequest)
        {
            return $this->selectCount($this->getWhereClauseFromHttpRequest($httpRequest));
        }

        /**
        * Add function here
        */
        function selectFromHttpRequest($httpRequest)
        {
            $config = &Config::getConfig();
            $where  = $this->getWhereClauseFromHttpRequest($httpRequest);

            return $this->select($where, "date DESC", $httpRequest->getValue("offset"), $config->getValue("list_num_rows"));
        }
    }

?>