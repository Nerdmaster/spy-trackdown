Spy Trackdown Phone
=====

This web application is meant to replace the phone which comes with Spy
Trackdown.  It's built assuming you run a LAMP environment, so if you aren't,
and you manage to get it working anyway, please feel free to send me details,
and I'll add that here.

Installing
-----

* Copy `config.inc.example.php` to `config.inc.php`.  You should adjust
  the settings for your database, at least by changing the password.
* Run `php scripts/create_database.php [host] [root username] [root password]`
  to create the database and user based on the values in config.inc.php.
* **TODO: Add db table creation info**
* **TODO: any other scripts to run?**
* Point Apache to the `public` directory.  It's best not to allow Apache
  to serve the root of the application, though it does need to have read
  access everywhere in the app.
* Give Apache rights to read all files in the app, and to write to the
  top-level `template-cache` directory.
* Set up directories and URL paths in `config.inc.php` to match your web
  server.

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
