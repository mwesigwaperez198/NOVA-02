<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_registration = $row['banner_registration'];
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

    if(empty($_POST['cust_email'])) {
        $valid = 0;
        $error_message .= LANG_VALUE_131."<br>";
    } else {
        if (filter_var($_POST['cust_email'], FILTER_VALIDATE_EMAIL) === false) {
            $valid = 0;
            $error_message .= LANG_VALUE_134."<br>";
        } else {
            $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
            $statement->execute(array($_POST['cust_email']));
            $total = $statement->rowCount();                            
            if($total) {
                $valid = 0;
                $error_message .= LANG_VALUE_147."<br>";
            }
        }
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

    if( empty($_POST['cust_password']) || empty($_POST['cust_re_password']) ) {
        $valid = 0;
        $error_message .= LANG_VALUE_138."<br>";
    }

    if( !empty($_POST['cust_password']) && !empty($_POST['cust_re_password']) ) {
        if(strlen($_POST['cust_password']) < 6) {
            $valid = 0;
            $error_message .= "Password must be at least 6 characters long.<br>";
        }
        if($_POST['cust_password'] != $_POST['cust_re_password']) {
            $valid = 0;
            $error_message .= LANG_VALUE_139."<br>";
        }
    }

    if($valid == 1) {

        $token = bin2hex(random_bytes(32));
        $cust_datetime = date('Y-m-d H:i:s');
        $cust_timestamp = time();

        // Hash password securely with bcrypt
        $hashed_password = password_hash($_POST['cust_password'], PASSWORD_BCRYPT);

        // saving into the database
        $statement = $pdo->prepare("INSERT INTO tbl_customer (
                                        cust_name,
                                        cust_cname,
                                        cust_email,
                                        cust_phone,
                                        cust_country,
                                        cust_address,
                                        cust_city,
                                        cust_state,
                                        cust_zip,
                                        cust_b_name,
                                        cust_b_cname,
                                        cust_b_phone,
                                        cust_b_country,
                                        cust_b_address,
                                        cust_b_city,
                                        cust_b_state,
                                        cust_b_zip,
                                        cust_s_name,
                                        cust_s_cname,
                                        cust_s_phone,
                                        cust_s_country,
                                        cust_s_address,
                                        cust_s_city,
                                        cust_s_state,
                                        cust_s_zip,
                                        cust_password,
                                        cust_token,
                                        cust_datetime,
                                        cust_timestamp,
                                        cust_status
                                    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $statement->execute(array(
                                        strip_tags($_POST['cust_name']),
                                        strip_tags($_POST['cust_cname']),
                                        strip_tags($_POST['cust_email']),
                                        strip_tags($_POST['cust_phone']),
                                        strip_tags($_POST['cust_country']),
                                        strip_tags($_POST['cust_address']),
                                        strip_tags($_POST['cust_city']),
                                        strip_tags($_POST['cust_state']),
                                        strip_tags($_POST['cust_zip']),
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        '',
                                        $hashed_password,
                                        $token,
                                        $cust_datetime,
                                        $cust_timestamp,
                                        0
                                    ));

        // Send email for confirmation of the account
        $to = $_POST['cust_email'];
        
        $subject = LANG_VALUE_150;
        $verify_link = BASE_URL.'verify.php?email='.$to.'&token='.$token;
        $message = '
'.LANG_VALUE_151.'<br><br>

<a href="'.$verify_link.'">'.$verify_link.'</a>';

        $headers = "From: noreply@" . BASE_URL . "\r\n" .
                   "Reply-To: noreply@" . BASE_URL . "\r\n" .
                   "X-Mailer: PHP/" . phpversion() . "\r\n" . 
                   "MIME-Version: 2.0\r\n" . 
                   "Content-Type: text/html; charset=ISO-8859-1\r\n";
        
        // Sending Email
        mail($to, $subject, $message, $headers);

        unset($_POST['cust_name']);
        unset($_POST['cust_cname']);
        unset($_POST['cust_email']);
        unset($_POST['cust_phone']);
        unset($_POST['cust_address']);
        unset($_POST['cust_city']);
        $success_message = LANG_VALUE_152;
    }
}
?>

<div class="py-20 bg-gray-50 min-h-[calc(100vh-200px)] flex items-center justify-center">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gray-900 p-8 text-center">
                    <h1 class="text-3xl font-black text-white uppercase tracking-tight"><?php echo LANG_VALUE_16; ?></h1>
                    <p class="text-gray-400 mt-2">Create your account to start shopping.</p>
                </div>
                
                <div class="p-8 md:p-12">
                    <?php
                    if($error_message != '') {
                        echo "<div class='mb-8 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm font-medium rounded-r-xl'>".$error_message."</div>";
                    }
                    if($success_message != '') {
                        echo "<div class='mb-8 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm font-medium rounded-r-xl'>".$success_message."</div>";
                    }
                    ?>

                    <form action="" method="post" class="space-y-8">
                        <?php $csrf->echoInputField(); ?>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_102; ?> *</label>
                                <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-4 focus:border-brand-primary outline-none transition-all" name="cust_name" value="<?php if(isset($_POST['cust_name'])){echo $_POST['cust_name'];} ?>" placeholder="John Doe">
                            </div>

                            <!-- Company Name -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_103; ?></label>
                                <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-4 focus:border-brand-primary outline-none transition-all" name="cust_cname" value="<?php if(isset($_POST['cust_cname'])){echo $_POST['cust_cname'];} ?>" placeholder="Optional">
                            </div>

                            <!-- Email Address -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_94; ?> *</label>
                                <input type="email" class="w-full border-2 border-gray-100 rounded-xl px-4 py-4 focus:border-brand-primary outline-none transition-all" name="cust_email" value="<?php if(isset($_POST['cust_email'])){echo $_POST['cust_email'];} ?>" placeholder="john@example.com">
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_104; ?> *</label>
                                <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-4 focus:border-brand-primary outline-none transition-all" name="cust_phone" value="<?php if(isset($_POST['cust_phone'])){echo $_POST['cust_phone'];} ?>" placeholder="+1 234 567 890">
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_105; ?> *</label>
                                <textarea name="cust_address" class="w-full border-2 border-gray-100 rounded-xl px-4 py-4 focus:border-brand-primary outline-none transition-all" rows="2" placeholder="Your street address"><?php if(isset($_POST['cust_address'])){echo $_POST['cust_address'];} ?></textarea>
                            </div>

                            <!-- Country -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_106; ?> *</label>
                                <select name="cust_country" class="w-full border-2 border-gray-100 rounded-xl px-4 py-4 focus:border-brand-primary outline-none transition-all">
                                    <option value="">Select country</option>
                                    <?php
                                    $statement = $pdo->prepare("SELECT * FROM tbl_country ORDER BY country_name ASC");
                                    $statement->execute();
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
                                    foreach ($result as $row) {
                                        echo "<option value='{$row['country_id']}'>{$row['country_name']}</option>";
                                    }
                                    ?>    
                                </select>
                            </div>

                            <!-- City -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_107; ?> *</label>
                                <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-4 focus:border-brand-primary outline-none transition-all" name="cust_city" value="<?php if(isset($_POST['cust_city'])){echo $_POST['cust_city'];} ?>" placeholder="Your City">
                            </div>

                            <!-- Password -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_96; ?> *</label>
                                <input type="password" class="w-full border-2 border-gray-100 rounded-xl px-4 py-4 focus:border-brand-primary outline-none transition-all" name="cust_password" placeholder="••••••••">
                            </div>

                            <!-- Re-Password -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_98; ?> *</label>
                                <input type="password" class="w-full border-2 border-gray-100 rounded-xl px-4 py-4 focus:border-brand-primary outline-none transition-all" name="cust_re_password" placeholder="••••••••">
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" name="form1" class="w-full bg-brand-primary text-white py-4 rounded-xl font-bold text-lg uppercase tracking-widest hover:bg-red-600 transition-all shadow-xl shadow-red-500/20">
                                <?php echo LANG_VALUE_15; ?>
                            </button>
                        </div>

                        <div class="text-center text-sm font-medium text-gray-500">
                            Already have an account? 
                            <a href="login.php" class="text-brand-primary font-bold hover:underline">Login here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>