# Link Shortener with Custom Open Graph Mirroring
## Installation Instructions
1. Copy the files to your server's root directory
2. Ensure that your server config allows the use of .htaccess
3. Create a MySQL table named `linkTable`
4. Create table columns with names `title`, `image`, `description`, `short`, and `link` as demonstrated below
5. Create a file in the root directory named `serverconnect.php`
6. It should create a MySQL connection named `$mysql` with appropriate login. The following is an appropriate example

#### MySQL Table Named linkTable
|title|image|description|short|link|
|---|---|---|---|---|
|Main title|full url to image|Description|example.com/short|full url|

#### PHP file named serverconnect.php
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
This allows quick and easy GUI based method of creating redirect links/short links on your website. More importantly, this allows you to write in your own Open Graph data, even if you don't own the original link. It does this by spoofing a shell page with it's own OG Data before proceeding with the redirect. An added bonus of this is large preview embedded YouTube links on Facebook.

## Upcoming Features
* Easier method of copying newly generated short link
* Package management with composer
* Additional sorting and filtering options for main list
* Parse non YouTube videos the same way a YouTube video is

## Known Bugs
* Currently does not provide image or video height/width so Facebook cannot asyncronously download a preview
