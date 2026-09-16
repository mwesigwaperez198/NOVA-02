<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings_view WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row)
{
	$footer_about = $row['footer_about'];
	$contact_email = $row['contact_email'];
	$contact_phone = $row['contact_phone'];
	$contact_address = $row['contact_address'];
	$footer_copyright = $row['footer_copyright'];
	$total_recent_post_footer = $row['total_recent_post_footer'];
    $total_popular_post_footer = $row['total_popular_post_footer'];
    $newsletter_on_off = $row['newsletter_on_off'];
    $before_body = $row['before_body'];
}
?>


<?php if($newsletter_on_off == 1): ?>
<section class="bg-gray-100 py-16">
	<div class="container mx-auto px-4">
		<div class="max-w-2xl mx-auto text-center">
			<?php
			if(isset($_POST['form_subscribe']))
			{
				if(empty($_POST['email_subscribe'])) 
			    {
			        $valid = 0;
			        $error_message1 .= LANG_VALUE_131;
			    }
			    else
			    {
			    	if (filter_var($_POST['email_subscribe'], FILTER_VALIDATE_EMAIL) === false)
				    {
				        $valid = 0;
				        $error_message1 .= LANG_VALUE_134;
				    }
				    else
				    {
				    	$statement = $pdo->prepare("SELECT * FROM tbl_subscriber WHERE subs_email=?");
				    	$statement->execute(array($_POST['email_subscribe']));
				    	$total = $statement->rowCount();							
				    	if($total)
				    	{
				    		$valid = 0;
				        	$error_message1 .= LANG_VALUE_147;
				    	}
				    	else
				    	{
				    		$key = md5(uniqid(rand(), true));
				    		$current_date = date('Y-m-d');
				    		$current_date_time = date('Y-m-d H:i:s');
				    		$statement = $pdo->prepare("INSERT INTO tbl_subscriber (subs_email,subs_date,subs_date_time,subs_hash,subs_active) VALUES (?,?,?,?,?)");
				    		$statement->execute(array($_POST['email_subscribe'],$current_date,$current_date_time,$key,0));
				    		$to = $_POST['email_subscribe'];
							$subject = 'Subscriber Email Confirmation';
							$verification_url = BASE_URL.'verify.php?email='.$to.'&key='.$key;
							$message = 'Thanks for your interest to subscribe our newsletter!<br><br>Please click this link to confirm your subscription: '.$verification_url.'<br><br>This link will be active only for 24 hours.';
							$headers = 'From: ' . $contact_email . "\r\n" . 'Reply-To: ' . $contact_email . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . "MIME-Version: 1.0\r\n" . "Content-Type: text/html; charset=ISO-8859-1\r\n";
							mail($to, $subject, $message, $headers);
							$success_message1 = LANG_VALUE_136;
				    	}
				    }
			    }
			}
			if($error_message1 != '') {
				echo "<script>alert('".$error_message1."')</script>";
			}
			if($success_message1 != '') {
				echo "<script>alert('".$success_message1."')</script>";
			}
			?>
			<form action="" method="post" class="space-y-6">
				<?php $csrf->echoInputField(); ?>
				<h2 class="text-3xl font-bold text-gray-900 mb-2"><?php echo LANG_VALUE_93; ?></h2>
				<p class="text-gray-600 mb-8">Stay updated with our latest offers and products.</p>
				<div class="flex flex-col sm:flex-row gap-3 shadow-lg rounded-2xl p-2 bg-white">
					<input type="email" class="flex-1 px-6 py-4 rounded-xl outline-none text-gray-700" placeholder="<?php echo LANG_VALUE_95; ?>" name="email_subscribe">
					<button class="bg-brand-primary text-white px-8 py-4 rounded-xl font-bold hover:bg-red-600 transition-all uppercase tracking-wide" type="submit" name="form_subscribe">
						<?php echo LANG_VALUE_92; ?>
					</button>
				</div>
			</form>
		</div>
	</div>
</section>
<?php endif; ?>

<footer class="bg-gray-900 text-gray-400 py-12">
	<div class="container mx-auto px-4">
		<div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
			<div class="col-span-1 md:col-span-2">
				<h3 class="text-white text-lg font-bold mb-6 uppercase tracking-wider">About Us</h3>
				<p class="leading-relaxed">
					<?php echo $footer_about; ?>
				</p>
			</div>
			<div>
				<h3 class="text-white text-lg font-bold mb-6 uppercase tracking-wider">Contact Info</h3>
				<ul class="space-y-4">
					<li class="flex items-start gap-3">
						<i class="fa fa-map-marker mt-1 text-brand-primary"></i>
						<span><?php echo $contact_address; ?></span>
					</li>
					<li class="flex items-center gap-3">
						<i class="fa fa-phone text-brand-primary"></i>
						<span><?php echo $contact_phone; ?></span>
					</li>
					<li class="flex items-center gap-3">
						<i class="fa fa-envelope text-brand-primary"></i>
						<span><?php echo $contact_email; ?></span>
					</li>
				</ul>
			</div>
			<div>
				<h3 class="text-white text-lg font-bold mb-6 uppercase tracking-wider">Follow Us</h3>
				<div class="flex gap-4">
					<?php
					$statement = $pdo->prepare("SELECT * FROM tbl_social");
					$statement->execute();
					$result = $statement->fetchAll(PDO::FETCH_ASSOC);
					foreach ($result as $row) {
						if($row['social_url'] != ''): ?>
						<a href="<?php echo $row['social_url']; ?>" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-brand-primary hover:text-white transition-all">
							<i class="<?php echo $row['social_icon']; ?>"></i>
						</a>
						<?php endif;
					}
					?>
				</div>
			</div>
		</div>
		<div class="border-t border-gray-800 pt-8 text-center text-sm">
			<p><?php echo $footer_copyright; ?></p>
		</div>
	</div>
