<?php

require 'class.iCalReader.php';

$ical   = new ICal('MyCal.ics');
$events = $ical->events();

$date = $events[0]['DTSTART'];
echo 'The ical date: ';
echo $date;
echo "<br />\n";

echo 'The number of events: ';
echo $ical->event_count;
echo "<br />\n";

echo '<hr/><hr/>';

foreach ($events as $event) {
    echo 'SUMMARY: ' . @$event['SUMMARY'] . "<br />\n";
    echo 'DTSTART: ' . $event['DTSTART'] . "<br />\n";
    echo 'DTEND: ' . $event['DTEND'] . "<br />\n";
    echo 'DTSTAMP: ' . $event['DTSTAMP'] . "<br />\n";
    echo 'UID: ' . @$event['UID'] . "<br />\n";
    echo 'CREATED: ' . @$event['CREATED'] . "<br />\n";
    echo 'LAST-MODIFIED: ' . @$event['LAST-MODIFIED'] . "<br />\n";
    echo 'DESCRIPTION: ' . @$event['DESCRIPTION'] . "<br />\n";
    echo 'LOCATION: ' . @$event['LOCATION'] . "<br />\n";
    echo 'SEQUENCE: ' . @$event['SEQUENCE'] . "<br />\n";
    echo 'STATUS: ' . @$event['STATUS'] . "<br />\n";
    echo 'TRANSP: ' . @$event['TRANSP'] . "<br />\n";
    echo 'ORGANIZER: ' . @$event['ORGANIZER'] . "<br />\n";
    echo 'ATTENDEE(S): ' . @$event['ATTENDEE'] . "<br />\n";
    echo '<hr/><hr/>';
}

?>
