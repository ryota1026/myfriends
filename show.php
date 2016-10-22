<?php
    // echo '<br>';
    // echo '<br>';
    //①DBと接続
    $dsn = 'mysql:dbname=myfriends;host=localhost';
    $user = 'root';
    $password='';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->query('SET NAMES utf8');
    //areaテーブルからパラメータのarea_idを使用してデータ1レコードを取得
    //$_GET = array('area_id'=>'20');
    //②GETパラメータを取得
    if(!empty($_GET['action']) && $_GET['action'] == 'delete') {
      $sql = 'DELETE FROM `friends` WHERE `friend_id` = ?';
      $date[] = $_GET['friend_id'];
      $stmt = $dbh->prepare($sql);
      $stmt->execute($date);

      header('Location: index.php');
      exit();
    }
    $area_id = $_GET['area_id'];

    //③SQL文を作成(DBからareasテーブルのarea_nameの情報を取得する)
    $sql = 'SELECT `area_name` FROM `areas` WHERE `area_id` = ?';
    $date[] = $area_id;
    //④SQL文を実行
    $stmt = $dbh->prepare($sql);
    $stmt->execute($date);

    //1レコード文しか取得しないのでfetchも一回のみ行う
    //⑤都道府県名を取得
    $area = $stmt->fetch(PDO::FETCH_ASSOC);

    // echo '<br>';
    // echo '<br>';

    // ⑥友達一覧を取得するSQL文を作成
    $sql = 'SELECT * FROM `friends` WHERE `area_id` = ?';
    $stmt = $dbh->prepare($sql);
    $stmt->execute($date);

    // 友達データ格納用変数
    $friends = array();

    $male = 0;
    $female = 0;

   // 友達のデータを取得
    while (1) {
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($rec == false) {
       break;
      }
      if($rec['gender'] == 0) {
        $male++;
      } elseif($rec['gender'] == 1){
        $female++;
      }
        $friends[] = $rec;

    }

   

    $dbh = null;

    // echo '<pre>';
    // var_dump($friends);
    // echo '</pre>';
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>myFriends</title>

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/timeline.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>
  <body>
  <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header page-scroll">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                  <span class="sr-only">Toggle navigation</span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="index.php"><span class="strong-title"><i class="fa fa-facebook-square"></i> My friends</span></a>
          </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" >
              <ul class="nav navbar-nav navbar-right">
              </ul>
          </div>
          <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-md-4 content-margin-top">
      <legend><?php echo $area['area_name']; ?>の友達</legend>
      <div class="well">男性：<?php echo $male; ?>名　女性:<?php echo $female; ?>名</div>
        <table class="table table-striped table-hover table-condensed">
          <thead>
            <tr>
              <th><div class="text-center">名前</div></th>
              <th><div class="text-center"></div></th>
            </tr>
          </thead>
          <tbody>
            <!-- 友達の名前を表示 -->
            <?php foreach($friends as $friend): ?>
            <tr>
              <td><div class="text-center">
                <?php echo $friend['friend_name']; ?></div>
              </td>
              <td>
                <div class="text-center">
                  <a href="edit.php?friend_id=<?php echo $friend['friend_id']; ?>"><i class="fa fa-pencil"></i></a>
                  &nbsp;&nbsp;&nbsp;&nbsp;
                  <a href="javascript:void(0);" onclick="destroy(<?php echo $friend['friend_id']; ?>);"><i class="fa fa-trash"></i></a>
                </div>
              </td>
            </tr>
          <?php endforeach ; ?>
          </tbody>
        </table>

        <input type="button" class="btn btn-default" value="新規作成" onClick="location.href='new.php'">
      </div>
    </div>
  </div>

      <script>
        function destroy(friend_id) {
          if(confirm('削除しますか？') == true) {
            //location.href=''で指定された''のページに飛ぶ
            location.href = 'show.php?action=delete&friend_id=<?php echo $friend['friend_id']; ?>';
            return true;
          } else {
            return false;
          }
        }
      </script>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>
