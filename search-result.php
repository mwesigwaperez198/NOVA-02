<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['search_text'])) {
    header('location: index.php');
    exit;
} else {
	if($_REQUEST['search_text']=='') {
		header('location: index.php');
    	exit;
	}
}
?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_search = $row['banner_search'];
}
?>

<div class="py-12 bg-white border-b">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl md:text-5xl font-black text-gray-900 mb-4 uppercase tracking-tight">
            Search: <span class="text-brand-primary"><?php echo strip_tags($_REQUEST['search_text']); ?></span>
        </h1>
        <div class="w-20 h-1.5 bg-brand-primary mx-auto rounded-full"></div>
    </div>
</div>

<div class="py-16 bg-gray-50 min-h-[600px]">
    <div class="container mx-auto px-4">
        <div class="max-w-7xl mx-auto">
            
            <?php
            $search_text = strip_tags($_REQUEST['search_text']);
            $search_param = '%'.$search_text.'%';
            
            // Pagination
            $adjacents = 5;
            $statement = $pdo->prepare("SELECT p_id FROM tbl_product WHERE p_is_active=? AND p_name LIKE ?");
            $statement->execute(array(1, $search_param));
            $total_pages = $statement->rowCount();

            $limit = 12;
            $page = @$_GET['page'] ?: 1;
            $start = ($page - 1) * $limit;

            $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_is_active=? AND p_name LIKE ? LIMIT $start, $limit");
            $statement->execute(array(1, $search_param));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);

            if(!$total_pages): ?>
                <div class="bg-white rounded-3xl p-12 text-center shadow-sm border border-gray-100">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa fa-search text-3xl text-gray-300"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">No results found</h2>
                    <p class="text-gray-500">We couldn't find any products matching "<?php echo $search_text; ?>". Try different keywords.</p>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <?php foreach ($result as $row): ?>
                        <div class="group bg-white rounded-3xl border border-gray-100 overflow-hidden hover:shadow-2xl transition-all duration-500">
                            <div class="relative aspect-square overflow-hidden bg-gray-100">
                                <img src="assets/uploads/<?php echo $row['p_featured_photo']; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="<?php echo $row['p_name']; ?>">
                                <div class="absolute inset-0 bg-black/5 group-hover:bg-black/0 transition-colors"></div>
                                <?php if($row['p_qty'] == 0): ?>
                                    <div class="absolute top-4 left-4 bg-gray-900/80 backdrop-blur-sm text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Out Of Stock</div>
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
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if($total_pages > $limit): ?>
                    <div class="mt-16 flex justify-center">
                        <nav class="flex items-center gap-2 bg-white px-4 py-2 rounded-2xl shadow-sm border border-gray-100">
                            <?php
                            $lastpage = ceil($total_pages/$limit);
                            $targetpage = "search-result.php?search_text=".urlencode($search_text);
                            
                            if ($page > 1): ?>
                                <a href="<?php echo $targetpage; ?>&page=<?php echo $page-1; ?>" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-gray-50 text-gray-500 transition-colors">
                                    <i class="fa fa-angle-left"></i>
                                </a>
                            <?php endif; ?>

                            <?php for($counter = 1; $counter <= $lastpage; $counter++): ?>
                                <?php if($counter == $page): ?>
                                    <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-brand-primary text-white font-bold"><?php echo $counter; ?></span>
                                <?php else: ?>
                                    <a href="<?php echo $targetpage; ?>&page=<?php echo $counter; ?>" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-gray-50 text-gray-700 transition-colors font-medium"><?php echo $counter; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($page < $lastpage): ?>
                                <a href="<?php echo $targetpage; ?>&page=<?php echo $page+1; ?>" class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-gray-50 text-gray-500 transition-colors">
                                    <i class="fa fa-angle-right"></i>
                                </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>