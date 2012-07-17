<div id="sf_admin_container"><h1><?php echo __('Customer Detail') ?></h1></div>
<div class="borderDiv">
    <form name="" method="post"  action="<?php echo url_for($targetUrl.'affiliate/numberProcess') ?>">
    <input type="hidden" value="<?php echo  $customer->getMobileNumber(); ?>" name="mobile_number" />
    <input type="hidden" value="<?php echo  $product->getId();  ?>" name="productid" />
    <input type="hidden" value="<?php echo  $product->getPrice();  ?>" name="extra_refill" />
    <input type="hidden" value="<?php echo  $newNumber;  ?>" name="newnumber" />
    <input type="hidden" value="<?php echo  $countrycode;  ?>" name="countrycode" />
    <ul class="fl col changenumber">
        <li>
            <label>New Mobile Number</label>
            <label><?php echo  $newNumber;  ?></label><br />
        </li>
        <li>
            <label>Customer Name</label>
            <label><?php echo  $customer->getFirstName(); ?>&nbsp;<?php echo  $customer->getLastName(); ?></label><br />
        </li>
        <li>
            <label>Old Mobile Number</label>
            <label><?php echo  $customer->getMobileNumber(); ?></label><br />
        </li>
        <li>
            <label>Product Detail</label>
            <label><?php echo $product->getDescription(); ?></label><br />
        </li>
        <li>
            <label>Amount</label>
            <label><?php echo  $product->getPrice(); ?></label><br />
        </li>
        <li style="margin-left:188px"><input type="submit" name="Pay" value="Pay" /><br /></li>
    </ul>
    </form>
    <div class="clr"></div>
</div>