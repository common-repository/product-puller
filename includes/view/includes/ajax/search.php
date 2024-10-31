<?php
/**
 * @var $value
 * @var $sonuc
 * @var $urunler
 * @var prdct_pllr_ajax_controller $this
 *
 */
//foreach ($urunler as $u){
//    echo $u['num'].'<br/>';
//    echo $u['img'].'<br/>';
//    echo $u['title'].'<br/>';
//    echo $u['id'].'<br/>';
//    echo $u['price'].'<br/>';
//    echo '<hr/>';
//}

//echo $whatLink;
if(count($urunler)>0):
    $x = 1;
    foreach ($urunler as $u){
        if($u['title'] !=""):
//            $product_id = esc_attr($u['id']);

            ?>
            <div class="prd-product-card" style="display: inline-block">
                <!--            <div class="prd-badge">Hot</div>-->
                <a href="<?php echo esc_url($url.'dp/'.$u['id'])?>" target="_blank">
                    <div class="prd-product-tumb">

                        <img src="<?php echo esc_url($u['img'])?>" alt="">

                    </div>
                </a>
                <div class="prd-product-details">
                    <h4><?php echo esc_html($u['title'])?></h4>
                    <div class="prd-product-bottom-details">
                        <div class="prd-product-price"><?php echo esc_attr($u['price'])?></div>
                        <div class="prd-product-links">

                            <a class="prd-product-add" id="<?php echo esc_attr($u['id'])?>" style="cursor: pointer;">
                                <!--                                <img src="--><?php //echo PRDP_ASSET.'woo.png'?><!--">-->
                                <i class="fa fa-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="title_input<?php echo esc_attr($u['id'])?>" value="<?php echo esc_attr($u['title'])?>"/>
                <input type="hidden" id="price_input<?php echo esc_attr($u['id'])?>" value="<?php echo esc_attr($u['price'])?>"/>
                <input type="hidden" id="img_input<?php echo esc_attr($u['id'])?>" value="<?php echo esc_url($u['img'])?>"/>
            </div>
            <?php

        endif;
    }
endif;
//echo $whatLink;
//echo $html;