<!DOCTYPE HTML>
<?php
	session_start();

    $pdo = new PDO('sqlite:alldata.db');
    $query = "SELECT id FROM UserTable WHERE Phone==";
    $query = $query."\"".$_COOKIE["user_id_cookie"]."\"";
    $sth = $pdo->query($query);
    $sth->setFetchMode(PDO::FETCH_NUM);
    $data = $sth->fetchAll();
	$user_id = $data[0][0];

    $query = "SELECT Name FROM UserTable WHERE Phone==";
    $query = $query."\"".$_COOKIE["user_id_cookie"]."\"";
    $sth = $pdo->query($query);
    $sth->setFetchMode(PDO::FETCH_NUM);
    $getName = $sth->fetchAll();
    $member = $getName[0][0];

	$register_logout_url = isset($_COOKIE["user_id_cookie"]) ? "./logout.php" : "./register.php";
	$login_profile_url = isset($_COOKIE["user_id_cookie"]) ? "./profile.php" : "./login.php";
	$cart_login_url = isset($_COOKIE["user_id_cookie"]) ? "./ShoppingCart.php" : "./login.php";

	if (isset($_POST['productID'])) {
		$_product_id = $_POST['productID'];
		$pdo = new PDO('sqlite:alldata.db');
        $sth = $pdo->prepare("DELETE FROM Cart WHERE user_id==$user_id AND product_id==$_product_id");
        $sth->execute();
	}
?>

