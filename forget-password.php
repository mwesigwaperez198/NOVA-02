<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings_view WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_forget_password = $row['banner_forget_password'];
}
?>

<?php
if(isset($_POST['form1'])) {
    $csrf->verifyRequest();

    $valid = 1;
        
    if(empty($_POST['cust_email'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_131."<br>";
    } else {
        if (filter_var($_POST['cust_email'], FILTER_VALIDATE_EMAIL) === false) {
            $valid = 0;
            $error_message .= LANG_VALUE_134."<br>";
        } else {
            $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
            $statement->execute(array(trim($_POST['cust_email'])));
            $total = $statement->rowCount();                        
            if(!$total) {
                $valid = 0;
                $error_message .= LANG_VALUE_135."<br>";
            }
        }
    }

    if($valid == 1) {

        $statement = $pdo->prepare("SELECT * FROM tbl_settings_view WHERE id=1");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                           
        foreach ($result as $row) {
            $forget_password_message = $row['forget_password_message'];
        }

        $token = bin2hex(random_bytes(32));
        $now = time();

        $statement = $pdo->prepare("UPDATE tbl_customer SET cust_token=?,cust_timestamp=? WHERE cust_email=?");
        $statement->execute(array($token,$now,strip_tags($_POST['cust_email'])));
        
        $reset_link = BASE_URL.'reset-password.php?email='.urlencode($_POST['cust_email']).'&token='.$token;
        $message = '<p>'.LANG_VALUE_142.'<br><br><a href="'.$reset_link.'">'.$reset_link.'</a></p>';
        
        $to      = $_POST['cust_email'];
        $subject = LANG_VALUE_143;
        $headers = "From: noreply@" . parse_url(BASE_URL, PHP_URL_HOST) . "\r\n" .
                   "Reply-To: noreply@" . parse_url(BASE_URL, PHP_URL_HOST) . "\r\n" .
                   "X-Mailer: PHP/" . phpversion() . "\r\n" . 
                   "MIME-Version: 1.0\r\n" . 
                   "Content-Type: text/html; charset=UTF-8\r\n";

        @mail($to, $subject, $message, $headers);

        $success_message = $forget_password_message;
    }
}
?>

<div class="py-20 bg-gray-50 min-h-[calc(100vh-200px)] flex items-center justify-center">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gray-900 p-8 text-center">
                    <h1 class="text-2xl font-black text-white uppercase tracking-tight"><?php echo LANG_VALUE_97; ?></h1>
                    <p class="text-gray-400 mt-2 text-sm">Enter your registered email to reset your password.</p>
                </div>

                <div class="p-8 md:p-10">
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
                            <input type="email" class="w-full border-2 border-gray-100 rounded-xl px-4 py-4 focus:border-brand-primary outline-none transition-all" name="cust_email" placeholder="your@email.com" required>
                        </div>
                        <button type="submit" name="form1" class="w-full bg-brand-primary text-white py-4 rounded-xl font-bold uppercase tracking-wide hover:bg-red-600 transition-all shadow-xl shadow-red-500/20">
                            <?php echo LANG_VALUE_4; ?>
                        </button>
                        <div class="text-center pt-2">
                            <a href="login.php" class="text-sm font-bold text-gray-500 hover:text-brand-primary transition-colors"><?php echo LANG_VALUE_12; ?></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
