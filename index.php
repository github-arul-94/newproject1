<?php
error_reporting(0);
$mysql_hostname = "localhost";
$mysql_user = "root";
$mysql_password = "";
$mysql_database = "interview_test";

$mysqli = new MySQLi($mysql_hostname, $mysql_user, $mysql_password, $mysql_database) 
			or die(mysqli_error());
            


?>
<html>
    <head>
        <title></title>
            <link href="bootstrapcss.css" rel="stylesheet">
         <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<style>
.item{
    width: 100%;
    padding: 10px 5px;
    margin-bottom: 10px;
    color: #fff;
    dmargin-left: 100px;
    text-align: center;
}
</style>
    </head>
    <body>
<div class="row">
<div class="col-md-12">
<h3>Create Timetables</h3>
    <div class="col-md-2">
    <div id="items">
        <div id="item1" class="item" data-src="English" style="background: #133C7D;">English</div>
        <div id="item2" class="item" data-src="Science" style="background: #DB1F1F;">Science</div>
        <div id="item3" class="item" data-src="Music" style="background: #D4592D;">Music</div>
        <div id="item4" class="item" data-src="History" style="background: #0C5319;">History</div>
        <div id="item4" class="item" data-src="Computer" style="background: #5B0C3E;">Computer</div>
        <div id="item4" class="item" data-src="Mathematics" style="background: #133C7D;">Mathematics</div>
        <div id="item4" class="item" data-src="Arts" style="background: #DB1F1F;">Arts</div>
        <div id="item4" class="item" data-src="Ethics" style="background: #D4592D;">Ethics</div>
    </div>
</div>
<div class="col-md-9">
        
        
                        <table class="table table-bordered table-striped" id="droppable">
                            <thead>
                            <tr>
                                <th></th>
                                <?php
                                $sql_query = "select * from weekdays"; 
                                $result = mysqli_query($mysqli,$sql_query);
                                $weekdays_count = mysqli_num_rows($result); 
                                while ($row = mysqli_fetch_array($result)) {         
                                		?>
                                        <th><?php echo $row['weekdays']; ?></th>
                                        <?php 
                                }
                                ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $sql_query_time = "select * from master_time"; 
                                $result_time = mysqli_query($mysqli,$sql_query_time);
                                while ($row = mysqli_fetch_array($result_time)) {  
                                    ?>
                                    <tr>
                                        <td><?php echo date('h:i',strtotime($row['times'])); ?></td>
                                        <?php
                                        if($row['break'] == 'N'){
                                            $sql_query_weekdays = "select * from weekdays"; 
                                            $result_weekdays = mysqli_query($mysqli,$sql_query_weekdays);
                                            while ($row_weekdays = mysqli_fetch_array($result_weekdays)) { 
                                                $subject = '';
                                                $check_sql_query = "select subject from timetable where time=".$row['id']." AND week_day=".$row_weekdays['id'];
                                          		$check_query = mysqli_query($mysqli,$check_sql_query);
                                                $count_rows = mysqli_num_rows($check_query);
                                                if($count_rows>0){
                                                    $check_row=mysqli_fetch_array($check_query);
                                                    if($check_row['subject'] == 'English'){
                                                        $bg_color = '#133C7D';
                                                    }
                                                    else if($check_row['subject'] == 'Science'){
                                                        $bg_color = '#DB1F1F';
                                                    } 
                                                    else if($check_row['subject'] == 'Music'){
                                                        $bg_color = '#D4592D';
                                                    } 
                                                    else if($check_row['subject'] == 'History'){
                                                        $bg_color = '#0C5319';
                                                    } 
                                                    else if($check_row['subject'] == 'Computer'){
                                                        $bg_color = '#5B0C3E';
                                                    } 
                                                    else if($check_row['subject'] == 'Mathematics'){
                                                        $bg_color = '#133C7D';
                                                    } 
                                                    else if($check_row['subject'] == 'Arts'){
                                                        $bg_color = '#DB1F1F';
                                                    } 
                                                    else if($check_row['subject'] == 'Ethics'){
                                                        $bg_color = '#D4592D';
                                                    } 
                                                    $subject = '<div class="item" style="background: '.$bg_color.'">'.$check_row['subject'].'</div>';
                                                }
                                                
                                                    ?>
                                                    <td  data-reftime="<?php echo $row['id']; ?>" data-weekid="<?php echo $row_weekdays['id']; ?>" class="container"><?php echo $subject; ?></td>
                                                    <?php 
                                            }
                                        }
                                        else{
                                            ?>
                                            <td colspan="<?php echo $weekdays_count; ?>" style="text-align: center;">Break</td>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                }      
                                    ?>
                                <tr>
                                
                                </tr>
                                <td></td>
                            </tbody>
                        </table>
                        
                    
        </div>   
       </div>     
        </div>           
    </body>
    <script>
;(function($,undefined){
    $('.item').draggable({
        cancel: "a.ui-icon",
        revert: true,
        helper: "clone",
        cursor: "move"
        , revertDuration: 0
    });
    
    var $container
    $('.container').droppable({
        accept: "#items .item",
        activeClass: "ui-state-highlight",
        drop: function( event, ui ) {
            var $item = ui.draggable.clone();
 
            $(this).addClass('has-drop').html($item);
            var subject = $(ui.draggable).text();
            var time = $(this).attr('data-reftime');
            var week = $(this).attr('data-weekid');
            $.ajax({
                type: "POST",
                url: "save.php",
                data: 'subject='+subject+'&time='+time+'&week='+week,
                success: function(result){
                    
                }
                });
        }
    });
})(jQuery);
</script>
</html>