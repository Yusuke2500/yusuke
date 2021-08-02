<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8;">
    <title>mission5-1</title>
    
    
</head>
<body>
<?php
    $edit_name="";
    $edit_comment="";
    $edit_num="";

    //DB接続設定
    $dsn='データベース名';
    $user='ユーザー名';
    $password='パスワード名';
    $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //PDO関数：PHPでMySQLを操作するときに利用する関数


    //CREATE文：テーブル作成
    //SOL文
    $sql="CREATE TABLE IF NOT EXISTS MISSION5"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name TEXT,"
    ."comment TEXT,"
    ."date TEXT,"
    ."password1 TEXT"
    .");";
    $stmt = $pdo->query($sql);
    

    
   
    
    
    //削除フォーム
    if(!empty($_POST["num_delete"]) && !empty($_POST["password2"])){
        $delete=$_POST["num_delete"];
        $del_pass=$_POST["password2"];

        //SELECT文：データレコードを取得
        $sql = 'SELECT * FROM MISSION5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            $row['id'].' ';
            $row['name'].' ';
            $row['comment'].' ';
            $row['date'].' ';
            $row['password1']."<br>";
            
            
            //削除機能場合分け
            //データベースから取り出したidとpassをフォームの値と比較
            //①id不一致，pass不一致
            if($row['id'] != $delete && $row['password1'] != $del_pass){
                $row['id'].' ';
                $row['name'].' ';
                $row['comment'].' ';
                $row['date']. "<br>";
                "<hr>";
                
            //②id一致，pass不一致
            }elseif($row['id'] == $delete && $row['password1'] != $del_pass){
                $row['id'].' ';
                $row['name'].' ';
                $row['comment'].' ';
                $row['date']. "<br>";
                "<hr>";
                
            //③id不一致，pass一致
            }elseif($row['id'] != $delete && $row['password1'] == $del_pass){
                $row['id'].' ';
                $row['name'].' ';
                $row['comment'].' ';
                $row['date']. "<br>";
                "<hr>";
            }
                
            //idとpassが一致したら削除
            if($row['id'] == $delete && $row['password1'] == $del_pass){
            //DELETE文：データレコードを削除
            $id = $delete;
            $sql = 'delete from MISSION5 where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            }
        }
    }
        
    
    //編集フォーム
    //フォームに表示する編集選択機能
    if(!empty($_POST["num_edit"]) && !empty($_POST["password3"])){
        $id=$_POST["num_edit"];
        $edit_pass=$_POST["password3"];    
    
        //SELECT文：データレコードを取得
        $sql = 'SELECT * FROM MISSION5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            $row['id'].' ';
            $row['name'].' ';
            $row['comment'].' ';
            $row['date'].' ';
            $row['password1']."<br>";

            //投稿番号と編集対象番号を比較
            //パスワードが一致した時入力フォームに投稿内容を表示
            if($row['id'] == $id && $row['password1'] == $edit_pass){
                $edit_num=$row['id'];
                $edit_name=$row['name'];
                $edit_comment=$row['comment'];
                
            }
        }
    }
                //上書きする編集実行機能        
                if(!empty($_POST["edit_do"])){
                    $id=$_POST["edit_do"];
                    $name=$_POST["name"];
                    $comment=$_POST["comment"];
                    $date=date("Y/m/d H:i:s");
                    $pass=$_POST["password1"];
                    
                    
                        //UPDATE文：データレコードの編集
                        $sql = 'UPDATE MISSION5 SET name=:name,comment=:comment,date=:date WHERE id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                        $stmt->execute();
                }               
        elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["password1"])){
        $name=$_POST["name"];
        $comment=$_POST["comment"];
        $date=date("Y/m/d H:i:s");
        $pass=$_POST["password1"];
    
        //INSERT文：データ(レコード)を挿入 
        $sql = $pdo -> prepare("INSERT INTO MISSION5 (name, comment, date, password1) VALUES(:name, :comment, :date, :password1)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':password1', $pass, PDO::PARAM_STR);
        $sql -> execute();

        
    
                        
                }

?>

<form action="" method="post">
    日本のリレー順位予想！<br>
    投稿<br>
    <input type="text" name="name" placeholder="名前"value="<?php echo $edit_name; ?>"><br>
    <input type="text" name="comment" placeholder="コメント" value="<?php echo $edit_comment; ?>"><br>
    <input type="hidden" name="edit_do" value="<?php echo $edit_num; ?>">
    <input type="text" name="password1" placeholder="パスワード">
    <input type="submit" name="submit" value="送信"><br>
    削除<br>
    <input type="num" name="num_delete" placeholder="削除対象番号"><br>
    <input type="text" name="password2" placeholder="パスワード">
    <input type="submit" name="delete" value="削除"><br>
    編集<br>
    <input type="num" name="num_edit" placeholder="編集対象番号"><br>
    <input type="text" name="password3" placeholder="パスワード">
    <input type="submit" name="edit" value="編集"><br>
</form>
<?php
//SELECT文：データレコードを取得して表示
        $sql = 'SELECT * FROM MISSION5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].' ';
            echo $row['name'].' ';
            echo $row['comment'].' ';
            echo $row['date']."<br>";
            echo "<hr>";
        }
?>
</body>
</html>
