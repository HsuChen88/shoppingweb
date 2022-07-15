<?php
    function checkUser($inputData, $rowName) {
        $dbName = 'sqlite:alldata.db';

        $pdo = new PDO($dbName);
        $query = "SELECT ";
        $query = $query.$rowName;
        $query = $query." FROM UserTable WHERE ";
        $query = $query.$rowName;
        $query = $query." == '$inputData'";
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
    
    if (isset($_POST['loginBtn'])) {
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        
        if ($phone=="" || $password=="") {
            header("Location:login.php");
        }
        $rowPhone = 'Phone';
        $rowPassword = 'password';

        //搜尋帳號
        $findAccount = checkUser($phone, $rowPhone);   // arg2:大寫為資料庫row名稱
        echo $findAccount;
        if ($findAccount == FALSE) {
            echo '<script language="javascript">';
            echo "alert(\"查無此帳號..\\n請再輸入一次或先加入會員\");";
            echo "location.href='login.php';";
            echo "</script>";
        }
    //     // 檢查密碼
        $checkPwd = checkUser($password, $rowPassword);       // arg2:大寫為資料庫row名稱
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

    else if (isset($_POST['addBtn'])) {
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
            echo "location.href='loregister.php';";
            echo "</script>";
        }
        // 確認密碼長度超過8個字
        else if ($pwdLeastMatches == FALSE) {
            echo '<script language="javascript">';
            echo "alert(\"輸入的密碼未達8個字\\n請再輸入一次\");";
            echo "location.href='loregister.php';";
            echo "</script>";
        }
        // 確認兩次密碼相同
        else if ($pwdSameMatches == FALSE) {
            echo '<script language="javascript">';
            echo "alert(\"兩次輸入的密碼不相同\\n請再輸入一次\");";
            echo "location.href='loregister.php';";
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
    }
?>