<html>
	<head>
		<title>購物車</title>
		<meta charset="utf-8" />
		<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
		<script src="https://kit.fontawesome.com/be03ab0af6.js" crossorigin="anonymous"></script>

		<link rel="stylesheet" href="./assets/css/style.css" />
		<link rel="stylesheet" href="./assets/css/header.css" />
		<link rel="stylesheet" href="./assets/css/footer.css" />
	</head>
	<body class="is-preload no-sidebar">
		<v-app id="app">
			<v-main>

			<div id="header">
				<v-row>
					<v-col cols="12" lg="3" md="3" sm="12">
						<a id="logo" href="./">
							<h2>Shawning Shop</h2>
						</a>
					</v-col>
					<v-col cols="12" lg="6" md="6" sm="12" >
						<v-form action="search.php" methods="GET" id="searchForm">
							<input type="text" placeholder="Search.." id="search" name="search"/>
							<v-btn type="submit"><v-icon>mdi-magnify</v-icon></v-btn>
						</v-form>
						<v-chip-group style="margin-left: 60px; padding-left: 60px"
							active-class="primmary--text"
							column
						>
							<v-chip 
								v-for="(tag, key) in tags"
								:key="tag"
								@click="fun( `${key}` )"
							>
								{{ tag }}
							</v-chip>
						</v-chip-group>
					</v-col>
					<v-col cols="12" lg="3" md="3" sm="12">
						<div id="nav" >
							<a href=<?php echo $cart_login_url ?>>
								<v-icon class="icon">mdi-cart</v-icon>購物車
							</a>
							<a href=<?php echo $register_logout_url ?>>
								<v-icon class="icon">mdi-account-plus</v-icon><?php echo isset($_COOKIE["user_id_cookie"]) ? "登出" : "註冊" ?>
							</a>
							<a href=<?php echo $login_profile_url ?>>
								<v-icon class="icon">mdi-account</v-icon> <?php echo isset($_COOKIE["user_id_cookie"]) ? "歡迎".$member : "登入" ?>
							</a>
						</div>
					</v-col>
				</v-row>
			</div>


			<div id="main">
			    <div id="main-wrapper">
							<table>
								<v-row>
									<v-col cols='12' sm='4'>圖片</v-col>
									<v-col cols='12' sm='2'>商品名稱</v-col>
									<v-col cols='12' sm='2'>單價</v-col>
									<v-col cols='12' sm='2'>數量</v-col>
									<v-col cols='12' sm='2'>操作</v-col>
								</v-row>
								<?php
									$sum = 0;
									$pdo = new PDO('sqlite:alldata.db');
									$query = "SELECT * FROM Cart WHERE user_id==$user_id";
									$sth = $pdo->query($query);
									$sth->setFetchMode(PDO::FETCH_NUM);
									$getCartData = $sth->fetchAll();

									if (isset($getCartData[0]) == NULL) {
										$nothing = 1;
									}
									else {
										$nothing = 0;
                                        
										for ($i=0; $i < count($getCartData); $i++) {
											$product_id = $getCartData[$i][2];
											$amount = $getCartData[$i][3];
											
											$pdo = new PDO('sqlite:alldata.db');
											$query = "SELECT * FROM Products WHERE id==$product_id";
											$sth = $pdo->query($query);
											$sth->setFetchMode(PDO::FETCH_NUM);
											$productData = $sth->fetchAll();
			
											$picture_ref = "./product_img/".$productData[0][5];
											$product_name= $productData[0][1];
											$price= $productData[0][4];
			
											$sum += $price * $amount;
											echo "
											<v-row>
											<v-col cols='12' sm='4'><img src='$picture_ref' alt='$product_name' style='height: 120px'></v-col>
											<v-col cols='12' sm='2'>$product_name</v-col>
											<v-col cols='12' sm='2'>$price</v-col>
											<v-col cols='12' sm='2'>$amount</v-col>
											<v-col cols='12' sm='2'><v-btn class='btn' color='#fb5552' @click='delBtnFunc(".$product_id.")'>刪除</v-btn></v-col>
											</v-row>
											<v-form id='func".$product_id."' name='func".$product_id."' class='func".$product_id."' action='./ShoppingCart.php' method='POST'>
												<input type='hidden' value='".$product_id."' name='productID'>
											</v-form>";
										}
									}?>

									
									<v-row>
										<v-col cols='12' sm='4'></v-col>
										<v-col cols='12' sm='2'></v-col>
										<v-col cols='12' sm='2'>總金額:</v-col>
										<v-col cols='12' sm='2'><?php echo $sum; ?></v-col>
										<v-col cols='12' sm='2'><v-btn class="ma-2 white--text" color='rgb(16, 111, 179)' x-large id="checkoutBtn" onclick="location.href='checkout.php';" <?php if ($nothing==1) echo "disabled" ?>>結帳</v-btn></v-col>
									</v-row>
							</table>
				</div>
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
						<a href="https://www.instagram.com/hsuchen1023/" style="text-decoration: none" target="_blank">
						<v-icon size="40px">
							mdi-instagram
						</v-icon>
						</a>
					</v-btn>
				</div>
				<div class="information">
					<h3>楊東倫<h3>
					<v-btn class="mx-4 white--text" icon>
						<a href="https://www.facebook.com/profile.php?id=100023998800521" style="text-decoration: none" target="_blank">
						<v-icon size="40px">
							mdi-facebook
						</v-icon>
						</a>
					</v-btn>
					<v-btn class="mx-4 white--text" icon>
						<a href="https://www.instagram.com/lun__0821/" style="text-decoration: none" target="_blank">
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
			
			</script>
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>

			<script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
			<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
			<script src="https://unpkg.com/axios/dist/axios.min.js"></script>


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
						slides: [
							'First',
							'Second',
							'Third',
							'Fourth',
							'Fifth',
						],
						tags: [
							'青軸',
							'紅軸',
							'銀軸',
							'無線',
							'RGB',
							'100%',
							'60%',
							'英文鍵帽'
						],
						items: [
							{
								src: './product_img/rog_flare2_2.jpg',
							},
							{
								src: './product_img/razer_pro_typeultra_white_yellow_en.jpg',
							},
							{
								src: './product_img/msi_gk71_red_2.jpg',
							},
							{
								src: './product_img/filco_104_2.jpg',
							}
						]
					}
				},
				methods: {
					delBtnFunc:(i)=>{
							func_num='func'+i;
							document.forms[func_num].submit();
					},
					fun: function(key) {
						var tagContent = document.getElementsByClassName("v-chip__content");
						str = tagContent[key].innerHTML;
						str = tagContent[key].innerHTML.replace(/\s/g, '');
						document.getElementById("search").value = str;
						document.getElementById("searchForm").submit();
					}
				}
			});


			</script>

	</body>
</html>