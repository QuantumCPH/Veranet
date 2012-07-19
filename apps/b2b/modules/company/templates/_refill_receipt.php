<?php
use_helper('I18N');
use_helper('Number');
?>
<style>
    p {
        margin: 8px auto;
    }

    table.receipt {
        width: 600px;
        border: 2px solid #ccc;
    }

    table.receipt td, table.receipt th {
        padding:5px;
    }

    table.receipt th {
        text-align: left;
    }

    table.receipt .payer_details {
        padding: 10px 0;
    }

    table.receipt .receipt_header, table.receipt .order_summary_header {
        font-weight: bold;
        text-transform: uppercase;
    }

    table.receipt .footer
    {
        font-weight: bold;
    }


</style>


<table width="600px">
    <tr style="border:0px solid #fff">
        <td colspan="4" align="right" style="text-align:right; border:0px solid #fff"><?php echo image_tag('logo.jpg'); ?></td>
    </tr>
</table>
<table class="receipt" cellspacing="0" width="600px">
    <tr bgcolor="#CCCCCC" class="receipt_header">
        <th colspan="3"><?php echo __('Order Receipt') ?></th>
        <th><?php echo __('Transaction No.') ?> <?php echo $transaction->getId() ?></th>
    </tr>
    <tr> 
        <td colspan="4" class="payer_summary">
            <?php echo __('Vat Number') ?>   <?php echo $company->getVatNo(); ?><br/>
            <?php echo sprintf("%s", $company->getName()) ?><br/>
            <?php echo $company->getAddress() ?><br/>
            <?php echo sprintf('%s %s', $company->getCity(), $company->getPostCode()) ?><br/>
            <br /><br />
        </td>
    </tr>
    <tr class="order_summary_header" bgcolor="#CCCCCC">
        <td><?php echo __('Date') ?></td>
        <td><?php echo __('Description') ?></td>
        <td><?php echo __('Quantity') ?></td>
        <td><?php echo __('Amount') ?></td>
    </tr>
    <tr>
        <td><?php echo $transaction->getCreatedAt('m-d-Y') ?></td>
        <td>
            <?php
            echo $transaction->getDescription();
            ?>
        </td>
        <td><?php echo "1"; ?></td>
        <td><?php echo number_format($subtotal = $transaction->getAmount(),2)?><?php echo sfConfig::get('app_currency_code');?></td>
    </tr>
    <tr>
        <td colspan="4" style="border-bottom: 2px solid #c0c0c0;">&nbsp;</td>
    </tr>
    <tr class="footer">
        <td>&nbsp;</td>
        <td><?php echo __('Subtotal') ?></td>
        <td>&nbsp;</td>
        <td><?php echo number_format($subtotal,2) ?><?php echo sfConfig::get('app_currency_code');?></td>
    </tr>
    <tr class="footer">
        <td>&nbsp;</td>
        <td><?php echo __('VAT') ?> <!--(<?php echo $vat == 0 ? '0%' : '25%' ?>)--></td>
        <td>&nbsp;</td>
        <td><?php echo number_format($vat, 2) ?></td>
    </tr>
    <tr class="footer">
        <td>&nbsp;</td>
        <td><?php echo __('Total') ?></td>
        <td>&nbsp;</td>
        <td><?php echo number_format($transaction->getAmount(), 2) ?><?php echo sfConfig::get('app_currency_code');?></td>
    </tr>
    <tr>
        <td colspan="4" style="border-bottom: 2px solid #c0c0c0;">&nbsp;</td>
    </tr>
    <tr class="footer">
    <td class="payer_summary" colspan="4" style="font-weight:normal; white-space: nowrap;">
    <?php echo __('%1%',array('%1%'=>sfConfig::get('app_postal_address_bottom')))?> </td>
  </tr>
</table>
