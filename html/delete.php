<?php  

$db = new mysqli('localhost:8889','root','root','webapp');
$id = filter_input(INPUT_GET,'id',FILTER_SANITIZE_NUMBER_INT);
$stmt = $db->prepare('delete from study where id=?');
$stmt->bind_param('i',$id);
$stmt->execute();
$selectDate = filter_input(INPUT_GET,'nengetu',FILTER_SANITIZE_SPECIAL_CHARS);

header('Location: change.php'); 
exit();
?>
<!-- <!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <section class="dead">
  <p>Delete Success!</p>
  <form action="change.php">
    <input type="hidden" value="<?= $selectDate;?>" name="nengetu">
    <input type="submit" value="戻る" class="back">
  </form>
  </section>
</body>
</html> -->