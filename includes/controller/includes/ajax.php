<?php
class prdct_pllr_ajax_controller extends prdct_pllr_general_controller{
    //Product search
    function product_search($type="amazon"){
        if($type=="amazon") {
            if (isset($_POST['value'])) {
                $args['value'] = sanitize_text_field($_POST['value']);
                $woo = isset($_POST['woo']) ? sanitize_text_field($_POST['woo']) : "normal";
                $args['this'] = $this;
                $args = $this->search_amazon(mb_convert_encoding($args['value'], 'HTML-ENTITIES', 'UTF-8'), sanitize_text_field($_POST['page']));
                if($woo=="normal") {
                    $this->view('ajax.search', $args);
                }else{
                    $this->view('ajax.woo-search', $args);
                }
            }
        }
        die();
    }


    //Amazon search
    function search_amazon($value,$page=1)
    {
        $local = $this->get_local_amazon();
        $com = $this->amazon_site($local);
        $args = array();
        $args['state'] = 0;
        $url = esc_url('https://www.amazon.'.$com.'/s?k='.$this->searchSlug(htmlspecialchars_decode($value)).($page==1 ? "" : "&page=".$page));
        $args['url'] = 'https://www.amazon.'.$com.'/';
        $args['whatLink'] = $url;
//        $path = $this->getDomAmazon(htmlspecialchars_decode($value),$page);
        $path = $this->getDomAmazon($value,$page);
        $xpath = $path['xpath'];
        $dom = $path['dom'];
        @$sonuclar = $xpath->query('//div[contains(@class, "a-section a-spacing-medium")]');
        if(isset($sonuclar->item(0)->nodeValue)){
            $args['state'] = 1;
            foreach ($sonuclar as $k => $s){
                $spath = $this->html_dom($s,$dom);
                @$id = $spath->query('//a[@class="a-link-normal a-text-normal"]/@href');
                $check = isset($id->item(0)->nodeValue) ? trim($this->convert_product_id($id->item(0)->nodeValue)) : "";
                if($check != "") {
                    @$image = $spath->query('//img/@src');
                    if (isset($image->item(0)->nodeValue)) {
                        $args['urunler'][$check]['img'] = $image->item(0)->nodeValue;
                    }
                    $args['urunler'][$check]['id'] = $check;
                    @$title = $spath->query('//h2/a/span');
                    if (isset($title->item(0)->nodeValue)) {
                        $args['urunler'][$check]['title'] = utf8_decode($title->item(0)->nodeValue);
                    }
                    @$price = $spath->query('//span[@class="a-price"]/span[@class="a-offscreen"]');
                    if (isset($price->item(0)->nodeValue)) {
                        $args['urunler'][$check]['price'] = utf8_decode($price->item(0)->nodeValue);
                    } else {
                        $args['urunler'][$check]['price'] = "---";
                    }
                }
            }
        }

        return $args;
    }

