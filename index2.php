<?php
    //DBへの接続
    $dsn = 'mysql:dbname=myfriends;host=localhost';
    $user = 'root';
    $password = '';
    $dbh = new PDO($dsn,$user,$password);
    $dbh->query('SEt NAME UTF-8');

    $sql = 'SELECT * FROM `areas` WHERE 1';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
?>