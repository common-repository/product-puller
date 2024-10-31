<?php
$controller = new prdct_pllr_general_controller();
$product = $controller->get_product_by_id(esc_sql($_GET['id']));
if($product !=""):
?>
<div style="float: left; margin-right: 10px; background-color: #fff; padding: 20px">
    <img src="<?php echo esc_url_raw($product->image)?>" style="width: 300px;">
</div>
<div>
    <label>Title: </label>
    <input type="text" id="pr_title" value="<?php echo esc_attr($controller->text_cleaner($product->title))?>" style="width: 30%">
    <br/>
    <br/>
    <label>Price: </label>
    <input type="text" id="pr_price" value="<?php echo esc_attr($controller->text_cleaner($product->price))?>" style="width: 10%">
    <br/>
    <br/>
    <input type="hidden" id="pr_id" value="<?php echo esc_attr($_GET['id'])?>" style="width: 10%">
    <button id="prdct_edit" class="button">Save</button>
</div>
<div style="clear: both"></div>
<?php
endif;