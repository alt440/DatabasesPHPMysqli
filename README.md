# DatabasesPHPMysqli
CHECK THE BRANCH finalVersion TO GET THE LATEST CODE FOR THE FINAL PROJECT AND EXPLANATION ON HOW TO SETUP THE FINAL PROJECT. ALL CODE FILES ARE LOCATED IN THE FOLDER BASICSETUP. EXTRACT ALL CONTENT FROM BASICSETUP FOLDER AND PUT THIS CONTENT INTO THE htdocs FOLDER OF Xampp. THEN LAUNCH Xampp TO SEE THE CONTENT. FOR MORE DETAILS REFER TO THE README IN THE finalVersion BRANCH.
COMP 353 - F / FALL 2019
Dr. Desai

PROJECT 1

MEMBERS:
- Mair ELBAZ, 40004558
- Daniel VIGNY-PAU, 40034769
- Francois DAVID, 40046319
- Alexandre THERRIEN, 40057134
- Charles-Antoine GUITE, 40063098

To run the project:

1) Install and open XAMPP and start both Apache and MySQL
2) Place all the project files in `C:\xampp\htdocs`
3) Open your browser and in the search bar type `localhost/` and include the file path of the .php file you want to run (note: htdocs is seen as the root here, so only include files and folders after, it is not necessary to write `C:/xampp/htdocs...` For this project, you only need to write `localhost/COMP353_P1.php` to make it work.)
4) Press enter and your php file will execute
5) (After execution of COMP353_P1.php) Now, if you go to `localhost/phpmyadmin`, you will see a new database called comp353 with the different tables required for the assignment and the information these tables contain.

Files in directory:
- COMP353_P1.php: the code for the project
- COMP353_project_1_code_documentation: documentation of the code, in both pdf and docx formats
- db19s-P1.csv: the data being used for the project, as provided by the professor (note, a typo with three consecutive '|' symbols has been removed)

STEPS TO SSH INTO ENCS SERVER USING CMD/TERMINAL:

SSH Into Concordia:
1) Type `ssh ENCSUSERNAME@login.encs.concordia.ca` (replace ENCSUSERNAME with your ENCS User name)
2) Enter your ENCS password (same as computer lab)
3) Type `mysql -h urc353.encs.concordia.ca -u urc353_2 -p urc353_2`
4) Enter the password `AqtjPG`

- To see tables type `show tables;`.
- To see the contents of a table type `SELECT * FROM TABLENAME;` (replace TABLENAME with name of desired table)


NETBEANS PROJECT PHP INTO CONCORDIA SERVER MADE FOR PROJECT:

1) Open Netbeans.
2) Create a new project. PHP application with remote server.
3) Project URL: `https://urc353.encs.concordia.ca/`
4) Create a remote connection as a SFTP.
5) Host name: `login.encs.concordia.ca`
6) Username: your ENCS username
7) Password: your ENCS password
8) Initial directory: `/www/groups/u/ur_comp353_2`
9) Test the connection and create new files.
10) Once a file is created, right click and upload.




