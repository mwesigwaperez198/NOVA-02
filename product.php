<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
    header('location: index.php');
    exit;
} else {
    // Check the id is valid or not
    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    if( $total == 0 ) {
        header('location: index.php');
        exit;
    }
}

foreach($result as $row) {
    $p_name = $row['p_name'];
    $p_old_price = $row['p_old_price'];
    $p_current_price = $row['p_current_price'];
    $p_qty = $row['p_qty'];
    $p_featured_photo = $row['p_featured_photo'];
    $p_description = $row['p_description'];
    $p_short_description = $row['p_short_description'];
    $p_feature = $row['p_feature'];
    $p_condition = $row['p_condition'];
    $p_return_policy = $row['p_return_policy'];
    $p_total_view = $row['p_total_view'];
    $p_is_featured = $row['p_is_featured'];
    $p_is_active = $row['p_is_active'];
    $ecat_id = $row['ecat_id'];
}

// Getting all categories name for breadcrumb
$statement = $pdo->prepare("SELECT
                        t1.ecat_id,
                        t1.ecat_name,
                        t1.mcat_id,

                        t2.mcat_id,
                        t2.mcat_name,
                        t2.tcat_id,

                        t3.tcat_id,
                        t3.tcat_name

                        FROM tbl_end_category t1
                        JOIN tbl_mid_category t2
                        ON t1.mcat_id = t2.mcat_id
                        JOIN tbl_top_category t3
                        ON t2.tcat_id = t3.tcat_id
                        WHERE t1.ecat_id=?");
$statement->execute(array($ecat_id));
$total = $statement->rowCount();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $ecat_name = $row['ecat_name'];
    $mcat_id = $row['mcat_id'];
    $mcat_name = $row['mcat_name'];
    $tcat_id = $row['tcat_id'];
    $tcat_name = $row['tcat_name'];
}


$p_total_view = $p_total_view + 1;

$statement = $pdo->prepare("UPDATE tbl_product SET p_total_view=? WHERE p_id=?");
$statement->execute(array($p_total_view,$_REQUEST['id']));


$statement = $pdo->prepare("SELECT * FROM tbl_product_size WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $size[] = $row['size_id'];
}

$statement = $pdo->prepare("SELECT * FROM tbl_product_color WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $color[] = $row['color_id'];
}


if(isset($_POST['form_review'])) {
    
    $statement = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=? AND cust_id=?");
    $statement->execute(array($_REQUEST['id'],$_SESSION['customer']['cust_id']));
    $total = $statement->rowCount();
    
    if($total) {
        $error_message = LANG_VALUE_68; 
    } else {
        $statement = $pdo->prepare("INSERT INTO tbl_rating (p_id,cust_id,comment,rating) VALUES (?,?,?,?)");
        $statement->execute(array($_REQUEST['id'],$_SESSION['customer']['cust_id'],$_POST['comment'],$_POST['rating']));
        $success_message = LANG_VALUE_163;    
    }
    
}

// Getting the average rating for this product
$t_rating = 0;
$statement = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
$tot_rating = $statement->rowCount();
if($tot_rating == 0) {
    $avg_rating = 0;
} else {
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
    foreach ($result as $row) {
        $t_rating = $t_rating + $row['rating'];
    }
    $avg_rating = $t_rating / $tot_rating;
}

