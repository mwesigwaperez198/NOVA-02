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

<div class="py-12 bg-white border-b">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tight"><?php echo LANG_VALUE_89; ?></h1>
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
                <div class="bg-white rounded-3xl p-12 shadow-sm border border-gray-100 flex flex-col items-center text-center">
                    <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center mb-8">
                        <i class="fa fa-check-circle text-5xl text-green-500"></i>
                    </div>
                    <h2 class="text-3xl font-black text-gray-900 mb-4"><?php echo LANG_VALUE_90; ?></h2>
                    <p class="text-gray-500 text-lg max-w-md mx-auto mb-8">
                        Welcome to your dashboard! Here you can manage your profile, track orders, and update your account settings easily.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 w-full max-w-xl mt-8">
                        <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="text-brand-primary text-2xl font-black mb-1">
                                <?php
                                $statement = $pdo->prepare("SELECT COUNT(*) FROM tbl_payment WHERE customer_id=?");
                                $statement->execute(array($_SESSION['customer']['cust_id']));
                                echo intval($statement->fetchColumn());
                                ?>
                            </div>
                            <div class="text-gray-400 text-xs font-bold uppercase tracking-widest">Total Orders</div>
                        </div>
                        <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                            <div class="text-brand-primary text-2xl font-black mb-1">
                                <?php
                                $statement = $pdo->prepare("SELECT COUNT(*) FROM tbl_payment WHERE customer_id=? AND payment_status=?");
                                $statement->execute(array($_SESSION['customer']['cust_id'], 'Completed'));
                                echo intval($statement->fetchColumn());
                                ?>
                            </div>
                            <div class="text-gray-400 text-xs font-bold uppercase tracking-widest">Completed Payments</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>