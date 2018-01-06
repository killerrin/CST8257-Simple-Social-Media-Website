<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 2018-01-05
 * Time: 14:55
 */

class DBPictureRepository extends DBGenericRepository
{
    public function __construct(DBManager $dbManager)
    {
        parent::__construct($dbManager, "Pictures");
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
        return new Picture($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
    }

    public function getAll()
    {
        $result = $this->dbManager->queryAll($this->tableName);
        return $this->parseQuery($result);
    }

    //  Get Picture by Picture_Id
    function getID($key) {
        $result = $this->dbManager->queryByFilter($this->tableName, "Picture_Id", $this->dbManager->escapeString($key));
        return $this->parseQuery($result)[0];
    }

    public function getAlbumFilename($albumID, $fileName)
    {
        $query = "SELECT * FROM $this->tableName
                  WHERE
                    Album_Id = ".$this->dbManager->escapeString($albumID)." AND
                    FileName = '".$this->dbManager->escapeString($fileName)."'";
        $result = $this->dbManager->queryCustom($query);
        return $this->parseQuery($result);
    }

    //  Return true if success, else false
    function insert(Picture $item) {
        $query = "INSERT INTO $this->tableName
                  VALUES(null, '".$this->dbManager->escapeString($item->Album_Id)."', '".$this->dbManager->escapeString($item->FileName)."', '".$this->dbManager->escapeString($item->Title)."', '".$this->dbManager->escapeString($item->Description)."', '".$this->dbManager->escapeString($item->Date_Added)."')";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    function update(Picture $item) {
        $query = "UPDATE $this->tableName
                  SET Album_Id = '".$this->dbManager->escapeString($item->Album_Id)."',
                  FileName = '".$this->dbManager->escapeString($item->FileName)."',
                  Title = '".$this->dbManager->escapeString($item->Title)."',
                  Description = '".$this->dbManager->escapeString($item->Description)."',
                  Date_Added = '".$this->dbManager->escapeString($item->Date_Added)."'
                  WHERE Picture_Id = '".$this->dbManager->escapeString($item->Picture_Id)."'";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    function delete(Picture $item) {
        $query = "DELETE FROM $this->tableName
                  WHERE Picture_Id = '".$this->dbManager->escapeString($item->Picture_Id)."'";
        return $this->dbManager->queryCustom($query);
    }
}