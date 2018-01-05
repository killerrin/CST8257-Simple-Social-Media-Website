<?php
/**
 * DBAccessibilityRepository short summary.
 *
 * DBAccessibilityRepository description.
 *
 * @version 1.0
 * @author andre
 */
class DBAccessibilityRepository extends DBGenericRepository
{
    public function __construct(DBManager $dbManager)
    {
        parent::__construct($dbManager, "Accessibility");
    }

    private function ParseQuery($result) {
        $arrayResult = array();
        while ($row = mysqli_fetch_row($result))
        {
            array_push($arrayResult, $this->rowToObject($row));
        }
        return $arrayResult;
    }

    private function rowToObject($row) {
    }

    public function getAll()
    {
        $result = $this->dbManager->queryAll($this->tableName);
        return $this->parseQuery($result);
    }

    function getID($key) {
        return $this->parseQuery($result);
    }

        $query = "INSERT INTO $this->tableName
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
        $query = "UPDATE $this->tableName
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
        $query = "DELETE FROM $this->tableName
        return $this->dbManager->queryCustom($query);
    }
}
