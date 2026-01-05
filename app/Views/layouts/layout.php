<!DOCTYPE HTML>
<html>
	<head>
		<title><?php echo $pageTitle ?? 'ShawningShop 鍵盤世界'; ?></title>
		<meta charset="utf-8" />
		<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
		<script src="https://kit.fontawesome.com/be03ab0af6.js" crossorigin="anonymous"></script>

		<link rel="stylesheet" href="./assets/css/style.css" />
		<link rel="stylesheet" href="./assets/css/header.css" />
		<link rel="stylesheet" href="./assets/css/footer.css" />
		<link rel="stylesheet" href="./assets/css/product-cards.css" />
		<link rel="stylesheet" href="./assets/css/cart.css" />
		<link rel="stylesheet" href="./assets/css/product-detail.css" />
		<?php if (isset($additionalCss)): ?>
			<?php foreach ($additionalCss as $css): ?>
				<link rel="stylesheet" href="<?php echo $css; ?>" />
			<?php endforeach; ?>
		<?php endif; ?>
	</head>
	<body class="is-preload <?php echo $bodyClass ?? 'homepage'; ?>">
		<v-app id="app">
			<v-main>
				<?php
				// 載入 header
				$headerPath = __DIR__ . '/header.php';
				if (file_exists($headerPath)) {
					include $headerPath;
				}
				
				// 載入主要視圖
				if (isset($contentView) && file_exists($contentView)) {
					include $contentView;
				}
				
				// 載入 footer
				$footerPath = __DIR__ . '/footer.php';
				if (file_exists($footerPath)) {
					include $footerPath;
				}
				?>
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
		<?php if (isset($additionalScripts)): ?>
			<?php foreach ($additionalScripts as $script): ?>
				<script src="<?php echo $script; ?>"></script>
			<?php endforeach; ?>
		<?php endif; ?>

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
					<?php if (isset($vueData)): ?>
						<?php foreach ($vueData as $key => $value): ?>
							<?php echo $key; ?>: <?php echo is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : json_encode($value, JSON_UNESCAPED_UNICODE); ?>,
						<?php endforeach; ?>
					<?php endif; ?>
					items: <?php echo isset($carouselItems) ? json_encode($carouselItems, JSON_UNESCAPED_UNICODE) : json_encode([
						['src' => './product_img/rog_flare2_2.jpg'],
						['src' => './product_img/razer_pro_typeultra_white_yellow_en.jpg'],
						['src' => './product_img/msi_gk71_red_2.jpg'],
						['src' => './product_img/filco_104_2.jpg']
					], JSON_UNESCAPED_UNICODE); ?>
				}
			},
			methods: {
				<?php if (isset($vueMethods)): ?>
					<?php foreach ($vueMethods as $methodName => $methodCode): ?>
						<?php echo $methodName; ?>: <?php echo $methodCode; ?>,
					<?php endforeach; ?>
				<?php endif; ?>
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
