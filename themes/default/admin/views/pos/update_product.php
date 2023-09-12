<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Add_Product'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form');
        echo admin_form_open("pos/update_product/", $attrib); ?>
        <div class="modal-body">
            <div class="form-group all">
                <?= lang("product_name", "name") ?>
                <?= form_input('name', (isset($_POST['name']) ? $_POST['name'] : ($product ? $product->name : '')), 'class="form-control gen_slug" id="name" required="required"'); ?>
            </div>
            <div class="row">
			    <div class="col-md-12">
			        <div class="row">
			            <div class="col-md-8">
			                <?= lang("product_code", "code") ?>
			                <div class="input-group">
			                    <?= form_input('code', (isset($_POST['code']) ? $_POST['code'] : ($product ? $product->code : '')), 'class="form-control" id="code"  required="required"') ?>
			                    <span class="input-group-addon pointer" id="random_num" style="padding: 1px 10px;">
			                                <i class="fa fa-random"></i>
			                            </span>
			                </div>
			            </div>
			            <div class="col-md-4">
			                <?= lang("category", "category") ?>
				                <?php
				                $cat[''] = "";
				                foreach ($categories as $category) {
				                    $cat[$category->id] = $category->name;
				                }
				                echo form_dropdown('category', $cat, (isset($_POST['category']) ? $_POST['category'] : ($product ? $product->category_id : '')), 'class="form-control select" id="category" placeholder="' . lang("select") . " " . lang("category") . '" required="required" style="width:100%"')
				                ?>
			            </div>
			        </div>
			    </div>
			</div>
            <div class="row">
			    <div class="col-md-12">
			        <div class="row">
			        	<div class="col-md-4">
			                <?= lang('product_unit', 'unit'); ?>
                			<?php
                			$pu[''] = lang('select').' '.lang('unit');
                			foreach ($base_units as $bu) {
                			    $pu[$bu->id] = $bu->name .' ('.$bu->code.')';
                			}
                			?>
                			<?= form_dropdown('unit', $pu, set_value('unit', ($product ? $product->unit : '')), 'class="form-control tip" id="unit" required="required" style="width:100%;"'); ?>
			            </div>
			            <div class="col-md-4">
                			<?= lang("product_cost", "cost") ?>
                			<?= form_input('cost', (isset($_POST['cost']) ? $_POST['cost'] : ($product ? $this->sma->formatDecimal($product->cost) : '')), 'class="form-control tip" id="cost" required="required"') ?>
            			</div>
            			<div class="col-md-4">
			                <?= lang("product_price", "price") ?>
                			<?= form_input('price', (isset($_POST['price']) ? $_POST['price'] : ($product ? $this->sma->formatDecimal($product->price) : '')), 'class="form-control tip" id="price" required="required"') ?>
			            </div>
			        </div>
			    </div>
			</div>
            
            <div class="row">
			    <div class="col-md-12">
			        <div class="row">
			            <?php if ($Settings->tax1) { ?>
			            <div class="col-md-4">
			                <?= lang('product_tax', "tax_rate") ?>
			                    <?php
			                    $tr[""] = "";
			                    foreach ($tax_rates as $tax) {
			                        $tr[$tax->id] = $tax->name;
			                    }
			                    echo form_dropdown('tax_rate', $tr, (isset($_POST['tax_rate']) ? $_POST['tax_rate'] : ($product ? $product->tax_rate : $Settings->default_tax_rate)), 'class="form-control select" id="tax_rate" placeholder="' . lang("select") . ' ' . lang("product_tax") . '" style="width:100%"')
			                    ?>
			            </div>
			            <div class="col-md-4">
			                <?= lang("tax_method", "tax_method") ?>
			                    <?php
			                    $tm = array('1' => lang('exclusive'), '0' => lang('inclusive'));
			                    echo form_dropdown('tax_method', $tm, (isset($_POST['tax_method']) ? $_POST['tax_method'] : ($product ? $product->tax_method : '')), 'class="form-control select" id="tax_method" placeholder="' . lang("select") . ' ' . lang("tax_method") . '" style="width:100%"');
			                    ?>
			            </div>
			            <?php } ?>
			            <div class="col-md-4">
			                <?= lang("Alert_Quantity", "alert_quantity") ?>
	                        <div
	                            class="input-group"> <?= form_input('alert_quantity', (isset($_POST['alert_quantity']) ? $_POST['alert_quantity'] : ($product ? $this->sma->formatQuantityDecimal($product->alert_quantity) : '')), 'class="form-control tip" id="alert_quantity"') ?>
	                            <span class="input-group-addon">
	                            <input type="checkbox" name="track_quantity" id="track_quantity"
	                                   value="1" <?= ($product ? (isset($product->track_quantity) ? 'checked="checked"' : '') : 'checked="checked"') ?>>
	                        	</span>
	                        </div>
			            </div>
			        </div>
			    </div>
			</div>
            <div class="form-group all">
                
            </div>

            

        </div>
            <div class="modal-footer">
                <?php echo form_submit('update', lang('update'), 'class="btn btn-primary"'); ?>
            </div>
    </div>
    <?php echo form_close(); ?>
</div>
<script>
    $('#random_num').click(function() {
        $(this)
            .parent('.input-group')
            .children('input')
            .val(generateCardNo(8));
    });
</script>
<?= $modal_js ?>
