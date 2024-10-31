<?php
/**
 * @var $title
 * @var $html
 * @var prdct_pllr_model $this
 * @var $local
 * @var $product_id
 * @var $price
 * @var $image
 */
$url = esc_url_raw($this->create_amazon_link($product_id));
$newTitle = esc_html($this->text_cleaner($title));
$cleanImage = esc_url($image);
return <<<HTML

<div class="prd-product-card">

<a href="{$url}" target="_blank">
<div class="prd-product-tumb">

<img src="{$cleanImage}" alt="">

</div>
</a>
<div class="prd-product-details">
<h4>{$newTitle}</h4>
<div class="prd-product-bottom-details">
<div class="prd-product-price">{$price}</div>
<div class="prd-product-links">

<a href="{$url}" class="prd-product-add" id="{$product_id}" style="cursor: pointer;" target="_blank">
<img src="{$this->asset('amazon3.png')}" style="width: 140px">
</a>
</div>
</div>
</div>
</div>
HTML;
