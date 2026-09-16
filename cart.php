<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings_view WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_cart = $row['banner_cart'];
}
?>

<?php
$error_message = '';
if(isset($_POST['form1'])) {

    $i = 0;
    $statement = $pdo->prepare("SELECT * FROM tbl_product");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $i++;
        $table_product_id[$i] = $row['p_id'];
        $table_quantity[$i] = $row['p_qty'];
    }

    $i=0;
    foreach($_POST['product_id'] as $val) {
        $i++;
        $arr1[$i] = $val;
    }
    $i=0;
    foreach($_POST['quantity'] as $val) {
        $i++;
        $arr2[$i] = $val;
    }
    $i=0;
    foreach($_POST['product_name'] as $val) {
        $i++;
        $arr3[$i] = $val;
    }
    
    $allow_update = 1;
    for($i=1;$i<=count($arr1);$i++) {
        for($j=1;$j<=count($table_product_id);$j++) {
            if($arr1[$i] == $table_product_id[$j]) {
                $temp_index = $j;
                break;
            }
        }
        if($table_quantity[$temp_index] < $arr2[$i]) {
        	$allow_update = 0;
            $error_message .= '"'.$arr2[$i].'" items are not available for "'.$arr3[$i].'"\n';
        } else {
            $_SESSION['cart_p_qty'][$i] = $arr2[$i];
        }
    }
    $error_message .= '\nOther items quantity are updated successfully!';
    ?>
    
    <?php if($allow_update == 0): ?>
    	<script>alert('<?php echo $error_message; ?>');</script>
	<?php else: ?>
		<script>alert('All Items Quantity Update is Successful!');</script>
	<?php endif; ?>
    <?php

}
?>

<div class="py-12 bg-white border-b">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4 uppercase tracking-tight"><?php echo LANG_VALUE_18; ?></h1>
        <div class="w-20 h-1.5 bg-brand-primary mx-auto rounded-full"></div>
    </div>
</div>

