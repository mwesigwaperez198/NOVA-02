<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_product_category = $row['banner_product_category'];
}
?>

<?php
if( !isset($_REQUEST['id']) || !isset($_REQUEST['type']) ) {
    header('location: index.php');
    exit;
} else {

    if( ($_REQUEST['type'] != 'top-category') && ($_REQUEST['type'] != 'mid-category') && ($_REQUEST['type'] != 'end-category') ) {
        header('location: index.php');
        exit;
    } else {

        $statement = $pdo->prepare("SELECT * FROM tbl_top_category");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
            $top[] = $row['tcat_id'];
            $top1[] = $row['tcat_name'];
        }

        $statement = $pdo->prepare("SELECT * FROM tbl_mid_category");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
            $mid[] = $row['mcat_id'];
            $mid1[] = $row['mcat_name'];
            $mid2[] = $row['tcat_id'];
        }

        $statement = $pdo->prepare("SELECT * FROM tbl_end_category");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
            $end[] = $row['ecat_id'];
            $end1[] = $row['ecat_name'];
            $end2[] = $row['mcat_id'];
        }

        if($_REQUEST['type'] == 'top-category') {
            if(!in_array($_REQUEST['id'],$top)) {
                header('location: index.php');
                exit;
            } else {

                // Getting Title
                for ($i=0; $i < count($top); $i++) { 
                    if($top[$i] == $_REQUEST['id']) {
                        $title = $top1[$i];
                        break;
                    }
                }
                $arr1 = array();
                $arr2 = array();
                // Find out all ecat ids under this
                for ($i=0; $i < count($mid); $i++) { 
                    if($mid2[$i] == $_REQUEST['id']) {
                        $arr1[] = $mid[$i];
                    }
                }
                for ($j=0; $j < count($arr1); $j++) {
                    for ($i=0; $i < count($end); $i++) { 
                        if($end2[$i] == $arr1[$j]) {
                            $arr2[] = $end[$i];
                        }
                    }   
                }
                $final_ecat_ids = $arr2;
            }   
        }

        if($_REQUEST['type'] == 'mid-category') {
            if(!in_array($_REQUEST['id'],$mid)) {
                header('location: index.php');
                exit;
            } else {
                // Getting Title
                for ($i=0; $i < count($mid); $i++) { 
                    if($mid[$i] == $_REQUEST['id']) {
                        $title = $mid1[$i];
                        break;
                    }
                }
                $arr2 = array();        
                // Find out all ecat ids under this
                for ($i=0; $i < count($end); $i++) { 
                    if($end2[$i] == $_REQUEST['id']) {
                        $arr2[] = $end[$i];
                    }
                }
                $final_ecat_ids = $arr2;
            }
        }

        if($_REQUEST['type'] == 'end-category') {
            if(!in_array($_REQUEST['id'],$end)) {
                header('location: index.php');
                exit;
            } else {
                // Getting Title
                for ($i=0; $i < count($end); $i++) { 
                    if($end[$i] == $_REQUEST['id']) {
                        $title = $end1[$i];
                        break;
                    }
                }
                $final_ecat_ids = array($_REQUEST['id']);
            }
        }
        
    }   
}
?>

<div class="py-12 bg-white border-b">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4 uppercase tracking-tight"><?php echo LANG_VALUE_50; ?> <?php echo $title; ?></h1>
        <div class="w-20 h-1.5 bg-brand-primary mx-auto rounded-full"></div>
    </div>
</div>

<div class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap lg:flex-nowrap gap-8">
            <!-- Sidebar -->
            <div class="w-full lg:w-1/4">
                <?php require_once('sidebar-category.php'); ?>
            </div>
            
            <!-- Product Grid -->
            <div class="w-full lg:w-3/4">
                <div class="mb-8 flex items-center justify-between">
                    <h3 class="text-2xl font-black text-gray-900 uppercase tracking-tight"><?php echo LANG_VALUE_51; ?> "<?php echo $title; ?>"</h3>
                </div>

                <?php
                // Checking if any product is available or not
                $prod_count = 0;
                $statement = $pdo->prepare("SELECT ecat_id FROM tbl_product");
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $row) {
                    $prod_table_ecat_ids[] = $row['ecat_id'];
                }

                foreach($final_ecat_ids as $ecid) {
                    if(isset($prod_table_ecat_ids) && in_array($ecid, $prod_table_ecat_ids)) {
                        $prod_count++;
                    }
                }

                if($prod_count == 0): ?>
                    <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-gray-100">
                        <i class="fa fa-info-circle text-4xl text-gray-200 mb-4"></i>
                        <p class="text-gray-500 font-medium"><?php echo LANG_VALUE_153; ?></p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php
                        foreach($final_ecat_ids as $ecid) {
                            $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE ecat_id=? AND p_is_active=?");
                            $statement->execute(array($ecid,1));
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                ?>
                                <div class="group bg-white rounded-3xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500">
                                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                                        <img src="assets/uploads/<?php echo $row['p_featured_photo']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="<?php echo $row['p_name']; ?>">
                                        <div class="absolute inset-0 bg-black/5 group-hover:bg-black/0 transition-colors"></div>
                                        
                                        <?php if($row['p_qty'] == 0): ?>
                                            <div class="absolute top-4 left-4 bg-gray-900/80 backdrop-blur-sm text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                                Out Of Stock
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="p-6">
                                        <h3 class="text-lg font-bold text-gray-900 mb-4 group-hover:text-brand-primary transition-colors line-clamp-2 h-14">
                                            <a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo $row['p_name']; ?></a>
                                        </h3>
                                        
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="text-xl font-black text-brand-primary">$<?php echo number_format($row['p_current_price'], 2); ?></div>
                                            <a href="product.php?id=<?php echo $row['p_id']; ?>" class="bg-gray-900 text-white w-12 h-12 flex items-center justify-center rounded-2xl hover:bg-brand-primary transition-all shadow-lg shrink-0">
                                                <i class="fa fa-shopping-cart"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>