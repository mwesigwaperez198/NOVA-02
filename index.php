<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings_view WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
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
    $home_featured_product_on_off = $row['home_featured_product_on_off'];
    $home_latest_product_on_off = $row['home_latest_product_on_off'];
    $home_popular_product_on_off = $row['home_popular_product_on_off'];
}
?>

<!-- Hero Banner Slider -->
<div class="relative overflow-hidden" style="background:#131921; max-height:420px;">
    <?php
    $statement = $pdo->prepare("SELECT * FROM tbl_slider LIMIT 3");
    $statement->execute();
    $sliders = $statement->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($sliders)):
        $first = $sliders[0];
    ?>
    <div id="hero-slider" class="relative">
        <?php foreach ($sliders as $i => $slide): ?>
        <div class="hero-slide <?php echo $i > 0 ? 'hidden' : ''; ?> relative" data-index="<?php echo $i; ?>">
            <img src="<?php echo BASE_URL; ?>assets/uploads/<?php echo $slide['photo']; ?>"
                 class="w-full object-cover"
                 style="max-height:420px; min-height:200px;"
                 alt="<?php echo htmlspecialchars($slide['heading']); ?>"
                 onerror="this.style.display='none'">
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/30 to-transparent flex items-center">
                <div class="px-6 md:px-16 max-w-xl">
                    <h1 class="text-white text-2xl md:text-4xl font-extrabold leading-tight mb-3">
                        <?php echo htmlspecialchars($slide['heading']); ?>
                    </h1>
                    <p class="text-gray-200 text-sm md:text-base mb-5 line-clamp-2">
                        <?php echo nl2br(htmlspecialchars($slide['content'])); ?>
                    </p>
                    <a href="<?php echo htmlspecialchars($slide['button_url']); ?>"
                       class="inline-block bg-yellow-400 text-gray-900 font-bold px-6 py-3 rounded-full text-sm hover:bg-yellow-300 transition-all">
                        <?php echo htmlspecialchars($slide['button_text']); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if(count($sliders) > 1): ?>
        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-2">
            <?php foreach ($sliders as $i => $s): ?>
            <button class="hero-dot w-2 h-2 rounded-full transition-all <?php echo $i == 0 ? 'bg-yellow-400 w-5' : 'bg-white/50'; ?>" data-dot="<?php echo $i; ?>"></button>
            <?php endforeach; ?>
        </div>
        <button id="hero-prev" class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-black/70"><i class="fa fa-angle-left"></i></button>
        <button id="hero-next" class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-black/70"><i class="fa fa-angle-right"></i></button>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="deals-banner text-center py-16 px-4">
        <h1 class="text-white text-3xl font-extrabold mb-2">Welcome to QuickShop.Ug</h1>
        <p class="text-gray-300 mb-6">Uganda's Marketplace — Everything You Need</p>
        <a href="product-category.php?type=all" class="inline-block bg-yellow-400 text-gray-900 font-bold px-8 py-3 rounded-full">Shop Now</a>
    </div>
    <?php endif; ?>
</div>

<!-- Category Pills (horizontal scroll) -->
<div class="bg-white border-b px-3 py-3">
    <div class="scroll-x flex gap-2">
        <a href="<?php echo BASE_URL; ?>product-category.php?type=all" class="category-pill">
            <i class="fa fa-th-large text-xs"></i> All
        </a>
        <?php
        $statement = $pdo->prepare("SELECT * FROM tbl_top_category WHERE show_on_menu=1");
        $statement->execute();
        $topcats = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach ($topcats as $cat): ?>
        <a href="<?php echo BASE_URL; ?>product-category.php?id=<?php echo $cat['tcat_id']; ?>&type=top-category" class="category-pill">
            <?php echo htmlspecialchars($cat['tcat_name']); ?>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- Services Strip -->
