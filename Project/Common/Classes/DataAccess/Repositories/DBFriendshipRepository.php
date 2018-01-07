<?php
/**
 * DBFriendshipStatusRepository short summary.
 *
 * DBFriendshipStatusRepository description.
 *
 * @version 1.0
 * @author andre
 */
class DBFriendshipRepository extends DBGenericRepository
{
    public function __construct(DBManager $dbManager)
    {
        parent::__construct($dbManager, "Friendships");
    }

    private function ParseQuery($result) : array {
        $arrayResult = array();
        while ($row = mysqli_fetch_row($result))
        {
            array_push($arrayResult, $this->rowToObject($row));
        }
        return $arrayResult;
    }

    private function rowToObject($row) : Friendship {
        return new Friendship($row[0], $row[1], $row[2]);
    }

    public function getAll() : array
    {
        $result = $this->dbManager->queryAll($this->tableName);
        return $this->parseQuery($result);
    }

    public function getAllForUser($userId) : array
    {
        $query = "SELECT * FROM $this->tableName
                  WHERE
                    Friend_RequesterId = '".$this->dbManager->escapeString($userId)."' OR
                    Friend_RequesteeId = '".$this->dbManager->escapeString($userId)."'";
        $result = $this->dbManager->queryCustom($query);
        return $this->parseQuery($result);
    }

    public function getID($requesterID, $requesteeID, $statusCode) : ?Friendship {
        $query = "SELECT * FROM $this->tableName
                  WHERE
                    Friend_RequesterId = '".$this->dbManager->escapeString($requesterID)."' AND
                    Friend_RequesteeId = '".$this->dbManager->escapeString($requesteeID)."' AND
                    Status_Code = '".$this->dbManager->escapeString($statusCode)."'";
        $result = $this->dbManager->queryCustom($query);
        return $this->parseQuery($result)[0];
    }

    //  Return true if success, else false
    public function insert(Friendship $item) {
        $query = "INSERT INTO $this->tableName
                  VALUES('".$this->dbManager->escapeString($item->Friend_RequesterId)."', '".$this->dbManager->escapeString($item->Friend_RequesteeId)."', '".$this->dbManager->escapeString($item->Status_Code)."')";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    public function update(Friendship $item) {
        $query = "UPDATE $this->tableName
                  SET Status_Code = '".$this->dbManager->escapeString($item->Status_Code)."'
                  WHERE Friend_RequesterId = '".$this->dbManager->escapeString($item->Friend_RequesterId)."' AND Friend_RequesteeId = '".$this->dbManager->escapeString($item->Friend_RequesteeId)."'";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    public function delete(Friendship $item) {
        $query = "DELETE FROM $this->tableName
                  WHERE
                    Friend_RequesterId = '".$this->dbManager->escapeString($item->Friend_RequesterId)."' AND
                    Friend_RequesteeId = '".$this->dbManager->escapeString($item->Friend_RequesteeId)."' AND
                    Status_Code = '".$this->dbManager->escapeString($item->Status_Code)."'";
        return $this->dbManager->queryCustom($query);
    }
}