<?php
class prdct_pllr_model{
    function view($path,$args=""){
        $path = str_replace('.','/',$path);
        if($args!=""){
            extract($args, EXTR_PREFIX_SAME, "wddx");
        }
        return include PRDP_VIEW_INC.$path.'.php';
    }

    function get_local_amazon(){
        if(get_option('product_puller_local_amazon')){
            return get_option('product_puller_local_amazon');
        }else{
            return "us";
        }
    }

    function check_api_imported(){
        if(get_option('product_puller_api_key')){
            return get_option('product_puller_api_key');
        }else{
            return "none";
        }
    }

    function getApiData($api){
        $url = $this->baseUrl().$api;
        $site = $this->get_my_url();
        $resolve = array(sprintf(
            "%s:%d:%s",
            $site,
            21,
            gethostbyname($site)
        ));

        $response = wp_remote_get(
            esc_url_raw($url),
            array(
                'headers' => array(
                    'User-Agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Encoding' => 'gzip,deflate',
                    'Accept-Charset' => 'utf-8,ISO-8859-1;q=0.7,*;q=0.7',
                    'Keep-Alive' => '115',
                    'Connection' => 'keep-alive',
                    'Cache-Control' => 'maxe-age=0',
                    'HTTP_X_FORWARDED_FOR' => gethostbyname($site),
                    'HTTP_CF_CONNECTING_IP' => gethostbyname($site),
                    'HTTP_CLIENT_IP' => gethostbyname($site),
                    'resolve' => $resolve,
                    'referer' => $site,
                ),

            )
        );
        return $response['body'];
    }

    function getFromAmazon($url,$value="",$page=1){
        $local = $this->get_local_amazon();
        $lang = $this->amazon_lang();

        $value = $this->searchSlug(html_entity_decode($value));
        $new = $url.'/s?k='.$value.($page==1 ? "" : "&page=".$page);
        $response = wp_remote_get(
            esc_url_raw($new),
            array(
                'headers' => array(
                    'User-Agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Encoding' => 'gzip,deflate',
                    'Accept-Charset' => 'utf-8,ISO-8859-1;q=0.7,*;q=0.7',
                    'Accept-Language' => $lang.','.$local.';q=0.5',
                    'Keep-Alive' => '115',
                    'Connection' => 'keep-alive',
                    'Cache-Control' => 'maxe-age=0'

                )
            )
        );
        return $response['body'];
    }

    function getProductFromAmazon($id){
        $local = $this->get_local_amazon();
        $com = $this->amazon_site($local);
        $lang = $this->amazon_lang();
        $url = 'https://www.amazon.'.$com.'/dp/'.$id;
        $response = wp_remote_get(
            esc_url_raw($url),
            array(
                'headers' => array(
                    'User-Agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Accept-Encoding' => 'gzip,deflate',
                    'Accept-Charset' => 'utf-8,ISO-8859-1;q=0.7,*;q=0.7',
                    'Accept-Language' => $lang.','.$local.';q=0.5',
                    'Keep-Alive' => '115',
                    'Connection' => 'keep-alive',
                    'Cache-Control' => 'maxe-age=0'

                )
            )
        );
        return $response['body'];
    }

    function getDomProductAmazon($id){
        $id = sanitize_text_field($id);
        $html = $this->getProductFromAmazon($id);
        $dom = new DomDocument(null, 'utf-8');
        @$dom->LoadHTML($html);
        /* Create a new XPath object */
        $xpath = new DomXPath($dom);
        $path = array();
        $path['xpath'] = $xpath;
        $path['dom'] = $dom;
        return $path;
    }


    function searchSlug($value){
        return strtolower(urlencode($value));
    }

