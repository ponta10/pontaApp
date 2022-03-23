<?php
/* htmlspecialcharsを短くする */
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES);
}

/* DBへの接続 */
function dbconnect() {
    $db = new mysqli('localhost', 'root', 'root', 'webapp');
    if (!$db) {
		die($db->error);
	}

    return $db;
}


?>


<?php   
//fetch_assoc $recordsの中から一つ取り出す
while ($today = $todays->fetch_assoc()){
  echo $today['sum(hour)'];
}?>

              <!-- <h2><?php   
                  //fetch_assoc $recordsの中から一つ取り出す
                  while ($record = $records->fetch_assoc()){
                    echo $record['sum(hour)'];
                  }?></h2> -->




<?php   while ($result01 = $stmt->fetch()){
    if(!isset($hours01)){
      $hours01 = 0;
    }
    echo $hours01;  }
        ?>



<?php 
          foreach($hoursToday as $hourToday){
            ?><?= $hoursToday?>,
            <?php
          }
          ?>

