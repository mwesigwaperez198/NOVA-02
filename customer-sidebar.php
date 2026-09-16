<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gray-900 px-6 py-6 text-center">
        <div class="w-20 h-20 bg-brand-primary/20 rounded-full flex items-center justify-center mx-auto mb-4 border-2 border-brand-primary/30">
            <i class="fa fa-user text-3xl text-brand-primary"></i>
        </div>
        <h3 class="text-white font-bold text-lg truncate"><?php echo htmlspecialchars($_SESSION['customer']['cust_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></h3>
        <p class="text-gray-400 text-xs mt-1 uppercase tracking-widest">Customer Account</p>
    </div>
    <nav class="p-4">
        <ul class="space-y-1">
            <li>
                <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-brand-primary text-white shadow-lg shadow-red-500/20' : 'text-gray-600 hover:bg-gray-50 hover:text-brand-primary'; ?>">
                    <i class="fa fa-home w-5"></i>
                    <span class="font-bold text-sm uppercase tracking-wide"><?php echo LANG_VALUE_89; ?></span>
                </a>
            </li>
            <li>
                <a href="customer-profile-update.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?php echo basename($_SERVER['PHP_SELF']) == 'customer-profile-update.php' ? 'bg-brand-primary text-white shadow-lg shadow-red-500/20' : 'text-gray-600 hover:bg-gray-50 hover:text-brand-primary'; ?>">
                    <i class="fa fa-user w-5"></i>
                    <span class="font-bold text-sm uppercase tracking-wide"><?php echo LANG_VALUE_117; ?></span>
                </a>
            </li>
            <li>
                <a href="customer-billing-shipping-update.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?php echo basename($_SERVER['PHP_SELF']) == 'customer-billing-shipping-update.php' ? 'bg-brand-primary text-white shadow-lg shadow-red-500/20' : 'text-gray-600 hover:bg-gray-50 hover:text-brand-primary'; ?>">
                    <i class="fa fa-truck w-5"></i>
                    <span class="font-bold text-sm uppercase tracking-wide"><?php echo LANG_VALUE_88; ?></span>
                </a>
            </li>
            <li>
                <a href="customer-password-update.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?php echo basename($_SERVER['PHP_SELF']) == 'customer-password-update.php' ? 'bg-brand-primary text-white shadow-lg shadow-red-500/20' : 'text-gray-600 hover:bg-gray-50 hover:text-brand-primary'; ?>">
                    <i class="fa fa-key w-5"></i>
                    <span class="font-bold text-sm uppercase tracking-wide"><?php echo LANG_VALUE_99; ?></span>
                </a>
            </li>
            <li>
                <a href="customer-order.php" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all <?php echo basename($_SERVER['PHP_SELF']) == 'customer-order.php' ? 'bg-brand-primary text-white shadow-lg shadow-red-500/20' : 'text-gray-600 hover:bg-gray-50 hover:text-brand-primary'; ?>">
                    <i class="fa fa-shopping-bag w-5"></i>
                    <span class="font-bold text-sm uppercase tracking-wide"><?php echo LANG_VALUE_24; ?></span>
                </a>
            </li>
            <li class="pt-4 mt-4 border-t border-gray-100">
                <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 transition-all">
                    <i class="fa fa-sign-out w-5"></i>
                    <span class="font-bold text-sm uppercase tracking-wide"><?php echo LANG_VALUE_14; ?></span>
                </a>
            </li>
        </ul>
    </nav>
</div>