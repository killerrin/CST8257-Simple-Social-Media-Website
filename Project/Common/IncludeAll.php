<?php
// Include important pages here
// ================================================================================

// ================ Models ================

// ================ Database ==============
include_once("Classes/DataAccess/DBManager.php");
include_once("Classes/DataAccess/DBGenericRepository.php");


// ================================================================================
// Start the Session in the Header since the header is included in all the pages
session_start();
?>