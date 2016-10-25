# Link Shortener with Open Graph Mirroring
## Installation Instructions
1. Copy the files to your server's root directory
2. Ensure that your server config allows the use of .htaccess
3. Create a MySQL table named "linkTable"
4. Create table columns with names `title`, `image`, `description`, `short`, and `link` as demonstrated below
5. Create a file in the root directory named `serverconnect.php`
6. It should create a MySQL connection named `$mysql` with appropriate log in. The following is an appropriate example

|title|image|description|short|link|
|---|---|---|---|---|
|Main title|full url to image|Description|example.com/short|full url|

```php
$servername = 'Your server name, tyypically localhost';
$username = 'MySQL Username';
$password = 'MySQL user Password';
$mysql = new mysqli($servername, $username, $password);

if ($mysql->connect_error)
    die("Connection to the server failed " . $mysql->connect_error);

if (!$mysql->select_db("database"))
    echo "Failed to select the database";
```
## Functionality
This link shortener is intended to be installed on a server with a database. When used, a custom short link will be created and a dummy page will be created containg Open Graph data scraped from the original link. After the dummy page prints the meta:og tags which is enough for any OG reader to scrape data, it will then instantly redirect to the full link. For YouTube Links, this will scrape a separate OG format which Facebook cannot detect and thus will show up as a large preview image as opposed to the forced small YouTube icons on posts

## Upcoming Features
* Easier method of copying newly generated short link
* Package management with composer
* Additional sorting and filtering options for main list
* Parse non YouTube videos the same way a YouTube video is

## Known Bugs
* Currently does not provide image or video height/width so Facebook cannot asyncronously download a preview
* https://en.wikipedia.org/wiki/Category:Lists_of_insects
