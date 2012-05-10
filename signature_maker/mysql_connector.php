<?php
    class mysql_connector
    {
        private $host;
        private $username;
        private $password;
        private $database;
        private $connection;
        private $results;

        public function __construct($m_host, $m_username, $m_pass, $m_database)
        {
            $this->host          = $m_host;
            $this->username      = $m_username;
            $this->password      = $m_pass;
            $this->database      = $m_database;
            $this->connection    = NULL;
            $this->results       = array();
        }

        public function __destruct()
        {
            $this->disconnect();
        }

        public function connect()
        {
            $this->connection = mysql_connect($this->host, $this->username, $this->password, true);
            if(!$this->connection)
                return false;

            if(!mysql_select_db($this->database, $this->connection))
                return false;

            return true;
        }

        public function disconnect()
        {
            if($this->connection)
            {
                mysql_close($this->connection);
                $this->connection = NULL;
            }
        }

        public function isOpen()
        {
            return $this->connection ? true : false;
        }

        public function query($query, $get_result = false)
        {
            if($this->isOpen())
                if($result = mysql_query($query, $this->connection))
                {
                    $this->results[] = $result;
                    if(!$get_result)
                        return (count($this->results) - 1);
                }

            if($get_result)
                return $this->getNextResult(count($this->results) - 1, true);
            else return -1;
        }

        public function getNextResult($query_number, $free_result = false)
        {
            if(!isset($this->results[$query_number]))
                return false;
            $return_row = mysql_fetch_array($this->results[$query_number], MYSQL_ASSOC);

            if((!$return_row || $free_result) && $this->results[$query_number])
                mysql_free_result($this->results[$query_number]);

            return $return_row;
        }
    }
?>