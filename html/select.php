<?php
$db = new mysqli('localhost:8889', 'root', 'root', 'webapp');
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <section class="delete">
    <?php
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    $selectDate = filter_input(INPUT_GET, 'nengetu', FILTER_SANITIZE_SPECIAL_CHARS);
    $stmt = $db->prepare('select * from study where id=?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->bind_result($id, $date2, $contents2, $language2, $hour2);
    while ($resultMonth2 = $stmt->fetch()) { ?>
      <?php
      if ($language2 === 'Laravel') {
        $imageLanguage = 'laravel.png';
      } elseif ($language2 === 'HTML') {
        $imageLanguage = 'HTML5のロゴアイコン.png';
      } elseif ($language2 === 'PHP') {
        $imageLanguage = 'new-php-logo.png';
      } elseif ($language2 === 'CSS') {
        $imageLanguage = 'css-118-569410.png';
      } elseif ($language2 === 'JavaScript') {
        $imageLanguage = 'js.jpg';
      } elseif ($language2 === 'SQL') {
        $imageLanguage = 'sql.jpg';
      } elseif ($language2 === 'Python') {
        $imageLanguage = 'python.png';
      } elseif ($language2 === 'React') {
        $imageLanguage = 'React.png';
      } else {
        $imageLanguage = 'pc.png';
      }

      if ($contents2 === 'ドットインストール') {
        $imageContents = 'dott.jpg';
      } elseif ($contents2 === 'N予備校') {
        $imageContents = 'n予備.jpg';
      } elseif ($contents2 === 'Udemy') {
        $imageContents = 'udemy.png';
      } elseif($contents2 === 'POSSE課題') {
        $imageContents = 'posse.jpg';
      }else{
        $imageContents = 'google.png';
      }
      ?>
      <li class="choice selected">
        <span><?= $date2; ?></span><img src="img/<?= $imageContents; ?>" class="image-con"><span><?= $contents2; ?></span><img src="img/<?= $imageLanguage; ?>" class="image-lan"><span><?= $language2; ?></span><span><?= $hour2; ?>時間</span>
      </li>
    <?php } ?>
    <p>本当に削除しますか？</p>
    <form action="delete.php" method="get">
      <input type="hidden" value="<?= $id; ?>" name="id">
      <input type="hidden" value="<?= $selectDate; ?>" name="nengetu">
      <input type="submit" value="はい" class="yes">
    </form>
      <input type="submit" value="いいえ" class="no" onclick="history.back()">
  </section>
</body>

</html>