<div class="py-16 bg-gray-50 min-h-[600px]">
	<div class="container mx-auto px-4">
		<div class="max-w-6xl mx-auto">

            <?php if(!isset($_SESSION['cart_p_id'])): ?>
                <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-gray-100">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300">
                        <i class="fa fa-shopping-cart text-5xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Your cart is empty!</h2>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">Looks like you haven't added anything to your cart yet. Explore our products and find something you love.</p>
                    <a href="index.php" class="inline-block bg-brand-primary text-white px-8 py-4 rounded-xl font-bold hover:bg-red-600 transition-all shadow-xl shadow-red-500/20">
                        Start Shopping
                    </a>
                </div>
            <?php else: ?>
            <form action="" method="post" class="flex flex-wrap lg:flex-nowrap gap-8">
                <?php $csrf->echoInputField(); ?>
                
                <!-- Cart Items -->
				<div class="w-full lg:w-2/3">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="hidden md:grid grid-cols-6 gap-4 p-6 bg-gray-50 border-b text-xs font-bold uppercase tracking-wider text-gray-500">
                            <div class="col-span-3">Product</div>
                            <div class="text-center">Price</div>
                            <div class="text-center">Quantity</div>
                            <div class="text-right">Action</div>
                        </div>

                        <div class="divide-y divide-gray-100">
                            <?php
                            $i=0;
                            foreach($_SESSION['cart_p_id'] as $key => $value) { $i++; $arr_cart_p_id[$i] = $value; }
                            $i=0;
                            foreach($_SESSION['cart_size_name'] as $key => $value) { $i++; $arr_cart_size_name[$i] = $value; }
                            $i=0;
                            foreach($_SESSION['cart_color_name'] as $key => $value) { $i++; $arr_cart_color_name[$i] = $value; }
                            $i=0;
                            foreach($_SESSION['cart_p_qty'] as $key => $value) { $i++; $arr_cart_p_qty[$i] = $value; }
                            $i=0;
                            foreach($_SESSION['cart_p_name'] as $key => $value) { $i++; $arr_cart_p_name[$i] = $value; }
                            $i=0;
                            foreach($_SESSION['cart_p_featured_photo'] as $key => $value) { $i++; $arr_cart_p_featured_photo[$i] = $value; }
                            $i=0;
                            foreach($_SESSION['cart_p_current_price'] as $key => $value) { $i++; $arr_cart_p_current_price[$i] = $value; }
                            $i=0;
                            foreach($_SESSION['cart_size_id'] as $key => $value) { $i++; $arr_cart_size_id[$i] = $value; }
                            $i=0;
                            foreach($_SESSION['cart_color_id'] as $key => $value) { $i++; $arr_cart_color_id[$i] = $value; }

                            $total_cart_price = 0;
                            ?>
                            <?php for($i=1;$i<=count($arr_cart_p_id);$i++): 
                                $row_total = $arr_cart_p_current_price[$i] * $arr_cart_p_qty[$i];
                                $total_cart_price += $row_total;
                            ?>
                            <div class="p-6 grid grid-cols-1 md:grid-cols-6 gap-6 items-center hover:bg-gray-50/50 transition-colors relative">
                                <div class="col-span-3 flex items-center gap-6">
                                    <div class="w-20 h-20 shrink-0 bg-gray-100 rounded-2xl overflow-hidden border border-gray-100">
                                        <img src="assets/uploads/<?php echo $arr_cart_p_featured_photo[$i]; ?>" class="w-full h-full object-contain p-2" alt="">
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900 mb-1 hover:text-brand-primary transition-colors line-clamp-1">
                                            <a href="product.php?id=<?php echo $arr_cart_p_id[$i]; ?>"><?php echo $arr_cart_p_name[$i]; ?></a>
                                        </h3>
                                        <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs font-medium">
                                            <?php if($arr_cart_size_name[$i]): ?>
                                                <span class="text-gray-500">Size: <span class="text-gray-900"><?php echo $arr_cart_size_name[$i]; ?></span></span>
                                            <?php endif; ?>
                                            <?php if($arr_cart_color_name[$i]): ?>
                                                <span class="text-gray-500">Color: <span class="text-gray-900"><?php echo $arr_cart_color_name[$i]; ?></span></span>
                                            <?php endif; ?>
                                        </div>
                                        <!-- Mobile Price -->
                                        <div class="md:hidden mt-2 font-black text-brand-primary">
                                            $<?php echo number_format($arr_cart_p_current_price[$i], 2); ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="hidden md:block text-center font-bold text-gray-900">
                                    $<?php echo number_format($arr_cart_p_current_price[$i], 2); ?>
                                </div>

                                <div class="flex justify-start md:justify-center items-center gap-4">
                                    <span class="md:hidden text-xs font-bold text-gray-400 uppercase tracking-widest">Qty:</span>
                                    <input type="hidden" name="product_id[]" value="<?php echo $arr_cart_p_id[$i]; ?>">
                                    <input type="hidden" name="product_name[]" value="<?php echo $arr_cart_p_name[$i]; ?>">
                                    <input type="number" class="w-20 border-2 border-gray-100 rounded-xl px-3 py-2 text-center focus:border-brand-primary outline-none transition-all font-bold" step="1" min="1" name="quantity[]" value="<?php echo $arr_cart_p_qty[$i]; ?>">
                                </div>

                                <div class="text-right absolute top-6 right-6 md:static">
                                    <a onclick="return confirmDelete();" href="cart-item-delete.php?id=<?php echo $arr_cart_p_id[$i]; ?>&size=<?php echo $arr_cart_size_id[$i]; ?>&color=<?php echo $arr_cart_color_id[$i]; ?>" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    
                    <div class="mt-8 flex flex-wrap gap-4">
                        <button type="submit" name="form1" class="bg-gray-900 text-white px-8 py-4 rounded-xl font-bold hover:bg-gray-800 transition-all flex items-center gap-2">
                            <i class="fa fa-refresh"></i>
                            Update Cart
                        </button>
                        <a href="index.php" class="bg-white text-gray-900 border-2 border-gray-100 px-8 py-4 rounded-xl font-bold hover:bg-gray-50 transition-all flex items-center gap-2">
                            <i class="fa fa-arrow-left"></i>
                            Continue Shopping
                        </a>
                    </div>
                </div>

                <!-- Summary Sidebar -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 sticky top-24">
                        <h2 class="text-2xl font-black text-gray-900 mb-8 uppercase tracking-tight">Order Summary</h2>
                        
                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-gray-500">
                                <span>Subtotal</span>
                                <span class="font-bold text-gray-900">$<?php echo number_format($total_cart_price, 2); ?></span>
                            </div>
                            <div class="flex justify-between text-gray-500">
                                <span>Shipping</span>
                                <span class="text-xs font-bold uppercase text-green-500">Calculated at checkout</span>
                            </div>
                            <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">Total</span>
                                <span class="text-3xl font-black text-brand-primary">$<?php echo number_format($total_cart_price, 2); ?></span>
                            </div>
                        </div>

                        <a href="checkout.php" class="block w-full bg-brand-primary text-white text-center py-5 rounded-2xl font-black text-lg uppercase tracking-widest hover:bg-red-600 transition-all shadow-xl shadow-red-500/20 mb-4">
                            Proceed to Checkout
                        </a>
                        
                        <div class="flex items-center justify-center gap-4 text-xs font-bold text-gray-400 uppercase tracking-widest">
                            <i class="fa fa-shield text-green-500"></i>
                            Secure Checkout
                        </div>
                    </div>
                </div>
            </form>
            <?php endif; ?>
		</div>
	</div>
</div>

                

			</div>
		</div>
	</div>
</div>


<?php require_once('footer.php'); ?>
