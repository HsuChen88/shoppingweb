<!DOCTYPE HTML>

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
						<input type="text" v-model="input" placeholder="Search.." id="search">
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
							<a href="/ShoppingCart.php">
								<v-icon class="icon">mdi-cart</v-icon>購物車
							</a>
							<a href="/register.html">
								<v-icon class="icon">mdi-account-plus</v-icon>註冊
							</a>
							<a href="/login.html">
								<v-icon class="icon">mdi-account</v-icon>登入
							</a>
						</div>
					</v-col>
				</v-row>
			</div>

			<div id="main">
				<v-carousel
					cycle
					height="400"
					hide-delimiter-background
					show-arrows= "hover"
				>
					<v-carousel-item
					v-for="(slide, i) in slides"
					:key="i"
					>
						<v-sheet
							:color="colors[i]"
							height="100%"
						>
							<div class="d-flex fill-height justify-center align-center">
								<div class="text-h2">
									{{ slide }} Slide
								</div>
							</div>
						</v-sheet>
					</v-carousel-item>
				</v-carousel>
			</div>

			<div id="footer">
				contact us:
				<input type="text" placeholder="tell us.." class="layout-input">
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
			<script src="assets/js/main.js"></script>

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
			'Work',
			'Home Improvement',
			'Vacation',
			'Food',
			'Drawers',
			'Shopping',
			'Art',
			'Tech'
		]
      }
    },
	methods: {
	}

});


</script>
		
	</body>
</html>