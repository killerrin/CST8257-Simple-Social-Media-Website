# Handle the Database State
#DROP SCHEMA IF EXISTS cst8257;
CREATE SCHEMA IF NOT EXISTS cst8257;
USE cst8257;

# Clear the DB
DROP TABLE IF EXISTS Friendships;

DROP TABLE IF EXISTS Comments;
DROP TABLE IF EXISTS Pictures;
DROP TABLE IF EXISTS Albums;

DROP TABLE IF EXISTS FriendshipStatus;
DROP TABLE IF EXISTS Users;
DROP TABLE IF EXISTS Accessibility;

# Create the Tables
CREATE TABLE Users
(
	User_Id		VARCHAR(16)		NOT NULL PRIMARY KEY,
    Name		VARCHAR(256)	NOT NULL,
    Phone		VARCHAR(16),
    Password	VARCHAR(256)
);

CREATE TABLE Accessibility
(
	Accessibility_Code	VARCHAR(16) 	NOT NULL PRIMARY KEY,
    Description			VARCHAR(127)	NOT NULL
);

CREATE TABLE Albums
(
	Album_Id			INT(11) 		PRIMARY KEY AUTO_INCREMENT,
    Title				VARCHAR(256) 	NOT NULL,
    Description			VARCHAR(3000),
    Date_Updated		DATE 			NOT NULL,
    Owner_Id			VARCHAR(16)		NOT NULL,
    Accessibility_Code	VARCHAR(16) 	NOT NULL,
    FOREIGN KEY (Owner_Id) REFERENCES Users(User_Id),
    FOREIGN KEY (Accessibility_Code) REFERENCES Accessibility(Accessibility_Code)
);

CREATE TABLE Pictures
(
	Picture_Id 		INT(11) 		NOT NULL PRIMARY KEY AUTO_INCREMENT,
	Album_Id 		INT(11) 		NOT NULL,
	FileName 		VARCHAR(255) 	NOT NULL,
   	Title 			VARCHAR(256) 	NOT NULL,
   	Description 	VARCHAR(3000),
	Date_Added 		DATE 			NOT NULL,
	FOREIGN KEY (Album_Id) REFERENCES Albums (Album_Id)
);

CREATE TABLE Comments
(
	Comment_Id 		INT(11) 		NOT NULL PRIMARY KEY AUTO_INCREMENT,
  	Author_Id 		VARCHAR(16) 	NOT NULL,
	Picture_Id 		INT(11) 		NOT NULL,
	Comment_Text 	VARCHAR(3000) 	NOT NULL,
	Date 			TIMESTAMP		NOT NULL DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (Author_Id) REFERENCES Users (User_Id),
	FOREIGN KEY (Picture_Id) REFERENCES Pictures (Picture_Id)
);

CREATE TABLE FriendshipStatus
(
	Status_Code		VARCHAR(16) 	NOT NULL PRIMARY KEY,
  	Description		VARCHAR(128) 	NOT NULL
);

CREATE TABLE Friendships
(
	Friend_RequesterId 	VARCHAR(16) 	NOT NULL,
	Friend_RequesteeId 	VARCHAR(16) 	NOT NULL,
	Status_Code			VARCHAR(16) 	NOT NULL,
	FOREIGN KEY (Friend_RequesterId) REFERENCES Users (User_Id),
	FOREIGN KEY (Friend_RequesteeId) REFERENCES Users (User_Id),
	FOREIGN KEY (Status_Code) REFERENCES FriendshipStatus (Status_Code)
);

# Create User
#DROP USER IF EXISTS 'PHPSCRIPT'@'*';
#CREATE USER 'PHPSCRIPT'@'*' IDENTIFIED BY '1234';
#GRANT ALL PRIVILEGES ON cst8257.* TO 'PHPSCRIPT'@'*';
DROP USER IF EXISTS 'PHPSCRIPT'@'localhost';
CREATE USER 'PHPSCRIPT'@'localhost' IDENTIFIED BY '1234';
GRANT ALL PRIVILEGES ON cst8257.* TO 'PHPSCRIPT'@'localhost';
FLUSH PRIVILEGES;