<?php if($home_service_on_off == 1): ?>
<?php
$statement = $pdo->prepare("SELECT * FROM tbl_service LIMIT 4");
$statement->execute();
$services = $statement->fetchAll(PDO::FETCH_ASSOC);
if (!empty($services)):
?>
<div class="bg-white mt-2 px-3 py-4">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <?php foreach ($services as $svc): ?>
        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
            <img src="<?php echo BASE_URL; ?>assets/uploads/<?php echo $svc['photo']; ?>"
                 class="w-10 h-10 object-contain shrink-0"
                 alt="<?php echo htmlspecialchars($svc['title']); ?>"
                 onerror="this.src='<?php echo BASE_URL; ?>assets/img/icon.png'">
            <div>
                <div class="text-xs font-bold text-gray-800 line-clamp-1"><?php echo htmlspecialchars($svc['title']); ?></div>
                <div class="text-xs text-gray-500 line-clamp-1"><?php echo htmlspecialchars(strip_tags($svc['content'])); ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
<?php endif; ?>

<?php
function render_app_product_section($title, $products, $view_all_url = '') {
    global $pdo;
    if (empty($products)) return;
    ?>
    <div class="mt-3 bg-white px-3 pt-4 pb-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="section-title"><?php echo htmlspecialchars($title); ?></h2>
            <?php if ($view_all_url): ?>
            <a href="<?php echo $view_all_url; ?>" class="text-sm text-blue-600 font-medium hover:underline">See all</a>
            <?php endif; ?>
        </div>
        <div class="scroll-x flex gap-3 md:grid md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6">
            <?php foreach ($products as $row):
                $t_rating = 0;
                $stmt1 = $pdo->prepare("SELECT AVG(rating) as avg_r, COUNT(*) as cnt FROM tbl_rating WHERE p_id=?");
                $stmt1->execute([$row['p_id']]);
                $rdata = $stmt1->fetch(PDO::FETCH_ASSOC);
                $avg_rating = round($rdata['avg_r'] ?? 0);
                $rating_count = $rdata['cnt'] ?? 0;
            ?>
            <div class="product-card shrink-0 w-40 md:w-auto">
                <a href="<?php echo BASE_URL; ?>product.php?id=<?php echo $row['p_id']; ?>" class="block relative">
                    <img src="<?php echo BASE_URL; ?>assets/uploads/<?php echo $row['p_featured_photo']; ?>"
                         alt="<?php echo htmlspecialchars($row['p_name']); ?>"
                         class="w-full aspect-square object-cover"
                         onerror="this.src='<?php echo BASE_URL; ?>assets/img/icon.png'">
                    <?php if($row['p_qty'] == 0): ?>
                    <div class="absolute top-2 left-2 bg-gray-800/80 text-white text-[10px] font-bold px-2 py-0.5 rounded">Out of Stock</div>
                    <?php endif; ?>
                    <?php if(!empty($row['p_previous_price']) && $row['p_previous_price'] > $row['p_current_price']): ?>
                    <div class="absolute top-2 right-2 bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded">
                        -<?php echo round((($row['p_previous_price'] - $row['p_current_price']) / $row['p_previous_price']) * 100); ?>%
                    </div>
                    <?php endif; ?>
                </a>
                <div class="p-2 flex flex-col gap-1 flex-1">
                    <a href="<?php echo BASE_URL; ?>product.php?id=<?php echo $row['p_id']; ?>" class="text-xs font-medium text-gray-800 line-clamp-2 leading-tight hover:text-blue-600">
                        <?php echo htmlspecialchars($row['p_name']); ?>
                    </a>
                    <?php if ($rating_count > 0): ?>
                    <div class="flex items-center gap-1">
                        <div class="star-rating">
                            <?php for($i=1;$i<=5;$i++) echo $i<=$avg_rating ? '★' : '☆'; ?>
                        </div>
                        <span class="text-[10px] text-gray-500">(<?php echo $rating_count; ?>)</span>
                    </div>
                    <?php endif; ?>
                    <div class="flex items-baseline gap-1 flex-wrap">
                        <span class="price-tag">UGX <?php echo number_format($row['p_current_price']); ?></span>
                        <?php if(!empty($row['p_previous_price']) && $row['p_previous_price'] > $row['p_current_price']): ?>
                        <span class="original-price">UGX <?php echo number_format($row['p_previous_price']); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if($row['p_qty'] > 0): ?>
                    <a href="<?php echo BASE_URL; ?>product.php?id=<?php echo $row['p_id']; ?>" class="add-to-cart-btn mt-1 text-center block">Add to Cart</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}
