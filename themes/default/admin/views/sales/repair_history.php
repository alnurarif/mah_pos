<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" style="text-align: center;" id="myModalLabel"><?php echo lang('Repair_History'); ?></h4>
        </div>
        <div class="modal-body">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table id="CompTable" cellpadding="0" cellspacing="0" border="0"
                               class="table table-bordered table-hover table-striped">
                            <thead>
                            <tr>
                                <th style="width:10%;"><?= $this->lang->line("Updated_Date"); ?></th>
                                <th style="width:15%;"><?= $this->lang->line("reference_no"); ?></th>
                                <th style="width:10%;"><?= $this->lang->line("Customer"); ?></th>
                                <th style="width:15%;"><?= $this->lang->line("Status"); ?></th>
                                <th style="width:15%;"><?= $this->lang->line("Signature"); ?></th>
                                <th style="width:25%;"><?= $this->lang->line("Comments"); ?></th>
                                <th style="width:10%;"><?= $this->lang->line("Updated_By"); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($payments)) {
                                foreach ($payments as $payment) { ?>
                                    <tr>
                                        <td><?= $this->sma->hrld($payment->created_date); ?></td>
                                        <td><?= $payment->reference_no; ?></td>
                                        <td><?= $payment->nam; ?></td>
                                        <td><?= $payment->repair_status; ?></td>
                                        <td><?= !empty($this->sma->decode_html($payment->signature)) ? '<img src="'.base_url('assets/customer_sign/'.$this->sma->decode_html($payment->signature)).'" alt="" style="width:170px;">' : '' ?></td>
                                        <td><?= $this->sma->decode_html($payment->repair_note); ?></td>
                                        <td><?= $payment->username; ?></td>
                                    </tr>
                                <?php }
                            } else {
                                echo "<tr><td colspan='6'>" . lang('no_data_available') . "</td></tr>";
                            } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $modal_js ?>