    function getDomAmazon($value,$page)
    {
        $local = $this->get_local_amazon();
        $com = $this->amazon_site($local);
        $url = 'https://www.amazon.'.$com;
        $data = $this->getFromAmazon($url,$value,$page);
        $dom = new DomDocument(null, 'utf-8');
        @$dom->LoadHTML($data);
        /* Create a new XPath object */
        $xpath = new DomXPath($dom);
        $path = array();
        $path['xpath'] = $xpath;
        $path['dom'] = $dom;
        return $path;

    }
    function html_dom($data,$xdom)
    {
        $dom = new DomDocument(null, 'utf-8');
        $html = $xdom->saveHTML($data);
        @$dom->LoadHTML($html);
        /* Create a new XPath object */
        $xpath = new DomXPath($dom);
        return $xpath;

    }

    function convert_product_id($link){
        $link = explode('dp/',$link);
        $link = explode('/',$link[1]);
        return str_replace('?dchild=1','',$link[0]);
    }


    function amazon_lang(){
        $local = $this->get_local_amazon();
        $url = 'en_US';
        switch ($local){
            //USA
            case "us":
                $url = "en_US";
                break;
            //Turkey
            case "tr":
                $url = "tr_TR";
                break;
            //UK
            case "uk":
                $url = "en_GB";
                break;
            //Japan
            case "jp":
                $url = "ja_JP";
                break;
            //Australia
            case "au":
                $url = "en_AU";
                break;
            //Brazil
            case "br":
                $url = "pt_BR";
                break;
            //Canada
            case "ca":
                $url = "en_CA";
                break;
            //China
            case "cn":
                $url = "zh_CN";
                break;
            //France
            case "fr":
                $url = "fr_FR";
                break;
            //Germany
            case "de":
                $url = "de_DE";
                break;
            //India
            case "in":
                $url = "hi_IN";
                break;
            //Italy
            case "it":
                $url = "it_IT";
                break;
            //Mexico
            case "mx":
                $url = "es_MX";
                break;
            //Netherlands
            case "nl":
                $url = "nl_NL";
                break;
            //Poland
            case "pl":
                $url = "pl_PL";
                break;
            //Singapore
            case "sg":
                $url = "en_SG";
                break;
            //Spain
            case "es":
                $url = "es_ES";
                break;
            //Sweden
            case "se":
                $url = "sv_SE";
                break;
            //United Arab Emirates
            case "ae":
                $url = "ar_AE";
                break;
            case "sa":
                $url = "ar_SA";
                break;

        };
        return $url;
    }


    function moneyFormat($number){
        $formatter = new NumberFormatter($this->amazon_lang(),  NumberFormatter::CURRENCY);
        return $formatter->format($number);
    }


    function amazon_site($local){
        $url = 'com';
        switch ($local){
            //USA
            case "us":
                $url = "com";
                break;
            //Turkey
            case "tr":
                $url = "com.tr";
                break;
            //UK
            case "uk":
                $url = "co.uk";
                break;
            //Japan
            case "jp":
                $url = "co.jp";
                break;
            //Australia
            case "au":
                $url = "com.au";
                break;
            //Brazil
            case "br":
                $url = "com.br";
                break;
            //Canada
            case "ca":
                $url = "ca";
                break;
            //China
            case "cn":
                $url = "cn";
                break;
            //France
            case "fr":
                $url = "fr";
                break;
            //Germany
            case "de":
                $url = "de";
                break;
            //India
            case "in":
                $url = "in";
                break;
            //Italy
            case "it":
                $url = "it";
                break;
            //Mexico
            case "mx":
                $url = "com.mx";
                break;
            //Netherlands
            case "nl":
                $url = "nl";
                break;
            //Poland
            case "pl":
                $url = "pl";
                break;
            //Singapore
            case "sg":
                $url = "sg";
                break;
            //Spain
            case "es":
                $url = "es";
                break;
            //Sweden
            case "se":
                $url = "se";
                break;
            //United Arab Emirates
            case "ae":
                $url = "ae";
                break;
            case "sa":
                $url = "sa";
                break;

        };
        return $url;
    }

    function asset($key){
        return PRDP_ASSET.$key;
    }

