<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-2x">&times;</i>
            </button>
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('Update_Repair_status'); ?></h4>
        </div>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'submit_form');
        echo admin_form_open_multipart("sales/repair_status/" . $inv->id, $attrib); ?>
        <!-- Added signature_name field -->
        <?= form_input(array('name' => 'signature_name', 'type' => 'hidden', 'id' => 'signature_name', 'value' => '')) ?>
        <div class="modal-body">
            <p><?= lang('enter_info'); ?></p>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?= lang('Repair_Details'); ?>
                </div>
                <div class="panel-body">
                    <table class="table table-condensed table-striped table-borderless" style="margin-bottom:0;">
                        <tbody>
                        <tr>
                            <td><?= lang('reference_no'); ?></td>
                            <td><?= $inv->reference_no; ?></td>
                        </tr>
                        <tr>
                            <td><?= lang('biller'); ?></td>
                            <td><?= $inv->biller; ?></td>
                        </tr>
                        <tr>
                            <td><?= lang('customer'); ?></td>
                            <td><?= $inv->nam; ?></td>
                        </tr>
                        <tr>
                            <td><?= lang('Repair_Status'); ?></td>
                            <td><strong><?= lang($inv->type_detail); ?></strong></td>
                        </tr>
                        <tr>
                            <td><?= lang('payment_status'); ?></td>
                            <td id="payment_status"><?= lang($inv->payment_status); ?></td>
                        </tr>
                        <tr>
                            <td><?= lang('Technician'); ?></td>
                            <td><?= ($user->first_name . " " . $user->last_name); ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if ($returned) { ?>
                <h4><?= lang('sale_x_action'); ?></h4>
            <?php } else { ?>
                <div class="form-group">
                    <?= lang('Repair_Status', 'Repair_Status'); ?>
                    <?php
                    $opts = array('In Progress' => lang('In Progress'),
                        'Waiting for Parts' => lang('Waiting for Parts'),
                        'Ready' => lang('Ready'),
                        'Notified as Ready' => lang('Notified as Ready'),
                        'Return/Not Fixable' => lang('Return/Not Fixable'),
                        'Notified as Not Fixable' => lang('Notified as Not Fixable'),
                        'Item Returned' => lang('Item Returned'),
                        'Partial Delivery' => lang('Partial Delivery'),
                        'Delivered' => lang('Delivered'));
                    ?>
                    <?= form_dropdown('status', $opts, $inv->type_detail, 'class="form-control" id="status" required="required" style="width:100%;"'); ?>
                </div>

                <div class="form-group" id="customer_signature" style="display: none;">
                    <label for="sign_area">Customer Signature <b>*</b></label>
                    <div class="row">
                        <div id="sign_area" class="col-sm-5">
                            <canvas class="table-bordered" id="cnv" name="cnv" width="470" height="100"
                                    style="background: white;"></canvas>
                        </div>
                        <canvas name="SigImg" id="SigImg" width="500" height="100"
                                style="display:none;"></canvas>
                        <div class="col-sm-2 col-sm-offset-5" style="padding-top: 15px; padding-left: 0;">
                            <a class="btn btn-primary btn-flat btn-block" id="SignBtn" name="SignBtn"
                               onclick="StartSign()">Get Sign</a>
                            <a class="btn btn-warning btn-flat btn-block"
                               id="clear-signature-btn">Clear</a>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <?= lang("Repair_Note", "Repair_Note"); ?>
                    <?php echo form_textarea('repair_note', (isset($_POST['repair_note'])), 'class="form-control" id="repair_note" required="required"'); ?>
                </div>
            <?php } ?>

        </div>
        <?php if (!$returned) { ?>
            <div class="modal-footer">
                <input type="button" value="Update" id="submit-update" class="btn btn-primary">
            </div>
        <?php } ?>
    </div>
    <?php echo form_close(); ?>
</div>


<form id="signature-form"
      action="<?php echo base_url(); ?>themes/default/admin/views/sales/save.php"
      name="FORM1" method="POST">
    <!--        <input class="btn btn-primary btn-flat btn-block" id="SignBtn" name="SignBtn"-->
    <!--               type="button" value="Get Sign" onclick="StartSign()">-->
    <!--        <input class="btn btn-warning btn-flat btn-block" type="button"-->
    <!--               id="clear-signature-btn" value="Clear">-->
    <input type="HIDDEN" name="bioSigData">
    <input type="HIDDEN" name="sigImgData">
    <br>
    <br>
    <input type="hidden" name="sigStringData" id="sigStringData" value="">
    <input type="hidden" name="sigRawData" id="sigRawData" value="">
    <input type="hidden" name="signature" id="signature-data" value="">
    <input type="hidden" id="signature_getname" value="">
    <input type="hidden" name="signature_file_name" id="signature_file_name"
           value="">
    <input type="hidden" id="signature-path" value="">
</form>


