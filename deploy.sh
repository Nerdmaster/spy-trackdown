#!/bin/bash

# Installs the spy trackdown game somewhere, using some moderately sane
# defaults for a debian system.
#
# To override variables, just set them before running the script, e.g.,
# `APACHE=nobody BRANCH=develop ./deploy.sh`

set -eu

DEPLOYDIR=${DEPLOYDIR:-"/opt/spy-trackdown"}
APACHE=${APACHE:-"www-data"}
REMOTEREPO=${REMOTEREPO:-"https://github.com/Nerdmaster/spy-trackdown.git"}
BRANCH=${BRANCH:-"master"}
COMPOSERBIN=${COMPOSERBIN:-"$DEPLOYDIR/composer.phar"}

echo "Deploying to $DEPLOYDIR"

# Make sure the deploy dir exists
mkdir -p $DEPLOYDIR

# Check out the repo if it's missing
cd $DEPLOYDIR
if [ ! -d $DEPLOYDIR/.git ]; then
  echo "Repo not found; cloning..."
  git clone $REMOTEREPO .
fi

# Make sure the template cache exists
mkdir -p $DEPLOYDIR/.template-cache

# Check out the latest version from $BRANCH!
git fetch
git checkout $BRANCH
git pull

if [ ! -d $DEPLOYDIR/vendor ]; then
  $DEPLOYDIR/composer.phar install --no-dev
fi

# Set up permissions - we use very specific paths so apache can only see what
# we want it to see.  This is very paranoid, given apache points to
# $DEPLOYDIR/public, but hey, why not be extra careful?
chown -R root:root $DEPLOYDIR
chmod -R o-rwx $DEPLOYDIR
chmod -R g-w $DEPLOYDIR

chgrp $APACHE $DEPLOYDIR
chgrp $APACHE $DEPLOYDIR/*.php
chgrp -R $APACHE $DEPLOYDIR/classes
chgrp -R $APACHE $DEPLOYDIR/data
chgrp -R $APACHE $DEPLOYDIR/public
chgrp -R $APACHE $DEPLOYDIR/templates
chgrp -R $APACHE $DEPLOYDIR/vendor

chmod -R g+rx $DEPLOYDIR
chmod -R g+rwx $DEPLOYDIR/data/games
chmod -R g+rwx $DEPLOYDIR/.template-cache

if [ ! -f $DEPLOYDIR/config.inc.php ]; then
  date=`date`
  secretkey=$(echo $date | mkpasswd --method=sha-512 -s | sed -s "s/\\\\//")
  cat $DEPLOYDIR/config.inc.example.php | grep -v SECRET_KEY > $DEPLOYDIR/config.inc.php
  echo "const SECRET_KEY=\"$secretkey\";" >> $DEPLOYDIR/config.inc.php
  echo "Copied default config, please edit this to suit your system!"
fi
