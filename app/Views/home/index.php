<div id="main">
	<v-carousel 
		cycle
		height="500"
		show-arrows="hover"
	>
		<v-carousel-item
			v-for="(item,i) in items"
			:key="i"
			:src="item.src"
		>
		</v-carousel-item>
	</v-carousel>

	<v-row id="display-product">
		<?php
		$product_list = [];
		foreach ($products as $i => $product) {
			array_push($product_list, $i);
		$product_id = $product['id'];
		$product_name = $product['product_name'];
		$amount = $product['amount'];
		$price = $product['price'];
		// 支援兩種欄位名稱：picture_name 或 image
		$picture_name = $product['picture_name'] ?? $product['image'] ?? '';
		$picture_ref = !empty($picture_name) ? "./product_img/" . $picture_name : "./product_img/placeholder.jpg";
			?>
			<v-col cols="12" lg="4" md="6" sm="12">
				<v-card outline name="product<?php echo $i; ?>" @click="choose(<?php echo $i; ?>)" class="product-card">
					<a href="./product.php?productId=<?php echo $product_id; ?>" class="product-card-link">
						<div class="product-image-wrapper">
							<img src="<?php echo $picture_ref; ?>" alt="<?php echo htmlspecialchars($picture_name); ?>" class="product-image"/>
						</div>
						<v-card-text class="product-card-content">
							<div class="product-id"><?php echo htmlspecialchars($product_id); ?></div>
							<div class="product-name"><?php echo htmlspecialchars($product_name); ?></div>
							<div class="product-price">價格 <strong><?php echo htmlspecialchars($price); ?></strong></div>
							<div class="product-stock">剩餘數量 <span class="stock-amount"><?php echo htmlspecialchars($amount); ?></span></div>
						</v-card-text>
					</a>
				</v-card>
			</v-col>
		<?php } ?>
	</v-row>
</div>

