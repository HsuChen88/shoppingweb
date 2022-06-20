<?php
	session_start();
	if (isset($_COOKIE["user_id_cookie"])) {
		$pdo = new PDO('sqlite:alldata.db');
		$query = "SELECT id FROM UserTable WHERE Phone==";
		$query = $query."\"".$_COOKIE["user_id_cookie"]."\"";
		$sth = $pdo->query($query);
		$sth->setFetchMode(PDO::FETCH_NUM);
		$getUserData = $sth->fetchAll();
		if ($getUserData[0] != FALSE) {
			$user_id = $getUserData[0][0];
		
			$query = "SELECT Name FROM UserTable WHERE id==$user_id";
			$sth = $pdo->query($query);
			$sth->setFetchMode(PDO::FETCH_NUM);
			$getName = $sth->fetchAll();
			$member = $getName[0][0];
		}
	}

	$register_logout_url = isset($_COOKIE["user_id_cookie"]) ? "./logout.php" : "./register.php";
	$login_profile_url = isset($_COOKIE["user_id_cookie"]) ? "./profile.php" : "./login.php";
	$cart_login_url = isset($_COOKIE["user_id_cookie"]) ? "./ShoppingCart.php" : "./login.php";
?>
<?php
	$product_id = $_SESSION['product_browse'];
	$pdo = new PDO('sqlite:alldata.db');
    $query = "SELECT * FROM Products WHERE id==$product_id";
    $sth = $pdo->query($query);
    $sth->setFetchMode(PDO::FETCH_NUM);
    $getProductData = $sth->fetchAll();

	$productName = $getProductData[0][1];
	$productCategory = $getProductData[0][2];
	$productAmount = $getProductData[0][3];
	$productPrice = $getProductData[0][4];
	$productImage = $getProductData[0][5];
	$productDescription = $getProductData[0][6];
?>
<?php
	function ToCart(){
		echo 'alert("幹!");';
		$user_id=2;
		$product_id=5;
		if ($user_id == "") {
			echo 'alert("請先登入會員!");';
		}
		else {			
			$pdo = new PDO('sqlite:alldata.db');
			$query = "SELECT amount FROM Cart WHERE user_id==$user_id AND product_id==$product_id";
			$sth = $pdo->query($query);
			$sth->setFetchMode(PDO::FETCH_NUM);
			$getCartData = $sth->fetchAll();
			$amount = $getCartData[0][0];
			if ($amount > 0) {
				$amount += 1;
				$sth = $pdo->prepare("UPDATE Cart SET amount=$amount WHERE user_id==$user_id AND product_id==$product_id");
				$sth->execute();
			}
			else {
				$sth = $pdo->prepare("INSERT INTO Cart VALUES(NULL,$user_id,$product_id,1)");
				$sth->execute();
			}
			
		echo 'alert("成功加入購物車!");';
		}
	}
?>

