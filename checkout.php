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
        <title>checkout</title>
    </head>
    <body>
        <div id='checkout' style='position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);'>
            商品已在路上了!!
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
    // var_dump($getCartData);
    // echo "<br>";
    for ($i=0; $i < count($getCartData); $i++) {
        $product_id = $getCartData[$i][2];
        $amount = $getCartData[$i][3];
        
        $pdo = new PDO('sqlite:alldata.db');
        $query = "SELECT amount FROM Products WHERE id==$product_id";
        $sth = $pdo->query($query);
        $sth->setFetchMode(PDO::FETCH_NUM);
        $productData = $sth->fetchAll(); 
        // var_dump($productData);
        // echo "<br>";
        $product_amount = $productData[0][0];
        $product_amount -= $amount;

        $sth = $pdo->prepare("UPDATE Products SET amount=$product_amount WHERE id=$product_id;");

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