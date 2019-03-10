# Graph Ghost
Link Shortener with Custom Open Graph Mirroring
## Installation Instructions
1. Copy the files to your server's root directory
2. Ensure that your server config allows the use of .htaccess
3. If using a development build execute `php composer.phar install` to install PHP dependencies. This command depends on your environment.
4. Create a file inside of the root directory called `secret.php` with the following format:
```php
<?php

define('SERVERNAME', 'dbhost.com');
define('USERNAME', 'db_username');
define('PASSWORD', 'db_password');
define('DATABASE', 'db_name');
define('ADMIN_USERNAME', 'admin_username');
define('ADMIN_PASSWORD', 'admin_password');

?>
```
5. Run the setup script, the url shoud be yoursite.com/shortpanel/setup.php
6. Remove the setup.php file from your server.

## Functionality
This allows quick and easy GUI based method of creating redirect links/short links on your website. More importantly, this allows you to write in your own Open Graph data, even if you don't own the original link. It does this by spoofing a shell page with it's own OG Data before proceeding with the redirect. An added bonus of this is large preview embedded YouTube links on Facebook.

## Development
- To compile css run `npx tailwind build src/style.css -c tailwind.js -o css/style.css`.