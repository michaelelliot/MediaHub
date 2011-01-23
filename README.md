MediaHub
=
Requirements
==
* MySQL 5+
* PHP 5.2+
* Apache 2.2+
* Twitter account

* Please follow the following steps to get MediaHub up and running on your server
1. Go to https:/www.twitter.com/apps and register a new application to get a set of consumer keys.
2. Rename config.ini.rename to config.ini and open it in a text editor.
3. Set consumer_key and consumer_secret under [oauth] to the values you got from Twitter.
4. Set admin_username under [oauth] to the twitter handle you'd like to be administrator
5. Update the server, username and password credentials under [mysql] to an existing account.
6. Setup a MySQL database called 'mediahub' and ensure [mysql] username has access.
7. Upload the *contents* of src/ to the location you'd like MediaTag to reside.
8. Navigate to the URL of this location in a browser.
9. ???
10. PROFIT!

See NOTES.md for more information
If you'd like to help with this project, please email me at michael@digital.com.au.