</footer>

<a href="#" class="fixed bottom-8 right-8 w-12 h-12 bg-brand-primary text-white rounded-full flex items-center justify-center shadow-xl hover:-translate-y-1 transition-all z-50 scrollup opacity-0 invisible">
	<i class="fa fa-angle-up text-2xl"></i>
</a>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings_view WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $stripe_public_key = $row['stripe_public_key'];
    $stripe_secret_key = $row['stripe_secret_key'];
}
?>

<script src="assets/js/jquery-2.2.4.min.js"></script>
<script src="https://js.stripe.com/v2/"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/jquery.magnific-popup.min.js"></script>
<script src="assets/js/select2.full.min.js"></script>

<script>
// Mobile Menu - Global Event Delegation
document.addEventListener('click', function(e) {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenuClose = document.getElementById('mobile-menu-close');
    const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
    const mobileMenuDrawer = document.getElementById('mobile-menu-drawer');
    const mobileMenuContent = document.getElementById('mobile-menu-content');

    // Toggle Open
    if (e.target.closest('#mobile-menu-button') || (mobileMenuButton && e.target.closest('#mobile-menu-button'))) {
        if (mobileMenuDrawer) {
            mobileMenuDrawer.classList.remove('invisible');
            setTimeout(() => {
                mobileMenuOverlay.classList.remove('opacity-0');
                mobileMenuOverlay.classList.add('opacity-100');
                mobileMenuContent.classList.remove('translate-x-full');
                mobileMenuContent.classList.add('translate-x-0');
            }, 10);
        }
    }

    // Toggle Close
    if (e.target.closest('#mobile-menu-close') || e.target.closest('#mobile-menu-overlay')) {
        if (mobileMenuDrawer) {
            mobileMenuOverlay.classList.remove('opacity-100');
            mobileMenuOverlay.classList.add('opacity-0');
            mobileMenuContent.classList.remove('translate-x-0');
            mobileMenuContent.classList.add('translate-x-full');
            setTimeout(() => {
                mobileMenuDrawer.classList.add('invisible');
            }, 300);
        }
    }
});

$(window).scroll(function() {
		if ($(this).scrollTop() > 100) {
			$('.scrollup').removeClass('opacity-0 invisible').addClass('opacity-100 visible');
		} else {
			$('.scrollup').removeClass('opacity-100 visible').addClass('opacity-0 invisible');
		}
	});
	
	$('.scrollup').click(function() {
		$("html, body").animate({ scrollTop: 0 }, 600);
		return false;
	});

	function confirmDelete()
	{
	    return confirm("Sure you want to delete this data?");
	}
	$(document).ready(function () {
		advFieldsStatus = $('#advFieldsStatus').val();

		$('#paypal_form').hide();
		$('#stripe_form').hide();
		$('#bank_form').hide();

        $('#advFieldsStatus').on('change',function() {
            advFieldsStatus = $('#advFieldsStatus').val();
            if ( advFieldsStatus == '' ) {
            	$('#paypal_form').hide();
				$('#stripe_form').hide();
				$('#bank_form').hide();
            } else if ( advFieldsStatus == 'PayPal' ) {
               	$('#paypal_form').show();
				$('#stripe_form').hide();
				$('#bank_form').hide();
            } else if ( advFieldsStatus == 'Stripe' ) {
               	$('#paypal_form').hide();
				$('#stripe_form').show();
				$('#bank_form').hide();
            } else if ( advFieldsStatus == 'Bank Deposit' ) {
            	$('#paypal_form').hide();
				$('#stripe_form').hide();
				$('#bank_form').show();
            }
        });
	});

	Stripe.setPublishableKey('<?php echo $stripe_public_key; ?>');
    function stripeResponseHandler(status, response) {
        if (response.error) {
            $('#submit-button').prop("disabled", false);
            $("#msg-container").html('<div style="color: red;border: 1px solid;margin: 10px 0px;padding: 5px;"><strong>Error:</strong> ' + response.error.message + '</div>');
            $("#msg-container").show();
        } else {
            var form$ = $("#stripe_form");
            var token = response['id'];
            form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
            form$.get(0).submit();
        }
    }
</script>
<?php echo $before_body; ?>

<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('<?php echo BASE_URL; ?>sw.js')
        .then(reg => {
          reg.addEventListener('updatefound', () => {
            const newWorker = reg.installing;
            newWorker.addEventListener('statechange', () => {
              if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                // New version deployed â€” auto-reload to get fresh content
                window.location.reload();
              }
            });
          });
        });
    });
  }
</script>
</body>
</html>
