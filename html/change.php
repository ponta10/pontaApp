<?php

$db = new mysqli('localhost:80', 'root', 'root', 'webapp');

// ini_set('display_errors', 1);

if (isset($_POST['contents'])) {
  $content = $_POST['contents'];
}
if (isset($_POST['language'])) {
  $language = $_POST['language'];
}

$date = filter_input(INPUT_POST, 'day', FILTER_SANITIZE_SPECIAL_CHARS);
$hour = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_NUMBER_INT);
$stmt = $db->prepare('insert into study(date,contents_name,language_name,hour) values(?,?,?,?)');
$stmt->bind_param('sssi', $date, $content, $language, $hour);
$stmt->execute();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  header("Location:change.php");
  exit;
}


$records = $db->query('select sum(hour) from study');


// date()で日時を出力
$timestamp = time();
$today = date("Y-m-d", $timestamp);
$stmt = $db->prepare('select sum(hour) from study where date=?');
$stmt->bind_param('s', $today);
$stmt->execute();
$stmt->bind_result($hours);



if (isset($_GET['nengetu'])) {
  $slectDate = $_GET['nengetu'];
} else {
  $slectDate = date('Y-m');
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="reset.css" />
  <link rel="stylesheet" href="style.css" />
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
  <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/material_blue.css" />
  <title>Pontaアプリ</title>
</head>

<body>
  <div class="black"></div>
  <header>
    <div class="logo">
      <img src="img/ponta.png" />
      <p>4th week</p>
    </div>
    <section class="btn" onclick="recording()">
      <button class="record">記録・投稿</button>
    </section>
  </header>
  <div class="main">
    <div class="data">
      <article>
        <section class="hour">
          <div class="hour-today hourBox">
            <h3>Today</h3>
            <h2>
              <?php
              while ($stmt->fetch()) {
                if (!isset($hours)) {
                  $hours = 0;
                }
                echo $hours;
              } ?>
            </h2>
            <h3>hour</h3>
          </div>
          <div class="hour-month hourBox">
            <h3>Month</h3>
            <h2>
              <?php
              $todayMonth = $slectDate . '-%';
              $stmt = $db->prepare('select sum(hour) from study where date like ?');
              $stmt->bind_param('s', $todayMonth);
              $stmt->execute();
              $stmt->bind_result($hoursMonth);
              while ($stmt->fetch()) {
                if (!isset($hoursMonth)) {
                  $hoursMonth = 0;
                }
                echo $hoursMonth;
              } ?>
            </h2>
            <h3>hour</h3>
          </div>
          <div class="hour-total hourBox">
            <h3>Total</h3>
            <h2>
              <?php while ($record = $records->fetch_assoc()) {
                if (!isset($record['sum(hour)'])) {
                  $record['sum(hour)'] = 0;
                }
                echo $record['sum(hour)'];
              } ?>
            </h2>
            <h3>hour</h3>
          </div>
        </section>
        <section class="bar">
          <canvas id="myBarChart"></canvas>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
        </section>
      </article>
      <aside>
        <section class="circle">
          <div class="circle-contents circleBox">
            <p>学習コンテンツ</p>
            <div id="donutchart2"></div>
            <div class="graphText2">
              <li class="legend2">N予備校</li>
              <br />
              <li class="legend2">ドットインストール</li>
              <br />
              <li class="legend2">POSSE課題</li>
              <li class="legend2">Udemy</li><br>
              <li class="legend2">その他</li>
            </div>
          </div>
          <div class="circle-language circleBox">
            <p>学習言語</p>
            <div id="donutchart"></div>
            <div class="graphText">
              <li class="legend">HTML</li>
              <li class="legend">CSS</li>
              <li class="legend">JavaScript</li>
              <br />
              <li class="legend">PHP</li>
              <li class="legend">SQL</li>
              <li class="legend">Laravel</li>
              <br />
              <li class="legend">Python</li>
              <li class="legend">React</li>
              <br />
              <li class="legend">その他</li>
            </div>
          </div>
        </section>
      </aside>
    </div>
    <section class="date">
      <form action="change.php?<?= $slectDate; ?>" method="get">
        <?php
        $nowMonth = date('m');
        $nowYear = date("Y");
        for ($i = $nowYear - 2; $i <= $nowYear + 2; $i++) {
          for ($ii = 1; $ii < 13; $ii++) {
            $dd = sprintf('%002d', $ii);
            if ($i . '-' . $dd == $slectDate) {
              if ($dd == 12) {
                $one = 1;
                $zeroOne = sprintf('%002d', $one);
                $afterYear = $i + 1;
                $afterMonth1 = str_replace($i, $afterYear, $slectDate);
                $afterMonth = str_replace('-' . $dd, '-' . $zeroOne, $afterMonth1);
                $before = $dd - 1;
                $before2 = sprintf('%002d', $before);
                $beforeMonth = str_replace('-' . $dd, '-' . $before2, $slectDate);
              } elseif ($dd == 1) {
                $twelve = 12;
                $beforeYear = $i - 1;
                $beforeMonth1 = str_replace($i, $beforeYear, $slectDate);
                $beforeMonth = str_replace('-' . $dd, '-' . $twelve, $beforeMonth1);
                $after = $dd + 1;
                $after2 = sprintf('%002d', $after);
                $afterMonth = str_replace('-' . $dd, '-' . $after2, $slectDate);
              } else {
                $after = $dd + 1;
                $after2 = sprintf('%002d', $after);
                $afterMonth = str_replace('-' . $dd, '-' . $after2, $slectDate);
                $before = $dd - 1;
                $before2 = sprintf('%002d', $before);
                $beforeMonth = str_replace('-' . $dd, '-' . $before2, $slectDate);
              }
              $nengetu .= '<option value="' . $slectDate . '" selected>' . $i . '年' . $dd . '月</option>';
            } else {
              $nengetu .= '<option value="' . $i . '-' . $dd . '">' . $i . '年' . $dd . '月</option>';
            }
          }
        }

        echo '<select name="nengetu" onchange="submit(this.form)">' . $nengetu . '</select>'; ?>
      </form>
    </section>
    <section class="btn" onclick="recording()">
      <button class="record">記録・投稿</button>
    </section>
    <section class="return" onclick="recording2()">
      <button class="return-btn">履歴</button>
    </section>
    <section class="modal">
      <div id="loader"></div>
      <div class="modal-text">
        <div class="modal-date">
          <p>学習日<span class="error day"> ※学習日を記入してください</span></p>
          <form action="" method="post">
            <input type="text" id="calendarTEST" name="day" />
        </div>
        <div class="modal-contents">
          <p>学習コンテンツ(複数選択不可)<span class="error contents"> ※選択してください</span></p>
          <select name="contents" class="select con">
            <option value="">選択してください</option>
            <option value="N予備校">N予備校</option>
            <option value="ドットインストール">ドットインストール</option>
            <option value="POSSE課題">POSSE課題</option>
            <option value="Udemy">Udemy</option>
            <option value="その他">その他</option>
          </select>
        </div>
        <div class="modal-language">
          <p>学習言語(複数選択不可)<span class="error language"> ※選択してください</span></p>
          <select name="language" class="select lan">
            <option value="">選択してください</option>
            <option value="HTML">HTML</option>
            <option value="CSS">CSS</option>
            <option value="JavaScript">JavaScript</option>
            <option value="PHP">PHP</option>
            <option value="Laravel">Laravel</option>
            <option value="SQL">SQL</option>
            <option value="Python">Python</option>
            <option value="React">React</option>
            <option value="その他">その他</option>
          </select>
        </div>
        <div class="modal-hour">
          <p>学習時間<span class="error times"> ※数字を入力してください</span></p>
          <input type="text" name="time" method="post" />
        </div>
        <div class="modal-twitter">
          <p>Twitter用コメント</p>
          <textarea class="comment"></textarea>
        </div>
        <div class="modal-share">
          <label>
            <input type="checkbox" class="checkbox tweet" />
            <span class="checkbox-fontas"></span>
            Twitterにシェアする
          </label>
        </div>
        <div class="closeBtn"><input type="image" src="img/iconmonstr-x-mark-6-240.png" value=""></div>
        <div class="closeBtn2"><img src="img/iconmonstr-x-mark-6-240.png" alt=""></div>
        </form>
        <button class="btnRecord">記録・投稿</button>
      </div>
      <div class="end">
        <p class="endText">AWESOME!</p>
        <i class="fas fa-check-circle"></i>
        <p>記録・投稿<br />完了しました</p>
      </div>
    </section>
    <section class="modalReturn">
      <div class="scroll">
        <?php
        $todayMonth = $slectDate . '-%';
        $stmt = $db->prepare('select * from study where date like ? order by date desc');
        $stmt->bind_param('s', $todayMonth);
        $stmt->execute();
        $stmt->bind_result($id1, $date1, $contents1, $language1, $hour1);
        while ($stmt->fetch()) { ?>
          <?php
          if ($language1 === 'Laravel') {
            $imageLanguage = 'laravel.png';
          } elseif ($language1 === 'HTML') {
            $imageLanguage = 'HTML5のロゴアイコン.png';
          } elseif ($language1 === 'PHP') {
            $imageLanguage = 'new-php-logo.png';
          } elseif ($language1 === 'CSS') {
            $imageLanguage = 'css-118-569410.png';
          } elseif ($language1 === 'JavaScript') {
            $imageLanguage = 'js.jpg';
          } elseif ($language1 === 'SQL') {
            $imageLanguage = 'sql.jpg';
          } elseif ($language1 === 'Python') {
            $imageLanguage = 'python.png';
          } elseif ($language1 === 'React') {
            $imageLanguage = 'react.png';
          } else {
            $imageLanguage = 'pc.png';
          }

          if ($contents1 === 'ドットインストール') {
            $imageContents = 'dott.jpg';
          } elseif ($contents1 === 'N予備校') {
            $imageContents = 'n予備.jpg';
          } elseif ($contents1 === 'Udemy') {
            $imageContents = 'udemy.png';
          } elseif ($contents1 === 'POSSE課題') {
            $imageContents = 'posse.jpg';
          } else {
            $imageContents = 'google.png';
          }
          ?>
          <form action="select.php" method="get">
            <li class="choice">
              <span><?= $date1; ?></span>
              <img src="img/<?= $imageContents; ?>" class="image-con">
              <span><?= $contents1; ?></span>
              <img src="img/<?= $imageLanguage; ?>" class="image-lan"><span><?= $language1; ?></span>
              <span><?= $hour1; ?>時間</span>
              <input type="hidden" value="<?= $id1; ?>" name="id">
              <input type="hidden" value="<?= $slectDate; ?>" name="nengetu"><input type="image" src="img/iconmonstr-trash-can-1-240.png">
            </li>
          </form>
        <?php }
        if (!isset($date1)) { ?>
          <p>履歴はありません</p>
        <?php } ?>
      </div>
      <div class="closeBtn3"><img src="img/iconmonstr-x-mark-6-240.png" alt=""></div>
    </section>
  </div>
  <a href="change.php?nengetu=<?= $beforeMonth; ?>" class="before"><img src="img/iconmonstr-angel-left-circle-thin-240.png" alt=""></a>
  <a href="change.php?nengetu=<?= $afterMonth; ?>" class="after"><img src="img/iconmonstr-angel-right-circle-thin-240.png" alt=""></a>
  <script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.0.min.js"></script>
  <script src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ja.js"></script>
  <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
  <script>
    flatpickr.localize(flatpickr.l10ns.ja);
    flatpickr("#calendarTEST");

    var ctx = document.getElementById("myBarChart");
    var myBarChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: [
          "",
          "2",
          "",
          "4",
          "",
          "6",
          "",
          "8",
          "",
          "10",
          "",
          "12",
          "",
          "14",
          "",
          "16",
          "",
          "18",
          "",
          "20",
          "",
          "22",
          "",
          "24",
          "",
          "26",
          "",
          "28",
          "",
          "30",
          "",
        ],
        datasets: [{
          //データベースの値挿入
          data: [
            <?php
            for ($todayNum = 1; $todayNum < 32; $todayNum++) {
              $todayNum2 = sprintf('%002d', $todayNum);
              $todayDay = '%' . $todayNum2;
              $stmt = $db->prepare('select sum(hour) from study where date like ? and date like ?');
              $stmt->bind_param('ss', $todayMonth, $todayDay);
              $stmt->execute();
              $stmt->bind_result($hoursToday);
              while ($stmt->fetch()) {
                if (!isset($hoursToday)) {
                  $hoursToday = 0;
                }
                echo $hoursToday . ',';
              }
            }
            ?>
          ],
          backgroundColor: "rgba(255,123,0,0.7)",
        }, ],
      },
      options: {
        legend: {
          display: false,
        },
        title: {
          // display: true,
          text: "#3BB9FF",
        },
        scales: {
          xAxes: [{
            gridLines: {
              display: false,
            },
          }, ],
          yAxes: [{
            ticks: {
              suggestedMax: 8,
              suggestedMin: 0,
              stepSize: 2,
              callback: function(value, index, values) {
                return value + "h";
              },
            },
            gridLines: {
              display: false,
            },
          }, ],
        },
      },
    });


    google.charts.load("current", {
      packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart2);

    function drawChart2() {
      //データベースの値挿入
      var data2 = google.visualization.arrayToDataTable([
        ["Task", "Hours per Day"],
        <?php
        $contents = ['N予備校', 'ドットインストール', 'POSSE課題', 'Udemy', 'その他'];
        foreach ($contents as $value2) {
          $stmt = $db->prepare('select sum(hour) from study where contents_name=? and date like ?');
          $stmt->bind_param('ss', $value2,$todayMonth);
          $stmt->execute();
          $stmt->bind_result($contentsHour);
          while ($stmt->fetch()) {
            if (!isset($contentsHour)) {
              $contentsHour = 0;
            } ?>["<?= $value2; ?>", <?= $contentsHour; ?>], <?php }
                                                        }
                                                            ?>
      ]);

      var options = {
        pieHole: 0.4,
        backgroundColor: "transparent",
        legend: "none",
        fontSize: 15,
        colors: ["#ff4500", "#ff4500", "#ff6347", "#ff8c00", "#ffa500"],
        pieSliceBorderColor: "none",
        chartArea: {
          left: 0,
          top: 0,
          width: "100%",
          height: "100%"
        },
      };

      var chart = new google.visualization.PieChart(
        document.getElementById("donutchart2")
      );
      chart.draw(data2, options);
    }

    google.charts.load("current", {
      packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      //データベースの値挿入
      var data = google.visualization.arrayToDataTable([
        ["Task", "Hours per Day"],
        <?php
        $languages = ['HTML', 'CSS', 'JavaScript', 'PHP', 'Laravel', 'SQL', 'Python', 'React', '情報システム基礎(その他)'];
        foreach ($languages as $value) {
          $stmt = $db->prepare('select sum(hour) from study where language_name=? and date like ?');
          $stmt->bind_param('ss', $value,$todayMonth);
          $stmt->execute();
          $stmt->bind_result($languageHour);
          while ($resultlanguage = $stmt->fetch()) {
            if (!isset($languageHour)) {
              $languageHour = 0;
            } ?>["<?= $value; ?>", <?= $languageHour; ?>], <?php }
                                                        }
                                                            ?>
      ]);

      var options = {
        pieHole: 0.4,
        backgroundColor: "transparent",
        colors: [
          "#FF2400",
          "#ff4500",
          "#ff6347",
          "#ff8c00",
          "#ffa500",
          "#f1a478",
          "#ffd27d",
          "#FFF380",
          "#FFF8C6",
        ],
        legend: "none",
        fontSize: 15,
        pieSliceBorderColor: "none",
        // 'width': 300,
        // 'height': 300,
        chartArea: {
          left: 0,
          top: 0,
          width: "100%",
          height: "100%"
        },
      };

      var chart = new google.visualization.PieChart(
        document.getElementById("donutchart")
      );
      chart.draw(data, options);
    }
  </script>
  <script src="main.js"></script>
</body>

</html>