<div id="main">
	<v-container class="product-detail-container">
		<v-row>
			<v-col cols="12" lg="6" md="6" sm="12" class="product-image-col">
				<v-carousel class="product-carousel">
					<v-carousel-item
						reverse-transition="fade-transition"
						transition="fade-transition"
					>
						<img src="./product_img/<?php echo htmlspecialchars($productImage); ?>" alt="product" class="product-detail-image"/>
					</v-carousel-item>
				</v-carousel>
			</v-col>
			<v-col cols="12" lg="6" md="6" sm="12" class="product-info-col">
				<h1 class="product-detail-name"><?php echo htmlspecialchars($productName); ?></h1>
				<div class="product-tags">
					<h4 class="tags-label">標籤：</h4>
					<div class="tags-container">
						<?php
						foreach ($categoryArray as $i => $cat) {
							echo "<v-chip class='product-tag-chip'>";
							echo htmlspecialchars($cat);
							echo "</v-chip>";
						}
						unset($cat);
						?>
					</div>
				</div>
				<v-card elevation="0" class="product-price-card">
					<h1 class="product-price-text">$<?php echo htmlspecialchars($productPrice); ?></h1>
				</v-card>
				<h4 class="product-stock-info">剩餘數量：<span class="stock-number"><?php echo htmlspecialchars($productAmount); ?></span></h4>
				<div class="product-description">
					<h4 class="description-title">產品介紹：</h4>
					<div class="description-content">
						<?php echo $productDescription; ?>
					</div>
				</div>
			</v-col>
		</v-row>
	</v-container>
</div>

<div id="sticky" class="sticky-cart-bar">
	<div class="sticky-content-wrapper">
		<div class="sticky-product-name">
			<h4><?php echo htmlspecialchars($productName); ?></h4>
		</div>
		<form action="./product.php" method="post" class="sticky-form">
			<input type="hidden" value="<?php echo htmlspecialchars($userId ?? ''); ?>" name="userId">
			<input type="hidden" value="<?php echo htmlspecialchars($productId); ?>" name="productId">
			<v-row class="sticky-form-row">
				<v-col cols="12" lg="4" md="4" sm="12" class="sticky-price-col">
					<label class="sticky-label">價格：</label>
					<input type="text" value="<?php echo htmlspecialchars($productPrice); ?>" disabled="disabled" name="productPrice" class="sticky-input sticky-price-input">
				</v-col>
				<v-col cols="12" lg="4" md="4" sm="12" class="sticky-amount-col">
					<label class="sticky-label">數量：</label>
					<input type="number" min="1" max="<?php echo htmlspecialchars($productAmount); ?>" value="1" name="addAmount" class="sticky-input sticky-amount-input">
				</v-col>
				<v-col cols="12" lg="4" md="4" sm="12" class="sticky-button-col">
					<v-btn type="submit" id="addCart" color="red" class="sticky-add-cart-btn" @click="location.href='product.php';">加入購物車</v-btn>
				</v-col>
			</v-row>
		</form>
	</div>
</div>

