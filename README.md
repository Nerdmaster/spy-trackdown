Spy Trackdown Phone
=====

This web application is meant to replace the phone which comes with Spy
Trackdown.  It's built assuming you run a LAMP environment, so if you aren't,
and you manage to get it working anyway, please feel free to send me details,
and I'll add that here.

Quick Note
-----

This is NOT ready for use.  It's not really even ready for playing with the
code other than perhaps as a pre-alpha state of what I hope to accomplish
someday sort of soon.  I'm pushing to github so I can reuse some of the PHP
stuff I've learned as I started building this.  Unrelated family insanity has
me working crazy hours for at least another month, at which point I hope to
continue this project, but for now it's pretty much dead code.

Note also that this doc isn't particularly useful, either.  The install guide,
for instance, speaks of a database script that currently doesn't even have any
relevancy at the moment.

Installing
-----

* Copy `config.inc.example.php` to `config.inc.php`.  You should adjust
  the settings for your database, at least by changing the password.
* Run `php scripts/create_database.php [host] [root username] [root password]`
  to create the database and user based on the values in config.inc.php.
* Point Apache to the `public` directory.  It's best not to allow Apache
  to serve the root of the application, though it does need to have read
  access everywhere in the app.
* Give Apache rights to read all files in the app, and to write to the
  top-level `template-cache` and `data/games` directories.
* Set up directories and URL paths in `config.inc.php` to match your web
  server.
* Set up a cron job to automatically purge files in data/games that aren't
  modified for a few weeks (in case of games that start but are forgotten
  or otherwise abandoned).
* Create or copy name generation files in data/name-gen.  Filenames must
  be numeric, and contents must be one or more strings delimited by a newline.
  The easiest way to set this up if you aren't expecting multiple games to
  run at once is to copy `data/name-gen/1.example` to `data/name-gen/1`, and
  follow that pattern for each of the other example files.

Testing
-----

Run `vendor/bin/phpunit Tests`.  This is really only necessary if you plan
to modify code.

Why?
-----

Spy Trackdown was a better game than we expected when we picked it up for our
son.  It's a lot like Clue in some ways - you need to use deductive reasoning
and strategy to win.  You might have to take notes.  You have to balance your
choices - block rival agents or move further?  Look for the mastermind or rush
for another covert agent?  Waste time misleading your opponents or just leave
the continent and grab your cards?

**But it requires a gadget to play**.  It has to be placed just right for the
loud audio everybody can hear.  It has cheap circuitry that makes the voice
hard to understand at times.  It's easy to forget whose turn it is, and there's
no way to fix somebody taking a turn out of order on accident.  The "secret"
messages are loud enough that everybody can hear them, destroying the strategy.
And if you turn the device off, your game is over.  And this doesn't even begin
to get into the fact that
[many people have had broken phones](http://www.buzzillions.com/reviews/spy-trackdown-board-game-sale-reviews).
A broken phone means *the game is over*.  For good.

A web application with a smartphone-friendly interface can fix all these
issues.  Granted, there's going to be no voice (at least for now), which takes
away some of the fun, but the silent text ensures secret messages can be kept
secret!  It's easy to know whose turn it is, as that's displayed on screen.
It's easy to see where a move is taking you.  The game doesn't end when you
have to put it away for the night.

And eventually (if others show any interest), other features could be added -
maybe custom game modes, auto-note-taking (*where was I last turn again?*),
and handicaps for younger kids to compete effectively.  Heck, maybe I could
even come up with new covert actions and different maps.
