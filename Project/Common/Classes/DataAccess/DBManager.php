<?php
class DBManager
{
    public $dbLink;

    public $host;
    public $username;
    public $password;
    public $dbName;
    public $port;

    function __construct() {
        // Because security isn't an issue for this test application,
        // We'll just hardcode this in. In a real application situation use
        // an external file
        $this->host = 'localhost';
        $this->username = 'PHPSCRIPT';
        $this->password = '1234';
        $this->dbName = 'cst8257';
        $this->port = 3306; // Default
        //$this->port = 3307; // Custom Port
    }

    function getError() { return mysqli_error($this->dbLink); }
    function getErrorCode() { return mysqli_errno($this->dbLink); }

    function isConnected() {
        return isset($this->dbLink);
    }

    function connect() {
        $this->dbLink = mysqli_connect(
            $this->host,
            $this->username,
            $this->password,
            $this->dbName,
            $this->port
        );

        if (!$this->dbLink) {
            echo "<p>".mysqli_connect_errno()."</p>";
            echo "<p>".mysqli_connect_error()."</p>";
            die("System is currently unavailable. Please try again later");
            //return false;
        }
        return true;
    }

    function close() {
        mysqli_close($this->dbLink);
        unset($this->dbLink);
    }

    function queryCustom($query) {
        //echo $query;
        $result = mysqli_query($this->dbLink, $query);
        return $result;
    }
    function queryAll($tableName) {
        $query = "SELECT * FROM $tableName";
        //echo $query;
        $result = mysqli_query($this->dbLink, $query);
        return $result;
    }
    function queryByFilter($tableName, $filterName, $filterValue) {
        $query = "SELECT *
                  FROM $tableName
                  WHERE $filterName = '$filterValue'";
        //echo $query;
        $result = mysqli_query($this->dbLink, $query);
        return $result;
    }


}