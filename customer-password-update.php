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

    if(empty($_POST['cust_current_password'])) {
        $valid = 0;
        $error_message .= "Current Password can not be empty.<br>";
    } else {
        // Fetch current password from database
        $statement = $pdo->prepare("SELECT cust_password FROM tbl_customer WHERE cust_id=?");
        $statement->execute(array($_SESSION['customer']['cust_id']));
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        $current_hash = $row['cust_password'];

        $current_valid = false;
        if (password_verify($_POST['cust_current_password'], $current_hash) || $current_hash === md5($_POST['cust_current_password'])) {
            $current_valid = true;
        }

        if(!$current_valid) {
            $valid = 0;
            $error_message .= "Current Password does not match.<br>";
        }
    }

    if( empty($_POST['cust_password']) || empty($_POST['cust_re_password']) ) {
        $valid = 0;
        $error_message .= LANG_VALUE_138."<br>";
    }

    if( !empty($_POST['cust_password']) && !empty($_POST['cust_re_password']) ) {
        if(strlen($_POST['cust_password']) < 6) {
            $valid = 0;
            $error_message .= "New password must be at least 6 characters long.<br>";
        }
        if($_POST['cust_password'] != $_POST['cust_re_password']) {
            $valid = 0;
            $error_message .= LANG_VALUE_139."<br>";
        }
    }
    
    if($valid == 1) {
        $hashed_password = password_hash($_POST['cust_password'], PASSWORD_BCRYPT);
        
        $statement = $pdo->prepare("UPDATE tbl_customer SET cust_password=? WHERE cust_id=?");
        $statement->execute(array($hashed_password, $_SESSION['customer']['cust_id']));
        
        $_SESSION['customer']['cust_password'] = $hashed_password;        
        $success_message = LANG_VALUE_141;
    }
}
?>

<div class="py-12 bg-white border-b">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tight"><?php echo LANG_VALUE_99; ?></h1>
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
                <div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-gray-100 max-w-2xl">
                    <h2 class="text-2xl font-black text-gray-900 mb-6 uppercase tracking-tight"><?php echo LANG_VALUE_99; ?></h2>
                    
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
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Current Password *</label>
                            <input type="password" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3.5 focus:border-brand-primary outline-none transition-all" name="cust_current_password" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_100; ?> *</label>
                            <input type="password" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3.5 focus:border-brand-primary outline-none transition-all" name="cust_password" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_101; ?> *</label>
                            <input type="password" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3.5 focus:border-brand-primary outline-none transition-all" name="cust_re_password" required>
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