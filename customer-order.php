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
        <h1 class="text-4xl font-black text-gray-900 uppercase tracking-tight"><?php echo LANG_VALUE_25; ?></h1>
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
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-900 text-white text-xs font-bold uppercase tracking-widest text-left">
                                    <th class="px-6 py-4">#</th>
                                    <th class="px-6 py-4"><?php echo LANG_VALUE_48; ?></th>
                                    <th class="px-6 py-4"><?php echo LANG_VALUE_27; ?></th>
                                    <th class="px-6 py-4"><?php echo LANG_VALUE_29; ?></th>
                                    <th class="px-6 py-4"><?php echo LANG_VALUE_30; ?></th>
                                    <th class="px-6 py-4"><?php echo LANG_VALUE_31; ?></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php
                                /* ===================== Pagination Code Starts ================== */
                                $adjacents = 5;
                                $statement = $pdo->prepare("SELECT id FROM tbl_payment WHERE customer_email=? ORDER BY id DESC");
                                $statement->execute(array($_SESSION['customer']['cust_email']));
                                $total_pages = $statement->rowCount();

                                $limit = 10;
                                $page = @$_GET['page'] ?: 1;
                                $start = ($page - 1) * $limit;
                                
                                $statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE customer_email=? ORDER BY id DESC LIMIT $start, $limit");
                                $statement->execute(array($_SESSION['customer']['cust_email']));
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                                if(!$total_pages): ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 italic">No orders found yet.</td>
                                    </tr>
                                <?php else:
                                    $tip = $start;
                                    foreach ($result as $row):
                                        $tip++;
                                        ?>
                                        <tr class="hover:bg-gray-50 transition-colors flex flex-col md:table-row p-6 md:p-0 relative">
                                            <td class="md:px-6 md:py-4 text-sm font-bold text-gray-400 mb-4 md:mb-0">#<?php echo $tip; ?></td>
                                            <td class="md:px-6 md:py-4 mb-6 md:mb-0">
                                                <div class="space-y-3">
                                                    <?php
                                                    $statement1 = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
                                                    $statement1->execute(array($row['payment_id']));
                                                    $items = $statement1->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($items as $item): ?>
                                                        <div class="text-sm">
                                                            <div class="font-bold text-gray-900"><?php echo $item['product_name']; ?></div>
                                                            <div class="text-xs text-gray-500 mt-1">
                                                                Qty: <?php echo $item['quantity']; ?> | Price: $<?php echo number_format($item['unit_price'], 2); ?>
                                                                <?php if($item['size']) echo " | Size: ".$item['size']; ?>
                                                                <?php if($item['color']) echo " | Color: ".$item['color']; ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </td>
                                            <td class="md:px-6 md:py-4 text-sm text-gray-600 mb-2 md:mb-0">
                                                <span class="md:hidden font-bold text-gray-400 uppercase text-[10px] tracking-widest block mb-1">Date</span>
                                                <?php echo $row['payment_date']; ?>
                                            </td>
                                            <td class="md:px-6 md:py-4 text-sm font-black text-brand-primary mb-4 md:mb-0">
                                                <span class="md:hidden font-bold text-gray-400 uppercase text-[10px] tracking-widest block mb-1">Total</span>
                                                $<?php echo number_format($row['paid_amount'], 2); ?>
                                            </td>
                                            <td class="md:px-6 md:py-4 md:text-center absolute top-6 right-6 md:static">
                                                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest <?php echo $row['payment_status'] == 'Completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'; ?>">
                                                    <?php echo $row['payment_status']; ?>
                                                </span>
                                            </td>
                                            <td class="md:px-6 md:py-4 text-sm text-gray-600">
                                                <span class="md:hidden font-bold text-gray-400 uppercase text-[10px] tracking-widest block mb-1">Method</span>
                                                <?php echo $row['payment_method']; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <?php if($total_pages > $limit): ?>
                    <div class="mt-8 flex justify-center">
                        <nav class="flex items-center gap-2 bg-white px-4 py-2 rounded-2xl shadow-sm border border-gray-100">
                            <?php
                            $lastpage = ceil($total_pages/$limit);
                            $targetpage = "customer-order.php";
                            
                            if ($page > 1): ?>
                                <a href="?page=<?php echo $page-1; ?>" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-gray-50 text-gray-500 transition-colors">
                                    <i class="fa fa-angle-left"></i>
                                </a>
                            <?php endif; ?>

                            <?php for($counter = 1; $counter <= $lastpage; $counter++): ?>
                                <?php if($counter == $page): ?>
                                    <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-brand-primary text-white font-bold"><?php echo $counter; ?></span>
                                <?php else: ?>
                                    <a href="?page=<?php echo $counter; ?>" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-gray-50 text-gray-700 transition-colors font-medium"><?php echo $counter; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($page < $lastpage): ?>
                                <a href="?page=<?php echo $page+1; ?>" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-gray-50 text-gray-500 transition-colors">
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>