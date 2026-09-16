<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings_view WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_checkout = $row['banner_checkout'];
}
?>

<?php
if(!isset($_SESSION['cart_p_id'])) {
    header('location: cart.php');
    exit;
}
?>

<div class="py-12 bg-white border-b">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4 uppercase tracking-tight"><?php echo LANG_VALUE_22; ?></h1>
        <div class="w-20 h-1.5 bg-brand-primary mx-auto rounded-full"></div>
    </div>
</div>

<div class="py-16 bg-gray-50 min-h-[600px]">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            
            <?php if(!isset($_SESSION['customer'])): ?>
                <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-gray-100">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa fa-user text-3xl text-gray-300"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6"><?php echo LANG_VALUE_160; ?></h2>
                    <a href="login.php" class="inline-block bg-brand-primary text-white px-8 py-4 rounded-xl font-bold hover:bg-red-600 transition-all shadow-xl shadow-red-500/20">
                        Login to Checkout
                    </a>
                </div>
            <?php else: ?>

            <div class="flex flex-wrap lg:flex-nowrap gap-12">
                <!-- Main Content -->
                <div class="w-full lg:w-2/3 space-y-12">
                    <!-- Order Items -->
                    <section>
                        <h3 class="text-2xl font-black text-gray-900 mb-6 uppercase tracking-tight"><?php echo LANG_VALUE_26; ?></h3>
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden divide-y divide-gray-100">
                            <?php
                            $total_cart_price = 0;
                            $i=0;
                            foreach($_SESSION['cart_p_id'] as $key => $value) { 
                                $i++;
                                $row_total = $_SESSION['cart_p_current_price'][$key] * $_SESSION['cart_p_qty'][$key];
                                $total_cart_price += $row_total;
                                ?>
                                <div class="p-6 flex items-center gap-6">
                                    <div class="w-16 h-16 shrink-0 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 p-2">
                                        <img src="assets/uploads/<?php echo $_SESSION['cart_p_featured_photo'][$key]; ?>" class="w-full h-full object-contain" alt="">
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900"><?php echo $_SESSION['cart_p_name'][$key]; ?></h4>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <?php if($_SESSION['cart_size_name'][$key]) echo "Size: ".$_SESSION['cart_size_name'][$key]; ?>
                                            <?php if($_SESSION['cart_color_name'][$key]) echo " | Color: ".$_SESSION['cart_color_name'][$key]; ?>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-gray-900">$<?php echo number_format($_SESSION['cart_p_current_price'][$key], 2); ?> x <?php echo $_SESSION['cart_p_qty'][$key]; ?></div>
                                        <div class="text-sm font-black text-brand-primary">$<?php echo number_format($row_total, 2); ?></div>
                                    </div>
                                </div>
                                <?php
                            }
                            // Store server-computed total in session so payment processors don't trust POST
                            $_SESSION['checkout_total'] = $total_cart_price;
                            ?>
                        </div>
                    </section>

                    <!-- Billing Details -->
                    <section>
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight"><?php echo LANG_VALUE_161; ?></h3>
                            <a href="customer-billing-shipping-update.php" class="text-brand-primary font-bold text-sm hover:underline">Edit Info</a>
                        </div>
                        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-4">
                                    <div>
                                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1"><?php echo LANG_VALUE_102; ?></span>
                                        <span class="text-gray-900 font-medium"><?php echo $_SESSION['customer']['cust_b_name']; ?></span>
                                    </div>
                                    <div>
                                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1"><?php echo LANG_VALUE_104; ?></span>
                                        <span class="text-gray-900 font-medium"><?php echo $_SESSION['customer']['cust_b_phone']; ?></span>
                                    </div>
                                    <div>
                                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Email</span>
                                        <span class="text-gray-900 font-medium"><?php echo $_SESSION['customer']['cust_email']; ?></span>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Address</span>
                                        <span class="text-gray-900 font-medium">
                                            <?php echo $_SESSION['customer']['cust_b_address']; ?><br>
                                            <?php echo $_SESSION['customer']['cust_b_city']; ?>, <?php echo $_SESSION['customer']['cust_b_state']; ?> <?php echo $_SESSION['customer']['cust_b_zip']; ?><br>
                                            <?php
                                            $statement = $pdo->prepare("SELECT country_name FROM tbl_country WHERE country_id=?");
                                            $statement->execute(array($_SESSION['customer']['cust_b_country']));
                                            echo $statement->fetchColumn();
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Sidebar Sidebar -->
                <div class="w-full lg:w-1/3">
                    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 sticky top-24">
                        <h2 class="text-2xl font-black text-gray-900 mb-8 uppercase tracking-tight"><?php echo LANG_VALUE_33; ?></h2>
                        
                        <?php
                        $checkout_access = 1;
                        if(empty($_SESSION['customer']['cust_b_name']) || empty($_SESSION['customer']['cust_b_address'])) {
                            $checkout_access = 0;
                        }
                        ?>

                        <?php if($checkout_access == 0): ?>
                            <div class="p-6 bg-red-50 rounded-2xl border-2 border-red-100 text-red-700 text-sm leading-relaxed mb-8">
                                <i class="fa fa-warning mr-2"></i>
                                Please complete your billing and shipping profile before proceeding. 
                                <a href="customer-billing-shipping-update.php" class="font-bold underline">Update Profile</a>
                            </div>
                        <?php else: ?>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide"><?php echo LANG_VALUE_34; ?> *</label>
                                    <select name="payment_method" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all font-bold" id="advFieldsStatus">
                                        <option value=""><?php echo LANG_VALUE_35; ?></option>
                                        <option value="PayPal"><?php echo LANG_VALUE_36; ?></option>
                                        <option value="Bank Deposit"><?php echo LANG_VALUE_38; ?></option>
                                    </select>
                                </div>

                                <div class="pt-6 border-t border-gray-100 space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-500 font-bold uppercase tracking-widest text-xs">Total Amount</span>
                                        <span class="text-3xl font-black text-brand-primary">$<?php echo number_format($total_cart_price, 2); ?></span>
                                    </div>

                                    <!-- PayPal Form -->
                                    <form action="<?php echo BASE_URL; ?>payment/paypal/payment_process.php" method="post" id="paypal_form" class="hidden">
                                        <input type="hidden" name="cmd" value="_xclick" />
                                        <input type="hidden" name="currency_code" value="USD" />
                                        <button type="submit" name="form1" class="w-full bg-[#0070ba] text-white py-4 rounded-xl font-bold text-lg hover:brightness-110 transition-all shadow-xl shadow-blue-500/20 flex items-center justify-center gap-3">
                                            <i class="fa fa-paypal"></i> Pay with PayPal
                                        </button>
                                    </form>

                                    <!-- Bank Form -->
                                    <form action="payment/bank/init.php" method="post" id="bank_form" class="hidden space-y-6">
                                        <input type="hidden" name="amount" value="<?php echo $total_cart_price; ?>">
                                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 text-xs text-gray-600 leading-relaxed italic">
                                            <?php
                                            $statement = $pdo->prepare("SELECT bank_detail FROM tbl_settings_view WHERE id=1");
                                            $statement->execute();
                                            echo nl2br($statement->fetchColumn());
                                            ?>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2"><?php echo LANG_VALUE_44; ?> (<?php echo LANG_VALUE_45; ?>)</label>
                                            <textarea name="transaction_info" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all text-sm" rows="4"></textarea>
                                        </div>
                                        <button type="submit" name="form3" class="w-full bg-brand-primary text-white py-4 rounded-xl font-bold text-lg uppercase tracking-widest hover:bg-red-600 transition-all shadow-xl shadow-red-500/20">
                                            Confirm Bank Transfer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mt-8 flex items-center justify-center gap-4 text-xs font-bold text-gray-400 uppercase tracking-widest">
                            <i class="fa fa-lock text-green-500 text-lg"></i>
                            Encrypted & Secure
                        </div>
                    </div>
                </div>
            </div>

            <?php endif; ?>
        </div>
    </div>
</div>



<?php require_once('footer.php'); ?>
