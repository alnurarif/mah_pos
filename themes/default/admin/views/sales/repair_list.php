<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
    <style>
        .repair_invoice_link{
            cursor: pointer;
        }
    </style>
    <script>
        function repair_status(x) {
            if (x == null) {
                return '';
            } else if (x == 'Waiting for Parts') {
                return '<div class="text-center"><span class="repair_status label label-warning">' + x + '</span></div>';
            } else if (x == 'Ready') {
                return '<div class="text-center"><span class="repair_status label label-info">' + x + '</span></div>';
            } else if (x == 'Notified as Ready') {
                return '<div class="text-center"><span class="repair_status label label-info">' + x + '</span></div>';
            } else if (x == 'Delivered') {
                return '<div class="text-center"><span class="repair_status label label-success">' + x + '</span></div>';
            } else if (x == 'Partial Delivery') {
                return '<div class="text-center"><span class="repair_status label label-info">' + x + '</span></div>';
            } else if (x == 'In Progress') {
                return '<div class="text-center"><span class="repair_status label label-default">' + x + '</span></div>';
            } else if (x == 'Return/Not Fixable') {
                return '<div class="text-center"><span class="repair_status label label-warning">' + x + '</span></div>';
            } else if (x == 'Notified as Not Fixable') {
                return '<div class="text-center"><span class="repair_status label label-warning">' + x + '</span></div>';
            } else if (x == 'Item Returned') {
                return '<div class="text-center"><span class="repair_status label label-danger">' + x + '</span></div>';
            } else {
                return '<div class="text-center"><span class="repair_status label label-default">' + x + '</span></div>';
            }
        }

        $(document).ready(function () {
            search_val = "<?= $dt_search;?>";
            oTable = $('#POSData').dataTable({
                "oSearch": {"sSearch": search_val},
                "aaSorting": [[1, "desc"], [2, "desc"]],
                "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
                "iDisplayLength": <?= $Settings->rows_per_page ?>,
                'bProcessing': true, 'bServerSide': true,
                'sAjaxSource': '<?= admin_url('sales/getrepair'.($warehouse_id ? '/'.$warehouse_id : '')) ?>',
                'fnServerData': function (sSource, aoData, fnCallback) {
                    aoData.push({
                        "name": "<?= $this->security->get_csrf_token_name() ?>",
                        "value": "<?= $this->security->get_csrf_hash() ?>"
                    });
                    $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
                },
                'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                    var oSettings = oTable.fnSettings();
                    // nRow.id = aData[0];
                    // nRow.className = "receipt_link";
                    // nRow.className = "repair_link";
                    nRow.id = aData[0];
                    nRow.setAttribute('data-return-id', aData[10]);
                    nRow.className = "repair_invoice_link re"+aData[10];
                    return nRow;
                },
                "aoColumns": [{
                    "bSortable": false,
                    "mRender": checkbox
                }, {"mRender": fld}, null, null, null,{"mRender": splitText},{"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": currencyFormat}, {"mRender": repair_status},{"mRender": row_status}, {"mRender": pay_status}, {"bSortable": false}],
                "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                    var gtotal = 0, paid = 0, balance = 0;
                    for (var i = 0; i < aaData.length; i++) {
                        gtotal += parseFloat(aaData[aiDisplay[i]][5]);
                        paid += parseFloat(aaData[aiDisplay[i]][6]);
                        balance += parseFloat(aaData[aiDisplay[i]][7]);
                    }
                    var nCells = nRow.getElementsByTagName('th');
                    nCells[6].innerHTML = currencyFormat(parseFloat(gtotal));
                    nCells[7].innerHTML = currencyFormat(parseFloat(paid));
                    nCells[8].innerHTML = currencyFormat(parseFloat(balance));
                }
            }).fnSetFilteringDelay().dtFilter([
                {column_number: 1, filter_default_label: "[<?=lang('date');?> (yyyy-mm-dd)]", filter_type: "text", data: []},
                {column_number: 2, filter_default_label: "[<?=lang('reference_no');?>]", filter_type: "text", data: []},
                {column_number: 3, filter_default_label: "[<?=lang('customer');?>]", filter_type: "text"},
                {column_number: 4, filter_default_label: "[<?=lang('Phone');?>]", filter_type: "text"},
                {column_number: 5, filter_default_label: "[<?=lang('items');?>]", filter_type: "text", data: []},
                {column_number: 8, filter_default_label: "[<?=lang('Repair_Status');?>]", filter_type: "text", data: []},
                {column_number: 10, filter_default_label: "[<?=lang('sale_status');?>]", filter_type: "text", data: []},
                {column_number: 11, filter_default_label: "[<?=lang('payment_status');?>]", filter_type: "text", data: []},
            ], "footer");
            function splitText(text){
                let products = text.split(',')
                console.log(products);
                let toShow = ""
                if(products.length >0){
                    products.map((product,index) => {
                        toShow += `<p style="text-align:left">${index+1}. ${product}</p>`;
                    })
                }
                return toShow
            }
            $(document).on('click', '.duplicate_pos', function (e) {
                e.preventDefault();
                var link = $(this).attr('href');
                if (localStorage.getItem('positems')) {
                    bootbox.confirm("<?= $this->lang->line('leave_alert') ?>", function (gotit) {
                        if (gotit == false) {
                            return true;
                        } else {
                            window.location.href = link;
                        }
                    });
                } else {
                    window.location.href = link;
                }
            });
            $(document).on('click', '.email_receipt', function () {
                var sid = $(this).attr('data-id');
                var ea = $(this).attr('data-email-address');
                var email = prompt("<?= lang("email_address"); ?>", ea);
                if (email != null) {
                    $.ajax({
                        type: "post",
                        url: "<?= admin_url('pos/email_receipt') ?>/" + sid,
                        data: { <?= $this->security->get_csrf_token_name(); ?>: "<?= $this->security->get_csrf_hash(); ?>", email: email, id: sid },
                    dataType: "json",
                        success: function (data) {
                        bootbox.alert(data.msg);
                    },
                    error: function () {
                        bootbox.alert('<?= lang('ajax_request_failed'); ?>');
                        return false;
                    }
                });
                }
            });
        });



    </script>

