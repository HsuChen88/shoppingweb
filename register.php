<!DOCTYPE HTML>
<?php
	session_start();

	$pdo = new PDO('sqlite:alldata.db');
    $query = "SELECT Name FROM UserTable WHERE Phone==";
    $query = $query."\"".$_COOKIE["user_id_cookie"]."\"";
    $sth = $pdo->query($query);
    $sth->setFetchMode(PDO::FETCH_NUM);
    $data = $sth->fetchAll();
	$member = $data[0][0];
	$register_logout_url = isset($_COOKIE["user_id_cookie"]) ? "/logout.php" : "/register.php";
	$login_profile_url = isset($_COOKIE["user_id_cookie"]) ? "/profile.php" : "/login.php";
	$cart_login_url = isset($_COOKIE["user_id_cookie"]) ? "/ShoppingCart.php" : "/login.php";
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
		
		<link rel="stylesheet" href="./assets/css/style.css" />
		<link rel="stylesheet" href="./assets/css/header.css" />
		<link rel="stylesheet" href="./assets/css/footer.css" />
		<link rel="stylesheet" href="./assets/css/login.css" />
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
					<v-col cols="12" lg="6" md="6" sm="12">
						<form action="search.php" methods="POST">
							<input type="text" placeholder="Search.." id="search"/>
							<v-btn type="submit"><v-icon>mdi-magnify</v-icon></v-btn>
						</form>
						<div>
							<v-chip-group
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
							{{input}}
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
				<v-card class="container">
                    <form class="login" method="POST" action="adduser.php">
                        <h1>加入會員</h1><br>
                        <h2>使用者名稱</h2>
                        <input type="text" id="userdata" name="name" placeholder="user"/><br>
                        <h2>手機號碼</h2>
                        <input type="text" id="userdata" name="phone"/><br>
                        <h2>輸入密碼<span>(至少8個字)</span></h2>
                        <input type="password" id="userdata" name="password"/><br>
                        <h2>再次輸入密碼</h2>
                        <input type="password" id="userdata" name="confirmPassword"/><br><br><br>
						<button type="submit" class="add" id="addBtn" name="addBtn">註冊</button>
                        <p>已經註冊過了嗎<a href="login.php">登入</a></p>
                    </form>
				</v-card>
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
						<a href="https://www.facebook.com/profile.php?id=100023998800521" style="text-decoration: none" target="_blank">
						<v-icon size="40px">
							mdi-facebook
						</v-icon>
						</a>
					</v-btn>
					<v-btn class="mx-4 white--text" icon>
						<a href="https://instagram.com/lun__0821?igshid=YmMyMTA2M2Y=" style="text-decoration: none" target="_blank">
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
<script src="https://unpkg.com/vue-router@2.0.0/dist/vue-router.js"></script>
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
		]
      }
    },
	methods: {
	}

});


</script>
		
	</body>
</html>
