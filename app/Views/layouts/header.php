<div id="header">
	<v-row>
		<v-col cols="12" lg="3" md="3" sm="12">
			<a id="logo" href="./">
				<h2>Shawning Shop</h2>
			</a>
		</v-col>
		<v-col cols="12" lg="6" md="6" sm="12">
			<v-form action="search.php" methods="GET" id="searchForm">
				<input type="text" placeholder="Search.." <?php if (isset($keyword) && $keyword != "") echo "value='".htmlspecialchars($keyword, ENT_QUOTES)."'"; ?> id="search" name="search"/>
				<v-btn type="submit"><v-icon>mdi-magnify</v-icon></v-btn>
			</v-form>
			<v-chip-group 
				active-class="primary--text"
				column
				class="tag-chip-group"
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
			<div id="nav">
				<a href="<?php echo $cart_login_url ?? './login.php'; ?>">
					<v-icon class="icon">mdi-cart</v-icon>購物車
				</a>
				<a href="<?php echo $register_logout_url ?? './register.php'; ?>">
					<v-icon class="icon">mdi-account-plus</v-icon><?php echo isset($_COOKIE["user_id_cookie"]) ? "登出" : "註冊"; ?>
				</a>
				<a href="<?php echo $login_profile_url ?? './login.php'; ?>">
					<v-icon class="icon">mdi-account</v-icon><?php echo isset($_COOKIE["user_id_cookie"]) && isset($member) ? "歡迎".htmlspecialchars($member) : "登入"; ?>
				</a>
			</div>
		</v-col>
	</v-row>
</div>