    function amazon_full_save($post = ""){
        if(get_option('product_puller_plr_fetch_full')) {
            global $wpdb;
            $defs = json_decode(base64_decode(get_option('product_puller_plr_fetch_full')));
            $price = isset($post['price']) ? sanitize_text_field($post['price']) : sanitize_text_field($_POST['price']);
            $tit = isset($post['title']) ? sanitize_text_field($post['title']) : sanitize_text_field($_POST['title']);
            $img = isset($post['img']) ? sanitize_text_field($post['img']) : sanitize_text_field($_POST['img']);
            $img = $this->image_cleaner($img);
            $product_id = isset($post['product_id']) ? sanitize_text_field($post['product_id']) : sanitize_text_field($_POST['product_id']);
            $path = $this->getDomProductAmazon($product_id);
            $xpath = $path['xpath'];
            $dom = $path['dom'];
            $data = array();
            //title
            @$title = $xpath->query($defs->title);
            $data['title'] = isset($title->item(0)->nodeValue) ? trim($title->item(0)->nodeValue) : "";
            //details
            @$details = $xpath->query($defs->details);
            if (@$details->length > 0) {
                foreach ($details as $d) {
                    $dpath = $this->html_dom($d, $dom);
                    @$detail_titles = $dpath->query('//th');
                    @$details = $dpath->query('//td');
                    if (@$detail_titles->length > 0)
                        foreach ($detail_titles as $k => $t) {
                            $title = utf8_decode($t->nodeValue);
                            $detail = utf8_decode($details->item($k)->nodeValue);
                            if ($this->AmazonProductDetailMacth($title)) {
                                $data['details'][$title] = $detail;
                            }
                        }

                }

            }else{
                @$details = $xpath->query('//table[@class="a-bordered"]//tr');
                if(@$details->length > 0){
                    foreach ($details as $d){
                        $dpath = $this->html_dom($d,$dom);
                        $detail = $dpath->query('//td/p');
                        $data['details'][esc_html(trim(utf8_decode($detail->item(0)->nodeValue)))] = esc_html(trim(utf8_decode($detail->item(1)->nodeValue)));
                    }
                }
            }
            //about
            @$about = $xpath->query($defs->about);
            if (@$about->length > 0) {
                foreach ($about as $k => $a) {
                    $data['about'][$k] = $a->nodeValue;
                }
            }

            //price
            @$pr = $xpath->query($defs->price);
            $data['price'] = isset($pr->item(0)->nodeValue) ? preg_replace("/[^0-9.]/", "", $pr->item(0)->nodeValue) : "";
            //desc
            @$desc = $xpath->query($defs->desc);
            $data['desc'] = isset($desc->item(0)->nodeValue) ? trim($desc->item(0)->nodeValue) : "";

            //images
            @$images = $xpath->query($defs->images);
            if(@$images->length > 0){
                $ix = 0;
                foreach ($images as $i){
                    if($i->nodeValue != "https://images-na.ssl-images-amazon.com/images/G/01/x-locale/common/transparent-pixel._V192234675_.gif") {
                        $data['images'][$ix] = str_replace("._AC_US40_", "", $this->image_cleaner($i->nodeValue));
                        $ix++;
                    }
                }
            }

            //rating
            @$star = $xpath->query($defs->rating);
            $st = isset($star->item(0)->nodeValue) ? trim($star->item(0)->nodeValue) : "";
            if($st !=""){
                $st = explode(' ',$st);
                $st = $st[1]!="yıldız" ? trim($st[0]) : trim($st[3]);
                $st = str_replace('5つ星のうち','',$st);
            }
            $data['rating'] = $st;

            //review
            @$review = $xpath->query($defs->review);
            $rv = isset($review->item(0)->nodeValue) ? trim($review->item(0)->nodeValue) : "";
            if($rv!=""){
                $rv = explode(' ',$rv);
                $rv = $rv[0] != "Liczba" ? $rv[0] : $rv[2];
                $rv = str_replace('個の評価','',$rv);
            }
            $data['review'] = trim($rv);

            //Summary
            @$sum = $xpath->query($defs->summary);
            if(isset($sum->item(0)->nodeValue)){
                foreach ($sum as $k=> $s) {
                    if($k==0) {
                        $smxpath = $this->html_dom($s, $dom);
                    }
                }
                $keys = $smxpath->query('//td[@class="a-span3"]/span');
                $values = $smxpath->query('//td[@class="a-span9"]/span');
                foreach ($keys as $k=> $key){
                    $data['sum'][trim(utf8_decode($key->nodeValue))] = trim(utf8_decode($values->item($k)->nodeValue));
                }

            }


            $cache = base64_encode(json_encode($data));

            $check = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'product_puller WHERE product_id="' . $product_id . '"');
            if ($check) {
                $wpdb->update(
                    $wpdb->prefix . 'product_puller',
                    array(
                        'title' => $tit,
                        'image' => $img,
                        'price' => $price,
                        'type' => 'amazon',
                        'local' => $this->get_local_amazon(),
                        'cache' => $cache
                    ),
                    array(
                        'product_id' => $product_id
                    )
                );
            } else {
                $wpdb->insert(
                    $wpdb->prefix . 'product_puller',
                    array(
                        'title' => $tit,
                        'image' => $img,
                        'price' => $price,
                        'type' => 'amazon',
                        'local' => $this->get_local_amazon(),
                        'product_id' => $product_id,
                        'cache' => $cache
                    )
                );
            }
            return $data;
            die();
        }
    }

    function amazon_standard_save(){
        global $wpdb;
        $price = sanitize_text_field($_POST['price']);
        $title = sanitize_text_field($_POST['title']);
        $img = sanitize_text_field($_POST['img']);
        $product_id = sanitize_text_field($_POST['product_id']);
        $check = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'product_puller WHERE product_id="'.$product_id.'"');
        if($check){
            $wpdb->update(
                $wpdb->prefix . 'product_puller',
                array(
                    'title' => $title,
                    'image' => $img,
                    'price' => $price,
                    'type' => 'amazon',
                    'local' => $this->get_local_amazon(),
                ),
                array(
                    'product_id'=> $product_id
                )
            );
        }else {
            $wpdb->insert(
                $wpdb->prefix . 'product_puller',
                array(
                    'title' => $title,
                    'image' => $img,
                    'price' => $price,
                    'type' => 'amazon',
                    'local' => $this->get_local_amazon(),
                    'product_id' => $product_id
                )
            );
        }

    }

    function amazon_standard_edit(){
        global $wpdb;
        $price = sanitize_text_field($_POST['price']);
        $title = sanitize_text_field($_POST['title']);
        $id = sanitize_text_field($_POST['id']);
        $wpdb->update($wpdb->prefix.'product_puller',
            array(
                'title' => $title,
                'price' => $price
            ),
            array(
                'id' => $id
            )
        );
    }

    function amazon_local_save(){
        if(get_option('product_puller_local_amazon')){
            update_option('product_puller_local_amazon', sanitize_text_field($_POST['local']), 'yes' );
        }else{
            add_option( 'product_puller_local_amazon', sanitize_text_field($_POST['local']), '', 'yes' );
        }
    }

    function check_api(){
        $this->delete_api_defs();
        $api = sanitize_text_field($_POST['api']);
        $data = $api != "" ? $this->getApiData($api) : "";
        $arr = array();
        if($data==""){
            $arr['member'] ="passive";
            $data = json_encode($data);
        }else{
            $json = json_decode($data);
            if($json->member == "active"){
                add_option('product_puller_api_key',$api,'','yes');
                $defs = $this->api_definitions();
                foreach ($defs as $d){
                    if(isset($json->$d)){
                        add_option('product_puller_'.$d,$json->$d,'','yes');
                    }
                }
            }
        }
        echo $data;
        die();
    }

    function save_lang(){
        $langs = $_POST['lang'];
        $lang = base64_encode(json_encode($langs));
        if(get_option('product_puller_lang')){
            update_option('product_puller_lang',$lang,'yes');
        }else{
            add_option('product_puller_lang',$lang,'','yes');
        }
        die();
    }

    function save_woo(){
        //Clerk
        $api = get_option('product_puller_api_key') ? get_option('product_puller_api_key') : "";
        $Api = json_decode($this->getApiData($api));
        eval($this->decrypt($Api->plr_woo,$Api->secret));
    }

    function save_affi(){
        $affi = sanitize_text_field($_POST['affi']);
        $this->import_option('affi_id',$affi);
        die();
    }

    function comparisonAjax(){
        $type = sanitize_text_field($_POST['type']);
        $asin = sanitize_text_field($_POST['asin']);
        if($type=="ready"){
            $this->amazon_comparison_fetch($asin,$_POST['before']);
        }else{
            $this->amazon_for_comparison_fetch($asin,$_POST['before']);
        }
        die();
    }

    function comparisonAddAjax(){
        global $wpdb;
        $items = base64_encode(sanitize_text_field(json_encode($_POST['items'])));
        $title = sanitize_text_field($_POST['title']);
        $price = sanitize_text_field($_POST['sort']);
        $edit = sanitize_text_field($_POST['edit']);
        $type = 'comparison';
        if(@$edit == "no") {
            $wpdb->insert(
                $wpdb->prefix . 'product_puller',
                array(
                    'title' => $title,
                    'price' => $price,
                    'type' => $type,
                    'cache' => $items,
                    'product_id' => $this->create_id_code('AMZN')
                )
            );
        }else{
            $id = esc_sql($edit);
            $wpdb->update(
                $wpdb->prefix . 'product_puller',
                array(
                    'title' => $title,
                    'price' => $price,
                    'type' => $type,
                    'cache' => $items,
                ),
                array(
                    'id' => $id
                )
            );
        }

        die();
    }

    function compileAttr(){
        $items = $_POST['items'];
//        echo json_encode($items);
        $attr_arr = array();
        $x = 0;
        $count = 0;
        foreach ($items as $k => $item){
            foreach ($item as $attr => $value){
                $attr_arr[$x] = $attr;
                $x++;
            }
            if(count($item)>$count){
                $count = count($item);
                $biggest = $k;
            }
        }
        $arr = array_unique($attr_arr);
        foreach ($items as $k => $item){
            foreach ($arr as $a){
                $items[$k][$a] = $items[$k][$a] ?? "---";
            }

        }
        foreach ($items as  $k=> $item){
            $items[$k] = array_merge($items[$biggest],$item);
        }

        echo json_encode($items);
        die();
    }


}