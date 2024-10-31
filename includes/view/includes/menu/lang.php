<?php
function prdct_pllr_view_menu_langs()
{
    if (!current_user_can('manage_options')) {
        return;
    }
   $model = new prdct_pllr_model();
    ?>
    <h1><?php esc_html_e(get_admin_page_title()); ?></h1>
    <button class="button" id="plr_save_lang">Save</button>
    <div id="test"></div>
    <div id="plr_lang_form" style="display: table">
        <div style="display: table-row;padding: 20px">
            <div style="display: table-cell;width: 200px;padding: 20px">Details: </div>
            <div style="display: table-cell;padding: 20px"><input style="width: 300px" class="plr-lang-inputs" type="text" name="Details" value="<?php echo esc_html($model->lang('Details'))?>"></div>
        </div>
        <div style="display: table-row;padding: 20px">
            <div style="display: table-cell;width: 200px;padding: 20px">About: </div>
            <div style="display: table-cell;padding: 20px">
                <input style="width: 300px" class="plr-lang-inputs" type="text" name="About" value="<?php echo esc_html($model->lang('About'))?>">
            </div>
        </div>
        <div style="display: table-row;padding: 20px">
            <div style="display: table-cell;width: 200px;padding: 20px">Summary: </div>
            <div style="display: table-cell;padding: 20px">
                <input style="width: 300px" class="plr-lang-inputs" type="text" name="Summary" value="<?php echo esc_html($model->lang('Summary'))?>">
            </div>
        </div>
        <div style="display: table-row;padding: 20px">
            <div style="display: table-cell;width: 200px;padding: 20px">Price: </div>
            <div style="display: table-cell;padding: 20px">
                <input style="width: 300px" class="plr-lang-inputs" type="text" name="Price" value="<?php echo esc_html($model->lang('Price'))?>">
            </div>
        </div>
        <div style="display: table-row;padding: 20px">
            <div style="display: table-cell;width: 200px;padding: 20px">Product description: </div>
            <div style="display: table-cell;padding: 20px">
                <input style="width: 300px" class="plr-lang-inputs" type="text" name="Product description" value="<?php echo esc_html($model->lang('Product description'))?>">
            </div>
        </div>
        <div style="display: table-row;padding: 20px">
            <div style="display: table-cell;width: 200px;padding: 20px"># ratings: </div>
            <div style="display: table-cell;padding: 20px">
                <input style="width: 300px" class="plr-lang-inputs" type="text" name="ratings" value="<?php echo esc_html($model->lang('ratings'))?>">
            </div>
        </div>
        <div style="display: table-row;padding: 20px">
            <div style="display: table-cell;width: 200px;padding: 20px">Customer Rating: </div>
            <div style="display: table-cell;padding: 20px">
                <input style="width: 300px" class="plr-lang-inputs" type="text" name="Customer Rating" value="<?php echo esc_html($model->lang('Customer Rating'))?>">
            </div>
        </div>
        <div style="display: table-row;padding: 20px">
            <div style="display: table-cell;width: 200px;padding: 20px">Cons: </div>
            <div style="display: table-cell;padding: 20px">
                <input style="width: 300px" class="plr-lang-inputs" type="text" name="Cons" value="<?php echo esc_html($model->lang('Cons'))?>">
            </div>
        </div>
        <div style="display: table-row;padding: 20px">
            <div style="display: table-cell;width: 200px;padding: 20px">Pros: </div>
            <div style="display: table-cell;padding: 20px">
                <input style="width: 300px" class="plr-lang-inputs" type="text" name="Pros" value="<?php echo esc_html($model->lang('Pros'))?>">
            </div>
        </div>
        <div style="display: table-row;padding: 20px">
            <div style="display: table-cell;width: 200px;padding: 20px">Conclusion: </div>
            <div style="display: table-cell;padding: 20px">
                <input style="width: 300px" class="plr-lang-inputs" type="text" name="Conclusion" value="<?php echo esc_html($model->lang('Conclusion'))?>">
            </div>
        </div>
        <div style="display: table-row;padding: 20px">
            <div style="display: table-cell;width: 200px;padding: 20px">Best Choice: </div>
            <div style="display: table-cell;padding: 20px">
                <input style="width: 300px" class="plr-lang-inputs" type="text" name="Best Choice" value="<?php echo esc_html($model->lang('Best Choice'))?>">
            </div>
        </div>
        <div style="display: table-row;padding: 20px">
            <div style="display: table-cell;width: 200px;padding: 20px">Good Choice: </div>
            <div style="display: table-cell;padding: 20px">
                <input style="width: 300px" class="plr-lang-inputs" type="text" name="Good Choice" value="<?php echo esc_html($model->lang('Good Choice'))?>">
            </div>
        </div>

    </div>
    <?php
}