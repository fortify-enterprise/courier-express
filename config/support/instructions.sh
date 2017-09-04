
===================================
# Live login for beanstream
===================================
Go to:
https://www.beanstream.com/Admin/sDefault.asp
CourierExpress
admin
7Cd7Bf38


===================================
# Spooled email sending
===================================

Reference:
http://borreli.com/blog/les-emails-avec-symfony-1-3/


===================================
Database upgrade production instructions
===================================

. copy the schema file from dev to production
. remove old migration differences in migrations folder
. run ./symfony doc:generate-migrations-diff
. run ./symfony doc:migrate
. run ./symfony doc:build --all-classes
. rsync from dev to production all files

# check what will be upgraded
#> php symfony project:deploy production -t

# do upgrade
#> php symfony project:deploy production --go

#. clear controllers if needed
. fix permissions



===================================
# Combining css and html for emails
===================================
http://www.pelagodesign.com/sidecar/emogrifier/

===================================
# Draw KML files
===================================
http://www.birdtheme.org/useful/googletool.html

===================================
# Display KML files
===================================
http://display-kml.appspot.com/

===================================
# Proxy lists for testing different countries:
===================================
http://proxy-list.org/en/index.php



===================================
# Crontab:
===================================

apache user:
# send emails using email pool courier express
* * * * * /usr/local/php/bin/php /web/projects/courierexpress/symfony project:send-emails >/dev/null
* * * * * /usr/local/php/bin/php /web/projects/staging.courierexpress/symfony project:send-emails >/dev/null

smsd user:
# send sms
* * * * * /usr/local/php/bin/php /web/projects/courierexpress/symfony sms-messages:read-reply-send >/dev/null
* * * * * /usr/local/php/bin/php /web/projects/staging.courierexpress/symfony sms-messages:read-reply-send >/dev/null



1. You have to patch the two files after new version of symfony is installed:

Important for autoextraction for i18n you need to patch the following files in symfony:


/web/projects/courierexpress/lib/vendor/symfony/lib/i18n/extract/sfI18nPhpExtractor.class.php replace extract with following function:

public function extract($content)
{
print $content . "\n";
preg_match_all("{__ text='(.*)'}", $content, $out, PREG_PATTERN_ORDER);
$words = $out[1];
foreach ($words as $word)
$strings [] = $word;
return $strings;

}


and


In lib/vendor/symfony/lib/i18n/extract/sfI18nExtract.class.php

change the following:

$files = sfFinder::type('file')->name('*.php');

to the following:

$files = sfFinder::type('file')->name('*.tpl');


2. You must have graphviz library installed
pear install Image_GraphViz-1.3.0RC3


===================================
# zip code database from
===================================
http://federalgovernmentzipcodes.us/

==================================
# PayPal login
==================================
info@courier-express.ca
InFoexpress&.c


==================================
Configure and upgrade phpunit
pear channel-discover pear.symfony-project.com
pear channel-discover components.ez.no
pear channel-discover pear.phpunit.de
pear upgrade pear
pear install phpunit/PHPUnit
==================================
