<?php
    $userName = $_POST['name'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    if ($userName == "") $userName = "user";
    
    $phonePattern = "/09[0-9]{8}$/";
    $passwordPattern = "/(?:"."$confirmPassword".")$/";
    preg_match($phonePattern, $phone, $phoneMatches);
    preg_match($passwordPattern, $password, $passwordMatches);
    
    // 確認兩次密碼相同
    if ($passwordMatches == FALSE) {
        echo '<script language="javascript">';
        echo "alert(\"兩次輸入的密碼不相同\\n請再輸入一次\");";
        echo "location.href='register.html';";
        echo "</script>";
    }
    // 確認電話號碼有無錯誤
    else if ($phoneMatches == FALSE) {
        echo '<script language="javascript">';
        echo "alert(\"請輸入正確的電話號碼\");";
        echo "location.href='register.html';";
        echo "</script>";
    }
    // 無誤 創建新用戶
    else {
        $dbName = 'sqlite:user.db';
        $pdo = new PDO($dbName);
        $query = "SELECT Phone FROM UserTable WHERE Phone==";
        $query = $query."\"".$_POST['phone']."\"";
        $sth = $pdo->query($query);
        $sth->setFetchMode(PDO::FETCH_NUM);
        $data = $sth->fetchAll();
        // 確認有無註冊過
        if ($data == TRUE) {
            echo '<script language="javascript">';
            echo "alert(\"您已註冊過會員 請登入\");";
            echo "location.href='login.html';";
            echo "</script>";
        }
        else {
            $sth = $pdo->prepare("INSERT INTO UserTable VALUES('$phone','$userName','$password')");
            $sth->execute();
            header("Location: midstop.html");
        }
    }

?>