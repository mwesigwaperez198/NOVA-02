<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_page WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
   $about_title = $row['about_title'];
    $about_content = $row['about_content'];
    $about_banner = $row['about_banner'];
}
?>

<div class="py-12 bg-white border-b">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4 uppercase tracking-tight"><?php echo $about_title; ?></h1>
        <div class="w-20 h-1.5 bg-brand-primary mx-auto rounded-full"></div>
    </div>
</div>

<div class="py-16 bg-gray-50 min-h-[400px]">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-3xl p-8 md:p-16 shadow-sm border border-gray-100">
            <div class="prose max-w-none text-gray-600 leading-relaxed text-lg">
                <?php echo $about_content; ?>
            </div>
        </div>
    </div>
</div>


<?php require_once('footer.php'); ?>