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

    private function ParseQuery($result) {
        $arrayResult = array();
        while ($row = mysqli_fetch_row($result))
        {
            array_push($arrayResult, $this->rowToObject($row));
        }
        return $arrayResult;
    }

    private function rowToObject($row) {
        return new Friendship($row[0], $row[1], $row[2]);
    }

    public function getAll()
    {
        $result = $this->dbManager->queryAll($this->tableName);
        return $this->parseQuery($result);
    }

    function getID($requesterID, $requesteeID, $statusCode) {
        $query = "SELECT * FROM $this->tableName
                  WHERE
                    Friend_RequesterId = '$requesterID' AND
                    Friend_RequesteeId = '$requesteeID' AND
                    Status_Code = '$statusCode'";
        $result = $this->dbManager->queryCustom($query);
        return $this->parseQuery($result);
    }

    //  Return true if success, else false
    function insert(Friendship $item) {
        $query = "INSERT INTO $this->tableName
                  VALUES('$item->Friend_RequesterId', '$item->Friend_RequesteeId', 'Status_Code')";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    function update(Friendship $item) {
        $query = "UPDATE $this->tableName
                  SET Friend_RequesterId = '$item->Friend_RequesterId', Friend_RequesteeId = '$item->Friend_RequesteeId', Status_Code = '$item->Status_Code'
                  WHERE Status_Code = '$item->Status_Code'";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    function delete(Friendship $item) {
        $query = "DELETE FROM $this->tableName
                  WHERE
                    Friend_RequesterId = '$item->Friend_RequesterId' AND
                    Friend_RequesteeId = '$item->Friend_RequesteeId' AND
                    Status_Code = '$item->Status_Code'";
        return $this->dbManager->queryCustom($query);
    }
}