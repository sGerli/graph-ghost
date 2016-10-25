# Link Shortener with Open Graph Mirroring
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
