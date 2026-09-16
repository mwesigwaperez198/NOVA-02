<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gray-900 px-6 py-4">
        <h3 class="text-white font-bold uppercase tracking-wider text-sm"><?php echo LANG_VALUE_49; ?></h3>
    </div>
    <div class="p-4">
        <ul class="space-y-2">
            <?php
                $i=0;
                $statement = $pdo->prepare("SELECT * FROM tbl_top_category WHERE show_on_menu=1");
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $row) {
                    $i++;
                    ?>
                    <li>
                        <a href="product-category.php?id=<?php echo $row['tcat_id']; ?>&type=top-category" class="flex items-center justify-between px-4 py-2 rounded-xl text-gray-700 hover:bg-gray-50 hover:text-brand-primary font-bold transition-all group">
                            <span class="truncate pr-2"><?php echo $row['tcat_name']; ?></span>
                            <i class="fa fa-chevron-right text-[10px] text-gray-300 group-hover:text-brand-primary transition-colors shrink-0"></i>
                        </a>
                        
                        <ul class="ml-4 mt-1 space-y-1 border-l-2 border-gray-100 pl-4 mb-4">
                            <?php
                            $statement1 = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE tcat_id=?");
                            $statement1->execute(array($row['tcat_id']));
                            $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result1 as $row1) {
                                ?>
                                <li>
                                    <a href="product-category.php?id=<?php echo $row1['mcat_id']; ?>&type=mid-category" class="block px-3 py-1.5 text-sm text-gray-600 hover:text-brand-primary transition-colors truncate">
                                        <?php echo $row1['mcat_name']; ?>
                                    </a>
                                    <ul class="ml-3 mt-1 space-y-1 border-l border-gray-100 pl-3">
                                        <?php
                                            $statement2 = $pdo->prepare("SELECT * FROM tbl_end_category WHERE mcat_id=?");
                                            $statement2->execute(array($row1['mcat_id']));
                                            $result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($result2 as $row2) {
                                                ?>
                                                <li>
                                                    <a href="product-category.php?id=<?php echo $row2['ecat_id']; ?>&type=end-category" class="block py-1 text-xs text-gray-400 hover:text-brand-primary transition-colors italic truncate">
                                                        <?php echo $row2['ecat_name']; ?>
                                                    </a>
                                                </li>
                                                <?php
                                            }
                                        ?>
                                    </ul>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </li>
                    <?php
                }
            ?>
        </ul>
    </div>
</div>
