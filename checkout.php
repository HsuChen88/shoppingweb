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
<?php
    // echo "你已購買廢物";
    $pdo = new PDO('sqlite:alldata.db');
	$query = "SELECT * FROM Cart WHERE user_id==$user_id";
	$sth = $pdo->query($query);
	$sth->setFetchMode(PDO::FETCH_NUM);
	$getCartData = $sth->fetchAll();
    var_dump($getCartData);
    echo "<br>";

    for ($i=0; $i < count($getCartData); $i++) {
        $product_id = $getCartData[$i][2];
        $amount = $getCartData[$i][3];
        
        $pdo = new PDO('sqlite:alldata.db');
        $query = "SELECT amount FROM Products WHERE id==$product_id";
        $sth = $pdo->query($query);
        $sth->setFetchMode(PDO::FETCH_NUM);
        $productData = $sth->fetchAll(); 
        var_dump($productData);
        echo "<br>";
        $product_amount = $productData[0][0];
        $product_amount -= $amount;
        echo $product_amount;
        $sth = $pdo->prepare("UPDATE Products SET amount='$product_amount' WHERE product_id==$product_id");
        $sth->execute();
    }
?>

<button id="backBtn">回首頁</button>

<script language="javascript">
	const backBtn = document.getElementById('backBtn')
	backBtn.addEventListener('click', function () {
		location.href='index.php';
	});
</script>