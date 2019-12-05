# How to setup your environment
Use the script 'createTablesRevised.php' to create all the different tables 
required by the project.

The file 'database_layer.php' contains some methods to access the database. It
is a work in progress. Right now it only contains the methods necessary to 
add content to the database (and not much about the test cases covered... will
be done soon).

The file 'database_layer_use_cases.php' covers some scenarios that were covered
over the test cases.

To run the project, launch your XAMPP server, and go over the files that are found
directly under the html/ folder. The requests/ folder is used for requests to the 
backend, and therefore does not compose the GUI.

# Test cases that were made for you to better understand
The folder 'Examples' currently contains two test cases to understand how to 
work with the methods I have made. There is also the file 'test_database_layer.php'
that you can also refer to, which contains a lot of documentation.

The 'requests' folder contains as well different implementations on how I manipulate the
DB. These files are because of the fact that to communicate with the backend, I am required
to make AJAX calls.

**Note that the test cases do not cover the case where nothing was covered from the DB. In that case, the results will return false. You have to verify that there is something returned from each queries you receive back.**

# database_layer.php limitations
Uploading images does not currently work. If someone has time, the method
addContent would need to be modified to be able to work with images.

Also, the addComment method tries to find its Content ID (CID) using its reply
string. This approach is limited, because two contents' replyString could be 
the same. I have not thought enough about it enough to give the best solution. 
know what is the best method to get the CID.

# Updated ER
I have included the file FinalER.drawio, which is the final ER diagram I came
up with to design the whole database.

You can go on draw.io and upload this file to see the FinalER.

# Missing functionalities back end
- Cannot reply with images
- Limiting functionality to the comment (compares content's string to know CID, weak way of doing it)
- Admin should be able to assign an event manager to an event
- Extend time period event (extra charge)
- Table for overflowing over bandwidth/ storage limits
- ~~Delete the event after has been archived for 7 years~~
- ~~General deletion methods (for admin: delete groups, remove members from event,...) (for event manager: remove members from event) (delete user)~~
- Debit details/ address/ phone number info for event manager
- ~~Get events of a user/ Get groups of a user~~
- Edit (~~user details~~, group, event)
- Add debit info to an event manager

# Missing functionalities front end
- See the pending requests to join group/~~event~~ and be able to accept members
- The whole group page (Charles Antoine started it, in folder Group): Invite members to group, remove members from group, reply with images
- The admin functionalities to be the supreme leader (accept event demands, select event admins, remove people from all groups/ all events,...)
- Scheduling an event date
- The controller functionalities to be the finance guy
- ~~Sending the one time code to invite new users to the group~~
- The 'hasSeenLastMessage' functionality

