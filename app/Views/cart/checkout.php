<!DOCTYPE HTML>
<html>
	<head>
		<title>訂單已完成</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" href="./assets/css/checkout.css" />
	</head>
	<body>
		<div id="checkout" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); text-align:center">
			<img src="./images/checkedout.jpg" alt="這是一隻郵差鳥" class="center" width="350" height="350">
			<h1>商品已經在路上囉~</h1>
			<button id="backBtn">回首頁</button>
		</div>
	</body>
</html>

<script language="javascript">
	const backBtn = document.getElementById('backBtn')
	backBtn.addEventListener('click', function () {
		location.href='index.php';
	});
</script>