?>

<!-- Deals of the Day Banner -->
<div class="deals-banner mt-3 px-4 py-4 flex items-center justify-between">
    <div>
        <div class="text-yellow-400 text-xs font-bold uppercase tracking-widest mb-1">🔥 Deals of the Day</div>
        <div class="text-white text-lg font-extrabold">Best Prices in Uganda</div>
        <div class="text-gray-300 text-xs mt-1">Limited time offers across all categories</div>
    </div>
    <a href="<?php echo BASE_URL; ?>product-category.php?type=all" class="bg-yellow-400 text-gray-900 font-bold px-4 py-2 rounded-full text-sm shrink-0 hover:bg-yellow-300">Shop Now</a>
</div>

<?php if($home_featured_product_on_off == 1): ?>
<?php
$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_is_featured=1 AND p_is_active=1 LIMIT ".(int)$total_featured_product_home);
$statement->execute();
$featured = $statement->fetchAll(PDO::FETCH_ASSOC);
render_app_product_section($featured_product_title ?: 'Featured Products', $featured, BASE_URL.'product-category.php?type=all');
?>
<?php endif; ?>

<?php if($home_latest_product_on_off == 1): ?>
<?php
$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_is_active=1 ORDER BY p_id DESC LIMIT ".(int)$total_latest_product_home);
$statement->execute();
$latest = $statement->fetchAll(PDO::FETCH_ASSOC);
render_app_product_section($latest_product_title ?: 'New Arrivals', $latest, BASE_URL.'product-category.php?type=all');
?>
<?php endif; ?>

<!-- Mid-page promo banner -->
<div class="mt-3 mx-3 rounded-xl overflow-hidden bg-gradient-to-r from-blue-600 to-blue-800 p-5 flex items-center justify-between">
    <div>
        <div class="text-white font-extrabold text-lg">Free Delivery</div>
        <div class="text-blue-200 text-sm">On orders above UGX 50,000</div>
    </div>
    <i class="fa fa-truck text-white text-4xl opacity-60"></i>
</div>

<?php if($home_popular_product_on_off == 1): ?>
<?php
$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_is_active=1 ORDER BY p_total_view DESC LIMIT ".(int)$total_popular_product_home);
$statement->execute();
$popular = $statement->fetchAll(PDO::FETCH_ASSOC);
render_app_product_section($popular_product_title ?: 'Most Popular', $popular, BASE_URL.'product-category.php?type=all');
?>
<?php endif; ?>

<!-- All Categories Grid -->
<?php if (!empty($topcats)): ?>
<div class="mt-3 bg-white px-3 pt-4 pb-5">
    <h2 class="section-title mb-3">Shop by Category</h2>
    <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
        <?php foreach ($topcats as $cat): ?>
        <a href="<?php echo BASE_URL; ?>product-category.php?id=<?php echo $cat['tcat_id']; ?>&type=top-category"
           class="flex flex-col items-center gap-2 p-3 bg-gray-50 rounded-xl hover:bg-yellow-50 hover:border-yellow-300 border border-transparent transition-all text-center">
            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                <i class="fa fa-tag text-gray-600"></i>
            </div>
            <span class="text-xs font-semibold text-gray-700 line-clamp-2 leading-tight"><?php echo htmlspecialchars($cat['tcat_name']); ?></span>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<script>
// Hero Slider
(function(){
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    if (slides.length <= 1) return;
    let cur = 0;
    function go(n) {
        slides[cur].classList.add('hidden');
        dots[cur].classList.remove('bg-yellow-400','w-5');
        dots[cur].classList.add('bg-white/50');
        cur = (n + slides.length) % slides.length;
        slides[cur].classList.remove('hidden');
        dots[cur].classList.add('bg-yellow-400','w-5');
        dots[cur].classList.remove('bg-white/50');
    }
    document.getElementById('hero-next')?.addEventListener('click', () => go(cur+1));
    document.getElementById('hero-prev')?.addEventListener('click', () => go(cur-1));
    dots.forEach(d => d.addEventListener('click', () => go(+d.dataset.dot)));
    setInterval(() => go(cur+1), 5000);
})();
</script>

<?php require_once('footer.php'); ?>
