<?php
/**
 * @var $title
 * @var $html
 * @var prdct_pllr_model $this
 * @var $local
 * @var $product_id
 * @var $price
 * @var $image
 * @var $cache
 */
$url = $this->create_amazon_link($product_id);
@$json = json_decode(base64_decode($cache));
$first = "";
if(isset($json)){

    $html = '<div id="product-puller-content">';
    $html .= '<div id="product-view">';

    $html .= '<div class="preview-image">';
    $html .= '<div id="thumbnail-container">';
    if(isset($json->images)){
        foreach ($json->images as $k=>$im){
            $first = $k==0 ? $im : $first;
            if($im !="https://images-na.ssl-images-amazon.com/images/G/01/x-locale/common/transparent-pixel._V192234675_.gif" && $im !=""){

                $html .= '<img class="thumbnail focused" src="'.esc_url_raw($this->image_cleaner($im)).'">';

            }

        }
    }else{
        $first = $image;
    }

    $html .= '</div>';
    $html .= '<div id="preview-enlarged">';
    $html .= '<img src="'.esc_url_raw($this->image_cleaner($first)).'">';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '<div class="product-info">';
    $html .= '<div class="product-title">'.esc_html($this->text_cleaner($title)).'</div>';
    $html .= '<div class="rating">';
    if($json->rating!=""):
        $html .= '<div class="Stars" style="--rating: '.str_replace(',','.',esc_html($json->rating)).'" aria-label="Rating of this product is '.$json->rating.' out of 5.">';
        $html .= '</div>'.esc_html($json->rating).'/5 &nbsp;&nbsp;&nbsp;&nbsp;'.esc_html($json->review).' '.$this->lang('ratings');
    endif;

    $html .= '</div>';
    $html .= '<hr>';
    $html .= '<div>';
    $html .= '<div style="float: left">';
    $html .= $price!="---" ? $this->lang('Price').': <b style="font-size:1.1em;">'.esc_html($price).'</b><br/><br/>' :"";
    $html .= '</div>';
    $html .= '<div style="float: right">';
    $html .= '<a href="'.esc_url_raw($url).'" target="_blank"><img src="'.$this->asset('amazon5.png').'" style="width: 180px;"></a>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '<div style="clear: both;margin-bottom: 10px"></div>';

    $html .= '<div class="description">';
    $html .= '<p>';

    if(isset($json->sum)){
        foreach ($json->sum as $k => $s){
            $html.= '<strong>'.esc_html($k).':</strong>  '.esc_html($s)."<br/>";
        }
    }


    $html .= '</p>';
    $html .= '</div>';


    $html .= '</div>';

    $html .= '</div>';

    $html .= '<div>';


    if(@$json->about!=""){

        $html .= '<div style="font-size: 18px;font-weight: bold">'.esc_html($this->lang("About")).'</div>';

        foreach ($json->about as $a) {
            $html .= '<div><b>.</b> ' . esc_html($a) . '</div>';
        }
    }



    $html .= '</div>';
    $html .= '<div>';


    if(@$json->desc!=""){

        $html .= '<div style="font-size: 18px;font-weight: bold">'.esc_html($this->lang('Product description')).'</div>';

        $html .= esc_html($json->desc);
    }



    $html .= '</div>';
    $html .= '<div style="display:table; width: 100%;margin-top: 10px">';


    if(isset($json->details)){

        $html .= '<div style="font-size: 18px;font-weight: bold">'.esc_html($this->lang("Details")).'</div>';

        foreach ($json->details  as $k => $value){

            $html .= '<div style="display: table-row">';
            $html .= '<div style="display: table-cell;width:50%; background-image:url('.$this->asset('details-bg.png').');background-repeat: repeat;padding: 10px; border-bottom:1px solid #ccc">';
            $html .= esc_html($k);
            $html .= '</div>';
            $html .= '<div style="display: table-cell;width:50%;padding: 10px;border-bottom:1px solid #ccc" >'.esc_html($value).'</div>';
            $html .= '</div>';

        }
    }

    $html .='</div>';

    $html .='</div>';

    return $html;

}else{
    return "<b>You have to pull this product again! If you have pulled this product data without entering your api-key, the detailed information of this product has not been imported to the database. </b>";
}