<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings_view WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_reset_password = $row['banner_reset_password'];
}
?>

<?php
if( !isset($_GET['email']) || !isset($_GET['token']) )
{
    header('location: '.BASE_URL.'login.php');
    exit;
}

$statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=? AND cust_token=?");
$statement->execute(array($_GET['email'],$_GET['token']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
$tot = $statement->rowCount();
if($tot == 0)
{
    header('location: '.BASE_URL.'login.php');
    exit;
}
foreach ($result as $row) {
    $saved_time = $row['cust_timestamp'];
}

$error_message2 = '';
$is_expired = (time() - $saved_time > 86400);
if($is_expired)
{
    $error_message2 = LANG_VALUE_144;
}

if(isset($_POST['form1'])) {
    $csrf->verifyRequest();

    $valid = 1;
    
    if($is_expired)
    {
        $valid = 0;
        $error_message .= LANG_VALUE_144.'<br>';
    }
    
    if( empty($_POST['cust_new_password']) || empty($_POST['cust_re_password']) )
    {
        $valid = 0;
        $error_message .= LANG_VALUE_140.'<br>';
    }
    else
    {
        if(strlen($_POST['cust_new_password']) < 6)
        {
            $valid = 0;
            $error_message .= "Password must be at least 6 characters long.<br>";
        }
        if($_POST['cust_new_password'] != $_POST['cust_re_password'])
        {
            $valid = 0;
            $error_message .= LANG_VALUE_139.'<br>';
        }
    }   

    if($valid == 1) {

        $hashed_password = password_hash($_POST['cust_new_password'], PASSWORD_BCRYPT);
        $statement = $pdo->prepare("UPDATE tbl_customer SET cust_password=?, cust_token=?, cust_timestamp=? WHERE cust_email=?");
        $statement->execute(array($hashed_password,'','',$_GET['email']));
        
        header('location: '.BASE_URL.'reset-password-success.php');
        exit;
    }
}
?>

<div class="py-20 bg-gray-50 min-h-[calc(100vh-200px)] flex items-center justify-center">
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden">
                <div class="bg-gray-900 p-8 text-center">
                    <h1 class="text-2xl font-black text-white uppercase tracking-tight"><?php echo LANG_VALUE_149; ?></h1>
                    <p class="text-gray-400 mt-2 text-sm">Please set your new account password.</p>
                </div>

                <div class="p-8 md:p-10">
                    <?php
                    if($error_message2 != '') {
                        echo "<div class='mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm font-medium rounded-r-xl'>".$error_message2."</div>";
                    }
                    if($error_message != '') {
                        echo "<div class='mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm font-medium rounded-r-xl'>".$error_message."</div>";
                    }
                    ?>
                    
                    <?php if(!$is_expired): ?>
                    <form action="" method="post" class="space-y-6">
                        <?php $csrf->echoInputField(); ?>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_100; ?> *</label>
                            <input type="password" class="w-full border-2 border-gray-100 rounded-xl px-4 py-4 focus:border-brand-primary outline-none transition-all" name="cust_new_password" required>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_101; ?> *</label>
                            <input type="password" class="w-full border-2 border-gray-100 rounded-xl px-4 py-4 focus:border-brand-primary outline-none transition-all" name="cust_re_password" required>
                        </div>
                        <button type="submit" name="form1" class="w-full bg-brand-primary text-white py-4 rounded-xl font-bold uppercase tracking-wide hover:bg-red-600 transition-all shadow-xl shadow-red-500/20">
                            <?php echo LANG_VALUE_149; ?>
                        </button>
                    </form>
                    <?php else: ?>
                        <div class="text-center">
                            <a href="forget-password.php" class="inline-block bg-brand-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-red-600 transition-all">Request New Reset Link</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
