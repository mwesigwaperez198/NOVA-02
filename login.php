<?php require_once('header.php'); ?>
<!-- fetching row banner login -->
<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings_view WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_login = $row['banner_login'];
}
?>
<!-- login form -->
<?php
if(isset($_POST['form1'])) {
    $csrf->verifyRequest();
        
    if(empty($_POST['cust_email']) || empty($_POST['cust_password'])) {
        $error_message = LANG_VALUE_132.'<br>';
    } else {
        
        $cust_email = filter_var(trim($_POST['cust_email']), FILTER_SANITIZE_EMAIL);
        $cust_password = $_POST['cust_password'];

        $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
        $statement->execute(array($cust_email));
        $total = $statement->rowCount();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if($total==0) {
            $error_message .= LANG_VALUE_133.'<br>';
        } else {
            $row = $result[0];
            $cust_status = $row['cust_status'];
            $row_password = $row['cust_password'];

            $password_valid = false;
            if (password_verify($cust_password, $row_password)) {
                $password_valid = true;
                if (password_needs_rehash($row_password, PASSWORD_BCRYPT)) {
                    $new_hash = password_hash($cust_password, PASSWORD_BCRYPT);
                    $up = $pdo->prepare("UPDATE tbl_customer SET cust_password=? WHERE cust_id=?");
                    $up->execute(array($new_hash, $row['cust_id']));
                }
            } elseif ($row_password === md5($cust_password)) {
                $password_valid = true;
                // Automatically migrate MD5 to bcrypt
                $new_hash = password_hash($cust_password, PASSWORD_BCRYPT);
                $up = $pdo->prepare("UPDATE tbl_customer SET cust_password=? WHERE cust_id=?");
                $up->execute(array($new_hash, $row['cust_id']));
            }

            if(!$password_valid) {
                $error_message .= LANG_VALUE_139.'<br>';
            } else {
                if($cust_status == 0) {
                    $error_message .= LANG_VALUE_148.'<br>';
                } else {
                    session_regenerate_id(true);
                    $_SESSION['customer'] = $row;
                    header("location: ".BASE_URL."dashboard.php");
                    exit;
                }
            }
        }
    }
}
?>

<div class="py-20 bg-gray-50 min-h-[calc(100vh-200px)] flex items-center justify-center">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gray-900 p-8 text-center">
                    <h1 class="text-3xl font-black text-white uppercase tracking-tight"><?php echo LANG_VALUE_10; ?></h1>
                    <p class="text-gray-400 mt-2">Welcome back! Please login to your account.</p>
                </div>
                
                <div class="p-8 md:p-12">
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
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_94; ?> *</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="fa fa-envelope-o"></i>
                                </span>
                                <input type="email" class="w-full border-2 border-gray-100 rounded-xl pl-12 pr-4 py-4 focus:border-brand-primary outline-none transition-all" name="cust_email" placeholder="your@email.com">
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-sm font-bold text-gray-700 uppercase tracking-wide"><?php echo LANG_VALUE_96; ?> *</label>
                                <a href="forget-password.php" class="text-xs font-bold text-brand-primary hover:underline"><?php echo LANG_VALUE_97; ?>?</a>
                            </div>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="fa fa-lock"></i>
                                </span>
                                <input type="password" class="w-full border-2 border-gray-100 rounded-xl pl-12 pr-4 py-4 focus:border-brand-primary outline-none transition-all" name="cust_password" placeholder="â€¢â€¢â€¢â€¢â€¢â€¢â€¢â€¢">
                            </div>
                        </div>

                        <button type="submit" name="form1" class="w-full bg-brand-primary text-white py-4 rounded-xl font-bold text-lg uppercase tracking-widest hover:bg-red-600 transition-all shadow-xl shadow-red-500/20">
                            <?php echo LANG_VALUE_4; ?>
                        </button>

                        <div class="pt-6 text-center text-sm font-medium text-gray-500">
                            Don't have an account? 
                            <a href="registration.php" class="text-brand-primary font-bold hover:underline">Register here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>
