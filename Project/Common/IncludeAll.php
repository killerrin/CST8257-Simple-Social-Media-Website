<?php
// Include important pages here
// ================================================================================

// ================ Models ================
include_once("Classes/Models/Accessibility.php");
include_once("Classes/Models/FriendshipStatus.php");
include_once("Classes/Models/User.php");
include_once("Classes/Models/Friendship.php");
include_once("Classes/Models/Album.php");
include_once("Classes/Models/Picture.php");
include_once("Classes/Models/Comment.php");

// ================ Database ==============
include_once("Classes/DataAccess/DBManager.php");
include_once("Classes/DataAccess/DBGenericRepository.php");
include_once("Classes/DataAccess/Repositories/DBUserRepository.php");
include_once("Classes/DataAccess/Repositories/DBAccessibilityRepository.php");
include_once("Classes/DataAccess/Repositories/DBAlbumRepository.php");
include_once("Classes/DataAccess/Repositories/DBFriendshipStatusRepository.php");
include_once("Classes/DataAccess/Repositories/DBFriendshipRepository.php");
include_once("Classes/DataAccess/Repositories/DBCommentRepository.php");
include_once("Classes/DataAccess/Repositories/DBPictureRepository.php");

// ================ Other =================
//include_once("Classes/Image/Picture.php");
include_once("Classes/Image/ImageManipulation.php");

// ================================================================================
// Start the Session in the Header since the header is included in all the pages
session_start();
?>