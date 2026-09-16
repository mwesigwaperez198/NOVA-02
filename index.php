<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings_view WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row)
{
    $cta_title = $row['cta_title'];
    $cta_content = $row['cta_content'];
    $cta_read_more_text = $row['cta_read_more_text'];
    $cta_read_more_url = $row['cta_read_more_url'];
    $cta_photo = $row['cta_photo'];
    $featured_product_title = $row['featured_product_title'];
    $featured_product_subtitle = $row['featured_product_subtitle'];
    $latest_product_title = $row['latest_product_title'];
    $latest_product_subtitle = $row['latest_product_subtitle'];
    $popular_product_title = $row['popular_product_title'];
    $popular_product_subtitle = $row['popular_product_subtitle'];
    $total_featured_product_home = $row['total_featured_product_home'];
    $total_latest_product_home = $row['total_latest_product_home'];
    $total_popular_product_home = $row['total_popular_product_home'];
    $home_service_on_off = $row['home_service_on_off'];
    $home_welcome_on_off = $row['home_welcome_on_off'];
    $home_featured_product_on_off = $row['home_featured_product_on_off'];
    $home_latest_product_on_off = $row['home_latest_product_on_off'];
    $home_popular_product_on_off = $row['home_popular_product_on_off'];

}


?>

<!-- Hero Section -->
<div class="relative bg-gray-900 overflow-hidden">
    <?php
    $statement = $pdo->prepare("SELECT * FROM tbl_slider LIMIT 1");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
    foreach ($result as $row) {            
        ?>
        <div class="absolute inset-0">
            <img src="assets/uploads/<?php echo $row['photo']; ?>" class="w-full h-full object-cover opacity-40" alt="Hero Background">
            <div class="absolute inset-0 bg-gradient-to-r from-gray-900 via-gray-900/40 to-transparent"></div>
        </div>
        <div class="relative container mx-auto px-4 py-24 md:py-32 lg:py-48 flex items-center">
            <div class="max-w-2xl">
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white leading-tight mb-6">
                    <?php echo $row['heading']; ?>
                </h1>
                <p class="text-xl text-gray-300 mb-10 leading-relaxed">
                    <?php echo nl2br($row['content']); ?>
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="<?php echo $row['button_url']; ?>" class="bg-brand-primary text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-red-600 transition-all transform hover:-translate-y-1 shadow-xl">
                        <?php echo $row['button_text']; ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>

<?php if($home_service_on_off == 1): ?>
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
                $statement = $pdo->prepare("SELECT * FROM tbl_service");
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
                foreach ($result as $row) {
                    ?>
                    <div class="group p-8 rounded-3xl bg-gray-50 border border-transparent hover:border-brand-primary/20 hover:bg-white hover:shadow-2xl transition-all duration-300">
                        <div class="w-16 h-16 mb-6 rounded-2xl bg-brand-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <img src="assets/uploads/<?php echo $row['photo']; ?>" class="w-10 h-10 object-contain" alt="<?php echo $row['title']; ?>">
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4 line-clamp-1"><?php echo $row['title']; ?></h3>
                        <p class="text-gray-600 leading-relaxed line-clamp-3">
                            <?php echo nl2br($row['content']); ?>
                        </p>
                    </div>
                    <?php
                }
            ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
function render_product_grid($title, $subtitle, $products) {
    ?>
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="mb-12 text-center">
                <h2 class="text-4xl font-extrabold text-gray-900 mb-4"><?php echo $title; ?></h2>
                <div class="w-24 h-1.5 bg-brand-primary mx-auto rounded-full mb-6"></div>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto"><?php echo $subtitle; ?></p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php foreach ($products as $row): ?>
                    <div class="group bg-white rounded-3xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500 flex flex-col">
                        <div class="relative aspect-square overflow-hidden bg-gray-100">
                            <img src="assets/uploads/<?php echo $row['p_featured_photo']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="<?php echo $row['p_name']; ?>">
                            <div class="absolute inset-0 bg-black/5 group-hover:bg-black/0 transition-colors"></div>
                            
                            <?php if($row['p_qty'] == 0): ?>
                                <div class="absolute top-4 left-4 bg-gray-900/80 backdrop-blur-sm text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">
                                    Out Of Stock
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-brand-primary transition-colors line-clamp-2 h-14">
                                <a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo $row['p_name']; ?></a>
                            </h3>
                            
                            <div class="flex items-center gap-1 mb-4">
                                <?php
                                global $pdo;
                                $t_rating = 0;
                                $statement1 = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=?");
                                $statement1->execute(array($row['p_id']));
                                $tot_rating = $statement1->rowCount();
                                if($tot_rating > 0) {
                                    $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($result1 as $row1) { $t_rating += $row1['rating']; }
                                    $avg_rating = $t_rating / $tot_rating;
                                } else {
                                    $avg_rating = 0;
                                }
                                
                                for($i=1;$i<=5;$i++) {
                                    $starClass = $i <= $avg_rating ? "text-yellow-400 fa-star" : "text-gray-200 fa-star-o";
                                    echo "<i class='fa $starClass'></i>";
                                }
                                ?>
                            </div>

                            <div class="mt-auto flex items-center justify-between gap-4">
                                <div class="text-xl font-black text-brand-primary">
                                    $<?php echo number_format($row['p_current_price'], 2); ?>
                                </div>
                                <a href="product.php?id=<?php echo $row['p_id']; ?>" class="bg-gray-900 text-white w-12 h-12 flex items-center justify-center rounded-2xl hover:bg-brand-primary transition-all shadow-lg shrink-0">
                                    <i class="fa fa-shopping-cart"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
}
?>

<?php if($home_featured_product_on_off == 1): ?>
    <?php
    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_is_featured=? AND p_is_active=? LIMIT ".$total_featured_product_home);
    $statement->execute(array(1,1));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    render_product_grid($featured_product_title, $featured_product_subtitle, $result);
    ?>
<?php endif; ?>


<?php if($home_latest_product_on_off == 1): ?>
    <?php
    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_is_active=? ORDER BY p_id DESC LIMIT ".$total_latest_product_home);
    $statement->execute(array(1));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    render_product_grid($latest_product_title, $latest_product_subtitle, $result);
    ?>
<?php endif; ?>


<?php if($home_popular_product_on_off == 1): ?>
    <?php
    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_is_active=? ORDER BY p_total_view DESC LIMIT ".$total_popular_product_home);
    $statement->execute(array(1));
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    render_product_grid($popular_product_title, $popular_product_subtitle, $result);
    ?>
<?php endif; ?>




<?php require_once('footer.php'); ?>