    function text_cleaner($text){
        $text = str_replace("\\'","'", $text);
        $text = str_replace("\'","'",$text);
        $text = str_replace("\&#039;","'",$text);
        $text = str_replace('\\"','"',$text);
        $text = str_replace('\"','"',$text);
        return $text;
    }

    function baseUrl(){
        return 'https://pluginpress.net/api/member/';
    }

    function get_my_url(){
        global $wpdb;
        $url = "xxx.xx";
        if(get_site_url()){
            $url = get_site_url();
            $url = str_replace('https://',"",$url);
            $url = str_replace('http://',"",$url);
            $url = str_replace('www.',"",$url);
            $url = explode("/",$url);
            $url = $url[0];
        }
        return $url;
    }

    function AmazonProductDetailMacth($t){
        $local = $this->get_local_amazon();
        $x = "Customer Reviews";
        $y = "Best Sellers Rank";
        $z = "Date First Available";
        $a = "Amazon Bestseller";
        Switch ($local){
            //USA
            case "us":
                $x = "Customer Reviews";
                $y = "Best Sellers Rank";
                $z = "Date First Available";
                $a = "Amazon Bestseller";
                break;
            //Turkey
            case "tr":
                $x = "Müşteri Yorumları";
                $y = "En Çok Satanlar Sıralaması";
                $z = "Satışa Sunulduğu İlk Tarih";
                $a = "Amazon Bestseller";
                break;
            //UK
            case "uk":
                $x = "Customer Reviews";
                $y = "Best Sellers Rank";
                $z = "Date First Available";
                $a = "Amazon Bestseller";
                break;
            //Japan
            case "jp":
                $x = "Customer Reviews";
                $y = "Best Sellers Rank";
                $z = "Date First Available";
                $a = "Amazon Bestseller";
                break;
            //Australia
            case "au":
                $x = "Customer Reviews";
                $y = "Best Sellers Rank";
                $z = "Date First Available";
                $a = "Amazon Bestseller";
                break;
            //Brazil
            case "br":
                $x = "Avaliações de clientes";
                $y = "Ranking dos mais vendidos";
                $z = "Disponível para compra desde";
                $a = "Amazon Bestseller";
                break;
            //Canada
            case "ca":
                $x = "Customer Reviews";
                $y = "Best Sellers Rank";
                $z = "Date First Available";
                $a = "Amazon Bestseller";
                break;
            //China
            case "cn":
                $x = "用户评分";
                $y = "亚马逊热销商品排名";
                $z = "Amazon.cn上架时间";
                $a = "Amazon Bestseller";
                break;
            //France
            case "fr":
                $x = "Moyenne des commentaires client";
                $y = "Classement des meilleures ventes d'Amazon";
                $z = "Date de mise en ligne sur Amazon.fr";
                $a = "Amazon Bestseller";
                break;
            //Germany
            case "de":
                $x = "Durchschnittliche Kundenbewertung";
                $y = "Amazon Bestseller-Rang";
                $z = "Im Angebot von Amazon.de seit";
                $a = "Amazon Bestseller";
                break;
            //India
            case "in":
                $x = "Customer Reviews";
                $y = "Best Sellers Rank";
                $z = "Date First Available";
                $a = "Amazon Bestseller";
                break;
            //Italy
            case "it":
                $x = "Media recensioni";
                $y = "Posizione nella classifica Bestseller di Amazon";
                $z = "Disponibile su Amazon.it a partire dal";
                $a = "Amazon Bestseller";
                break;
            //Mexico
            case "mx":
                $x = "Producto en Amazon.com.mx desde";
                $y = "Opinión media de los clientes";
                $z = "Clasificación en los más vendidos de Amazon";
                $a = "Amazon Bestseller";
                break;
            //Netherlands
            case "nl":
                $x = "Klantenrecensies";
                $y = "Plaats in bestsellerlijst";
                $z = "Datum eerste beschikbaarheid";
                $a = "Amazon Bestseller";
                break;
            //Poland
            case "pl":
                $x = "Oceny klientów";
                $y = "Ranking najlepszych sprzedawców";
                $z = "Data pierwszej dostępności";
                $a = "Amazon Bestseller";
                break;
            //Singapore
            case "sg":
                $x = "Customer Reviews";
                $y = "Best Sellers Rank";
                $z = "Date First Available";
                $a = "Amazon Bestseller";
                break;
            //Spain
            case "es":
                $x = "Valoración media de los clientes";
                $y = "Clasificación en los más vendidos de Amazon";
                $z = "Producto en Amazon.es desde";
                $a = "Amazon Bestseller";
                break;
            //Sweden
            case "se":
                $x = "Customer Reviews";
                $y = "Best Sellers Rank";
                $z = "Date First Available";
                $a = "Amazon Bestseller";
                break;
            //United Arab Emirates
            case "ae":
                $x = "Customer Reviews";
                $y = "Best Sellers Rank";
                $z = "Date First Available";
                $a = "Amazon Bestseller";
                break;
            case "sa":
                $x = "مراجعات المستخدمين";
                $y = "تصنيف الأفضل مبيعاً";
                $z = "تاريخ توفر أول منتج";
                $a = "Amazon Bestseller";
                break;

        }


        if(trim($t) != $x && trim($t) != $y && trim($t) != $z && trim($t) != $a && trim($t) != "ASIN") {
            return true;
        }else{
            return false;
        }
    }

