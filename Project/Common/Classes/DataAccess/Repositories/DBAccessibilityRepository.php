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
        return new Accessibility($row[0], $row[1]);
    }

    public function getAll()
    {
        $result = $this->dbManager->queryAll($this->tableName);
        return $this->parseQuery($result);
    }

    //  Get Accessibility by Accessibility_Code
    function getID($key) {
        $result = $this->dbManager->queryByFilter($this->tableName, "Accessibility_Code", $key);
        return $this->parseQuery($result);
    }

    //  Return true if success, else false
    function insert(Accessibility $item) {
        $query = "INSERT INTO $this->tableName
                  VALUES('$item->Accessibility_Code', '$item->Description')";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    function update(Accessibility $item) {
        $query = "UPDATE $this->tableName
                  SET Description = '$item->Description'
                  WHERE Accessibility_Code = '$item->Accessibility_Code'";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    function delete(Accessibility $item) {
        $query = "DELETE FROM $this->tableName
                  WHERE Accessibility_Code = '$item->Accessibility_Code'";
        return $this->dbManager->queryCustom($query);
    }
}