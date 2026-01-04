<?php

require_once __DIR__ . '/../vendor/autoload.php';

// TODO: 這個檔案尚未遷移到 MVC 架構，暫時保留舊的實作
session_start();

use App\Services\Database;
use App\Models\User;

$db = Database::getInstance()->getConnection();
$userModel = new User();

if (isset($_COOKIE["user_id_cookie"])) {
    $user = $userModel->findByPhone($_COOKIE["user_id_cookie"]);
    if ($user) {
        $user_id = $user['id'];
        $user_phone = $user['Phone'];
        $user_name = $user['Name'];
    } else {
        header("Location: ./login.php");
        exit;
    }
} else {
    header("Location: ./login.php");
    exit;
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>會員資料</title>
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
						<form action="search.php" methods="POST">
							<input type="text" placeholder="Search.." id="search" name="search"/>
							<v-btn type="submit"><v-icon>mdi-magnify</v-icon></v-btn>
						</form>
					</v-col>
				</v-row>
			</div>


			<div id="main">
			    <div id="main-wrapper">
							<v-table class="datatable" style="border: 1px black;">
								<v-row style="background-color: rgb(16, 111, 179);">
									<v-col cols='5' sm='12' style="color: #FFFFFF; font-size: 180%;">會員資料</v-col>
								</v-row>
								<v-row>
									<v-col cols='5' sm='3' style='padding: 20px; font-size: 150%'>會員名稱</v-col>
                                    <?php echo "<v-col cols='5' sm='6' style='padding: 20px; font-size: 150%'>".htmlspecialchars($user_name)."</v-col>"; ?>
								</v-row>
								<v-row>
									<v-col cols='5' sm='3' style='padding: 20px; font-size: 150%'>會員電話</v-col>
                                    <?php echo "<v-col cols='5' sm='6' style='padding: 20px; font-size: 150%'>".htmlspecialchars($user_phone)."</v-col>"; ?>
								</v-row>
							</v-table>
				</div>
			</div>
			<div id="footer">
			<div class="information">
					<h3>鄭旭辰</h3>
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
					<h3>楊東倫</h3>
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
					]
				}
				},
				methods: {

                }
			});


			</script>

	</body>
</html>

