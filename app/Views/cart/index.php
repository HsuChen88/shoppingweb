<div id="main">
	<div id="main-wrapper">
		<div class="cart-container">
			<v-row class="cart-header d-none d-sm-flex">
				<v-col cols="12" sm="4">圖片</v-col>
				<v-col cols="12" sm="2">商品名稱</v-col>
				<v-col cols="12" sm="2">單價</v-col>
				<v-col cols="12" sm="2">數量</v-col>
				<v-col cols="12" sm="2">操作</v-col>
			</v-row>
			<?php if ($isEmpty): ?>
				<v-row>
					<v-col cols="12">
						<p class="empty-cart-message">購物車是空的</p>
					</v-col>
				</v-row>
			<?php else: ?>
				<?php foreach ($cartItems as $item): ?>
					<v-row class="cart-item">
						<v-col cols="12" sm="4" class="cart-item-image">
							<?php 
							$item_picture = $item['picture_name'] ?? $item['image'] ?? '';
							$item_picture_src = !empty($item_picture) ? "./product_img/" . $item_picture : "./product_img/placeholder.jpg";
							?>
							<img src="<?php echo htmlspecialchars($item_picture_src); ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="cart-product-image">
						</v-col>
						<v-col cols="12" sm="2" class="cart-item-name">
							<div class="cart-label d-sm-none">商品名稱：</div>
							<?php echo htmlspecialchars($item['product_name']); ?>
						</v-col>
						<v-col cols="12" sm="2" class="cart-item-price">
							<div class="cart-label d-sm-none">單價：</div>
							<?php echo htmlspecialchars($item['price']); ?>
						</v-col>
						<v-col cols="12" sm="2" class="cart-item-amount">
							<div class="cart-label d-sm-none">數量：</div>
							<?php echo htmlspecialchars($item['amount']); ?>
						</v-col>
						<v-col cols="12" sm="2" class="cart-item-action">
							<v-btn class="btn cart-delete-btn" color="#fb5552" @click="delBtnFunc(<?php echo $item['product_id']; ?>)">刪除</v-btn>
						</v-col>
					</v-row>
					<v-form id="func<?php echo $item['product_id']; ?>" name="func<?php echo $item['product_id']; ?>" class="func<?php echo $item['product_id']; ?>" action="./ShoppingCart.php" method="POST">
						<input type="hidden" value="<?php echo $item['product_id']; ?>" name="productID">
					</v-form>
				<?php endforeach; ?>
			<?php endif; ?>

			<v-row class="cart-footer">
				<v-col cols="12" sm="6" class="cart-total">
					<div class="total-label">總金額：</div>
					<div class="total-amount"><strong><?php echo htmlspecialchars($total); ?></strong></div>
				</v-col>
				<v-col cols="12" sm="6" class="cart-checkout">
					<v-btn class="ma-2 white--text checkout-btn" color="rgb(16, 111, 179)" x-large id="checkoutBtn" onclick="location.href='checkout.php';" <?php if ($isEmpty) echo "disabled"; ?>>結帳</v-btn>
				</v-col>
			</v-row>
		</div>
	</div>
</div>

