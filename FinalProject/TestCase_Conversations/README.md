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