<script type="text/javascript">
    var imgWidth;
    var imgHeight;

    function StartSign() {
        var isInstalled = document.documentElement.getAttribute('SigPlusExtLiteExtension-installed');
        if (!isInstalled) {
            alert("SigPlusExtLite extension is either not installed or disabled. Please install or enable extension.");
            return;
        }
        var canvasObj = document.getElementById('cnv');
        // canvasObj.getContext('2d').clearRect(0, 0, canvasObj.width, canvasObj.height);

        $('#sigStringData').val('SigString: ');
        $('#sigRawData').val('Base64 String: ');
        //document.FORM1.sigStringData.value = "SigString: ";
        //document.FORM1.sigRawData.value = "Base64 String: ";
        imgWidth = canvasObj.width;
        imgHeight = canvasObj.height;
        var message = {
            "firstName": "",
            "lastName": "",
            "eMail": "",
            "location": "",
            "imageFormat": 1,
            "imageX": imgWidth,
            "imageY": imgHeight,
            "imageTransparency": false,
            "imageScaling": false,
            "maxUpScalePercent": 0.0,
            "rawDataFormat": "ENC",
            "minSigPoints": 25
        };

        top.document.addEventListener('SignResponse', SignResponse, false);
        var messageData = JSON.stringify(message);
        var element = document.createElement("MyExtensionDataElement");
        element.setAttribute("messageAttribute", messageData);
        document.documentElement.appendChild(element);
        var evt = document.createEvent("Events");
        evt.initEvent("SignStartEvent", true, false);
        element.dispatchEvent(evt);
    }

    function SignResponse(event) {
        var str = event.target.getAttribute("msgAttribute");
        var obj = JSON.parse(str);
        SetValues(obj, imgWidth, imgHeight);
    }

    function SetValues(objResponse, imageWidth, imageHeight) {
        var obj = null;
        if (typeof (objResponse) === 'string') {
            obj = JSON.parse(objResponse);
        } else {
            obj = JSON.parse(JSON.stringify(objResponse));
        }

        var ctx = document.getElementById('cnv').getContext('2d');

        if (obj.errorMsg != null && obj.errorMsg != "" && obj.errorMsg != "undefined") {
            alert(obj.errorMsg);
        } else {
            if (obj.isSigned) {
                //$('#sigStringData').val($('#sigStringData').val() + obj.sigString);
                //$('#sigRawData').val($('#sigRawData').val() + obj.imageData);
                document.getElementById('sigRawData').value += obj.imageData;
                document.getElementById('sigStringData').value += obj.sigString;
                var img = new Image();
                img.onload = function () {
                    ctx.drawImage(img, 0, 0, imageWidth, imageHeight);
                }
                img.src = "data:image/png;base64," + obj.imageData;
                finalData = "data:image/png;base64," + obj.imageData;
                <?php $sign_file_name = 'signature_' . time() . '.png';?>;
                $('#signature_name').val('<?php echo $sign_file_name ?>');
                $('#signature-data').val(finalData);
            }
        }
    }

    function ClearFormData() {
        //$('#sigStringData').val($('');
        //$('#sigStringData').val($('');
        document.getElementById('sigStringData').value = "SigString: ";
        document.getElementById('sigRawData').value = "Base64 String: ";
        document.getElementById('SignBtn').disabled = false;
    }

</script>

<script>
    canvas = document.getElementById('cnv');

    $(document).ready(function () {
        $('#status').on('change', function () {
            if (this.value === 'In Progress' || this.value === 'Waiting for Parts' || this.value === 'Ready' || this.value === 'Return/Not Fixable') {
                $('#customer_signature').hide();
            }
            if (this.value === 'Item Returned' || this.value === 'Partial Delivery' || this.value === 'Delivered') {
                $('#customer_signature').show();
            }
        });

        $("#submit-update").click(function (e) {
            if ($('#status').val() === 'Delivered')
            {
                var payment_status = $('#payment_status').text();
                if (payment_status != 'Paid') {
                    bootbox.alert('Please complete the payment before delivery!');
                    return false;
                }
            }

            if ($('#status').val() === 'Item Returned' || $('#status').val() === 'Partial Delivery' || $('#status').val() === 'Delivered') {
                if ($('#signature_name').val() == ''  && parseInt(signature_pad_active)) {
                    bootbox.alert('Please take customer signature to complete the transaction!');
                    return false;
                }
                else {
                    e.preventDefault();

                    $('#signature_file_name').val('<?php echo $sign_file_name ?>');

                    var _url = $("#signature-form").attr("action");

                    var data = $("#signature-form").serialize();
                    var formData = new FormData($('#signature-form')[0]);

                    $.ajax({
                        url: _url,
                        type: 'POST',
                        dataType: "text",
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function (data) {
                            $('#signature_getname').val(data);
                            console.log(data);
                        }
                    });
                }

            }
            $('.bv-hidden-submit').click();
        });

        $("#clear-signature-btn").click(function (e) {
            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
            $('#signature-data').val('');
            $('#signature_name').val('');
        });
    });
</script>

<?= $modal_js ?>
