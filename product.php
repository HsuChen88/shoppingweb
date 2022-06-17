<?php
    ini_set("display_errors","On");
    error_reporting(E_ALL);
?>
<?php
	session_start();

    $pdo = new PDO('sqlite:alldata.db');
    $query = "SELECT id FROM UserTable WHERE Phone==";
    $query = $query."\"".$_COOKIE["user_id_cookie"]."\"";
    $sth = $pdo->query($query);
    $sth->setFetchMode(PDO::FETCH_NUM);
    $data = $sth->fetchAll();
	$user_id = $data[0][0];


    $product_id = 1;    // 應該取得該頁面商品的id 暫時用1是冰淇淋
?>
<?php
    function addToCart($_user_id, $_product_id){
        $pdo = new PDO('sqlite:alldata.db');
        $query = "SELECT amount FROM Cart WHERE product_id=$_product_id";
        $sth = $pdo->query($query);
        $sth->setFetchMode(PDO::FETCH_NUM);
        $getCartData = $sth->fetchAll();
        if ($getCartData==FALSE) {
            $sth = $pdo->prepare("INSERT INTO Cart VALUES(NULL,'$_user_id','$_product_id',1)");
            $sth->execute();
        }
        else {
            $amount = $getCartData[0][0];
            $amount += 1;
            $sth = $pdo->prepare("UPDATE Cart SET amount='$amount' WHERE product_id==$_product_id");
            $sth->execute();
        }
        echo "product added.";
    }
?>
<html>
    <head>
        <title>商品頁</title>
    </head>
    <body>
        <button id="addToCartBtn">加入購物車</button>
    </body>
    <script language="javascript">
		const loginBtn = document.getElementById('getproductBtn')
		loginBtn.addEventListener('click', function () {
			var addCart = "<?php addToCart($user_id, $product_id); ?>"
			console.log(addCart);
		});
	</script>
</html>
