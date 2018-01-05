<?php
class DBUserRepository extends DBGenericRepository
{
    function __construct(DBManager $dbManager) {
        parent::__construct($dbManager, "Users");
    }

    private function parseQuery($result) {
        $arrayResult = array();
        while ($row = mysqli_fetch_row($result))
        {
        	array_push($arrayResult, $this->rowToObject($row));
        }
        return $arrayResult;
    }
    private function rowToObject($row) {
        return new User($row[0], $row[1], $row[2], $row[3]);
    }

    function getAll() {
        $result = $this->dbManager->queryAll($this->tableName);
        return $this->parseQuery($result);
    }

    function getID($key) {
        $result = $this->dbManager->queryByFilter($this->tableName, "StudentId", $key);
        return $this->parseQuery($result);
    }

    // Return True of Success, False if failed
    function insert(User $item) {
        $query = "INSERT INTO $this->tableName
                  VALUES('$item->User_Id', '$item->Name', '$item->Phone', '$item->Password')";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    function update(User $item) {
        $query = "UPDATE $this->tableName
                  SET Name = '$item->Name', Phone = '$item->Phone', Password = '$item->Password'
                  WHERE User_Id = '$item->User_Id'";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    function delete(User $item) {
        $query = "DELETE FROM $this->tableName
                  WHERE User_Id = '$item->User_Id'";
        return $this->dbManager->queryCustom($query);
    }
}
?>