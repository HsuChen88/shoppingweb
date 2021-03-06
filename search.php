<!DOCTYPE HTML>
<?php
	session_start();

	// echo $_COOKIE["user_id_cookie"];
	$pdo = new PDO('sqlite:alldata.db');
    $query = "SELECT Name FROM UserTable WHERE Phone==";
    $query = $query."\"".$_COOKIE["user_id_cookie"]."\"";
    $sth = $pdo->query($query);
    $sth->setFetchMode(PDO::FETCH_NUM);
    $data = $sth->fetchAll();
	$member = $data[0][0];
	$register_logout_url = isset($_COOKIE["user_id_cookie"]) ? "./logout.php" : "./register.php";
	$login_profile_url = isset($_COOKIE["user_id_cookie"]) ? "./profile.php" : "./login.php";
	$cart_login_url = isset($_COOKIE["user_id_cookie"]) ? "./ShoppingCart.php" : "./login.php";

	$browse='product_browse';
	$keyword = $_GET['search'];
?>
<html>
	<head>
		<title>ShawningShop 鍵盤世界</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
		<script src="https://kit.fontawesome.com/be03ab0af6.js" crossorigin="anonymous"></script>

		<link rel="stylesheet" href="./assets/css/style.css" />
		<link rel="stylesheet" href="./assets/css/header.css" />
		<link rel="stylesheet" href="./assets/css/footer.css" />
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
						<v-form action="search.php" methods="GET" id="searchForm">
							<input type="text" placeholder="Search.." <?php if ($keyword!="") echo "value='".$keyword."'" ?> id="search" name="search"/>
							<v-btn type="submit"><v-icon>mdi-magnify</v-icon></v-btn>
						</v-form>
						<div>
							<v-chip-group style="margin-left: 60px; padding-left: 60px"
							active-class="primary--text"
							column
							>
							<v-chip class="bg-white"
								v-for="(tag, key) in tags"
								:key="tag"
								@click="fun( `${key}` )"
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
					<?php
						$pdo = new PDO('sqlite:alldata.db');
						if ($keyword != "") {
							$search_key = $keyword;
							$query =  "SELECT * FROM Products WHERE product_name LIKE '%";
							$query = $query.$search_key;
							$query = $query."%'";
						}
						else {
							$query =  "SELECT * FROM Products";
						}
						
						$sth = $pdo->query($query);
						$sth->setFetchMode(PDO::FETCH_NUM);
						$getProductData = $sth->fetchAll();
					?>
					<v-row>
						
						<?php
							$product_list=array();

							foreach ($getProductData as $i => $value) {
								array_push($product_list, $i);
								$product_id = $value[0];
								$product_name = $value[1];
								$amount = $value[3];
								$price= $value[4];
								$picture_name= $value[5];
								$picture_ref = "./product_img/".$picture_name;

								echo "
								<v-col
									cols='12' lg='4' md='6' sm='12'
								>
								<form id='search".$i."' name='search".$i."' class='search".$i."' action='./product.php' method='post'>
									<v-card outline name='product".$i."' @click='choose(".$i.")'>
										<input type='hidden' value='".$product_id."' name='productId'>
										<img src='$picture_ref' alt='$picture_name' style='height: 120px'/>
										<br>
										$product_id<br>
										$product_name<br>
										價格$price<br>
										剩餘數量$amount
										<br>
									</v-card>
								</form>
								</v-col>
								";
							};
							
						?>
						
					</v-row>
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
		allData:'',
		query:'',
		nodata:false
      }
    },
	methods: {
		choose:(i)=>{
			search='search'+i;
			document.forms[search].submit();
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