    function fixBadUnicodeForJson($str) {
        $str = preg_replace("/\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})/e", 'chr(hexdec("$1")).chr(hexdec("$2")).chr(hexdec("$3")).chr(hexdec("$4"))', $str);
        $str = preg_replace("/\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})/e", 'chr(hexdec("$1")).chr(hexdec("$2")).chr(hexdec("$3"))', $str);
        $str = preg_replace("/\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})/e", 'chr(hexdec("$1")).chr(hexdec("$2"))', $str);
        $str = preg_replace("/\\\\u00([0-9a-f]{2})/e", 'chr(hexdec("$1"))', $str);
        return $str;
    }
    function fixBadUnicode($str) {
        return utf8_decode(preg_replace("/\\\\u00([0-9a-f]{2})\\\\u00([0-9a-f]{2})/e", 'chr(hexdec("$1")).chr(hexdec("$2"))', $str));
    }

    function api_definitions(){
        $defs = [
            'plr_fetch_full',
            'product_comparison',
            'amazon_woo_puller'
        ];
        return $defs;
    }

    function api_def_longer($def){
        return 'product_puller_'.$def;
    }
    function delete_api_defs(){
        if(get_option('product_puller_api_key')){
            delete_option('product_puller_api_key');
        }

        $defs = $this->api_definitions();
        foreach ($defs as $d){
            if(get_option('product_puller_'.$d)){
                delete_option('product_puller_'.$d);
            }
        }
    }

    function checkPast($option){
        $value = false;
        if(get_option('product_puller_'.$option)){
            $date = new DateTime(get_option('product_puller_'.$option));
            $now  = new DateTime();
            if($now<$date){
                $value = true;
            }
        }
        return $value;
    }

    function image_cleaner($link){
        $link = explode('._S',$link);
        $link = explode('._AC',$link[0]);
        return $link[0].'..jpg';
    }

    function lang($text){
        if(get_option('product_puller_lang')){
            $json = json_decode(base64_decode(get_option('product_puller_lang')),true);
            return (isset($json[$text]) ? $json[$text] : $text);
        }else{
            return $text;
        }
    }

    function import_option($key,$data){
        if(get_option('product_puller_'.$key) OR get_option('product_puller_'.$key)==""){
            update_option('product_puller_'.$key,$data,'yes');
            if($data==""){
                delete_option('product_puller_'.$key);
            }
        }else{
            if($data==""){
                delete_option('product_puller_'.$key);
            }else {
                add_option('product_puller_' . $key, $data, '', 'yes');
            }
        }
    }

