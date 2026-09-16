<!-- This is main configuration File -->
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

// Getting all language variables into array
$statement = $pdo->prepare("SELECT * FROM tbl_language");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	define('LANG_VALUE_'.$row['lang_id'],$row['lang_value']);
}

$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
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

// Check if a page is on or off from the database
$cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
?>
<!DOCTYPE html>
<html lang="en">
<head>

	<!-- Meta Tags -->
	<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>

	<!-- Favicon -->
	<link rel="icon" type="image/png" href="assets/uploads/<?php echo $favicon; ?>">

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
	}

	if($cur_page == 'index.php' || $cur_page == 'login.php' || $cur_page == 'registration.php' || $cur_page == 'cart.php' || $cur_page == 'checkout.php' || $cur_page == 'forget-password.php' || $cur_page == 'reset-password.php' || $cur_page == 'product-category.php' || $cur_page == 'product.php') {
		?>
		<title><?php echo $meta_title_home; ?></title>
		<meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
		<meta name="description" content="<?php echo $meta_description_home; ?>">
		<?php
	}
	if($cur_page == 'about.php') {
		?>
		<title><?php echo $about_meta_title; ?></title>
		<meta name="keywords" content="<?php echo $about_meta_keyword; ?>">
		<meta name="description" content="<?php echo $about_meta_description; ?>">
		<?php
	}
	if($cur_page == 'faq.php') {
		?>
		<title><?php echo $faq_meta_title; ?></title>
		<meta name="keywords" content="<?php echo $faq_meta_keyword; ?>">
		<meta name="description" content="<?php echo $faq_meta_description; ?>">
		<?php
	}
	if($cur_page == 'contact.php') {
		?>
		<title><?php echo $contact_meta_title; ?></title>
		<meta name="keywords" content="<?php echo $contact_meta_keyword; ?>">
		<meta name="description" content="<?php echo $contact_meta_description; ?>">
		<?php
	}
	if($cur_page == 'product.php') {
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
	if($cur_page == 'dashboard.php') {
		?>
		<title>Dashboard - <?php echo $meta_title_home; ?></title>
		<meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
		<meta name="description" content="<?php echo $meta_description_home; ?>">
		<?php
	}
	?>
	
	<?php if($cur_page == 'product.php'): ?>
		<meta property="og:title" content="<?php echo $og_title; ?>">
		<meta property="og:type" content="website">
		<meta property="og:url" content="<?php echo BASE_URL.$og_slug; ?>">
		<meta property="og:description" content="<?php echo $og_description; ?>">
		<meta property="og:image" content="assets/uploads/<?php echo $og_photo; ?>">
	<?php endif; ?>

	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

	<!-- Stylesheets -->
	<link rel="stylesheet" href="assets/css/main-tailwind.css">
	<link rel="stylesheet" href="assets/css/font-awesome.min.css">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>

<?php echo $before_head; ?>

</head>
<body class="bg-gray-50">

<?php echo $after_body; ?>

<!-- top bar -->
<div class="bg-gray-900 text-white py-2 hidden md:block">
	<div class="container mx-auto px-4">
		<div class="flex justify-between items-center text-sm">
			<div class="flex space-x-6">
				<div class="flex items-center gap-2">
					<i class="fa fa-phone text-brand-primary"></i> 
					<span><?php echo $contact_phone; ?></span>
				</div>
				<div class="flex items-center gap-2">
					<i class="fa fa-envelope-o text-brand-primary"></i> 
					<span><?php echo $contact_email; ?></span>
				</div>
			</div>
			<div>
				<ul class="flex space-x-4">
					<?php
					$statement = $pdo->prepare("SELECT * FROM tbl_social");
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					foreach ($result as $row) {
						if($row['social_url'] != ''): ?>
						<li><a href="<?php echo $row['social_url']; ?>" class="hover:text-brand-primary transition-colors"><i class="<?php echo $row['social_icon']; ?>"></i></a></li>
						<?php endif;
					}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="bg-white border-b sticky top-0 z-50">
	<div class="container mx-auto px-4 py-4">
		
		<!-- Desktop Header (md and up) -->
		<div class="hidden md:flex items-center justify-between gap-8">
			<div class="shrink-0">
				<a href="index.php" class="block">
					<img src="assets/uploads/<?php echo $logo; ?>" alt="logo image" class="h-12 w-auto">
				</a>
			</div>
			<div class="flex-1 max-w-xl">
				<form class="relative" action="search-result.php" method="get">
					<?php $csrf->echoInputField(); ?>
					<input type="text" class="w-full border rounded-full px-6 py-4 focus:ring-2 focus:ring-brand-primary focus:border-transparent outline-none transition-all" placeholder="<?php echo LANG_VALUE_2; ?>" name="search_text">
					<button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-brand-primary text-white w-10 h-10 rounded-full hover:bg-red-600 transition-all flex items-center justify-center">
						<i class="fa fa-search"></i>
					</button>
				</form>
			</div>
			<div class="flex items-center space-x-6">
				<div class="flex items-center space-x-4">
					<?php if(isset($_SESSION['customer'])): ?>
						<div class="hidden lg:block text-sm text-right">
							<span class="text-gray-500 uppercase text-[10px] font-bold tracking-widest block mb-0.5"><?php echo LANG_VALUE_13; ?></span>
							<span class="font-black text-gray-900"><?php echo htmlspecialchars($_SESSION['customer']['cust_name'], ENT_QUOTES, 'UTF-8'); ?></span>
						</div>
						<a href="dashboard.php" class="text-gray-600 hover:text-brand-primary transition-colors">
							<i class="fa fa-user-circle-o text-2xl"></i>
						</a>
					<?php else: ?>
						<a href="login.php" class="text-gray-600 hover:text-brand-primary transition-colors flex items-center gap-2">
							<i class="fa fa-sign-in text-xl"></i>
							<span class="hidden sm:inline text-xs font-black uppercase tracking-widest"><?php echo LANG_VALUE_9; ?></span>
						</a>
						<a href="registration.php" class="text-gray-600 hover:text-brand-primary transition-colors flex items-center gap-2">
							<i class="fa fa-user-plus text-xl"></i>
							<span class="hidden sm:inline text-xs font-black uppercase tracking-widest"><?php echo LANG_VALUE_15; ?></span>
						</a>
					<?php endif; ?>
				</div>
				<a href="cart.php" class="relative group">
					<div class="bg-gray-100 p-3 rounded-full group-hover:bg-brand-primary transition-colors">
						<i class="fa fa-shopping-cart text-gray-700 group-hover:text-white transition-colors"></i>
					</div>
					<span class="absolute -top-2 -right-2 bg-brand-primary text-white text-[10px] font-black px-2 py-0.5 rounded-full border-2 border-white">
						<?php echo isset($_SESSION['cart_p_id']) ? count($_SESSION['cart_p_id']) : '0'; ?>
					</span>
				</a>
			</div>
		</div>

		<!-- Mobile Header (below md) -->
		<div class="md:hidden space-y-4">
			<div class="flex items-center">
				<div class="w-1/3 flex justify-start">
					<button class="text-gray-600 focus:outline-none p-2 -ml-2" id="mobile-menu-button">
						<i class="fa fa-bars text-2xl"></i>
					</button>
				</div>
				<div class="w-1/3 flex justify-center">
					<a href="index.php" class="block">
						<img src="assets/uploads/<?php echo $logo; ?>" alt="logo image" class="h-10 w-auto">
					</a>
				</div>
				<div class="w-1/3 flex justify-end items-center space-x-4">
					<a href="<?php echo isset($_SESSION['customer']) ? 'dashboard.php' : 'login.php'; ?>" class="text-gray-600 hover:text-brand-primary transition-colors">
						<i class="fa <?php echo isset($_SESSION['customer']) ? 'fa-user-circle-o' : 'fa-sign-in'; ?> text-2xl"></i>
					</a>
					<a href="cart.php" class="relative">
						<div class="bg-gray-100 p-2 rounded-full">
							<i class="fa fa-shopping-cart text-gray-700"></i>
						</div>
						<span class="absolute -top-1.5 -right-1.5 bg-brand-primary text-white text-[10px] font-black px-1.5 py-0.5 rounded-full border-2 border-white">
							<?php echo isset($_SESSION['cart_p_id']) ? count($_SESSION['cart_p_id']) : '0'; ?>
						</span>
					</a>
				</div>
			</div>
			<div class="w-full">
				<form class="relative" action="search-result.php" method="get">
					<?php $csrf->echoInputField(); ?>
					<input type="text" class="w-full border rounded-full px-6 py-4 focus:ring-2 focus:ring-brand-primary focus:border-transparent outline-none transition-all text-sm" placeholder="<?php echo LANG_VALUE_2; ?>" name="search_text">
					<button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 bg-brand-primary text-white w-10 h-10 rounded-full flex items-center justify-center">
						<i class="fa fa-search"></i>
					</button>
				</form>
			</div>
		</div>
	</div>

	<!-- Mobile Menu Drawer -->
	<div id="mobile-menu-drawer" class="fixed inset-0 z-[100] invisible transition-all duration-300">
		<div class="absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300" id="mobile-menu-overlay"></div>
		<div class="absolute right-0 top-0 h-full w-80 bg-white shadow-2xl translate-x-full transition-transform duration-300 overflow-y-auto" id="mobile-menu-content">
			<div class="p-6 border-b flex justify-between items-center bg-gray-900">
				<span class="font-black text-white uppercase tracking-widest text-lg">Menu</span>
				<button class="text-gray-400 hover:text-brand-primary transition-colors" id="mobile-menu-close">
					<i class="fa fa-times text-2xl"></i>
				</button>
			</div>
			<nav class="p-6">
				<ul class="space-y-6">
					<li><a href="index.php" class="block font-black text-gray-900 hover:text-brand-primary uppercase tracking-wider">Home</a></li>
					<?php
					$statement = $pdo->prepare("SELECT * FROM tbl_top_category WHERE show_on_menu=1");
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					foreach ($result as $row) {
						?>
						<li class="space-y-4">
							<a href="product-category.php?id=<?php echo $row['tcat_id']; ?>&type=top-category" class="font-black text-gray-900 hover:text-brand-primary uppercase tracking-wider block border-b pb-2 border-gray-100"><?php echo $row['tcat_name']; ?></a>
							<ul class="ml-4 space-y-3">
								<?php
								$statement1 = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE tcat_id=?");
								$statement1->execute(array($row['tcat_id']));
								$result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
								foreach ($result1 as $row1) {
									?>
									<li><a href="product-category.php?id=<?php echo $row1['mcat_id']; ?>&type=mid-category" class="block text-sm font-bold text-gray-600 hover:text-brand-primary"><?php echo $row1['mcat_name']; ?></a></li>
									<?php
								}
								?>
							</ul>
						</li>
						<?php
					}
					?>
					<li class="pt-6 border-t border-gray-100 space-y-4">
						<?php
						$statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
						$statement->execute();
						$result = $statement->fetchAll(PDO::FETCH_ASSOC);		
						foreach ($result as $row) {
							$about_title = $row['about_title'];
							$faq_title = $row['faq_title'];
							$contact_title = $row['contact_title'];
						}
						?>
						<a href="about.php" class="block font-black text-gray-900 hover:text-brand-primary uppercase tracking-wider text-sm"><?php echo $about_title; ?></a>
						<a href="faq.php" class="block font-black text-gray-900 hover:text-brand-primary uppercase tracking-wider text-sm"><?php echo $faq_title; ?></a>
						<a href="contact.php" class="block font-black text-gray-900 hover:text-brand-primary uppercase tracking-wider text-sm"><?php echo $contact_title; ?></a>
					</li>
				</ul>
			</nav>
		</div>
	</div>

	<!-- Desktop Main Navigation -->
	<nav class="hidden md:block bg-gray-50 border-t">
		<div class="container mx-auto px-4">
			<ul class="flex items-center space-x-8 py-3">
				<li><a href="index.php" class="font-bold text-gray-700 hover:text-brand-primary transition-colors uppercase tracking-wider text-sm">Home</a></li>
				<?php
				$statement = $pdo->prepare("SELECT * FROM tbl_top_category WHERE show_on_menu=1");
				$statement->execute();
				$result = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach ($result as $row) {
					?>
					<li class="relative group">
						<a href="product-category.php?id=<?php echo $row['tcat_id']; ?>&type=top-category" class="font-bold text-gray-700 hover:text-brand-primary transition-colors uppercase tracking-wider text-sm flex items-center gap-1">
							<?php echo $row['tcat_name']; ?>
							<i class="fa fa-angle-down"></i>
						</a>
						<div class="absolute left-0 top-full mt-2 min-w-[14rem] bg-white border border-gray-100 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
							<ul class="py-2">
								<?php
								$statement1 = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE tcat_id=?");
								$statement1->execute(array($row['tcat_id']));
								$result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
								foreach ($result1 as $row1) {
									?>
									<li class="relative group/sub">
										<a href="product-category.php?id=<?php echo $row1['mcat_id']; ?>&type=mid-category" class="flex items-center justify-between px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-brand-primary">
											<?php echo $row1['mcat_name']; ?>
											<i class="fa fa-angle-right"></i>
										</a>
										<div class="absolute left-full top-0 ml-0.5 min-w-[14rem] bg-white border border-gray-100 shadow-xl opacity-0 invisible group-hover/sub:opacity-100 group-hover/sub:visible transition-all duration-300">
											<ul class="py-2">
												<?php
												$statement2 = $pdo->prepare("SELECT * FROM tbl_end_category WHERE mcat_id=?");
												$statement2->execute(array($row1['mcat_id']));
												$result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
												foreach ($result2 as $row2) {
													?>
													<li><a href="product-category.php?id=<?php echo $row2['ecat_id']; ?>&type=end-category" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-brand-primary"><?php echo $row2['ecat_name']; ?></a></li>
													<?php
												}
												?>
											</ul>
										</div>
									</li>
									<?php
								}
								?>
							</ul>
						</div>
					</li>
					<?php
				}
				?>
				<li><a href="about.php" class="font-bold text-gray-700 hover:text-brand-primary transition-colors uppercase tracking-wider text-sm"><?php echo $about_title; ?></a></li>
				<li><a href="faq.php" class="font-bold text-gray-700 hover:text-brand-primary transition-colors uppercase tracking-wider text-sm"><?php echo $faq_title; ?></a></li>
				<li><a href="contact.php" class="font-bold text-gray-700 hover:text-brand-primary transition-colors uppercase tracking-wider text-sm"><?php echo $contact_title; ?></a></li>
			</ul>
		</div>
	</nav>
</div>
