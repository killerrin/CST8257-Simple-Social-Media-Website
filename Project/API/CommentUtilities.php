<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 2018-01-08
 * Time: 11:37
 */

include_once("../Common/IncludeAll.php");
header("Content-Type: application/json");

if (empty($_GET)) die();

$dbManager = new DBManager();
$userRepo = new DBUserRepository($dbManager);

$dbManager->connect();

switch (count($_GET)) {
    case 1:
        switch (array_keys($_GET)[0]) {
            case "userID":
                $name = $userRepo->getID($_GET['userID'])->Name;

                echo json_encode($name);
                break;
            default:
                break;
        }
        break;
    default:
        break;
}


$dbManager->close();