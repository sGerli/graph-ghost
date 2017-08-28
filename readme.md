# Link Shortener with Custom Open Graph Mirroring
## Installation Instructions
1. Copy the files to your server's root directory
2. Ensure that your server config allows the use of .htaccess
3. Create a MySQL table named `short_links`
4. Create table columns with names `title`, `image`, `description`, `short`, `link`, and `clicks` as demonstrated below
5. Create a file in the root directory named `secret.php`
6. Insert your database credentials as outlined in the `Database.php __construct()` function

#### MySQL Table Named short_links
|title|image|description|short|link|clicks|
|---|---|---|---|---|---|
|Main title|full url to image|Description|example.com/short|full url|Click counter|

## Functionality
This allows quick and easy GUI based method of creating redirect links/short links on your website. More importantly, this allows you to write in your own Open Graph data, even if you don't own the original link. It does this by spoofing a shell page with it's own OG Data before proceeding with the redirect. An added bonus of this is large preview embedded YouTube links on Facebook.

## Upcoming Features
* Easier method of copying newly generated short link
* Additional sorting and filtering options for main list

## Known Bugs
* Currently does not provide image or video height/width so Facebook cannot asyncronously download a preview
