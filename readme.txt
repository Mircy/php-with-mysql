Building Web Applications using MySQL and PHP - FMA

http://titan.dcs.bbk.ac.uk/~mgance01/w1fma/index.php

Description
-----------
This application containts all files necesary to create an online photo gallery.
 
Installation
------------
Go to 'sql' folder, open the .sql file and run the query from the file into your database to create the necessary table.
Move all files and folders in exactly same format into your webspace.

NOTE
-----
After you upload all the files and folders , set maximum permision(777) to 'thumbs' and 'uploads' folders.

Configuration
-------------
A configuration file is located in 'includes' folder.
You need change database credentials with yours.
You can change css file name or location.
You can change the language file , but don't forget to add yours in 'includes' folder.

JSON
-----

This application contain a JSON web service.
To use it you need to open a photo in full view and copy file name which is located after 'index.php?image='.
Go to http://titan.dcs.bbk.ac.uk/~mgance01/w1fma/json.php?photo= and add the file name to it to get file info.