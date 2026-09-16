<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings_view WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$footer_about     = $row['footer_about'];
	$contact_email    = $row['contact_email'];
	$contact_phone    = $row['contact_phone'];
	$contact_address  = $row['contact_address'];
	$footer_copyright = $row['footer_copyright'];
	$newsletter_on_off = $row['newsletter_on_off'];
	$before_body      = $row['before_body'];
}
// Fallback contact info
if (empty($contact_email))   $contact_email   = 'mwesigwagershom7@gmail.com';
if (empty($footer_copyright)) $footer_copyright = '&copy; ' . date('Y') . ' QuickShop.Ug &mdash; Powered by NOVARA Technologies';
?>

<?php if($newsletter_on_off == 1): ?>
<section class="bg-gray-100 py-12 mt-3">
	<div class="max-w-xl mx-auto px-4 text-center">
		<?php
		if(isset($_POST['form_subscribe'])) {
			if(empty($_POST['email_subscribe'])) {
				$error_message1 .= 'Email is required.';
			} elseif(filter_var($_POST['email_subscribe'], FILTER_VALIDATE_EMAIL) === false) {
				$error_message1 .= 'Invalid email address.';
			} else {
				$statement = $pdo->prepare("SELECT * FROM tbl_subscriber WHERE subs_email=?");
				$statement->execute(array($_POST['email_subscribe']));
				if($statement->rowCount()) {
					$error_message1 .= 'This email is already subscribed.';
				} else {
					$key = md5(uniqid(rand(), true));
					$statement = $pdo->prepare("INSERT INTO tbl_subscriber (subs_email,subs_date,subs_date_time,subs_hash,subs_active) VALUES (?,?,?,?,?)");
					$statement->execute(array($_POST['email_subscribe'], date('Y-m-d'), date('Y-m-d H:i:s'), $key, 0));
					$success_message1 = 'Subscribed! Check your email to confirm.';
				}
			}
		}
		if(!empty($error_message1))   echo "<p class='text-red-600 mb-3 text-sm'>$error_message1</p>";
		if(!empty($success_message1)) echo "<p class='text-green-600 mb-3 text-sm'>$success_message1</p>";
		?>
		<h2 class="text-2xl font-bold text-gray-900 mb-1">Stay in the Loop</h2>
		<p class="text-gray-500 text-sm mb-5">Get the best deals delivered to your inbox.</p>
		<form action="" method="post" class="flex gap-2 max-w-md mx-auto">
			<?php $csrf->echoInputField(); ?>
			<input type="email" name="email_subscribe" placeholder="Your email address"
				   class="flex-1 px-4 py-3 border border-gray-300 rounded-lg text-sm outline-none focus:border-yellow-400">
			<button type="submit" name="form_subscribe"
					class="bg-yellow-400 text-gray-900 font-bold px-5 py-3 rounded-lg text-sm hover:bg-yellow-300 transition-all shrink-0">
				Subscribe
			</button>
		</form>
	</div>
</section>
<?php endif; ?>

<footer class="bg-gray-900 text-gray-400 mt-3">
	<div class="max-w-6xl mx-auto px-4 py-10">
		<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">

			<!-- Brand -->
			<div>
				<div class="text-white font-extrabold text-xl mb-1">QuickShop<span class="text-yellow-400">.Ug</span></div>
				<div class="text-xs text-gray-500 mb-3">Uganda's Marketplace</div>
				<p class="text-sm leading-relaxed">
					<?php echo !empty($footer_about) ? htmlspecialchars($footer_about) : 'Your one-stop marketplace for everything in Uganda. Quality products, fast delivery, best prices.'; ?>
				</p>
				<div class="mt-4 flex gap-3">
					<?php
					$statement = $pdo->prepare("SELECT * FROM tbl_social");
					$statement->execute();
					$socials = $statement->fetchAll(PDO::FETCH_ASSOC);
					foreach ($socials as $s) {
						if(!empty($s['social_url'])): ?>
						<a href="<?php echo htmlspecialchars($s['social_url']); ?>"
						   class="w-8 h-8 bg-gray-800 rounded-full flex items-center justify-center hover:bg-yellow-400 hover:text-gray-900 transition-all text-sm">
							<i class="<?php echo $s['social_icon']; ?>"></i>
						</a>
						<?php endif;
					} ?>
				</div>
			</div>

			<!-- Quick Links -->
			<div>
				<h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4">Quick Links</h4>
				<ul class="space-y-2 text-sm">
					<li><a href="<?php echo BASE_URL; ?>index.php" class="hover:text-yellow-400 transition-colors">Home</a></li>
					<li><a href="<?php echo BASE_URL; ?>product-category.php?type=all" class="hover:text-yellow-400 transition-colors">All Products</a></li>
					<li><a href="<?php echo BASE_URL; ?>about.php" class="hover:text-yellow-400 transition-colors">About Us</a></li>
					<li><a href="<?php echo BASE_URL; ?>faq.php" class="hover:text-yellow-400 transition-colors">FAQ</a></li>
					<li><a href="<?php echo BASE_URL; ?>contact.php" class="hover:text-yellow-400 transition-colors">Contact</a></li>
					<li><a href="<?php echo BASE_URL; ?>cart.php" class="hover:text-yellow-400 transition-colors">My Cart</a></li>
				</ul>
			</div>

			<!-- Contact -->
			<div>
				<h4 class="text-white font-bold text-sm uppercase tracking-wider mb-4">Contact Us</h4>
				<ul class="space-y-3 text-sm">
					<?php if(!empty($contact_address)): ?>
					<li class="flex items-start gap-3">
						<i class="fa fa-map-marker text-yellow-400 mt-0.5 shrink-0"></i>
						<span><?php echo htmlspecialchars($contact_address); ?></span>
					</li>
					<?php endif; ?>
					<?php if(!empty($contact_phone)): ?>
					<li class="flex items-center gap-3">
						<i class="fa fa-phone text-yellow-400 shrink-0"></i>
						<a href="tel:<?php echo $contact_phone; ?>" class="hover:text-yellow-400 transition-colors"><?php echo htmlspecialchars($contact_phone); ?></a>
					</li>
					<?php endif; ?>
					<li class="flex items-center gap-3">
						<i class="fa fa-envelope text-yellow-400 shrink-0"></i>
						<a href="mailto:mwesigwagershom7@gmail.com" class="hover:text-yellow-400 transition-colors">mwesigwagershom7@gmail.com</a>
					</li>
				</ul>
			</div>
		</div>

		<!-- Bottom bar -->
		<div class="border-t border-gray-800 pt-6 flex flex-col md:flex-row items-center justify-between gap-2 text-xs text-gray-500">
			<span><?php echo $footer_copyright; ?></span>
			<span>Built by <span class="text-yellow-400 font-semibold">NOVARA Technologies</span></span>
		</div>
	</div>
