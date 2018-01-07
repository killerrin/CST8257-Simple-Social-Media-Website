<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 2018-01-05
 * Time: 14:55
 */

class DBCommentRepository extends DBGenericRepository
{
    public function __construct(DBManager $dbManager)
    {
        parent::__construct($dbManager, "Comments");
    }

    private function ParseQuery($result) : array {
        $arrayResult = array();
        while ($row = mysqli_fetch_row($result))
        {
            array_push($arrayResult, $this->rowToObject($row));
        }
        return $arrayResult;
    }

    private function rowToObject($row) : Comment {
        return new Comment($row[0], $row[1], $row[2], $row[3], $row[4]);
    }

    public function getAll() : array
    {
        $result = $this->dbManager->queryAll($this->tableName);
        return $this->parseQuery($result);
    }

    //  Get Accessibility by Accessibility_Code
    public function getID($key) : Comment {
        $result = $this->dbManager->queryByFilter($this->tableName, "Comment_Id", $this->dbManager->escapeString($key));
        return $this->parseQuery($result)[0];
    }

    //  Return true if success, else false
    public function insert(Comment $item) {
        $query = "INSERT INTO $this->tableName
                  VALUES(null, '".$this->dbManager->escapeString($item->Author_Id)."', '".$this->dbManager->escapeString($item->Picture_Id)."', '".$this->dbManager->escapeString($item->Comment_Text)."', '".$this->dbManager->escapeString($item->Date)."')";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    public function update(Comment $item) {
        $query = "UPDATE $this->tableName
                  SET Comment_Id = '".$this->dbManager->escapeString($item->Comment_Id)."',
                  Author_Id = '".$this->dbManager->escapeString($item->Author_Id)."',
                  Picture_Id = '".$this->dbManager->escapeString($item->Picture_Id)."',
                  Comment_Text = '".$this->dbManager->escapeString($item->Comment_Text)."',
                  Date = '".$this->dbManager->escapeString($item->Date)."'
                  WHERE Comment_Id = '".$this->dbManager->escapeString($item->Comment_Id)."'";
        return $this->dbManager->queryCustom($query);
    }

    // Return True of Success, False if failed
    public function delete(Comment $item) {
        $query = "DELETE FROM $this->tableName
                  WHERE Comment_Id = '".$this->dbManager->escapeString($item->Comment_Id)."'";
        return $this->dbManager->queryCustom($query);
    }
}