<?php if ($Owner || $GP['bulk_actions']) {
    echo admin_form_open('sales/sale_actions', 'id="action-form"');
} ?>
    <div class="box">
        <div class="box-header">
            <h2 class="blue"><i
                        class="fa-fw fa fa-barcode"></i><?= lang('Repair') . ' (' . ($warehouse_id ? $warehouse->name : lang('all_warehouses')) . ')'; ?>
            </h2>

            <div class="box-icon">
                <ul class="btn-tasks">
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"  data-placement="left" title="<?= lang("actions") ?>"></i></a>
                        <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                            <li><a href="<?= admin_url('pos') ?>"><i class="fa fa-plus-circle"></i> <?= lang('add_sale') ?></a></li>
                            <li><a href="#" id="excel" data-action="export_excel"><i class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
                            <li class="divider"></li>
                            <li><a href="#" class="bpo" title="<b><?= $this->lang->line("delete_sales") ?></b>" data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>" data-html="true" data-placement="left"><i class="fa fa-trash-o"></i> <?= lang('delete_sales') ?></a></li>
                        </ul>
                    </li>
                    <?php if (!empty($warehouses)) { ?>
                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-building-o tip" data-placement="left" title="<?= lang("warehouses") ?>"></i></a>
                            <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                                <li><a href="<?= admin_url('sales/repair_list') ?>"><i class="fa fa-building-o"></i> <?= lang('all_warehouses') ?></a></li>
                                <li class="divider"></li>
                                <?php
                                foreach ($warehouses as $warehouse) {
                                    echo '<li><a href="' . admin_url('sales/repair_list/' . $warehouse->id) . '"><i class="fa fa-building"></i>' . $warehouse->name . '</a></li>';
                                }
                                ?>
                            </ul>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <div class="box-content">
            <div class="row">
                <div class="col-lg-12">
                    <p class="introtext"><?= lang('list_results'); ?></p>

                    <div class="table-responsive">
                        <table id="POSData" class="table table-bordered table-hover table-striped">
                            <thead>
                            <tr>
                                <th style="min-width:30px; width: 30px; text-align: center;">
                                    <input class="checkbox checkft" type="checkbox" name="check"/>
                                </th>
                                <th><?= lang("date"); ?></th>
                                <th><?= lang("reference_no"); ?></th>
                                <th><?= lang("customer"); ?></th>
                                <th><?= lang("Phone"); ?></th>
                                <th><?= lang("items"); ?></th>
                                <th><?= lang("grand_total"); ?></th>
                                <th><?= lang("paid"); ?></th>
                                <th><?= lang("balance"); ?></th>
                                <th><?= lang("Repair_Status"); ?></th>
                                <th><?= lang("sale_status"); ?></th>
                                <th><?= lang("payment_status"); ?></th>
                                <th style="width:80px; text-align:center;"><?= lang("actions"); ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td colspan="11" class="dataTables_empty"><?= lang("loading_data"); ?></td>
                            </tr>
                            </tbody>
                            <tfoot class="dtFilter">
                            <tr class="active">
                                <th style="min-width:30px; width: 30px; text-align: center;">
                                    <input class="checkbox checkft" type="checkbox" name="check"/>
                                </th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th><?= lang("grand_total"); ?></th>
                                <th><?= lang("paid"); ?></th>
                                <th><?= lang("balance"); ?></th>
                                <th class="defaul-color"></th>
                                <th class="defaul-color"></th>
                                <th class="defaul-color"></th>
                                <th style="width:80px; text-align:center;"><?= lang("actions"); ?></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php if ($Owner || $GP['bulk_actions']) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
<?php } ?> 