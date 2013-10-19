<?php

// Production mode?  If off, disables caching which is helpful during development.
const PRODUCTION = true;

// Database stuff
const DBNAME = "spy_trackdown";
const DBUSER = "spy_trackdown";
const DBPASS = "changeme";
const DBHOST = "127.0.0.1";

// Filepath and URL roots
const ROOT = "/var/www/html/spy-trackdown";
const WEBROOT = "http://www.yoursite.com/spy-trackdown";

// Configurable general stuff
const APPNAME = "Spy Trackdown Phone";

// Used for random hashing of stuff to make guessing data harder
// An acceptable (though not perfectly secure) way to generate this is via the following
// command:
//
// echo `date` | mkpasswd --method=sha-512 -s | sed -e "s/^.*\\$//"
const SECRET_KEY = "Change me!";