if(isset($_POST['form_add_to_cart'])) {

	// getting the currect stock of this product
	$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
	$statement->execute(array($_REQUEST['id']));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
	foreach ($result as $row) {
		$current_p_qty = $row['p_qty'];
	}
	if($_POST['p_qty'] > $current_p_qty):
		$temp_msg = 'Sorry! There are only '.$current_p_qty.' item(s) in stock';
		?>
		<script type="text/javascript">alert('<?php echo $temp_msg; ?>');</script>
		<?php
	else:
    if(isset($_SESSION['cart_p_id']))
    {
        $arr_cart_p_id = array();
        $arr_cart_size_id = array();
        $arr_cart_color_id = array();
        $arr_cart_p_qty = array();
        $arr_cart_p_current_price = array();

        $i=0;
        foreach($_SESSION['cart_p_id'] as $key => $value) 
        {
            $i++;
            $arr_cart_p_id[$i] = $value;
        }

        $i=0;
        foreach($_SESSION['cart_size_id'] as $key => $value) 
        {
            $i++;
            $arr_cart_size_id[$i] = $value;
        }

        $i=0;
        foreach($_SESSION['cart_color_id'] as $key => $value) 
        {
            $i++;
            $arr_cart_color_id[$i] = $value;
        }


        $added = 0;
        if(!isset($_POST['size_id'])) {
            $size_id = 0;
        } else {
            $size_id = $_POST['size_id'];
        }
        if(!isset($_POST['color_id'])) {
            $color_id = 0;
        } else {
            $color_id = $_POST['color_id'];
        }
        for($i=1;$i<=count($arr_cart_p_id);$i++) {
            if( ($arr_cart_p_id[$i]==$_REQUEST['id']) && ($arr_cart_size_id[$i]==$size_id) && ($arr_cart_color_id[$i]==$color_id) ) {
                $added = 1;
                break;
            }
        }
        if($added == 1) {
           $error_message1 = 'This product is already added to the shopping cart.';
        } else {

            $i=0;
            foreach($_SESSION['cart_p_id'] as $key => $res) 
            {
                $i++;
            }
            $new_key = $i+1;

            if(isset($_POST['size_id'])) {

                $size_id = $_POST['size_id'];

                $statement = $pdo->prepare("SELECT * FROM tbl_size WHERE size_id=?");
                $statement->execute(array($size_id));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
                foreach ($result as $row) {
                    $size_name = $row['size_name'];
                }
            } else {
                $size_id = 0;
                $size_name = '';
            }
            
            if(isset($_POST['color_id'])) {
                $color_id = $_POST['color_id'];
                $statement = $pdo->prepare("SELECT * FROM tbl_color WHERE color_id=?");
                $statement->execute(array($color_id));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
                foreach ($result as $row) {
                    $color_name = $row['color_name'];
                }
            } else {
                $color_id = 0;
                $color_name = '';
            }
          

            $_SESSION['cart_p_id'][$new_key] = $_REQUEST['id'];
            $_SESSION['cart_size_id'][$new_key] = $size_id;
            $_SESSION['cart_size_name'][$new_key] = $size_name;
            $_SESSION['cart_color_id'][$new_key] = $color_id;
            $_SESSION['cart_color_name'][$new_key] = $color_name;
            $_SESSION['cart_p_qty'][$new_key] = $_POST['p_qty'];
            $_SESSION['cart_p_current_price'][$new_key] = $_POST['p_current_price'];
            $_SESSION['cart_p_name'][$new_key] = $_POST['p_name'];
            $_SESSION['cart_p_featured_photo'][$new_key] = $_POST['p_featured_photo'];

            $success_message1 = 'Product is added to the cart successfully!';
        }
        
    }
    else
    {

        if(isset($_POST['size_id'])) {

            $size_id = $_POST['size_id'];

            $statement = $pdo->prepare("SELECT * FROM tbl_size WHERE size_id=?");
            $statement->execute(array($size_id));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
            foreach ($result as $row) {
                $size_name = $row['size_name'];
            }
        } else {
            $size_id = 0;
            $size_name = '';
        }
        
        if(isset($_POST['color_id'])) {
            $color_id = $_POST['color_id'];
            $statement = $pdo->prepare("SELECT * FROM tbl_color WHERE color_id=?");
            $statement->execute(array($color_id));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
            foreach ($result as $row) {
                $color_name = $row['color_name'];
            }
        } else {
            $color_id = 0;
            $color_name = '';
        }
        

        $_SESSION['cart_p_id'][1] = $_REQUEST['id'];
        $_SESSION['cart_size_id'][1] = $size_id;
        $_SESSION['cart_size_name'][1] = $size_name;
        $_SESSION['cart_color_id'][1] = $color_id;
        $_SESSION['cart_color_name'][1] = $color_name;
        $_SESSION['cart_p_qty'][1] = $_POST['p_qty'];
        $_SESSION['cart_p_current_price'][1] = $_POST['p_current_price'];
        $_SESSION['cart_p_name'][1] = $_POST['p_name'];
        $_SESSION['cart_p_featured_photo'][1] = $_POST['p_featured_photo'];

        $success_message1 = 'Product is added to the cart successfully!';
    }
	endif;
}
?>

<?php
if($error_message1 != '') {
    echo "<script>alert('".$error_message1."')</script>";
}
if($success_message1 != '') {
    echo "<script>alert('".$success_message1."')</script>";
    header('location: product.php?id='.$_REQUEST['id']);
}
?>