    function create_amazon_link($id){
        $local = $this->get_local_amazon();
        $url = $this->amazon_site($local);
        $amazon = 'https://amazon.'.$url;
        $tag = get_option('product_puller_affi_id') ? '/?tag='.get_option('product_puller_affi_id') : "";
        return $amazon.'/dp/'.$id.$tag;

    }

    function create_id_code($prefix){
        $time = microtime(true);
        $t = explode('.',$time);
        $code = $prefix.$t[0];
        return $code;
    }

    function getComparison($id){
        global $wpdb;
        $id = esc_sql($id);
        $result = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'product_puller WHERE type="comparison" AND id='.$id);
        $comp = "";
        if(count($result)>0){
            foreach ($result as $r){
                $comp = $r;
            }
        }
        return $comp;
    }

    function getByComparisonID($id){
        global $wpdb;
        $id = esc_sql($id);
        $result = $wpdb->get_results('SELECT * FROM '.$wpdb->prefix.'product_puller WHERE type="comparison" AND product_id='.$id);
        $comp = "";
        if(count($result)>0){
            foreach ($result as $r){
                $comp = $r;
            }
        }
        return $comp;
    }

    function hasFeaturedClass($data){
        return $data['featured'] ? ' item-comp-featured' : '';
    }

    function hasFeaturedAttr($data){
        return $data['featured'] ? 'font-weight:900;' : '';
    }

    function decrypt($data, $key, $method='AES-256-CBC')
    {
        $data = base64_decode($data);
        $ivSize = openssl_cipher_iv_length($method);
        $iv = substr($data, 0, $ivSize);
        $data = openssl_decrypt(substr($data, $ivSize), $method, $key, OPENSSL_RAW_DATA, $iv);

        return $data;
    }

    function Generate_Featured_Image_Woo( $image_url, $post_id , $featured=0 ){
        $upload_dir = wp_upload_dir();
        $image_data = file_get_contents($image_url);
        $filename = basename($image_url);
        if(wp_mkdir_p($upload_dir['path']))
            $file = $upload_dir['path'] . '/' . $filename;
        else
            $file = $upload_dir['basedir'] . '/' . $filename;
        file_put_contents($file, $image_data);

        $wp_filetype = wp_check_filetype($filename, null );
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        $res1= wp_update_attachment_metadata( $attach_id, $attach_data );
        if($featured == 1 ) {
            $res2 = set_post_thumbnail($post_id, $attach_id);
        }
        return $attach_id;
    }

    function addProductGallery($product_id, $image_id_array) {


        //if there is more than 1 image - add the rest to product gallery
        if(sizeof($image_id_array) > 1) {
            array_shift($image_id_array); //removes first item of the array (because it's been set as the featured image already)
            update_post_meta($product_id, '_product_image_gallery', implode(',',$image_id_array)); //set the images id's left over after the array shift as the gallery images
        }
    }

     function refLink($url){
         $ref = '&ref=wp_puller';
         $api = get_option('product_puller_api_key') ? '&api='.get_option('product_puller_api_key'): '';
         $from = '&from='.$this->get_my_url();
         $trash = '?Encoding=UTF8&showVariations=true&smid=A3P5ROKL5A1OLE&pf_rd_p=3d142137-7236-4d73-8df3-c0b943b7075e&pd_rd_wg=hXsJv&pf_rd_r=4A0R45W1V60BSTGG83HY';
         return $url.$trash.$ref.$api.$from;
     }

    function create_product_content($data){
        @$about = $data['about'];
        @$sum = $data['sum'];
        $content = "";
        if(count($sum)>0){
            $content .= '<b>'.$this->lang('Summary').'</b><br>';
            $content .= "<ul>";
            foreach ($sum as $k => $s) {
                $content .= '<li><b>'.esc_html(trim($k)).':</b> '.esc_html(trim($s)).'</li>';
            }
            $content .= "</ul>";
        }
        if(count($about)>0){
            $content .= '<b>'.$this->lang('About').'</b><br>';
            $content .= "<ul>";
            foreach ($about as $a) {
                $content .= '<li>'.esc_html(trim($a)).'</li>';
            }
            $content .= "</ul>";
        }
        return esc_sql($content);
    }

    function AddAttributes($product_id,$attrs){

        $attributes = array(); // Initializing

        // Loop through defined attribute data

        $key = 0;
        foreach ($attrs as $attr => $value) {
            // Clean attribute name to get the taxonomy
            $taxonomy = 'pa_' . wc_sanitize_taxonomy_name(trim($attr));


            $option_term_ids = array(); // Initializing

            // Loop through defined attribute data options (terms values)

            if (!term_exists($value, $taxonomy)) {
                wp_insert_term(
                    trim($value),
                    $taxonomy
                );

            }
            wp_set_object_terms($product_id, trim($value), $taxonomy, true);
            $option_term_ids[] = get_term_by('name', trim($value), $taxonomy)->term_id;

            // Loop through defined attribute data
            $attributes[$taxonomy] = array(
                'name' => $taxonomy,
                'value' => $option_term_ids, // Need to be term IDs
                'position' => $key + 1,
                'is_visible' => '1',
                'is_variation' => '1',
                'is_taxonomy' => '1'
            );
            $key++;
        }



        // Save the meta entry for product attributes
        update_post_meta( $product_id, '_product_attributes', $attributes );
        flush_rewrite_rules();
        delete_transient('wc_attribute_taxonomies');
    }

    function create_product_attribute( $label_name ){
        global $wpdb;

        $slug = sanitize_title(trim( $label_name) );
//        $slug =  wc_sanitize_taxonomy_name( trim($label_name) );

//        if ( strlen( $slug ) >= 28 ) {
//            return new WP_Error( 'invalid_product_attribute_slug_too_long', sprintf( __( 'Name "%s" is too long (28 characters max). Shorten it, please.', 'woocommerce' ), $slug ), array( 'status' => 400 ) );
//        } elseif ( wc_check_if_attribute_name_is_reserved( $slug ) ) {
//            return new WP_Error( 'invalid_product_attribute_slug_reserved_name', sprintf( __( 'Name "%s" is not allowed because it is a reserved term. Change it, please.', 'woocommerce' ), $slug ), array( 'status' => 400 ) );
//        } elseif ( taxonomy_exists( wc_attribute_taxonomy_name( $label_name ) ) ) {
//            return new WP_Error( 'invalid_product_attribute_slug_already_exists', sprintf( __( 'Name "%s" is already in use. Change it, please.', 'woocommerce' ), $label_name ), array( 'status' => 400 ) );
//        }

        if(!wc_check_if_attribute_name_is_reserved( $slug ) && !taxonomy_exists( wc_attribute_taxonomy_name( trim($label_name) ) )) {
            $data = array(
                'attribute_label' => $label_name,
                'attribute_name' => $slug,
                'attribute_type' => 'select',
                'attribute_orderby' => 'menu_order',
                'attribute_public' => 1, // Enable archives ==> true (or 1)
            );

            $results = $wpdb->insert("{$wpdb->prefix}woocommerce_attribute_taxonomies", $data);
//            $results = $wpdb->query('INSERT INTO '.$wpdb->prefix.'woocommerce_attribute_taxonomies (attribute_label,attribute_name,attribute_type,attribute_orderby,attribute_public) VALUES ("'.sanitize_text_field($label_name).'","'.$slug.'","select","menu_order",1)');

            if (!is_wp_error($results)) {
                $id = $wpdb->insert_id;

                do_action('woocommerce_attribute_added', $id, $data);
                flush_rewrite_rules();
                delete_transient('wc_attribute_taxonomies');

            }


        }
    }

}