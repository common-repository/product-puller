<?php
function prdct_pllr_view_menu_dashboard(){
    if (!current_user_can('manage_options')) {
        return;
    }
    $model = new prdct_pllr_model();
    $amazon = $model->get_local_amazon();
    $api = $model->check_api_imported();
    $tag_detailed = "";
    $tag_detailed_text = "You can show the products in more detailed way.";
    $tag_compare_text = "Pull a product comparison from Amazon and more.";
    $tag_compare = "";
    $tag_woo_text = "Import an Amazon product data to WooCommerce.";
    $tag_woo = "";
    if(get_option('product_puller_plr_fetch_full')){
        $tag_detailed = "plr-dashcard-bought";
        $tag_detailed_text = "Detailed Products";
    }
    if($model->checkPast('product_comparison')){
        $tag_compare = "plr-dashcard-bought";
        $tag_compare_text = "Product Comparison";
    }

    if($model->checkPast('amazon_woo_puller')){
        $tag_woo = "plr-dashcard-bought";
        $tag_woo_text = "Amazon Woo Puller";
    }
    ?>
    <h1><?php esc_html_e(get_admin_page_title()); ?></h1>
    <div style="background-color: transparent; padding: 0;" class="prd-dashboard">
        <main>
            <input id="tab1" type="radio" name="tabs" class="none-prd" checked>
            <label for="tab1">Dashboard</label>
            <input id="tab2" type="radio" name="tabs" class="none-prd">
            <label for="tab2">News</label>

            <input id="tab3" type="radio" name="tabs" class="none-prd">
            <label for="tab3">Changelog</label>

            <input id="tab4" type="radio" name="tabs" class="none-prd">
            <label for="tab4">Documentation</label>
            <input id="tab5" type="radio" name="tabs" class="none-prd">
            <label for="tab5">Activation Extention</label>
            <section id="content1">
                <h2>Api-Key</h2>

                <input type="hidden" value="<?php echo $model->refLink('https://pluginpress.net/profile')?>" id="profile_puller_hidden">
                <?php if($api=="none"):?>
                <input type="text" placeholder="Enter your api-key here..." style="width: 400px;" id="pllr-api-input">
                <button type="button" class="button" id="pllr-api-btn">Check</button>
                    <div id="api-msg">For more option, register <a href="<?php echo $model->refLink('https://pluginpress.net/register')?>" target="_blank">pluginpress.net</a> and get your api key.</div>
                <?php else:?>
                    <input type="text" placeholder="Enter your api-key here..." style="width: 400px;border:2px solid green;color:green" id="pllr-api-input" value="<?php echo get_option('product_puller_api_key')?>">
                    <button type="button" class="button" id="pllr-api-btn" style="border:2px solid green;color:green"><i class="fa fa-check"></i> Update</button>
                    <div id="api-msg">If you have purchased a product and want to activate it, click "Update" button.</div>
                <?php endif;?>
                <br/>
                <h2>Your amazon Associate ID for affiliate program:</h2>
                <?php if(!get_option('product_puller_affi_id')):?>
                <input type="text" placeholder="Enter your user ID here..." style="width: 400px;" id="pllr-affi-input">
                <button type="button" class="button" id="pllr-affi-btn">Save</button>
                <?php else:?>
                <input type="text" placeholder="Enter your api-key here..." style="width: 400px;border:2px solid green;color:green" id="pllr-affi-input" value="<?php echo get_option('product_puller_affi_id')?>">
                <button type="button" class="button" id="pllr-affi-btn" style="border:2px solid green;color:green"><i class="fa fa-check"></i> Saved</button>
                <?php endif;?>
                <br/>
                <h2 style="">Select a country of Amazon</h2>
                <select id="amazon_local">
                    <option value="au" <?php echo ($amazon=="au" ? "selected" : "")?>>Australia</option>
                    <option value="br" <?php echo ($amazon=="br" ? "selected" : "")?>>Brazil (Brasil)</option>
                    <option value="ca" <?php echo ($amazon=="ca" ? "selected" : "")?>>Canada</option>
                    <option value="cn" <?php echo ($amazon=="cn" ? "selected" : "")?>>China (中国大陆)</option>
                    <option value="fr" <?php echo ($amazon=="fr" ? "selected" : "")?>>France</option>
                    <option value="de" <?php echo ($amazon=="de" ? "selected" : "")?>>Germany (Deutschland)</option>
                    <option value="in" <?php echo ($amazon=="in" ? "selected" : "")?>>India</option>
                    <option value="it" <?php echo ($amazon=="it" ? "selected" : "")?>>Italy (Italia)</option>
                    <option value="jp" <?php echo ($amazon=="jp" ? "selected" : "")?>>Japan (日本)</option>
                    <option value="mx" <?php echo ($amazon=="mx" ? "selected" : "")?>>Mexico (México)</option>
                    <option value="nl" <?php echo ($amazon=="nl" ? "selected" : "")?>>Netherlands (Nederland)</option>
                    <option value="pl" <?php echo ($amazon=="pl" ? "selected" : "")?>>Poland (Polska)</option>
                    <option value="sa" <?php echo ($amazon=="sa" ? "selected" : "")?>>Saudi Arabia (المملكة العربية السعودية)</option>
                    <option value="sg" <?php echo ($amazon=="sg" ? "selected" : "")?>>Singapore</option>
                    <option value="es" <?php echo ($amazon=="es" ? "selected" : "")?>>Spain (España)</option>
                    <option value="se" <?php echo ($amazon=="se" ? "selected" : "")?>>Sweden (Sverige)</option>
                    <option value="tr" <?php echo ($amazon=="tr" ? "selected" : "")?>>Turkey (Türkiye)</option>
                    <option value="ae" <?php echo ($amazon=="ae" ? "selected" : "")?>>United Arab Emirates</option>
                    <option value="uk" <?php echo ($amazon=="uk" ? "selected" : "")?>>United Kingdom</option>
                    <option value="us" <?php echo ($amazon=="us" ? "selected" : "")?>>United States</option>
                </select>
                &nbsp;<button type="button" class="button" id="save_amazon_local">Save</button>
                <br/><br/><br/>
                <hr/>
                <div style="text-align: center"><h2>Open Premium Settings</h2></div>
                <div class="plr-dashcard-container">
                    <div class="plr-dashcard card-1 <?php echo $tag_detailed?>" id="plr-card-1">
                        <div class="plr-dashcard-check"></div>
                        <div class="plr-dashcard-img"></div>
                        <a href="<?php echo $model->refLink('https://pluginpress.net/register')?>" class="plr-dashcard-link" target="_blank">
                            <div class="plr-dashcard-img-hovered"></div>
                        </a>
                        <div class="plr-dashcard-info">
                            <div class="plr-dashcard-about">
                                <a class="plr-dashcard-tag tag-detail">Detailed Products</a>
                                <div class="plr-dashcard-time"><b>Free</b></div>
                            </div>
                            <h1 class="plr-dashcard-title" id="title-card-1"><?php echo $tag_detailed_text?></h1>
                            <div class="plr-dashcard-creator">&nbsp;</div>
                        </div>
                    </div>
                    <div class="plr-dashcard card-2 <?php echo $tag_compare?>" id="plr-card-2">
                        <div class="plr-dashcard-check"></div>
                        <div class="plr-dashcard-img"></div>
                        <a href="<?php echo $model->refLink('https://pluginpress.net/product/product-comparison')?>" class="plr-dashcard-link" target="_blank">
                            <div class="plr-dashcard-img-hovered"></div>
                        </a>
                        <div class="plr-dashcard-info">
                            <div class="plr-dashcard-about">
                                <a class="plr-dashcard-tag tag-compare">Product Comparison</a>
                                <div class="plr-dashcard-time"><b>£99.99/year</b></div>
                            </div>
                            <h1 class="plr-dashcard-title" id="title-card-2"><?php echo $tag_compare_text?></h1>
                            <div class="plr-dashcard-creator">&nbsp;</div>
                        </div>
                    </div>
                    <div class="plr-dashcard card-3 <?php echo $tag_woo?>" id="plr-card-3">
                        <div class="plr-dashcard-check"></div>
                        <div class="plr-dashcard-img"></div>
                        <a href="<?php echo $model->refLink('https://pluginpress.net/product/amazon-woo-puller')?>" class="plr-dashcard-link" target="_blank">
                            <div class="plr-dashcard-img-hovered"></div>
                        </a>
                        <div class="plr-dashcard-info">
                            <div class="plr-dashcard-about">
                                <a class="plr-dashcard-tag tag-woo">Amazon Woo Puller</a>
                                <div class="plr-dashcard-time"><b>99.99/year</b></div>
                            </div>
                            <h1 class="plr-dashcard-title" id="title-card-3"><?php echo $tag_woo_text?></h1>
                            <div class="plr-dashcard-creator">&nbsp;</div>
                        </div>
                    </div>
                </div>

            </section>
            <section id="content2">...</section>
            <section id="content3">...</section>
            <section id="content4">
                <b>To show product just enter product_id in shortcode below.</b><br><br>
                <code>[puller id="B08Q599Z8X"]</code><br>
                You can show multiple products.<br>
                <code>[puller ids="B08Q599Z8X,BXXXXXXX,BXXXXXXX2,..."]</code><br>
                If you have api key from pluginpress.net, you can show the data more detailed way<br>
                <code>[puller id="B08Q599Z8X" data="detailed"]</code>
                <br>Check the <a href="https://demo.pluginpress.net/product-puller/2021/09/11/free-version-settings/" target="_blank">demo</a>
                <h1>Video Tutorial</h1>
                <h3>Basic Usage</h3>
                <iframe width="560" height="315" src="https://www.youtube.com/embed/jvrpV3doGS4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <h3>Open Detailed Version for Free</h3>
                <iframe width="560" height="315" src="https://www.youtube.com/embed/uuPrvP4QGLw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>


            </section>
            <section id="content5">
                <?php
                include PRDP_VIEW_INC.'menu/dashboard.activation.php';
                ?>
            </section>



        </main>
    </div>
    <div style="padding-right: 100px">
    <div style="float: right">ClerkSoftware.com</div>
    </div>
    <?php


}