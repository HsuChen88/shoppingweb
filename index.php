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
	$register_logout_url = isset($_COOKIE["user_id_cookie"]) ? "/logout.php" : "/register.html";
	$login_profile_url = isset($_COOKIE["user_id_cookie"]) ? "/profile.php" : "/login.html";
?>
<html>
	<head>
		<title>楊東翰</title>
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
	<body class="is-preload homepage">
		<v-app id="app">
			<v-main>
			<div id="header">
				<v-row>
					<v-col cols="12" lg="3" md="3" sm="12">
						<a id="logo" href="/">
							<h2>Shawning Shop</h2>
						</a>
					</v-col>
					<v-col cols="12" lg="6" md="6" sm="12" >
						<form action="search.php" methods="POST">
							<input type="text" placeholder="Search.." id="search" name="search"/>
							<v-btn type="submit"><v-icon>mdi-magnify</v-icon></v-btn>
						</form>
						<v-chip-group style="margin-left: 60px; padding-left: 60px"
							active-class="primmary--text"
							column
						>
							<v-chip 
								v-for="tag in tags"
								:key="tag"
							>
								{{ tag }}
							</v-chip>
						</v-chip-group>
					</v-col>
					<v-col cols="12" lg="3" md="3" sm="12">
						<div id="nav" >
							<a href="/ShoppingCart.php">
								<v-icon class="icon">mdi-cart</v-icon>購物車
							</a>
							<a href=<?php echo $register_logout_url ?>>
								<v-icon class="icon">mdi-account-plus</v-icon><?php echo isset($_COOKIE["user_id_cookie"]) ? "登出" : "註冊" ?>
							</a>
							<a href="/loginnew.php">
								<v-icon class="icon">mdi-account</v-icon><?php echo isset($_COOKIE["user_id_cookie"]) ? $member : "登入" ?>
							</a>
						</div>
					</v-col>
				</v-row>
			</div>

			<div id="main">
				<v-carousel
					cycle
					height="500"
					show-arrows= "hover"
				>
					<v-carousel-item
					v-for="(item,i) in items"
					:key="i"
					:src="item.src"
					>
					</v-carousel-item>
				</v-carousel>
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

			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>

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
			'無線',
			'RGB',
			'80 %',
			'65 %',
			'PBT',
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
	}

});


</script>
		
	</body>
</html>
