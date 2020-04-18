<?php
// 時間設定
date_default_timezone_set('Asia/Tokyo');
$timestamp = time() ;
$now = date("Y/m/d H:i:s",$timestamp);
// ↑時間設定

$err_msg = "";//エラーメッセージを格納する変数を定義
// 変数宣言
$submit = $_POST['submit'];
$id = $_POST['id'];
$name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
$zip1 = htmlspecialchars($_POST['zip1'], ENT_QUOTES, 'UTF-8');
$zip2 = htmlspecialchars($_POST['zip2'], ENT_QUOTES, 'UTF-8');
$area = $_POST['area'];
$live1 = htmlspecialchars($_POST['live1'], ENT_QUOTES, 'UTF-8');
$live2 = htmlspecialchars($_POST['live2'], ENT_QUOTES, 'UTF-8');
$tel = htmlspecialchars($_POST['tel'], ENT_QUOTES, 'UTF-8');
$mail = htmlspecialchars($_POST['mail'], ENT_QUOTES, 'UTF-8');
$txt = htmlspecialchars($_POST['txt'], ENT_QUOTES, 'UTF-8');
$err_msg = array();
// ↑変数宣言

// DB接続時のユーザー名とパスワード
$user = 'ootaren';
$password = '04060406';

// PHPのエラーを表示するように設定
error_reporting(E_ALL & ~E_NOTICE);

// データベースの接続
try {
  $dbh = new PDO('mysql:dbname=contacts;host=localhost', $user, $password);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo 'DB接続失敗:' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
  exit;
}

?>
<!DOCTYPE html>
<html lang="ja" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>お問い合わせ確認画面</title>
  <link rel="stylesheet" href="/ootajyuuki/styles.css" type="text/css">
</head>
<body>
  <div id="contentsMain">
    <h2>確認画面</h2>
    <ul><br>
      <!-- 入力した日付時刻 -->
      <li>
        <span class="time"><?php echo $now; ?></span>
      </li><br>

      <!-- 名前 -->
      <li>
        <?php if ($name == '') {
          echo '名前が入力されていません。' . "<br>" . "<br>";
          $err_msg[] = '名前が入力されていません。' . "<br>";
        } else {
          echo 'お名前：' . $name . "<br>" . "<br>";
        } ?>
      </li><br>

      <!-- 郵便番号 -->
      <li>
        <?php if ($zip1 == '' || $zip2 == '') {
          echo '郵便番号が入力されていません。' . "<br>" . "<br>";
          $err_msg[] = '郵便番号が入力されていません。' . "<br>";
        } else {
          echo '郵便番号：' . $zip1 . " - " . $zip2 . "<br>" . "<br>";
        } ?>
      </li><br>

      <!-- 都道府県エリア -->
      <li>
        <?php
        if ($area == null) {
          echo '都道府県を選択してください。';
          $err_msg[] = '都道府県を選択しいてください。' . "<br>";
        } else {
          echo '都道府県：' . $area;
        }
        ?>
      </li><br>

      <!-- 住所(市区村町) -->
      <li>
        <?php if ($live1 == '') {
          echo '住所が入力されていません。' . "<br>" . "<br>";
          $err_msg[] = '住所が入力されていません。' . "<br>";
        } else {
          echo '住所：' . $live1 . $live2 . "<br>" . "<br>";
        } ?>
      </li><br>

      <!-- 電話番号 -->
      <li>
        <?php if ($tel == '') {
          echo '電話番号が入力されていません。' . "<br>" . "<br>";
          $err_msg[] = '電話番号が入力されていません。' . "<br>";
        } else {
          echo '電話番号：' . $tel . "<br>" . "<br>";
        } ?>
      </li><br>

      <!-- メールアドレス -->
      <li>
        <?php if ($mail == '') {
          echo 'メールアドレスが入力されていません。' . "<br>" . "<br>";
          $err_msg[] = 'メールアドレスが入力されていません。' . "<br>";
        } else {
          echo 'メールアドレス：' . $mail . "<br>" . "<br>";
        } ?>
      </li><br>

      <!-- お問い合わせ内容 -->
      <li>
        <?php if ($txt == '') {
          echo '見積もり・お問い合わせ内容が入力されていません。' . "<br>" . "<br>";
          $err_msg[] = '見積もり・お問い合わせ内容が入力されていません。' . "<br>";
        } else {
          echo '見積もり・お問い合わせ内容：' . $txt . "<br>" . "<br>";
        } ?>
      </li><br>
    </ul>


    <!-- エラーが1項目でもあると表示するもの -->
    <?php if (count($err_msg)): ?>
      <?php foreach ($err_msg as $msg): ?>
        <ul class="err_msg">
          <li><?php echo $msg; ?></li>
        </ul>
      <?php endforeach; ?>
    <?php else: ?>
      <p>以上の内容でよろしければ<span class="ok_btn">送信</span>を。</p>
      <p>修正する場合は<span class="no_btn">修正</span>を。</p>
      <form action="sendmail.php" method="post">
        <input type="hidden" name="now" value="<?php $now ?>"><br>
        <input type="hidden" name="name" value="<?php $name ?>"><br>
        <input type="hidden" name="zip1" value="<?php $zip1 ?>"><br>
        <input type="hidden" name="zip2" value="<?php $zip2 ?>"><br>
        <input type="hidden" name="area" value="<?php $area ?>"><br>
        <input type="hidden" name="live1" value="<?php $live1 ?>"><br>
        <input type="hidden" name="live2" value="<?php $live2 ?>"><br>
        <input type="hidden" name="tel" value="<?php $tel ?>"><br>
        <input type="hidden" name="mail" value="<?php $mail ?>"><br>
        <input type="hidden" name="txt" value="<?php $txt ?>"><br><br>
        <input type="button" onClick="history.back()" value="修正する"><br>
        <input type="submit" value="送信">
      </form>
    <?php endif; ?><?php  ?>




    <?php
    if ($_POST['submit']) {
      try {
        $dbh = new PDO('mysql:dbname=contacts;host=localhost;charset=utf8', $user, $password);
        $sql = "INSERT INTO members (name, zip1, zip2, area, live1, live2, tel, mail, txt, now) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $name, PDO::PARAM_STR);
        $stmt->bindValue(2, $zip1, PDO::PARAM_INT);
        $stmt->bindValue(3, $zip2, PDO::PARAM_INT);
        $stmt->bindValue(4, $area, PDO::PARAM_STR);
        $stmt->bindValue(5, $live1, PDO::PARAM_STR);
        $stmt->bindValue(6, $live2, PDO::PARAM_STR);
        $stmt->bindValue(7, $tel, PDO::PARAM_INT);
        $stmt->bindValue(8, $mail, PDO::PARAM_STR);
        $stmt->bindValue(9, $txt, PDO::PARAM_STR);
        $stmt->bindValue(10, $now, PDO::PARAM_STR);

        $stmt->execute();
        $dbh = null;
        echo '<form action="sendmail.php" method="post"></form>';

      } catch (PDOException $e) {
        echo 'DB接続エラー: ' . htmlspecialchars($e->getMassage(), ENT_QUOTES, 'UTF-8') . "<br>";
        die();
      }
    }

    ?>
  </div>
</body>
</html>
