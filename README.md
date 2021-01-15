# Project Manager System <img src="system.png" width="50"> 

This project is created to learn and master the basics of MySQL database.

"Project Manager System" is a simple webpage which have two pages - employees and projects. Depending on which page you are it allows you to add employees or projects to the system. Also you can update or delete project and employee without deleting the related item. In addition, when you update the employee data you can also assign/unassign a project.
&nbsp;

# <img src="technology.png" width="50"> Technologies

- HTML5
- SCSS
- PHP 7.3
- MySQL
- Javascript ES6
&nbsp;

# How to run<img src="run.png" width="50">

## MySQL database release

1. Download or clone this repository. In example: `C:/Program Files/Ampps/www/yourdirectory` .

2. Import `database.sql` file in your MySQL using root user, because it will have full access to all of the databases.

3. Check what users already exist: `SELECT User FROM mysql.user;`

4. If you don't have a user with `username: admin`, create it with the following code: </br>
`CREATE USER 'admin'@'localhost' IDENTIFIED BY 'admin123';` </br> otherwise create a user with your chosen name and change `admin` to it. </br></br>
P.S. If you want to delete the user after using the project, you can do it with this code: `DROP USER 'admin'@'localhost';`

5. The created user by default can only view data, so we need to grant him rights: </br></br>
`GRANT DELETE, INSERT, SELECT, UPDATE ON project_manager_php.employees TO 'admin'@'localhost';` </br>
`GRANT DELETE, INSERT, SELECT, UPDATE ON project_manager_php.projects TO 'admin'@'localhost';` </br></br>
The created rights can be checked using the following query: `SHOW GRANTS FOR 'admin'@'localhost';`

6. Login with new connection: 

```
    Username: admin
    Password: admin123
```
P.S. If you created the connection with different username, then replace it in `connection.php` file in this line: `$username = "admin"` .

## Run the project

1.	Install and run xampp/wamp/ampps.

2.	Go to browser using this link with your directory name: `http://localhost/yourdirectory/`

Now you should be able to see all information in the "Project Manager System".

