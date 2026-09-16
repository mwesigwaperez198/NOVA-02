<?php require_once('header.php'); ?>

<?php
// Check if the customer is logged in or not
if(!isset($_SESSION['customer'])) {
    header('location: '.BASE_URL.'logout.php');
    exit;
} else {
    // If customer is logged in, but admin make him inactive, then force logout this user.
    $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id=? AND cust_status=?");
    $statement->execute(array($_SESSION['customer']['cust_id'],0));
    $total = $statement->rowCount();
    if($total) {
        header('location: '.BASE_URL.'logout.php');
        exit;
    }
}
?>

<?php
if (isset($_POST['form1'])) {
    $csrf->verifyRequest();

    $b_name = htmlspecialchars(trim(strip_tags($_POST['cust_b_name'])), ENT_QUOTES, 'UTF-8');
    $b_cname = htmlspecialchars(trim(strip_tags($_POST['cust_b_cname'])), ENT_QUOTES, 'UTF-8');
    $b_phone = htmlspecialchars(trim(strip_tags($_POST['cust_b_phone'])), ENT_QUOTES, 'UTF-8');
    $b_country = intval($_POST['cust_b_country']);
    $b_address = htmlspecialchars(trim(strip_tags($_POST['cust_b_address'])), ENT_QUOTES, 'UTF-8');
    $b_city = htmlspecialchars(trim(strip_tags($_POST['cust_b_city'])), ENT_QUOTES, 'UTF-8');
    $b_state = htmlspecialchars(trim(strip_tags($_POST['cust_b_state'])), ENT_QUOTES, 'UTF-8');
    $b_zip = htmlspecialchars(trim(strip_tags($_POST['cust_b_zip'])), ENT_QUOTES, 'UTF-8');

    $s_name = htmlspecialchars(trim(strip_tags($_POST['cust_s_name'])), ENT_QUOTES, 'UTF-8');
    $s_cname = htmlspecialchars(trim(strip_tags($_POST['cust_s_cname'])), ENT_QUOTES, 'UTF-8');
    $s_phone = htmlspecialchars(trim(strip_tags($_POST['cust_s_phone'])), ENT_QUOTES, 'UTF-8');
    $s_country = intval($_POST['cust_s_country']);
    $s_address = htmlspecialchars(trim(strip_tags($_POST['cust_s_address'])), ENT_QUOTES, 'UTF-8');
    $s_city = htmlspecialchars(trim(strip_tags($_POST['cust_s_city'])), ENT_QUOTES, 'UTF-8');
    $s_state = htmlspecialchars(trim(strip_tags($_POST['cust_s_state'])), ENT_QUOTES, 'UTF-8');
    $s_zip = htmlspecialchars(trim(strip_tags($_POST['cust_s_zip'])), ENT_QUOTES, 'UTF-8');

    // update data into the database
    $statement = $pdo->prepare("UPDATE tbl_customer SET 
                            cust_b_name=?, 
                            cust_b_cname=?, 
                            cust_b_phone=?, 
                            cust_b_country=?, 
                            cust_b_address=?, 
                            cust_b_city=?, 
                            cust_b_state=?, 
                            cust_b_zip=?,
                            cust_s_name=?, 
                            cust_s_cname=?, 
                            cust_s_phone=?, 
                            cust_s_country=?, 
                            cust_s_address=?, 
                            cust_s_city=?, 
                            cust_s_state=?, 
                            cust_s_zip=? 

                            WHERE cust_id=?");
    $statement->execute(array(
        $b_name, $b_cname, $b_phone, $b_country, $b_address, $b_city, $b_state, $b_zip,
        $s_name, $s_cname, $s_phone, $s_country, $s_address, $s_city, $s_state, $s_zip,
        $_SESSION['customer']['cust_id']
    ));  
   
    $success_message = LANG_VALUE_122;

    $_SESSION['customer']['cust_b_name'] = $b_name;
    $_SESSION['customer']['cust_b_cname'] = $b_cname;
    $_SESSION['customer']['cust_b_phone'] = $b_phone;
    $_SESSION['customer']['cust_b_country'] = $b_country;
    $_SESSION['customer']['cust_b_address'] = $b_address;
    $_SESSION['customer']['cust_b_city'] = $b_city;
    $_SESSION['customer']['cust_b_state'] = $b_state;
    $_SESSION['customer']['cust_b_zip'] = $b_zip;
    $_SESSION['customer']['cust_s_name'] = $s_name;
    $_SESSION['customer']['cust_s_cname'] = $s_cname;
    $_SESSION['customer']['cust_s_phone'] = $s_phone;
    $_SESSION['customer']['cust_s_country'] = $s_country;
    $_SESSION['customer']['cust_s_address'] = $s_address;
    $_SESSION['customer']['cust_s_city'] = $s_city;
    $_SESSION['customer']['cust_s_state'] = $s_state;
    $_SESSION['customer']['cust_s_zip'] = $s_zip;
}
?>

<div class="py-12 bg-white border-b">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tight"><?php echo LANG_VALUE_88; ?></h1>
    </div>
</div>

<div class="py-16 bg-gray-50 min-h-[600px]">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap lg:flex-nowrap gap-12">
            <!-- Sidebar -->
            <div class="w-full lg:w-1/4">
                <?php require_once('customer-sidebar.php'); ?>
            </div>
            
            <!-- Main Content -->
            <div class="w-full lg:w-3/4">
                <div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-gray-100">
                    <h2 class="text-2xl font-black text-gray-900 mb-8 uppercase tracking-tight"><?php echo LANG_VALUE_88; ?></h2>

                    <?php
                    if($error_message != '') {
                        echo "<div class='mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm font-medium rounded-r-xl'>".$error_message."</div>";
                    }
                    if($success_message != '') {
                        echo "<div class='mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm font-medium rounded-r-xl'>".$success_message."</div>";
                    }
                    ?>
                    
                    <form action="" method="post" class="space-y-10">
                        <?php $csrf->echoInputField(); ?>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                            <!-- Billing Address -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wide border-b pb-3 text-brand-primary">
                                    <i class="fa fa-credit-card mr-2"></i> <?php echo LANG_VALUE_86; ?>
                                </h3>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><?php echo LANG_VALUE_102; ?></label>
                                    <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" name="cust_b_name" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_b_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><?php echo LANG_VALUE_103; ?></label>
                                    <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" name="cust_b_cname" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_b_cname'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><?php echo LANG_VALUE_104; ?></label>
                                    <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" name="cust_b_phone" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_b_phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><?php echo LANG_VALUE_106; ?></label>
                                    <select name="cust_b_country" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all bg-white">
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_country ORDER BY country_name ASC");
                                        $statement->execute();
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            ?>
                                            <option value="<?php echo $row['country_id']; ?>" <?php if($row['country_id'] == ($_SESSION['customer']['cust_b_country'] ?? 0)) {echo 'selected';} ?>><?php echo htmlspecialchars($row['country_name'], ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><?php echo LANG_VALUE_105; ?></label>
                                    <textarea name="cust_b_address" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" rows="3"><?php echo htmlspecialchars($_SESSION['customer']['cust_b_address'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><?php echo LANG_VALUE_107; ?></label>
                                    <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" name="cust_b_city" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_b_city'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><?php echo LANG_VALUE_108; ?></label>
                                    <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" name="cust_b_state" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_b_state'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><?php echo LANG_VALUE_109; ?></label>
                                    <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" name="cust_b_zip" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_b_zip'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                            </div>

                            <!-- Shipping Address -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wide border-b pb-3 text-brand-primary">
                                    <i class="fa fa-truck mr-2"></i> <?php echo LANG_VALUE_87; ?>
                                </h3>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><?php echo LANG_VALUE_102; ?></label>
                                    <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" name="cust_s_name" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_s_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><?php echo LANG_VALUE_103; ?></label>
                                    <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" name="cust_s_cname" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_s_cname'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><?php echo LANG_VALUE_104; ?></label>
                                    <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" name="cust_s_phone" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_s_phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><?php echo LANG_VALUE_106; ?></label>
                                    <select name="cust_s_country" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all bg-white">
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_country ORDER BY country_name ASC");
                                        $statement->execute();
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) {
                                            ?>
                                            <option value="<?php echo $row['country_id']; ?>" <?php if($row['country_id'] == ($_SESSION['customer']['cust_s_country'] ?? 0)) {echo 'selected';} ?>><?php echo htmlspecialchars($row['country_name'], ENT_QUOTES, 'UTF-8'); ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><?php echo LANG_VALUE_105; ?></label>
                                    <textarea name="cust_s_address" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" rows="3"><?php echo htmlspecialchars($_SESSION['customer']['cust_s_address'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><?php echo LANG_VALUE_107; ?></label>
                                    <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" name="cust_s_city" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_s_city'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><?php echo LANG_VALUE_108; ?></label>
                                    <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" name="cust_s_state" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_s_state'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2"><?php echo LANG_VALUE_109; ?></label>
                                    <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" name="cust_s_zip" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_s_zip'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-gray-100">
                            <button type="submit" name="form1" class="bg-brand-primary text-white px-8 py-4 rounded-xl font-bold uppercase tracking-wide hover:bg-red-600 transition-all shadow-xl shadow-red-500/20">
                                <?php echo LANG_VALUE_5; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>
