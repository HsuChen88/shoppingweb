<?php
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $rowPhone = "Phone";
    $rowPassword = "Password";

    // 搜尋帳號
    $findAccount = checkUser($phone, $rowPhone);   // arg2:大寫為資料庫row名稱
    if ($findAccount == FALSE) {
        echo '<script language="javascript">';
        echo "alert(\"查無此帳號..\\n請再輸入一次或先加入會員\");";
        echo "location.href='login.html';";
        echo "</script>";
    }
    // 檢查密碼
    $checkPwd = checkUser($password, $rowPassword);       // arg2:大寫為資料庫row名稱
    if ($checkPwd == TRUE) {
        header("Location:index.html");
    }
    else {
        echo '<script language="javascript">';
        echo "alert(\"密碼不正確..\\n請再輸入一次\");";
        echo "location.href='login.html';";
        echo "</script>";
    }

    function checkUser($_phone, $rowName) {
        $dbName = 'sqlite:alldata.db';

        $pdo = new PDO($dbName);
        $query = "SELECT $rowName FROM UserTable WHERE $rowName=='$_phone'";
        $sth = $pdo->query($query);
        $sth->setFetchMode(PDO::FETCH_NUM);
        $result = $sth->fetchAll();
        // var_dump($result);
        
        if ($result == False) {
            return FALSE;
        }
        else {
            return TRUE;
        }
    }
?>