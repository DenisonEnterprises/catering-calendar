<?php
include("includes/connection.php");
include("includes/icalendar.php");
$records = mysql_num_rows(mysql_query("select * from  events"));
if ($_POST['stage'] == 1) {
    $ical = new iCalendar();
    $filename = $_FILES['file1']['tmp_name'];
    $ical->parse("$filename");
    $ical_data = $ical->get_all_data();

    $timezone = "{$ical_data['VCALENDAR']['X-WR-TIMEZONE']}";
    if (function_exists('date_default_timezone_set'))
        date_default_timezone_set($timezone);
    $strsql1 = "Insert into events(ID,StartDate,StartTime,EndDate,EndTime,Title,Location,Description) values  ";
    if (!empty($ical_data['VEVENT'])) {
        foreach ($ical_data['VEVENT'] as $key => $data) {

            //get StartDate And StartTime
            $start_dttimearr = explode('T', $data['DTSTART']);
            $StartDate = $start_dttimearr[0];
            $startTime = $start_dttimearr[1];

            //get EndDate And EndTime
            $end_dttimearr = explode('T', $data['DTEND']);
            $EndDate = $end_dttimearr[0];
            $EndTime = $end_dttimearr[1];

            $strsql1.="('" . $data['UID'] . "','" . $StartDate . "','" . $startTime . "','" . $EndDate . "','" . $EndTime . "','" . mysql_real_escape_string($data['SUMMARY']) . "','" . mysql_real_escape_string($data['LOCATION']) . "','" . mysql_real_escape_string($data['DESCRIPTION']) . "')";
            $strsql1.=",";
        }
        $strsql1 = rtrim($strsql1, ',');

        mysql_query($strsql1);
    }

    header('Location:index.php');
}

if ($_GET['stage'] == "empty") {
    mysql_query(" TRUNCATE events");
    header('Location:index.php');
}
?>


<html>
    <head>
        <title>Import Export  ICS</title>
        <script type="text/javascript">
            function validate(){
                if(document.ics_frm.file1.value == ""){
                    alert("please upload a valid Icalendra file.");
                    document.form1.file1.focus();
                    return false;
                }
                if(document.ics_frm.file1.value != ""){
                    if(!/(\.ics|\.ICS)$/i.test(document.ics_frm.file1.value)) 
                    {
                        alert("please upload a valid Icalendra file\nPlease upload a image file with an extention of one of the following :\n\n ICS,ics");
                        document.form1.file1.focus();
                        return false;
                    }
                }
            }
        </script>    
    </head>

    <body>
        <div id="container"  style="width:800px;">
            <div style="float:left;align:center">
                <form action="index.php" method="post" enctype="multipart/form-data" name="ics_frm" onsubmit="return validate();">
                    <input type="hidden" name="stage" value="1" />
                    <input type="file" name="file1" />
                    <input type="submit" value="submit" /><br/><br/>
                    &nbsp;</form>  

                <div style="clear:both;"></div>
                <?php if ($records > 0) { ?>
                    <div><a href="index.php?stage=empty">Empty Table </a></div> <br/> 
                <?php } ?>
                <table border="0" width="690" id="table22" class="inner_font" cellspacing="1" cellpadding="4" style="font-family:Verdana; font-size:10pt" bgcolor="#99CCFF">

                    <tr>
                        <td align="center" width="5%" >
                            <b>#</b>
                        </td>
                        <td align="center" width="20%"  >
                            <b>Event Name</b></td>
                        <td align="center" width="22%"  >
                            <b>Start Date</b></td>
                        <td align="center" width="22%"  >
                            <b>End Date</b></td>
                        <td  align="center" class="tbl_text">
                            <font color="#000000"><b>Location</b></font></td>
                    </tr>
                    <?php
                    $i = 1;
                    $sqlquery = mysql_query("select * from events  order by ID");
                    while ($row = @mysql_fetch_array($sqlquery)) {
                        ?>
                        <tr>
                            <td style="padding-top:8px; padding-bottom:8px"  align="center" width="5%" bgcolor="#FFFFFF"><?php print $i; ?></td>
                            <td style="padding-top:8px; padding-bottom:8px"  align="center" width="20%" bgcolor="#FFFFFF"><?php print stripslashes($row['Title']); ?></td>
                            <td style="padding-top:8px; padding-bottom:8px"  align="center" width="22%" bgcolor="#FFFFFF">
                                <?php echo date('m/d/Y', strtotime($row['StartDate'])) . " @ " . date("g:i a", strtotime($row['StartTime'])); ?>
                                 
                            </td>

                            <td style="padding-top:8px; padding-bottom:8px"  align="center" width="22%" bgcolor="#FFFFFF">
                                 <?php echo date('m/d/Y', strtotime($row['EndDate'])) . " @ " . date("g:i a", strtotime($row['EndTime'])); ?></td>

                            <td  width="20%" style="text-align: center; padding-top:8px; padding-bottom:8px"  align="center" bgcolor="#FFFFFF" >
                                <?php echo stripslashes($row['Location']); ?>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                    ?>
                </table>



            </div>
            
        </div> 
    </body>
</html>