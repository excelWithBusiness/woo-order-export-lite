<div id='fields_control_products' class='fields_control_style' style="display: none;">
    <div class='div_meta' >
        <label>Meta key:</label><select id='select_custom_meta_products'>
                <?php
                foreach (WC_Order_Export_Data_Extractor::get_all_product_custom_meta_fields() as $meta_id => $meta_name) {
                    echo "<option value=$meta_name >$meta_name</option>";
                };
                ?>
            </select>
        <label>Column Name:</label><input type='text' id='colname_custom_meta_products'/>
        <div style="text-align: right;">
            <button  id='button_custom_meta_products' class='button-secondary'>Add Field</button>
        </div>
    </div>
    <div class='div_custom'> 
        <label>Column Name:</label><input type='text' id='colname_custom_field_products'/>
        <label>Value:</label><input type='text' id='value_custom_field_products'/>
        <div style="text-align: right;">
            <button  id='button_custom_field_products' class='button-secondary'>Add Static Field</button>
        </div>
    </div>           
</div>

<div id='fields_control_coupons' class='fields_control_style' style="display: none;">
    <div class='div_meta' >
        <label>Meta key:</label><select id='select_custom_meta_coupons'>
                <?php
                foreach (WC_Order_Export_Data_Extractor::get_all_coupon_custom_meta_fields() as $meta_id => $meta_name) {
                    echo "<option value=$meta_name >$meta_name</option>";
                };
                ?>
            </select>
        <label>Column Name:</label><input type='text' id='colname_custom_meta_coupons'/></label>
        <div style="text-align: right;">
            <button  id='button_custom_meta_coupons' class='button-secondary'>Add Field</button>
        </div>
    </div>
    <div class='div_custom'> 
        <label>Column Name:</label><input type='text' id='colname_custom_field_coupons'/></label>
        <label>Value:</label><input type='text' id='value_custom_field_coupons'/></label>
        <div style="text-align: right;">
            <button  id='button_custom_field_coupons' class='button-secondary'>Add Static Field</button>
        </div>
    </div>           
</div>