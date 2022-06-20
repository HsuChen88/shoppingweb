<!DOCTYPE HTML>
<?php
    session_start();

    $pdo = new PDO('sqlite:alldata.db');
	$query = "SELECT id FROM UserTable WHERE Phone==";
    $query = $query."\"".$_COOKIE["user_id_cookie"]."\"";
	$sth = $pdo->query($query);
	$sth->setFetchMode(PDO::FETCH_NUM);
	$getUserData = $sth->fetchAll();
    $user_id = $getUserData[0][0];
?>

<html>
    <head>
        <title>訂單已完成</title>
    </head>
    <body>
        <div id='checkout' style='position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); text-align:center'>
            <img src="/images/checkedout.jpg" alt="這是一隻郵差鳥" class="center" width="350" height="350">
            <h1>商品已經在路上囉~</h1>
            <button id="backBtn">回首頁</button>
        </div>
    </body>
</html>

<?php
    $pdo = new PDO('sqlite:alldata.db');
	$query = "SELECT * FROM Cart WHERE user_id==$user_id";
	$sth = $pdo->query($query);
	$sth->setFetchMode(PDO::FETCH_NUM);
	$getCartData = $sth->fetchAll();

    $tmp =[];
    for ($i=0; $i < count($getCartData); $i++) {
        $product_id = $getCartData[$i][2];
        $amount = $getCartData[$i][3];

        array_push($tmp,[$product_id, $amount]);

        $pdo = new PDO('sqlite:alldata.db');
        $query = "SELECT amount FROM Products WHERE id==$product_id";
        $sth = $pdo->query($query);
        $sth->setFetchMode(PDO::FETCH_NUM);
        $productData = $sth->fetchAll();
        $product_amount = $productData[0][0];
        $product_amount -= $amount;
        var_dump($tmp);
        
        // 扣掉庫存商品數
        $sth = $pdo->prepare("UPDATE Products SET amount=$product_amount WHERE id=$product_id;");
        $sth->execute();

        echo "user_id".$user_id."<br>";
        echo "product_id".$product_id."<br>";
        echo "product_amount".$product_amount."<br>";
        echo "amount".$amount."<br>";
        // 刪除購物車內容
        $pdo = new PDO('sqlite:alldata.db');
        $sth = $pdo->prepare("DELETE FROM Cart WHERE user_id=$user_id");
        $sth->execute();
    }
?>


<script language="javascript">
	const backBtn = document.getElementById('backBtn')
	backBtn.addEventListener('click', function () {
		location.href='index.php';
	});
</script>