<div id="main">
	<v-card class="container">
		<form class="login" method="POST" action="condition.php">
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

