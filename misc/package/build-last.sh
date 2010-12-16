#!/bin/bash
VERSION="1.1b2"
DEST_PATH=/home/piwik/builds
URL_REPO=http://piwik@dev.piwik.org/svn/trunk
URL_TAGS=http://piwik@dev.piwik.org/svn/tags
HTTP_PATH=/home/www/builds.piwik.org

function die() {
        echo -e "$0: $1"
        exit 2
}

if [ ! -e $DEST_PATH ] ; then
        echo "Destination directory does not exists... Creating it !";
        mkdir -p $DEST_PATH;
fi

echo "checkout repository for tag $VERSION"
rm -rf $DEST_PATH/piwik_last_version
svn export $URL_TAGS/$VERSION $DEST_PATH/piwik_last_version > /dev/null || die "Problem checking out the last version tag"

echo "preparing release $VERSION "
cd $DEST_PATH
rm -rf $DEST_PATH/piwik
mv piwik_last_version piwik
echo `grep "'$VERSION'" piwik/core/Version.php`
if [ `grep "'$VERSION'" piwik/core/Version.php | wc -l` -ne 1 ]  ; then
  echo "version $VERSION not matching core/Version.php";
  exit
fi
rm -rf piwik/libs/PhpDocumentor-1.3.2/
rm -rf piwik/libs/FirePHPCore/
rm -rf piwik/tmp/cache/*
rm -rf piwik/tmp/logs/*
rm -rf piwik/tmp/templates_c/*
rm -f piwik/misc/db-schema*
rm -f piwik/misc/diagram_general_request*
cp piwik/tests/README.txt .
rm -rf piwik/tests/*
find piwik/plugins -name tests -type d -exec rm -rf {} \; 2> /dev/null
mv README.txt piwik/tests/
cp piwik/misc/How\ to\ install\ Piwik.html .
cp piwik/misc/WebAppGallery/*.xml .

echo "writing manifest file..."
find piwik -type f -printf '%s ' -exec md5sum {} \; | fgrep -v 'manifest.inc.php' | sed '1,$ s/\([0-9]*\) \([a-z0-9]*\) *piwik\/\(.*\)/\t\t"\3" => array("\1", "\2"),/; 1 s/^/<?php\n\/\/ This file is automatically generated during the Piwik build process\n class Manifest {\n\tstatic $files=array(\n/; $ s/$/\n\t);\n}/' > piwik/config/manifest.inc.php

echo "packaging release..."
zip -r piwik-$VERSION.zip piwik How\ to\ install\ Piwik.html *.xml > /dev/null 2> /dev/null
tar -czf piwik-$VERSION.tar.gz piwik How\ to\ install\ Piwik.html *.xml
mv piwik-$VERSION.{zip,tar.gz} $HTTP_PATH
rm -rf piwik

if [ `echo $VERSION | grep -E 'rc|b|a|alpha|beta|dev' -i | wc -l` -eq 1 ]  ; then
  echo "Beta or RC release";
  echo $VERSION > $HTTP_PATH/LATEST_BETA
  echo "build finished! http://builds.piwik.org/piwik-$VERSION.zip"
else
  echo "Stable release";
  #hard linking piwik.org/latest.zip to the newly created build
  for i in zip tar.gz; do
    ln -sf $HTTP_PATH/piwik-$VERSION.$i $HTTP_PATH/latest.$i
  done
  echo $VERSION > $HTTP_PATH/LATEST
  echo "build finished! http://piwik.org/latest.zip"
fi

