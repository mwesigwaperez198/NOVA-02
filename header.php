<?php
ob_start();
session_start();
include("admin/inc/config.php");
include("admin/inc/functions.php");
include("admin/inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();
$error_message = '';
$success_message = '';
$error_message1 = '';
$success_message1 = '';

$statement = $pdo->prepare("SELECT * FROM tbl_language");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	define('LANG_VALUE_'.$row['lang_id'],$row['lang_value']);
}

$statement = $pdo->prepare("SELECT * FROM tbl_settings_view WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$logo = $row['logo'];
	$favicon = $row['favicon'];
	$contact_email = $row['contact_email'];
	$contact_phone = $row['contact_phone'];
	$meta_title_home = $row['meta_title_home'];
	$meta_keyword_home = $row['meta_keyword_home'];
	$meta_description_home = $row['meta_description_home'];
	$before_head = $row['before_head'];
	$after_body = $row['after_body'];
	$before_body = $row['before_body'];
}

$cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width,initial-scale=1.0,viewport-fit=cover"/>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>assets/uploads/<?php echo $favicon; ?>">
	<link rel="manifest" href="<?php echo BASE_URL; ?>manifest.json">
	<meta name="theme-color" content="#131921">
	<meta name="mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="apple-mobile-web-app-title" content="QuickShop">
	<link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>assets/uploads/<?php echo $favicon; ?>">

	<?php
	$statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
	$statement->execute();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	foreach ($result as $row) {
		$about_meta_title = $row['about_meta_title'];
		$about_meta_keyword = $row['about_meta_keyword'];
		$about_meta_description = $row['about_meta_description'];
		$faq_meta_title = $row['faq_meta_title'];
		$faq_meta_keyword = $row['faq_meta_keyword'];
		$faq_meta_description = $row['faq_meta_description'];
		$contact_meta_title = $row['contact_meta_title'];
		$contact_meta_keyword = $row['contact_meta_keyword'];
		$contact_meta_description = $row['contact_meta_description'];
		$about_title = $row['about_title'];
		$faq_title = $row['faq_title'];
		$contact_title = $row['contact_title'];
	}

	if(in_array($cur_page, ['index.php','login.php','registration.php','cart.php','checkout.php','forget-password.php','reset-password.php','product-category.php','product.php','dashboard.php','search-result.php',''])) {
		echo "<title>".htmlspecialchars($meta_title_home)."</title>";
		echo "<meta name='keywords' content='".htmlspecialchars($meta_keyword_home)."'>";
		echo "<meta name='description' content='".htmlspecialchars($meta_description_home)."'>";
	} elseif($cur_page == 'about.php') {
		echo "<title>".htmlspecialchars($about_meta_title)."</title>";
	} elseif($cur_page == 'faq.php') {
		echo "<title>".htmlspecialchars($faq_meta_title)."</title>";
	} elseif($cur_page == 'contact.php') {
		echo "<title>".htmlspecialchars($contact_meta_title)."</title>";
	}

	if($cur_page == 'product.php' && isset($_REQUEST['id'])) {
		$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
		$statement->execute(array($_REQUEST['id']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $row) {
			$og_photo = $row['p_featured_photo'];
			$og_title = $row['p_name'];
			$og_slug = 'product.php?id='.$_REQUEST['id'];
			$og_description = substr(strip_tags($row['p_description']),0,200).'...';
		}
	}
	?>

	<?php if($cur_page == 'product.php' && isset($og_title)): ?>
		<meta property="og:title" content="<?php echo htmlspecialchars($og_title); ?>">
		<meta property="og:type" content="website">
		<meta property="og:url" content="<?php echo BASE_URL.$og_slug; ?>">
		<meta property="og:description" content="<?php echo htmlspecialchars($og_description); ?>">
		<meta property="og:image" content="<?php echo BASE_URL; ?>assets/uploads/<?php echo $og_photo; ?>">
	<?php endif; ?>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/main-tailwind.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/font-awesome.min.css">

	<style>
		* { font-family: 'Inter', sans-serif; }
		body { background: #f3f4f6; padding-bottom: 70px; }
		@media(min-width:768px){ body { padding-bottom: 0; } }
		.app-header { background: #131921; }
		.app-search { background: #febd69; border-radius: 4px; }
		.app-search input { background: white; border-radius: 3px 0 0 3px; }
		.app-search button { background: #febd69; border-radius: 0 3px 3px 0; }
		.app-search button:hover { background: #f3a847; }
		.bottom-nav { position: fixed; bottom: 0; left: 0; right: 0; background: white; border-top: 1px solid #e5e7eb; z-index: 100; display: flex; }
		@media(min-width:768px){ .bottom-nav { display: none; } }
		.bottom-nav a { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 8px 4px; font-size: 10px; color: #6b7280; text-decoration: none; gap: 3px; }
		.bottom-nav a.active, .bottom-nav a:hover { color: #131921; }
		.bottom-nav a i { font-size: 20px; }
		.cart-badge { position: relative; }
		.cart-badge .badge { position: absolute; top: -6px; right: -8px; background: #f90; color: #131921; font-size: 9px; font-weight: 800; padding: 1px 4px; border-radius: 10px; }
		.category-pill { display: inline-flex; align-items: center; gap: 6px; background: white; border: 1px solid #e5e7eb; border-radius: 20px; padding: 6px 14px; font-size: 13px; font-weight: 500; color: #374151; white-space: nowrap; cursor: pointer; transition: all .2s; text-decoration: none; }
		.category-pill:hover, .category-pill.active { background: #131921; color: white; border-color: #131921; }
		.product-card { background: white; border-radius: 8px; overflow: hidden; transition: box-shadow .2s; display: flex; flex-direction: column; }
		.product-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.12); }
		.product-card img { width: 100%; aspect-ratio: 1; object-fit: cover; }
		.prime-badge { background: #00a8e0; color: white; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 3px; }
		.add-to-cart-btn { background: #ffd814; color: #0f1111; font-weight: 600; border: none; border-radius: 20px; padding: 8px 16px; font-size: 13px; cursor: pointer; transition: background .2s; width: 100%; }
		.add-to-cart-btn:hover { background: #f7ca00; }
		.section-title { font-size: 20px; font-weight: 700; color: #0f1111; }
		.deals-banner { background: linear-gradient(135deg, #131921 0%, #232f3e 100%); }
		.star-rating { color: #f90; font-size: 12px; }
		.price-tag { color: #b12704; font-weight: 700; font-size: 16px; }
		.original-price { color: #888; text-decoration: line-through; font-size: 12px; }
		.scroll-x { overflow-x: auto; scrollbar-width: none; -ms-overflow-style: none; }
		.scroll-x::-webkit-scrollbar { display: none; }
	</style>

	<?php echo $before_head; ?>
</head>
<body>

<?php echo $after_body; ?>

<!-- App Header -->
<header class="app-header sticky top-0 z-50">
	<!-- Top bar: Logo + Search + Account + Cart -->
	<div class="px-3 py-2 flex items-center gap-2">
		<!-- Logo -->
		<a href="<?php echo BASE_URL; ?>index.php" class="shrink-0 mr-1">
			<img src="<?php echo BASE_URL; ?>assets/uploads/<?php echo $logo; ?>" alt="QuickShop" class="h-8 w-auto brightness-0 invert">
		</a>

		<!-- Search Bar -->
		<form class="flex-1 flex app-search" action="<?php echo BASE_URL; ?>search-result.php" method="get">
			<?php $csrf->echoInputField(); ?>
			<input type="text" name="search_text" placeholder="Search products, brands..." class="flex-1 px-3 py-2 text-sm outline-none min-w-0">
			<button type="submit" class="px-3 py-2 shrink-0">
				<i class="fa fa-search text-gray-800"></i>
			</button>
		</form>

		<!-- Account (desktop) -->
		<a href="<?php echo isset($_SESSION['customer']) ? BASE_URL.'dashboard.php' : BASE_URL.'login.php'; ?>" class="hidden md:flex flex-col items-start text-white shrink-0">
			<span class="text-xs text-gray-300"><?php echo isset($_SESSION['customer']) ? 'Hello, '.htmlspecialchars($_SESSION['customer']['cust_name']) : 'Hello, sign in'; ?></span>
			<span class="text-sm font-bold">Account</span>
		</a>

		<!-- Cart (desktop) -->
		<a href="<?php echo BASE_URL; ?>cart.php" class="hidden md:flex items-center gap-1 text-white shrink-0">
			<div class="relative">
				<i class="fa fa-shopping-cart text-2xl"></i>
				<span class="absolute -top-2 -right-2 bg-yellow-400 text-gray-900 text-[10px] font-black px-1.5 rounded-full">
					<?php echo isset($_SESSION['cart_p_id']) ? count($_SESSION['cart_p_id']) : '0'; ?>
				</span>
			</div>
			<span class="text-sm font-bold">Cart</span>
		</a>
	</div>

	<!-- Category Nav (desktop) -->
	<nav class="hidden md:block bg-gray-800 px-4">
		<div class="flex items-center gap-1 py-1 overflow-x-auto scrollbar-hide">
			<a href="<?php echo BASE_URL; ?>index.php" class="text-white text-sm px-3 py-1.5 rounded hover:bg-gray-600 whitespace-nowrap font-medium">All</a>
			<?php
			$statement = $pdo->prepare("SELECT * FROM tbl_top_category WHERE show_on_menu=1 LIMIT 8");
			$statement->execute();
			$cats = $statement->fetchAll(PDO::FETCH_ASSOC);
			foreach ($cats as $cat): ?>
				<a href="<?php echo BASE_URL; ?>product-category.php?id=<?php echo $cat['tcat_id']; ?>&type=top-category" class="text-white text-sm px-3 py-1.5 rounded hover:bg-gray-600 whitespace-nowrap"><?php echo htmlspecialchars($cat['tcat_name']); ?></a>
			<?php endforeach; ?>
			<a href="<?php echo BASE_URL; ?>about.php" class="text-white text-sm px-3 py-1.5 rounded hover:bg-gray-600 whitespace-nowrap"><?php echo htmlspecialchars($about_title ?? 'About'); ?></a>
			<a href="<?php echo BASE_URL; ?>contact.php" class="text-white text-sm px-3 py-1.5 rounded hover:bg-gray-600 whitespace-nowrap"><?php echo htmlspecialchars($contact_title ?? 'Contact'); ?></a>
		</div>
	</nav>
</header>

<!-- Mobile Bottom Nav -->
<nav class="bottom-nav md:hidden">
	<a href="<?php echo BASE_URL; ?>index.php" class="<?php echo $cur_page == 'index.php' || $cur_page == '' ? 'active' : ''; ?>">
		<i class="fa fa-home"></i>Home
	</a>
	<a href="<?php echo BASE_URL; ?>product-category.php?type=all" class="<?php echo $cur_page == 'product-category.php' ? 'active' : ''; ?>">
		<i class="fa fa-th-large"></i>Categories
	</a>
	<a href="<?php echo BASE_URL; ?>search-result.php" class="<?php echo $cur_page == 'search-result.php' ? 'active' : ''; ?>">
		<i class="fa fa-search"></i>Search
	</a>
	<a href="<?php echo BASE_URL; ?>cart.php" class="cart-badge <?php echo $cur_page == 'cart.php' ? 'active' : ''; ?>">
		<i class="fa fa-shopping-cart"></i>
		<span class="badge"><?php echo isset($_SESSION['cart_p_id']) ? count($_SESSION['cart_p_id']) : '0'; ?></span>
		Cart
	</a>
	<a href="<?php echo isset($_SESSION['customer']) ? BASE_URL.'dashboard.php' : BASE_URL.'login.php'; ?>" class="<?php echo in_array($cur_page, ['dashboard.php','login.php','registration.php']) ? 'active' : ''; ?>">
		<i class="fa fa-user"></i><?php echo isset($_SESSION['customer']) ? 'Account' : 'Sign In'; ?>
	</a>
</nav>
