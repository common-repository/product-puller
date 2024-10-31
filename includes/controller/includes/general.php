<?php
class prdct_pllr_general_controller extends prdct_pllr_model{

    function get_product($id){
        global $wpdb;
        $results = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'product_puller WHERE product_id="'.esc_sql($id).'"');
        $data = "";
        if(count($results)>0) {
            foreach ($results as $r){
                $data = $r;
            }
        }
        return $data;
    }

    function get_product_by_id($id){
        global $wpdb;
        $results = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'product_puller WHERE id="'.esc_sql($id).'"');
        $data = "";
        if(count($results)>0) {
            foreach ($results as $r){
                $data = $r;
            }
        }
        return $data;
    }

    function amazon_comparison_fetch($id,$before){
        $path = $this->getDomProductAmazon($id);
        $xpath = $path['xpath'];
        $dom = $path['dom'];
        $comp = array();
        $comp['before'] = $before;
        //Products
        @$products = $xpath->query('//tr[@class="comparison_table_image_row"]/th');
        if(@$products->length>0){

            foreach ($products as $k => $p){
                $asin = $p->getAttribute('data-asin');
                $comp['asin'][] = $asin;
                @$img = $xpath->query('//th[@data-asin="'.$asin.'"]//div/center/img');
                $comp['image'][$asin] = isset($img->item(0)->nodeValue) ? esc_url_raw($img->item(0)->getAttribute('data-src')) : "";
                $comp['title'][$asin] = isset($img->item(0)->nodeValue) ? esc_html($img->item(0)->getAttribute('alt')) : "";
            }
        }
        //Ratings
        @$ratings = $xpath->query('//tr[@id="comparison_custormer_rating_row"]/td');
        if(@$ratings->length>0){
            foreach ($ratings as $r){
                $rpath = $this->html_dom($r,$dom);
                @$rate = $rpath->query('//span[contains(@class,"declarative")]');
                $asin = isset($rate->item(0)->nodeValue) ? json_decode($rate->item(0)->getAttribute('data-a-popover')) : "";
                $url_components = parse_url($asin->url);
                parse_str($url_components['query'], $params);
                $asin = trim($params['asin']);
                @$rates = $rpath->query('//i/span');
                $st = isset($rates->item(0)->nodeValue) ? $rates->item(0)->nodeValue : "";
                $st = explode(' ',$st);
                $st = $st[1]!="yıldız" ? trim($st[0]) : trim($st[3]);
                $st = str_replace('5つ星のうち','',$st);
                $rating = $st;
                $comp['rating'][$asin] = $rating;
                $votes = $rpath->query('//a[@class="a-link-normal"]');
                $vote = isset($votes->item(0)->nodeValue) ? str_replace("(","",$votes->item(0)->nodeValue) : "";
                $comp['vote'][$asin] = str_replace(")","",$vote);
            }
        }
        //Prices
        @$prices = $xpath->query('//tr[@id="comparison_price_row"]/td');
        if(@$prices->length>0){
            foreach ($prices as $k=>$p){
                $ppath = $this->html_dom($p,$dom);
                @$price = $ppath->query('//span[@class="a-offscreen"]');
                $price = isset($price->item(0)->nodeValue) ? $price->item(0)->nodeValue : "";
                if($price==""){
                    @$price = $ppath->query('//span[contains(@class,"a-color-price")]');
                    $price = isset($price->item(0)->nodeValue) ? trim(utf8_decode($price->item(0)->nodeValue)) : "";
                }
                @$symbol = $ppath->query('//span[@class="a-price-symbol"]');
                $symbol = isset($symbol->item(0)->nodeValue) ? $symbol->item(0)->nodeValue : "";
                $whole = $ppath->query('//span[@class="a-price-whole"]');
                $decimal =  $ppath->query('//span[@class="a-price-decimal"]');
                $fraction =  $ppath->query('//span[@class="a-price-fraction"]');
                $whole = isset($whole->item(0)->nodeValue) ? $whole->item(0)->nodeValue : "";
                $decimal = isset($decimal->item(0)->nodeValue) ? $decimal->item(0)->nodeValue : "";
                $fraction = isset($fraction->item(0)->nodeValue) ? $fraction->item(0)->nodeValue : "";
                $real = $whole.$fraction;
                $comp['price'][$comp['asin'][$k]]['symbol'] = $symbol;
                $comp['price'][$comp['asin'][$k]]['real'] = $real;
                $comp['price'][$comp['asin'][$k]]['price'] = $price;

            }
        }
        //attributes
        @$attrs = $xpath->query('//tr[@class="comparison_other_attribute_row"]');
        if(@$attrs->length>0){
            foreach ($attrs as $a){
                $apath = $this->html_dom($a,$dom);
                @$attr = $apath->query('//th/span');
                $attr = isset($attr->item(0)->nodeValue) ? esc_html(utf8_decode($attr->item(0)->nodeValue)) : "";
                @$attr_values = $apath->query('//td/span');

                if(@$attr_values->length>0){
                    foreach ($attr_values as $k=>$at){

                        $comp['attr'][$attr][$comp['asin'][$k]] = $at->nodeValue;
                    }

                }
            }
        }

        $this->view('comparison.amazon.add',$comp);



    }

    function amazon_for_comparison_fetch($id,$before){
        $path = $this->getDomProductAmazon($id);
        $xpath = $path['xpath'];
        $dom = $path['dom'];
        $data = array();
        $data['asin'][0] = $id;
        $data['before'] = $before;
        $defs = json_decode(base64_decode(get_option('product_puller_plr_fetch_full')));
        @$title = $xpath->query($defs->title);
        $data['title'][$id] = isset($title->item(0)->nodeValue) ? trim($title->item(0)->nodeValue) : "";
        @$price = $xpath->query('//span[contains(@id,"priceblock")]');
        $data['price'][$id]['price'] = isset($price->item(0)->nodeValue) ? trim($price->item(0)->nodeValue) : "";
        $data['price'][$id]['real'] = "";
        $data['price'][$id]['symbol']= "";
        @$images = $xpath->query($defs->images);
        if(@$images->length > 0){
            $data['image'][$id]=str_replace("._AC_US40_","",$images->item(0)->nodeValue);
        }
        @$details = $xpath->query($defs->details);
        if (@$details->length > 0) {
            foreach ($details as $d) {
                $dpath = $this->html_dom($d, $dom);
                @$detail_titles = $dpath->query('//th');
                @$details = $dpath->query('//td');
                if (@$detail_titles->length > 0)
                    foreach ($detail_titles as $k => $t) {
                        $title = utf8_decode($t->nodeValue);
                        $detail = $details->item($k)->nodeValue;
                        if ($this->AmazonProductDetailMacth($title)) {
                            $data['attr'][$title][$id] = $detail;
                        }
                    }

            }

        }else{
            @$details = $xpath->query('//table[@class="a-bordered"]//tr');
            if(@$details->length > 0){
                foreach ($details as $d){
                    $dpath = $this->html_dom($d,$dom);
                    $detail = $dpath->query('//td/p');
                    $data['attr'][esc_html(trim(utf8_decode($detail->item(0)->nodeValue)))][$id] = esc_html(trim(utf8_decode($detail->item(1)->nodeValue)));
                }
            }
        }
        //rating
        @$rate = $xpath->query($defs->rating);
        $st = isset($rate->item(0)->nodeValue) ? trim($rate->item(0)->nodeValue) : "";
        if($st !=""){
            $st = explode(' ',$st);
            $st = $st[1]!="yıldız" ? trim($st[0]) : trim($st[3]);
            $st = str_replace('5つ星のうち','',$st);
        }
        $data['rating'][$id] = $st;

        //review
        @$vote = $xpath->query($defs->review);
        $rv = isset($vote->item(0)->nodeValue) ? trim($vote->item(0)->nodeValue) : "";
        if($rv!=""){
            $rv = explode(' ',$rv);
            $rv = $rv[0] != "Liczba" ? $rv[0] : $rv[2];
            $rv = str_replace('個の評価','',$rv);
        }
        $data['vote'][$id] = trim($rv);
        $this->view('comparison.amazon.add',$data);

    }
}