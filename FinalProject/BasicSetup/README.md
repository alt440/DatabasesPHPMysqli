COMP 353 - F / FALL 2019 Dr. Desai

PROJECT 1

MEMBERS:

    Mair ELBAZ, 40004558
    Daniel VIGNY-PAU, 40034769
    Francois DAVID, 40046319
    Alexandre THERRIEN, 40057134, CRSMGR: al_therr
    Charles-Antoine GUITE, 40063098

# Test Users to be Used for Testing the System
UserID and Password
Admin: username: rrr & password: rrr
Controller: username: controller & password: controller
Regular users: username: aaa & password: aaa (blank user, has just been created), username: username1 & password: password1, username: username2 & password: password2

# Get Started
Go to https://urc353.encs.concordia.ca/index.html to get started. The log in screen will be there waiting for your credientials.

# Setting it up on your own PC
To run the project:

    Install and open XAMPP and start both Apache and MySQL
    Make sure your credentials to phpmyadmin are username:'root' and password:'' or blank
    Create a schema called comp353_final_project
    Place all the project files in C:\xampp\htdocs
    Open your browser and in the search bar type localhost/ and include the file path of the .php file you want to run (note: htdocs is seen as the root here, so only include files and folders after, it is not necessary to write C:/xampp/htdocs...)
    First thing you will want to do is run createTablesRevised.php located in localhost/path-to-this-project/createTablesRevised.php
    Once your tables are created, run the script insertIntoTables.php to populate the tables located in localhost/path-to-this-project/insertIntoTables.php
    Now that this is done, you can use the credentials: (the administrator username and password will be) ‘systemAdmin’ and ‘systemAdmin’, or (the controller username and password will be) ‘alexandre’ and ‘alexandre’, or (a regular user username and password would be) ‘username1’ and ‘password1’
    Type localhost/path-to-this-project/html/index.html to get started.
    Now, if you go to localhost/phpmyadmin, you will see a new database called comp353 with the different tables required for the assignment and the information these tables contain.

# What was modified since the demo
Passwords are now encrypted, admin can now add/remove users, input validation done massively.

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

# database_layer.php limitations
Uploading images does not currently work. If someone has time, the method
addContent would need to be modified to be able to work with images.

# Updated ER
I have included the file FinalER.drawio, which is the final ER diagram I came
up with to design the whole database.

You can go on draw.io and upload this file to see the FinalER.


