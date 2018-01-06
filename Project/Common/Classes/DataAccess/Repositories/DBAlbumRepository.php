<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 2018-01-04
 * Time: 22:02
 */

class DBAlbumRepository extends DBGenericRepository
{
    public function __construct(DBManager $dbManager)
    {
        parent::__construct($dbManager, "Albums");
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
        return new Album($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
    }

    public function getAll()
    {
        $result = $this->dbManager->queryAll($this->tableName);
        return $this->parseQuery($result);
    }

    public function getAllForUser($userId)
    {
        $query = "SELECT * FROM $this->tableName
                  WHERE
                    Owner_Id = '".$this->dbManager->escapeString($userId)."'";
        $result = $this->dbManager->queryCustom($query);
        return $this->parseQuery($result);
    }

    public function getAllForUserAccessibility($userId, $accessibilityCode)
    {
        $query = "SELECT * FROM $this->tableName
                  WHERE
                    Owner_Id = '".$this->dbManager->escapeString($userId)."' AND
                    Accessibility_Code = '".$this->dbManager->escapeString($accessibilityCode)."'";
        $result = $this->dbManager->queryCustom($query);
        return $this->parseQuery($result);
    }

//  Get Album by Album_Id
    function getID($key) {
        $result = $this->dbManager->queryByFilter($this->tableName, "Album_Id", $this->dbManager->escapeString($key));
        return $this->parseQuery($result)[0];
    }

//  Return true if success, else false
    function insert(Album $item) {
        $query = "INSERT INTO $this->tableName
                  VALUES(null, '".$this->dbManager->escapeString($item->Title)."', '".$this->dbManager->escapeString($item->Description)."', '".$this->dbManager->escapeString($item->Date_Updated)."', '".$this->dbManager->escapeString($item->Owner_Id)."', '".$this->dbManager->escapeString($item->Accessibility_Code)."')";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    function update(Album $item) {
        $query = "UPDATE $this->tableName
                  SET Title = '".$this->dbManager->escapeString($item->Title)."', Description = '".$this->dbManager->escapeString($item->Description)."', Date_Updated = '".$this->dbManager->escapeString($item->Date_Updated)."', Owner_Id = '".$this->dbManager->escapeString($item->Owner_Id)."', Accessibility_Code = '".$this->dbManager->escapeString($item->Accessibility_Code)."'
                  WHERE Album_Id = $item->Album_Id";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    function delete(Album $item) {
        $query = "DELETE FROM $this->tableName
                  WHERE Album_Id = ".$this->dbManager->escapeString($item->Album_Id);
        return $this->dbManager->queryCustom($query);
    }
}