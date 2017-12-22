USE CST8257;

INSERT INTO Accessibility (Accessibility_Code, Description) VALUES
('private', 'Accessible only by the owner'),
('shared', 'Accessible by the owner and friends');

INSERT INTO FriendshipStatus (Status_Code, Description) VALUES
('accepted', 'The request to become a friend has been accepted'),
('request', 'A request has been sent to become a friend');