<html>
	<head>
		<title>楊東翰</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
		<script src="https://kit.fontawesome.com/be03ab0af6.js" crossorigin="anonymous"></script>
		<script>add_flag = 0</script>

		<link rel="stylesheet" href="./assets/css/style.css" />
		<link rel="stylesheet" href="./assets/css/header.css" />
		<link rel="stylesheet" href="./assets/css/footer.css" />
		<link rel="stylesheet" href="./assets/css/stickbottom.css" />
	</head>
	<body class="is-preload homepage">
		<v-app id="app">
			<v-main>
			<div id="header">
				<v-row>
					<v-col cols="12" lg="3" md="3" sm="12">
						<a id="logo" href="./">
							<h2>Shawning Shop</h2>
						</a>
					</v-col>
					<v-col cols="12" lg="6" md="6" sm="12">
						<form action="search.php" methods="POST">
							<input type="text" placeholder="Search.." id="search"/>
							<v-btn type="submit"><v-icon>mdi-magnify</v-icon></v-btn>
						</form>
						<div>
							<v-chip-group style="margin-left: 60px; padding-left: 60px"
							active-class="primary--text"
							column
							>
								<v-chip class="bg-white"
								v-for="tag in tags"
								:key="tag"
								>
								{{ tag }}
								</v-chip>
							</v-chip-group>
						</div>
					</v-col>
					<v-col cols="12" lg="3" md="3" sm="12">
						<div id="nav">
							<a href=<?php echo $cart_login_url ?>>
								<v-icon class="icon">mdi-cart</v-icon>購物車
							</a>
							<a href=<?php echo $register_logout_url ?>>
								<v-icon class="icon">mdi-account-plus</v-icon><?php echo isset($_COOKIE["user_id_cookie"]) ? "登出" : "註冊" ?>
							</a>
							<a href=<?php echo $login_profile_url ?>>
								<v-icon class="icon">mdi-account</v-icon><?php echo isset($_COOKIE["user_id_cookie"]) ? "歡迎".$member : "登入" ?>
							</a>
						</div>
					</v-col>
				</v-row>
			</div>

			<div id="main">
				<v-container>
					<v-row>
						<v-col cols='12' lg='6' md='6' sm='12'>
							<v-carousel>
								<v-carousel-item
								reverse-transition="fade-transition"
								transition="fade-transition"
								>
								<img src='./product_img/<?php echo $productImage?>' alt='product'/>
								</v-carousel-item>
							</v-carousel>
						</v-col>
						<v-col cols='12' lg='6' md='6' sm='12'>
								<h1><?php echo $productName ?></h1><br>
								<div style="display: inline-flex;">
									<h4>標籤：</h4>
										<v-chip>
											<?php echo $productCategory ?>
										</v-chip>
									<br><br>
								</div>
								<v-card elevation="0" text-align="left" class="blue--text"><h1>$<?php echo $productPrice ?></h1><br></v-card>
								<h4>剩餘數量：<?php echo $productAmount ?></h4><br>
							    <h4>產品介紹：<?php echo $productDescription ?></h4>
						</v-col>
					</v-row>

				</v-container>
				
			</div>

			
			<div id='sticky'>
				<h3>價格：<?php echo $productPrice ?></h3>
				<v-btn color="red" @click="addToCart">加入購物車</v-btn>
			</div>
			
			<div id="footer">
				<div class="information">
					<h3>鄭旭辰<h3>
					<v-btn class="mx-4 white--text" icon>
						<a href="https://www.facebook.com/hsu.chen95763" style="text-decoration: none" target="_blank">
						<v-icon size="40px">
							mdi-facebook
						</v-icon>
						</a>
					</v-btn>
					<v-btn class="mx-4 white--text" icon>
						<a href="https://www.facebook.com/hsu.chen95763" style="text-decoration: none" target="_blank">
						<v-icon size="40px">
							mdi-instagram
						</v-icon>
						</a>
					</v-btn>
				</div>
				<div class="information">
					<h3>楊東倫<h3>
					<v-btn class="mx-4 white--text" icon>
						<a href="https://www.facebook.com/hsu.chen95763" style="text-decoration: none" target="_blank">
						<v-icon size="40px">
							mdi-facebook
						</v-icon>
						</a>
					</v-btn>
					<v-btn class="mx-4 white--text" icon>
						<a href="https://www.facebook.com/hsu.chen95763" style="text-decoration: none" target="_blank">
						<v-icon size="40px">
							mdi-instagram
						</v-icon>
						</a>
					</v-btn>
				</div>

			</div>
			<div id="bottom">
				This Website is made by Shawn & Dino in 2022 June.
			</div>
			
			</v-main>
		</v-app>

		<!-- Scripts -->

<script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-sticky-position@2.0.0/dist/sticky.min.js"></script>


<script>

new Vue({
	el:'#app',
	vuetify: new Vuetify(),
	data () {
      return {
        colors: [
			'indigo',
			'warning',
			'pink darken-2',
			'red lighten-1',
			'deep-purple accent-4',
        ],
		tags: [
			'青軸',
			'紅軸',
			'無線',
			'RGB',
			'80 %',
			'65 %',
			'PBT',
			'英文鍵帽'
		],
		allData:'',
		query:'',
		nodata:false
      }
    },
	methods: {
		addToCart:()=>{
			<?php ToCart(); ?>;
		}	
	}
});


</script>
		
	</body>
</html>