</footer>

<!-- Scroll to top (desktop only) -->
<a href="#" class="hidden md:flex fixed bottom-6 right-6 w-10 h-10 bg-yellow-400 text-gray-900 rounded-full items-center justify-center shadow-lg hover:bg-yellow-300 transition-all z-50 scrollup opacity-0 invisible">
	<i class="fa fa-angle-up text-xl"></i>
</a>

<?php
$statement = $pdo->prepare("SELECT stripe_public_key FROM tbl_settings_view WHERE id=1");
$statement->execute();
$srow = $statement->fetch(PDO::FETCH_ASSOC);
$stripe_public_key = $srow['stripe_public_key'] ?? '';
?>

<script src="<?php echo BASE_URL; ?>assets/js/jquery-2.2.4.min.js"></script>
<script src="https://js.stripe.com/v2/"></script>
<script src="<?php echo BASE_URL; ?>assets/js/owl.carousel.min.js"></script>
<script src="<?php echo BASE_URL; ?>assets/js/select2.full.min.js"></script>

<script>
$(window).scroll(function(){
	if($(this).scrollTop()>100){
		$('.scrollup').removeClass('opacity-0 invisible').addClass('opacity-100 visible');
	} else {
		$('.scrollup').removeClass('opacity-100 visible').addClass('opacity-0 invisible');
	}
});
$('.scrollup').click(function(){ $("html,body").animate({scrollTop:0},400); return false; });

function confirmDelete(){ return confirm("Sure you want to delete this?"); }

$(document).ready(function(){
	$('#paypal_form,#stripe_form,#bank_form').hide();
	$('#advFieldsStatus').on('change',function(){
		var v=$(this).val();
		$('#paypal_form,#stripe_form,#bank_form').hide();
		if(v==='PayPal') $('#paypal_form').show();
		else if(v==='Stripe') $('#stripe_form').show();
		else if(v==='Bank Deposit') $('#bank_form').show();
	});
});

Stripe.setPublishableKey('<?php echo htmlspecialchars($stripe_public_key); ?>');
function stripeResponseHandler(status,response){
	if(response.error){
		$('#submit-button').prop("disabled",false);
		$("#msg-container").html('<div style="color:red;border:1px solid;margin:10px 0;padding:5px"><strong>Error:</strong> '+response.error.message+'</div>').show();
	} else {
		var form$=$("#stripe_form");
		form$.append("<input type='hidden' name='stripeToken' value='"+response['id']+"'/>");
		form$.get(0).submit();
	}
}
</script>

<?php echo $before_body ?? ''; ?>

<script>
if('serviceWorker' in navigator){
	window.addEventListener('load',()=>{
		navigator.serviceWorker.register('<?php echo BASE_URL; ?>sw.js').then(reg=>{
			reg.addEventListener('updatefound',()=>{
				const w=reg.installing;
				w.addEventListener('statechange',()=>{
					if(w.state==='installed'&&navigator.serviceWorker.controller) window.location.reload();
				});
			});
		});
	});
}
</script>
</body>
</html>
