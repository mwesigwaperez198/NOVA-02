<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $faq_title = $row['faq_title'];
    $faq_banner = $row['faq_banner'];
}
?>

<div class="py-12 bg-white border-b">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4 uppercase tracking-tight"><?php echo $faq_title; ?></h1>
        <div class="w-20 h-1.5 bg-brand-primary mx-auto rounded-full"></div>
    </div>
</div>

<div class="py-16 bg-gray-50 min-h-[400px]">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl mx-auto space-y-4">
            <?php
            $statement = $pdo->prepare("SELECT * FROM tbl_faq");
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
            foreach ($result as $row) {
                ?>
                <details class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden" <?php if($row['faq_id']==1) echo 'open'; ?>>
                    <summary class="flex items-center justify-between p-6 cursor-pointer list-none">
                        <h4 class="text-lg font-bold text-gray-900 group-hover:text-brand-primary transition-colors pr-8">
                            <?php echo $row['faq_title']; ?>
                        </h4>
                        <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center group-open:rotate-180 transition-transform">
                            <i class="fa fa-angle-down text-gray-400"></i>
                        </div>
                    </summary>
                    <div class="px-6 pb-6 pt-0">
                        <div class="border-t border-gray-50 pt-6 prose max-w-none text-gray-600 leading-relaxed">
                            <?php echo $row['faq_content']; ?>
                        </div>
                    </div>
                </details>
                <?php
            }
            ?>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>
