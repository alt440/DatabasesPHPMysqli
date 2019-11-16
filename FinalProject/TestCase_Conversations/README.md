# Conversations
In this directory, you will find how I initialized my tables (in createTables.php), populated them with data (populateTables.php), and how I executed three test cases related to conversations. 

Note: I have put all the data I needed in the Participant table, because I did not need to create a Person table to demonstrate my test cases.

Note: I am using XAMPP and PHPMyAdmin. Refer to the main directory of this git to know how to use these tools.

Warning: Some variables/tables I have put do not fully follow the ER diagram we came up with. An updated ER diagram will soon follow.

Here is the list of test cases I covered:

1.Request conversation content in the order they were originally put with the names of the people who published that content in a group.

2.Request conversation content in the order they were originally put with the names of the people who published that content on an event page.

3.Request conversation content in the order they were originally put with the names of the people who published that content on an event page along with comments (different privilege level).

# createTables.php
This script shows you how I created my tables. I will soon put an ER diagram explaining the changes I have made to the ER diagram to make it all work.

# populateTables.php
This script shows you how I organized the data in the tables. It shows you examples of content that needed to be put using my architecture to make the queries work.

# testLogicConversation.php
This script demonstrates how I fulfilled test case 1 (with output).

# testLogicEventConversation.php
This script demonstrates how I fulfilled test case 2 (with output).

# testLogicEventWCommentConversation.php
This script demonstrates how I fulfilled test case 3 (with output).

# UpdatedER
This file can be imported into draw.io to see what changes I have put to the ER model to make my test cases work. Go to File->Open From->Device and select this file and you will see the differences of my ER diagram.

Here are some comments on the changes that I have made:

- I realized that the Participant - Content relationship is actually a 1 to m relationship, because a participant can have different contents, but a content cannot come from multiple participants. (so Post and Post_Comment do not need (CID, PID) and (CoID, PID) as their composite keys, they only need (CID) and (CoID)).

- I added the table Post_Comment because it was necessary to establish who posted that comment. It accomplishes the same goal that the table Post does.

- I removed the variable TypeID from Content. As it turns out, we can have an EventID at the same time as a GroupID: I make the EventID field mandatory, because a Group always belongs to an Event. And I make the GroupID field non-mandatory, because the Content could be published on the Event. Thus, to know if the Content belongs to an Event or to a Group, I check whether EventID and GroupID are set. If both are set, then the content belongs to the Group. Otherwise, if only EventID is set, the Content belongs to the Event page.

- I removed GroupOwner and requestStatus from the table Group, and put requestStatus into Is_Member_Group. requestStatus can be used to tell whether a participant is a 'ADMIN','MEMBER', or 'PENDING'. The state 'PENDING' happens when the user has not yet been added to the Group. This works the same way for the requestStatus variable in Is_Member_Event. 

- I added the variable hasSeenLastMessage to be able to determine whether or not a user has seen the last message of an Event or a Group. This will allow us to determine on the home page whether or not we need to indicate how many Event and Group discussions the user has not been updated on.

- I have added the fields CID (parent ContentID) and CoID (CoID is short for CommentID), as well as Timestamp (Comments also need timestamps to be ordered the way they were posted) to the Comment table.

