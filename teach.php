<!DOCTYPE html>
<html lang="ja">
    <head lang="ja">
        <meta charset="utf-8">
        <title>Mission5-1</title>
    </head>
    <body>
        
        <?php
            $dsn = 'mysql:dbname=データベース名;host=localhost';
            $user = 'ユーザ名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
            $sql = "CREATE TABLE IF NOT EXISTS Mission5"
            ." ("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "name char(32),"
            . "comment TEXT,"
            . "datetime DATETIME,"
            ."password TEXT"
            .");";
            $stmt = $pdo->query($sql);
            //SQL分を開く

            $comment = $_POST["comment"]; //コメント
            $name = $_POST["name"] ; //名前
            $password = $_POST["password"]; //パスワード
            //新規投稿
            if($_POST["submit"] && !$_POST["editor"]){
                if(!empty($comment) && !empty($name)){
                    if(!empty($password)){
                        $sql = $pdo -> prepare("INSERT INTO Mission5 (name, comment, datetime, password) VALUES (:name, :comment, now(), :password)");
                        //準備を行う
                        $sql -> bindParam(':name',$name,PDO::PARAM_STR);
                        $sql -> bindParam(':comment',$comment,PDO::PARAM_STR);
                        $sql -> bindParam(':password',$password,PDO::PARAM_STR);
                        $sql -> execute();
                        //登録を行う
                    }
                    else{
                        $erroe="パスワードを入力してください<br>";
                    }
                }
                else{
                    $error="文字を入力してください<br>";
                }      
            }
            //編集投稿
            else if($_POST["submit"]&&$_POST["editor"]){
                if(!empty($comment) && !empty($name)){
                    if(!empty($_POST["password"])){
                        $id = $_POST["editor"];//変更する投稿番号
                        //変更する番号のみ抽出
                        $sql = 'SELECT * FROM Mission5 WHERE id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
                        $stmt->execute();
                        $results = $stmt->fetchAll();
                        foreach($results as $row){
                            $tmp = $row["password"];
                        }
                        if($tmp==$password){
                            $sql = 'UPDATE Mission5 SET name=:name,comment=:comment,datetime=now(),password=:password WHERE id=:id';
                            $stmt=$pdo->prepare($sql);
                            $stmt -> bindParam(':id',$id,PDO::PARAM_INT);
                            $stmt -> bindParam(':name',$name,PDO::PARAM_STR);
                            $stmt -> bindParam(':comment',$comment,PDO::PARAM_STR);
                            $stmt -> bindParam(':password',$password,PDO::PARAM_STR);
                            $stmt->execute();
                        }
                    }
                    else{
                        $error="パスワードを入力してください<br>";
                    }
                }
                else{
                    $error="文字を入力してください<br>";
                }
            }
            //削除
            else if($_POST["delete"]){
                if(!empty($_POST["del_num"])){
                    if(!empty($_POST["password"])){
                        $id=$_POST["del_num"];
                        $sql = 'SELECT * FROM Mission5 WHERE id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
                        $stmt->execute();
                        $results = $stmt->fetchAll();
                        foreach($results as $row){
                            $tmp = $row["password"];
                        }
                        if($tmp==$password){
                            $sql = 'delete from Mission5 where id=:id';
                            $stmt=$pdo->prepare($sql);
                            $stmt->bindParam(':id',$id,PDO::PARAM_INT);
                            $stmt->execute();
                        }
                    }
                    else{
                        $error="パスワードを確認してください<br>";
                    }
                }
                else{
                    $error="削除番号を入力してください";
                }
            }
            //編集番号
            else if($_POST["edit"]){
                if(!empty($_POST["edit_num"])){
                    if(!empty($_POST["password"])){
                        $id=$_POST["edit_num"];
                        $sql = 'SELECT * FROM Mission5 WHERE id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
                        $stmt->execute();
                        $results = $stmt->fetchAll();
                        foreach($results as $row){
                            $tmp = $row["password"];
                        }
                        if($tmp == $password){
                            foreach($results as $row){
                                $edit_name=$row["name"];
                                $edit_com=$row["comment"];
                                $edit_num=$id;
                            }
                        }
                        else{
                            $error="パスワードが違います<br>";
                        }
                    }                  
                    else{
                        $error="パスワードを入力してください<br>";
                    }
                }
                
            }
        ?>
        
        <form action="" method="post">
            【名前】:
            <input type="text" name="name" value="<?php if(isset($edit_name)){echo $edit_name;}?>">
            <br>
            【コメント】:
            <input type="text" name="comment" value="<?php if(isset($edit_com)){echo $edit_com;}?>">
            <br>
            <input type="hidden" name="editor" value="<?php if(isset($edit_num)){echo $edit_num;}?>"> 
            <br>
            【パスワード】:
            <input type="text" name="password">
            <br>
            <input type="submit" name="submit">
        </form>
        <form action="" method="post">
            【削除番号】:
            <input type="number" name="del_num">
            <br>
            【パスワード】:
            <input type="text" name="password">
            <br>
            <input type="submit" name="delete" value="削除">
        </form>
        <form action="" method="post">
            【編集番号】:
            <input type="number" name="edit_num" placeholder="番号">
            <br>
            【パスワード】:
            <input type="text" name="password">
            <br>
            <input type="submit" name="edit" value="編集">
        </form>

        <?php
            $sql = 'SELECT * FROM Mission5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();

            foreach($results as $row){
                echo $row['id'].',';
                echo $row['name'].',';
                echo $row['comment'].',';
                echo $row['datetime'].'<br>';
                echo "<hr>";
            }
        ?>        
    </body>
</html>