<div class="py-8 bg-white border-b">
    <div class="container mx-auto px-4">
        <nav class="flex text-sm font-medium text-gray-500">
            <ol class="flex items-center space-x-2">
                <li><a href="<?php echo BASE_URL; ?>" class="hover:text-brand-primary">Home</a></li>
                <li class="flex items-center space-x-2">
                    <i class="fa fa-angle-right text-gray-300"></i>
                    <a href="<?php echo BASE_URL.'product-category.php?id='.$tcat_id.'&type=top-category' ?>" class="hover:text-brand-primary"><?php echo $tcat_name; ?></a>
                </li>
                <li class="flex items-center space-x-2">
                    <i class="fa fa-angle-right text-gray-300"></i>
                    <a href="<?php echo BASE_URL.'product-category.php?id='.$mcat_id.'&type=mid-category' ?>" class="hover:text-brand-primary"><?php echo $mcat_name; ?></a>
                </li>
                <li class="flex items-center space-x-2 text-gray-900">
                    <i class="fa fa-angle-right text-gray-300"></i>
                    <span><?php echo $p_name; ?></span>
                </li>
            </ol>
        </nav>
    </div>
</div>

<div class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="flex flex-wrap lg:flex-nowrap">
                <!-- Product Gallery -->
                <div class="w-full lg:w-1/2 p-8 lg:p-12 bg-gray-50/50">
                    <div class="relative aspect-square rounded-2xl overflow-hidden bg-white shadow-inner mb-6">
                        <img id="main-product-image" src="assets/uploads/<?php echo $p_featured_photo; ?>" class="w-full h-full object-contain p-4" alt="<?php echo $p_name; ?>">
                    </div>
                    
                    <div class="grid grid-cols-4 gap-4">
                        <button onclick="updateMainImage('assets/uploads/<?php echo $p_featured_photo; ?>')" class="aspect-square rounded-xl border-2 border-brand-primary overflow-hidden hover:opacity-80 transition-all bg-white p-2">
                            <img src="assets/uploads/<?php echo $p_featured_photo; ?>" class="w-full h-full object-contain" alt="Thumbnail">
                        </button>
                        <?php
                        $statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=?");
                        $statement->execute(array($_REQUEST['id']));
                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($result as $row) {
                            ?>
                            <button onclick="updateMainImage('assets/uploads/product_photos/<?php echo $row['photo']; ?>')" class="aspect-square rounded-xl border-2 border-transparent hover:border-brand-primary/50 overflow-hidden hover:opacity-80 transition-all bg-white p-2">
                                <img src="assets/uploads/product_photos/<?php echo $row['photo']; ?>" class="w-full h-full object-contain" alt="Thumbnail">
                            </button>
                            <?php
                        }
                        ?>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="w-full lg:w-1/2 p-8 lg:p-12">
                    <div class="mb-8">
                        <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-4 leading-tight"><?php echo $p_name; ?></h1>
                        
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex items-center gap-1 text-yellow-400">
                                <?php
                                for($i=1;$i<=5;$i++) {
                                    $starClass = $i <= $avg_rating ? "fa-star" : "fa-star-o text-gray-200";
                                    echo "<i class='fa $starClass'></i>";
                                }
                                ?>
                            </div>
                            <span class="text-sm text-gray-500 font-medium">(<?php echo $tot_rating; ?> Reviews)</span>
                        </div>

                        <div class="text-gray-600 leading-relaxed text-lg mb-8">
                            <?php echo $p_short_description; ?>
                        </div>

                        <div class="flex items-baseline gap-4 mb-8">
                            <span class="text-4xl font-black text-brand-primary">$<?php echo number_format($p_current_price, 2); ?></span>
                            <?php if($p_old_price != ''): ?>
                                <span class="text-xl text-gray-400 line-through">$<?php echo number_format($p_old_price, 2); ?></span>
                            <?php endif; ?>
                        </div>

                        <form action="" method="post" class="space-y-8">
                            <input type="hidden" name="p_current_price" value="<?php echo $p_current_price; ?>">
                            <input type="hidden" name="p_name" value="<?php echo $p_name; ?>">
                            <input type="hidden" name="p_featured_photo" value="<?php echo $p_featured_photo; ?>">

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <?php if(isset($size)): ?>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_52; ?></label>
                                    <select name="size_id" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all">
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_size");
                                        $statement->execute();
                                        $sizes = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($sizes as $s) {
                                            if(in_array($s['size_id'],$size)) {
                                                echo "<option value='{$s['size_id']}'>{$s['size_name']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <?php endif; ?>

                                <?php if(isset($color)): ?>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_53; ?></label>
                                    <select name="color_id" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all">
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_color");
                                        $statement->execute();
                                        $colors = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($colors as $c) {
                                            if(in_array($c['color_id'],$color)) {
                                                echo "<option value='{$c['color_id']}'>{$c['color_name']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="flex flex-wrap items-end gap-6">
                                <div class="min-w-[8rem]">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide"><?php echo LANG_VALUE_55; ?></label>
                                    <input type="number" class="w-full border-2 border-gray-100 rounded-xl px-4 py-4 focus:border-brand-primary outline-none transition-all" step="1" min="1" name="p_qty" value="1">
                                </div>
                                <div class="flex-1">
                                    <button type="submit" name="form_add_to_cart" class="w-full bg-brand-primary text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-red-600 transition-all shadow-xl shadow-red-500/20 flex items-center justify-center gap-3">
                                        <i class="fa fa-shopping-cart"></i>
                                        <?php echo LANG_VALUE_154; ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="pt-8 border-t border-gray-100">
                        <div class="flex items-center gap-4 text-sm font-medium text-gray-500">
                            <span>Share this:</span>
                            <div class="sharethis-inline-share-buttons"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Tabs -->
        <div class="mt-12 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="border-b border-gray-100">
                <nav class="flex overflow-x-auto">
                    <button onclick="switchTab('description')" class="tab-btn active px-8 py-5 text-sm font-bold uppercase tracking-wider border-b-2 border-brand-primary text-brand-primary whitespace-nowrap" id="tab-description-btn">
                        <?php echo LANG_VALUE_59; ?>
                    </button>
                    <button onclick="switchTab('feature')" class="tab-btn px-8 py-5 text-sm font-bold uppercase tracking-wider border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap" id="tab-feature-btn">
                        <?php echo LANG_VALUE_60; ?>
                    </button>
                    <button onclick="switchTab('condition')" class="tab-btn px-8 py-5 text-sm font-bold uppercase tracking-wider border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap" id="tab-condition-btn">
                        <?php echo LANG_VALUE_61; ?>
                    </button>
                    <button onclick="switchTab('return')" class="tab-btn px-8 py-5 text-sm font-bold uppercase tracking-wider border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap" id="tab-return-btn">
                        <?php echo LANG_VALUE_62; ?>
                    </button>
                </nav>
            </div>
            
            <div class="p-8 lg:p-12">
                <div id="tab-description" class="tab-content prose max-w-none text-gray-600 leading-relaxed">
                    <?php echo $p_description ?: LANG_VALUE_70; ?>
                </div>
                <div id="tab-feature" class="tab-content hidden prose max-w-none text-gray-600 leading-relaxed">
                    <?php echo $p_feature ?: LANG_VALUE_71; ?>
                </div>
                <div id="tab-condition" class="tab-content hidden prose max-w-none text-gray-600 leading-relaxed">
                    <?php echo $p_condition ?: LANG_VALUE_72; ?>
                </div>
                <div id="tab-return" class="tab-content hidden prose max-w-none text-gray-600 leading-relaxed">
                    <?php echo $p_return_policy ?: LANG_VALUE_73; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateMainImage(src) {
    document.getElementById('main-product-image').src = src;
    // Update border highlights
    event.currentTarget.parentElement.querySelectorAll('button').forEach(btn => btn.classList.remove('border-brand-primary'));
    event.currentTarget.classList.add('border-brand-primary');
}

function switchTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
    document.getElementById('tab-' + tabId).classList.remove('hidden');
    
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-brand-primary', 'text-brand-primary');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    const activeBtn = document.getElementById('tab-' + tabId + '-btn');
    activeBtn.classList.remove('border-transparent', 'text-gray-500');
    activeBtn.classList.add('border-brand-primary', 'text-brand-primary');
}
</script>

<div class="py-20 bg-gray-100">
    <div class="container mx-auto px-4">
        <div class="mb-12">
            <h2 class="text-3xl font-black text-gray-900"><?php echo LANG_VALUE_155; ?></h2>
            <p class="text-gray-500 mt-2"><?php echo LANG_VALUE_156; ?></p>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php
            $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE ecat_id=? AND p_id!=? LIMIT 4");
            $statement->execute(array($ecat_id,$_REQUEST['id']));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                ?>
                <div class="group bg-white rounded-3xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500">
                    <div class="relative aspect-square overflow-hidden bg-gray-100">
                        <img src="assets/uploads/<?php echo $row['p_featured_photo']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="<?php echo $row['p_name']; ?>">
                        <div class="absolute inset-0 bg-black/5 group-hover:bg-black/0 transition-colors"></div>
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
            ?>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
