<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?= lang('today_sale'); ?>
            <p>( <span id="currentDate"></span> )</p>

				<script>
					var today = new Date();
					var date = (today.getMonth()+1)+'-'+today.getDate()+'-'+today.getFullYear();
					document.getElementById("currentDate").innerHTML = date;
				</script>
			</h4>
        </div>
        <div class="modal-body">
            <table width="100%" class="stable">
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('cash_in_hand'); ?>:</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                            <span><?= $this->sma->formatMoney($this->session->userdata('cash_in_hand')); ?></span></h4>
                    </td>
                </tr>
                <tr>
                    <td width="200px;" style="font-weight:bold;"><h4><?= lang('Cash_Payment_(Paid)'); ?>:</h4></td>
                    <td style="text-align:right;"><h4>
                            <span><?= $this->sma->formatMoney($cashsales->paid ? $cashsales->paid : '0.00'); ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #EEE;"><h4><?= lang('Cash_Payment_(Due)'); ?>:</h4></td>
                    <td style="text-align:right; border-bottom: 1px solid #EEE;"><h4>
                            <span style="color:red;"><?= $this->sma->formatMoney(($cashsales->total ? $cashsales->total : '0.00') - ($cashsales->paid ? $cashsales->paid : '0.00')); ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('cc_sale'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                            <span><?= $this->sma->formatMoney($ccsales->paid ? $ccsales->paid : '0.00') . ' (' . $this->sma->formatMoney($ccsales->total ? $ccsales->total : '0.00') . ')'; ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('Debit_Card_Payment'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                            <span><?= $this->sma->formatMoney($dcsales->paid ? $dcsales->paid : '0.00') . ' (' . $this->sma->formatMoney($dcsales->total ? $dcsales->total : '0.00') . ')'; ?></span>
                        </h4></td>
                </tr>
                <?php if ($pos_settings->paypal_pro) { ?>
                    <tr>
                        <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('paypal_pro'); ?>:</h4></td>
                        <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                                <span><?= $this->sma->formatMoney($pppsales->paid ? $pppsales->paid : '0.00') . ' (' . $this->sma->formatMoney($pppsales->total ? $pppsales->total : '0.00') . ')'; ?></span>
                            </h4></td>
                    </tr>
                <?php } ?>
                <?php if ($pos_settings->authorize) { ?>
                    <tr>
                        <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('stripe'); ?>:</h4></td>
                        <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                                <span><?= $this->sma->formatMoney($authorizesales->paid ? $authorizesales->paid : '0.00') . ' (' . $this->sma->formatMoney($authorizesales->total ? $authorizesales->total : '0.00') . ')'; ?></span>
                            </h4></td>
                    </tr>
                <?php } ?>
                <?php if ($pos_settings->stripe) { ?>
                    <tr>
                        <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('stripe'); ?>:</h4></td>
                        <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                                <span><?= $this->sma->formatMoney($stripesales->paid ? $stripesales->paid : '0.00') . ' (' . $this->sma->formatMoney($stripesales->total ? $stripesales->total : '0.00') . ')'; ?></span>
                            </h4></td>
                    </tr>
                <?php } ?>
                <tr>
                    <td style="border-top: 1px solid #DDD;"><h4><?= lang('refunds'); ?>:</h4></td>
                    <td style="text-align:right;border-top: 1px solid #DDD;"><h4>
                            <span><?= $this->sma->formatMoney($refunds->returned ? $refunds->returned : '0.00') . ' (' . $this->sma->formatMoney($refunds->total ? $refunds->total : '0.00') . ')'; ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-top: 1px solid #DDD;"><h4><?= lang('returns'); ?>:</h4></td>
                    <td style="text-align:right; border-top: 1px solid #DDD;"><h4>
                            <span><?= $this->sma->formatMoney($returns->total ? '-'.$returns->total : 0); ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #DDD;"><h4><?= lang('expenses'); ?>:</h4></td>
                    <td style="text-align:right;border-bottom: 1px solid #DDD;"><h4>
                            <span><?php $expense = $expenses ? $expenses->total : 0; echo $this->sma->formatMoney($expense) . ' (' . $this->sma->formatMoney($expense) . ')'; ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td width="200px;" style="font-weight:bold;"><h4><strong><?= lang('total_cash'); ?></strong>:</h4></td>
                    <td style="text-align:right;"><h4>
                            <span><strong><?= $cashsales->paid ? $this->sma->formatMoney(($cashsales->paid + ($this->session->userdata('cash_in_hand'))) - $expense + ($refunds->returned ? $refunds->returned : 0) - ($returns->total ? $returns->total : 0)) : $this->sma->formatMoney($this->session->userdata('cash_in_hand') - $expense - ($returns->total ? $returns->total : 0)); ?></strong></span>
                        </h4></td>
                </tr>
                
                <tr>
                    <td style="border-bottom: 3px solid #DDD;"><h4 style="font-weight:bold;"><?= lang('Total_Sales_(Paid)'); ?>:</h4></td>
                    <td width="200px;" style="text-align:right; border-bottom: 3px solid #DDD;"><h4 style="font-weight:bold;">
                            <span><?= $this->sma->formatMoney(($cashsales->paid ? $cashsales->paid : '0.00') + ($ccsales->paid ? $ccsales->paid : '0.00') + ($dcsales->paid ? $dcsales->paid : '0.00')); ?></span>
                        </h4></td>
                </tr>
                <tr>
                    <td width="200px;"><h4 style="font-weight:bold;"><?= lang('Total_Sales_(With_Due)'); ?>:</h4></td>
                    <td width="100px;" style="text-align:right;"><h4 style="font-weight:bold;">
                            <span style="color:red;"><?= $this->sma->formatMoney($totalsales->total ? $totalsales->total : '0.00'); ?></span>
                        </h4></td>
                </tr>
            </table>
        </div>
    </div>

</div>
