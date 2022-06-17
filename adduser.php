<?php
    $userName = $_POST['name'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    if ($userName == "") $userName = "user";
    
    $phonePattern = "/09[0-9]{8}$/";
    $pwdLeastPattern = "/.{8}/";
    $pwdSamePattern = "/(?:"."$confirmPassword".")$/";
    preg_match($phonePattern, $phone, $phoneMatches);
    preg_match($pwdLeastPattern, $password, $pwdLeastMatches);
    preg_match($pwdSamePattern, $password, $pwdSameMatches);
    
    // 確認電話號碼有無錯誤
    if ($phoneMatches == FALSE) {
        echo '<script language="javascript">';
        echo "alert(\"請輸入正確的電話號碼\");";
        echo "location.href='register.html';";
        echo "</script>";
    }
    // 確認密碼長度超過8個字
    else if ($pwdLeastMatches == FALSE) {
        echo '<script language="javascript">';
        echo "alert(\"輸入的密碼未達8個字\\n請再輸入一次\");";
        echo "location.href='register.html';";
        echo "</script>";
    }
    // 確認兩次密碼相同
    else if ($pwdSameMatches == FALSE) {
        echo '<script language="javascript">';
        echo "alert(\"兩次輸入的密碼不相同\\n請再輸入一次\");";
        echo "location.href='register.html';";
        echo "</script>";
    }
    // 無誤 創建新用戶
    else {
        $dbName = 'sqlite:alldata.db';
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
            echo "location.href='login.php';";
            echo "</script>";
        }
        else {
            $sth = $pdo->prepare("INSERT INTO UserTable VALUES(NULL,'$phone','$userName','$password')");
            $sth->execute();
            echo '<script language="javascript">';
            echo "alert(\"您已成功加入會員 請登入\");";
            echo "location.href='login.php';";
            echo "</script>";
        }
    }

?>