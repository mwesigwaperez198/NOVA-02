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

    $valid = 1;

    if(empty($_POST['cust_name'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_123."<br>";
    }

    if(empty($_POST['cust_phone'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_124."<br>";
    }

    if(empty($_POST['cust_address'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_125."<br>";
    }

    if(empty($_POST['cust_country'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_126."<br>";
    }

    if(empty($_POST['cust_city'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_127."<br>";
    }

    if(empty($_POST['cust_state'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_128."<br>";
    }

    if(empty($_POST['cust_zip'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_129."<br>";
    }

    if($valid == 1) {
        $c_name = htmlspecialchars(trim(strip_tags($_POST['cust_name'])), ENT_QUOTES, 'UTF-8');
        $c_cname = htmlspecialchars(trim(strip_tags($_POST['cust_cname'])), ENT_QUOTES, 'UTF-8');
        $c_phone = htmlspecialchars(trim(strip_tags($_POST['cust_phone'])), ENT_QUOTES, 'UTF-8');
        $c_country = intval($_POST['cust_country']);
        $c_address = htmlspecialchars(trim(strip_tags($_POST['cust_address'])), ENT_QUOTES, 'UTF-8');
        $c_city = htmlspecialchars(trim(strip_tags($_POST['cust_city'])), ENT_QUOTES, 'UTF-8');
        $c_state = htmlspecialchars(trim(strip_tags($_POST['cust_state'])), ENT_QUOTES, 'UTF-8');
        $c_zip = htmlspecialchars(trim(strip_tags($_POST['cust_zip'])), ENT_QUOTES, 'UTF-8');

        // update data into the database
        $statement = $pdo->prepare("UPDATE tbl_customer SET cust_name=?, cust_cname=?, cust_phone=?, cust_country=?, cust_address=?, cust_city=?, cust_state=?, cust_zip=? WHERE cust_id=?");
        $statement->execute(array(
            $c_name,
            $c_cname,
            $c_phone,
            $c_country,
            $c_address,
            $c_city,
            $c_state,
            $c_zip,
            $_SESSION['customer']['cust_id']
        ));  
       
        $success_message = LANG_VALUE_130;

        $_SESSION['customer']['cust_name'] = $c_name;
        $_SESSION['customer']['cust_cname'] = $c_cname;
        $_SESSION['customer']['cust_phone'] = $c_phone;
        $_SESSION['customer']['cust_country'] = $c_country;
        $_SESSION['customer']['cust_address'] = $c_address;
        $_SESSION['customer']['cust_city'] = $c_city;
        $_SESSION['customer']['cust_state'] = $c_state;
        $_SESSION['customer']['cust_zip'] = $c_zip;
    }
}
?>

<div class="py-12 bg-white border-b">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tight"><?php echo LANG_VALUE_117; ?></h1>
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
                <div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-gray-100 max-w-3xl">
                    <h2 class="text-2xl font-black text-gray-900 mb-8 uppercase tracking-tight"><?php echo LANG_VALUE_117; ?></h2>

                    <?php
                    if($error_message != '') {
                        echo "<div class='mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm font-medium rounded-r-xl'>".$error_message."</div>";
                    }
                    if($success_message != '') {
                        echo "<div class='mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm font-medium rounded-r-xl'>".$success_message."</div>";
                    }
                    ?>
                    
                    <form action="" method="post" class="space-y-6">
                        <?php $csrf->echoInputField(); ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_102; ?> *</label>
                                <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3.5 focus:border-brand-primary outline-none transition-all" name="cust_name" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_103; ?></label>
                                <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3.5 focus:border-brand-primary outline-none transition-all" name="cust_cname" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_cname'], ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_94; ?> *</label>
                                <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3.5 bg-gray-50 text-gray-500 cursor-not-allowed outline-none transition-all" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_email'], ENT_QUOTES, 'UTF-8'); ?>" disabled>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_104; ?> *</label>
                                <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3.5 focus:border-brand-primary outline-none transition-all" name="cust_phone" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_phone'], ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_105; ?> *</label>
                                <textarea name="cust_address" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3.5 focus:border-brand-primary outline-none transition-all" rows="3" required><?php echo htmlspecialchars($_SESSION['customer']['cust_address'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_106; ?> *</label>
                                <select name="cust_country" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3.5 focus:border-brand-primary outline-none transition-all bg-white" required>
                                <?php
                                $statement = $pdo->prepare("SELECT * FROM tbl_country ORDER BY country_name ASC");
                                $statement->execute();
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    ?>
                                    <option value="<?php echo $row['country_id']; ?>" <?php if($row['country_id'] == $_SESSION['customer']['cust_country']) {echo 'selected';} ?>><?php echo htmlspecialchars($row['country_name'], ENT_QUOTES, 'UTF-8'); ?></option>
                                    <?php
                                }
                                ?>
                                </select>                                    
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_107; ?> *</label>
                                <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3.5 focus:border-brand-primary outline-none transition-all" name="cust_city" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_city'], ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_108; ?> *</label>
                                <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3.5 focus:border-brand-primary outline-none transition-all" name="cust_state" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_state'], ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_109; ?> *</label>
                                <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3.5 focus:border-brand-primary outline-none transition-all" name="cust_zip" value="<?php echo htmlspecialchars($_SESSION['customer']['cust_zip'], ENT_QUOTES, 'UTF-8'); ?>" required>
                            </div>
                        </div>
                        <button type="submit" name="form1" class="bg-brand-primary text-white px-8 py-4 rounded-xl font-bold uppercase tracking-wide hover:bg-red-600 transition-all shadow-xl shadow-red-500/20">
                            <?php echo LANG_VALUE_5; ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>
