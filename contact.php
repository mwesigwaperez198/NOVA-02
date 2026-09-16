<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $contact_title = $row['contact_title'];
    $contact_banner = $row['contact_banner'];
}
$statement = $pdo->prepare("SELECT * FROM tbl_settings_view WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $contact_map_iframe = $row['contact_map_iframe'];
    $contact_email = $row['contact_email'];
    $contact_phone = $row['contact_phone'];
    $contact_address = $row['contact_address'];
}
?>

<div class="py-12 bg-white border-b">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4 uppercase tracking-tight"><?php echo $contact_title; ?></h1>
        <div class="w-20 h-1.5 bg-brand-primary mx-auto rounded-full"></div>
    </div>
</div>

<div class="py-16 bg-gray-50 min-h-[600px]">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="flex flex-wrap lg:flex-nowrap gap-12">
                <!-- Contact Form -->
                <div class="w-full lg:w-2/3">
                    <div class="bg-white rounded-3xl p-8 md:p-12 shadow-sm border border-gray-100">
                        <h2 class="text-2xl font-black text-gray-900 mb-8 uppercase tracking-tight">Send us a message</h2>
                        
                        <?php
                        if(isset($error_message) && $error_message != '') {
                            echo "<div class='mb-8 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm font-medium rounded-r-xl'>".$error_message."</div>";
                        }
                        if(isset($success_message) && $success_message != '') {
                            echo "<div class='mb-8 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm font-medium rounded-r-xl'>".$success_message."</div>";
                        }
                        ?>

                        <form action="" method="post" class="space-y-6">
                            <?php $csrf->echoInputField(); ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Name *</label>
                                    <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" name="visitor_name" placeholder="John Doe">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Email Address *</label>
                                    <input type="email" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" name="visitor_email" placeholder="john@example.com">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Phone Number *</label>
                                    <input type="text" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" name="visitor_phone" placeholder="+1 234 567 890">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wide">Message *</label>
                                    <textarea name="visitor_message" class="w-full border-2 border-gray-100 rounded-xl px-4 py-3 focus:border-brand-primary outline-none transition-all" rows="6" placeholder="How can we help you?"></textarea>
                                </div>
                            </div>
                            <button type="submit" name="form_contact" class="bg-brand-primary text-white px-10 py-4 rounded-xl font-bold text-lg uppercase tracking-widest hover:bg-red-600 transition-all shadow-xl shadow-red-500/20">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Office Info -->
                <div class="w-full lg:w-1/3 space-y-8">
                    <div class="bg-gray-900 rounded-3xl p-8 text-white shadow-xl">
                        <h2 class="text-xl font-black mb-8 uppercase tracking-widest text-brand-primary">Our Office</h2>
                        
                        <div class="space-y-8">
                            <div class="flex gap-4">
                                <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center shrink-0">
                                    <i class="fa fa-map-marker text-xl text-brand-primary"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm uppercase tracking-widest text-gray-400 mb-2">Address</h4>
                                    <p class="text-gray-200 leading-relaxed"><?php echo nl2br($contact_address); ?></p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center shrink-0">
                                    <i class="fa fa-phone text-xl text-brand-primary"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm uppercase tracking-widest text-gray-400 mb-2">Phone</h4>
                                    <p class="text-gray-200"><?php echo $contact_phone; ?></p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center shrink-0">
                                    <i class="fa fa-envelope text-xl text-brand-primary"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm uppercase tracking-widest text-gray-400 mb-2">Email</h4>
                                    <a href="mailto:<?php echo $contact_email; ?>" class="text-gray-200 hover:text-brand-primary transition-colors"><?php echo $contact_email; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-gray-100 h-[300px]">
                        <?php 
                        // Clean up the iframe from database to make it responsive
                        $map = preg_replace('/width="\d+"/', 'width="100%"', $contact_map_iframe);
                        $map = preg_replace('/height="\d+"/', 'height="100%"', $map);
                        echo $map; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>
