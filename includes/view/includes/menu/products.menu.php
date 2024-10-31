<a href="<?php echo admin_url('admin.php?page=pull_products&add=new')?>" class="button" style="background-color: #0a4b78; color:#fff; border:0">Add New Products</a>
<?php
$args = array(
    "type" => "amazon,ebay,product",
    "singular" => "Product",
    "plural" => "Products",
    "link" => 'admin.php?page=pull_products&id='
);
$Cache = new PRDCT_PLLR_List_Table($args);
if (isset($_POST['bulk-delete'])) {
    foreach ($_POST['bulk-delete'] as $id) {
        $Cache->delete_customer(esc_sql($id));
    }
}

echo '<div style="width: %90 !important;padding: 20px">';
$Cache->prepare_items();
echo "<form method='post'>";
$Cache->search_box("Search Product ID/Title", "search_product_id");
$Cache->display();
echo "</form>";
echo '</div>';