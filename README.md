# SWU

## Introduction

SWU, _Schoolwork Web Utilities_, is a web application that allows a
teacher to publish his/her assignments, students to turn in their
homeworks, and everybody to track what has been done and what is left.

It is developed in PHP / Javascript / HTML5, using the Yii framework.

It is published under the GNU Affero General Public License. The license
file is available in the root directory of the project.

## Credits

SWU basically uses [Yii](http://www.yiiframework.com/) as PHP framework
and [MySQL](http://www.mysql.com/) as database backend.

It also uses:

  * [Twig](http://twig.sensiolabs.org) as a template engine for some
  tasks (see [license](http://twig.sensiolabs.org/license);
  
  * Some icons from [FamFamFam](http://www.famfamfam.com/)'s set.

## Setup

Follow the following steps. If you have problems, please contact the 
author.

1. Download the Yii framework from http://www.yiiframework.com/download/
(release 1.1.15) and unzip it / untar it.

2. Download the source code of the application and unzip it / untar it
(you might prefer to use git for that purpose).

3. Find the files ending with "-dist" and make a copy of them, removing
the "-dist" part of the name.

4. Download [Twig](http://twig.sensiolabs.org/doc/intro.html#installation), 
and install it on the protected/vendor directory (if you want, you can 
use _composer_ for that -- follow the 
[instructions](http://twig.sensiolabs.org/doc/installation.html)).

5. Create a MySQL database, and create the needed tables in it. The SQL
code is available in the file _protected/data/schema.mysql.sql_.

6. Insert some default values in the mailtemplate table. The SQL code is
availabale in the file _protected/data/mailtemplate_table_samplecontents.mysql.sql_.

7. Edit the file _protected/config/main.php_ to store database's 
dbname, username and password.

## Configuration

All settings are to be written in the _protected/config/main.php_ file.

There are some defaults, so you can omit some of the settings. You can 
have a look at the defaults in the file _protected/components/Helpers.php_ 
(see the function _getYiiParam()_).

