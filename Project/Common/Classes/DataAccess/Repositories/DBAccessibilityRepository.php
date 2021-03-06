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

    private function ParseQuery($result) : array {
        $arrayResult = array();
        while ($row = mysqli_fetch_row($result))
        {
            array_push($arrayResult, $this->rowToObject($row));
        }
        return $arrayResult;
    }

    private function rowToObject($row) : Accessibility {
        return new Accessibility($row[0], $row[1]);
    }

    public function getAll() : array
    {
        $result = $this->dbManager->queryAll($this->tableName);
        return $this->parseQuery($result);
    }

    //  Get Accessibility by Accessibility_Code
    public function getID($key) : ?Accessibility {
        $result = $this->dbManager->queryByFilter($this->tableName, "Accessibility_Code", $this->dbManager->escapeString($key));
        return $this->parseQuery($result)[0];
    }

    //  Return true if success, else false
    public function insert(Accessibility $item) {
        $query = "INSERT INTO $this->tableName
                  VALUES('".$this->dbManager->escapeString($item->Accessibility_Code)."', '".$this->dbManager->escapeString($item->Description)."')";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    public function update(Accessibility $item) {
        $query = "UPDATE $this->tableName
                  SET Description = '".$this->dbManager->escapeString($item->Description)."'
                  WHERE Accessibility_Code = '".$this->dbManager->escapeString($item->Accessibility_Code)."'";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    public function delete(Accessibility $item) {
        $query = "DELETE FROM $this->tableName
                  WHERE Accessibility_Code = '".$this->dbManager->escapeString($item->Accessibility_Code)."'";
        return $this->dbManager->queryCustom($query);
    }
}