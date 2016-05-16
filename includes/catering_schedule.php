<div class="col-md-6">
    <div id="formMine2">
        <h1>Catering Schedule:</h1>
        <hr>
        <ul class="no-bullets">
            <?php
                $sql_statement = "SELECT event_id, food_type, attendance, date, end_time FROM event ORDER BY DATE(date)";
                
                $sth = $connect->prepare($sql_statement);
                $sth->execute();
                $sth->bind_result($event_id, $food_type, $attendance, $date, $start_time);
                $sth->store_result();
                
                while ($sth->fetch()) {
                    if ( strtotime( "yesterday" ) <= strtotime($date) ) {
                        $newDate = date("l, F j", strtotime($date));
                        $newTime = date("h:i A", strtotime($start_time));
                        echo "<li><h3>$food_type</h3><h4 style=\"color:gray\">$newDate at $newTime</li></h4>";
                        echo    "<ul style=\"padding-left: 50px;\">";
                        $result = $connect->query("SELECT COUNT(*) FROM attendee WHERE $event_id = event_id and confirmed = \"1\"");
                        $row = $result->fetch_row();
                        $count = $row[0];
                        for ($x = $count; $x > 0; $x--) {
                            echo "<li style=\"color: red;\">Spot Taken</li>";
                        }
                        for ($y = $attendance - $count; $y > 0; $y--) {
                            echo "<li>Vacant Spot</li>";;
                        }
                        echo    "</ul>";
                        echo "<hr>";
                    }
                }
                
                $sth->close();
                $connect->close();
            ?>
        </ul>
    </div>
</div>