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

//  Get Album by Album_Id
    function getID($key) {
        $result = $this->dbManager->queryByFilter($this->tableName, "Album_Id", $key);
        return $this->parseQuery($result);
    }

//  Return true if success, else false
    function insert(Album $item) {
        $query = "INSERT INTO $this->tableName
                  VALUES('$item->Album_Id', '$item->Title', '$item->Description', '$item->Date_Updated', '$item->Owner_Id', '$item->Accessibility_Code')";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    function update(Album $item) {
        $query = "UPDATE $this->tableName
                  SET Title = '$item->Title', Description = '$item->Description', Date_Updated = '$item->Date_Updated', Owner_Id = '$item->Owner_Id', Accessibility_Code = '$item->Accessibility_Code'
                  WHERE Album_Id = '$item->Album_Id'";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    function delete(Album $item) {
        $query = "DELETE FROM $this->tableName
                  WHERE Album_Id = '$item->Album_Id'";
        return $this->dbManager->queryCustom($query);
    }
}