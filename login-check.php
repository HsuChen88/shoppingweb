<?php
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $rowPhone = "Phone";
    $rowPassword = "Password";
    // 搜尋帳號
    $findAccount = checkUser($phone, $rowPhone);   // arg2:大寫為資料庫row名稱
    echo $findAccount;
    if ($findAccount == FALSE) {
        echo '<script language="javascript">';
        echo "alert(\"查無此帳號..\\n請再輸入一次或先加入會員\");";
        echo "location.href='login.php';";
        echo "</script>";
    }
    else {
        // 檢查密碼
        $checkPwd = checkUser($password, $rowPassword);       // arg2:大寫為資料庫row名稱
        echo $checkPwd;
        if ($checkPwd == TRUE) {
            session_start();
            $_SESSION['user_id'] = $phone;
            $cookie_value = $_SESSION['user_id'];
            setcookie("user_id_cookie", $cookie_value, time() + (86400 * 7), "/");
            header("Location:index.php");
        }
        else {
            echo '<script language="javascript">';
            echo "alert(\"密碼不正確..\\n請再輸入一次\");";
            echo "location.href='login.php';";
            echo "</script>";
        }
    }
    

    function checkUser($_check, $rowName) {
        $dbName = 'sqlite:alldata.db';

        $pdo = new PDO($dbName);
        $query = "SELECT $rowName FROM UserTable WHERE $rowName=='$_check'";
        $sth = $pdo->query($query);
        $sth->setFetchMode(PDO::FETCH_NUM);
        $result = $sth->fetchAll();
        
        if ($result == False) {
            return FALSE;
        }
        else {
            return TRUE;
        }
    }
?>