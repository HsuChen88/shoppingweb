<?php
	$user_id=$_POST['userId'];
	$product_id=$_POST['productId'];
    $product_price=$_POST['productPrice'];
    $product_amount=$_POST['productAmount'];
    
	    if ($user_id == "") {
		    echo '<script>
            alert("請先登入會員!");
            location.href = "./login.php";
            </script>';
	    }
	    else {
			$pdo = new PDO('sqlite:alldata.db');
			$query = "SELECT amount FROM Cart WHERE user_id=$user_id AND product_id=$product_id";
			$sth = $pdo->query($query);
			$sth->setFetchMode(PDO::FETCH_NUM);
			$getCartData = $sth->fetchAll();
			$amount = $getCartData[0][0];
			if ($amount > 0) {
				$amount += $product_amount;
				$sth = $pdo->prepare("UPDATE Cart SET amount=$amount WHERE user_id==$user_id AND product_id==$product_id");
				$sth->execute();
			}
			else {
				$sth = $pdo->prepare("INSERT INTO Cart VALUES(NULL,$user_id,$product_id,1)");
				$sth->execute();
			}
			
            echo '<script>
            alert("成功加入購物車!");
            location.href = "./search.php";
            </script>';
		}
?>