<?php    
	use_helper('Number');
	$sf_user->setCulture('da_DK');
?>
<div id="sf_admin_container"><h3>Report on Dates  From :  <?php echo date('d-m-Y', strtotime($startdate)); ?>  To : <?php echo date('d-m-Y', strtotime($enddate)); ?></h3></div>
<div id="sf_admin_container"><h1><?php echo  __('SMS Detail Report') ?></h1></div>
<table width="75%" cellspacing="0" cellpadding="2" class="tblAlign">
    <thead>
        <tr class="headings">
            <th>Id</th>
            <th>Customer name</th>
            <th>Phone no.</th>
            <th>Email</th>
            <th>Description</th>
            <th>Product</th>
            <th>Agent name</th>
            <th>Registration type</th>
            <th>Alert Activated</th>
            <th>Sent</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="10" style="text-align:center;font-weight: bold;">
                Total SMS sent :
            <?php
                $c = new Criteria();
		$c->add(SmsAlertSentPeer::CREATED_AT, $startdate,CRITERIA::GREATER_EQUAL);
		$c->addAnd(SmsAlertSentPeer::CREATED_AT, "$enddate 23:59:59", CRITERIA::LESS_EQUAL);
                echo $smsusgae=SmsAlertSentPeer::doCount($c);
            ?>
            </td>
        </tr>
    </tfoot>
    <tbody>
<?php
    $c = new Criteria();
    $c->add(SmsAlertSentPeer::CREATED_AT, $startdate,CRITERIA::GREATER_EQUAL);
    $c->addAnd(SmsAlertSentPeer::CREATED_AT, "$enddate 23:59:59", CRITERIA::LESS_EQUAL);
    $smsuslageDetails=SmsAlertSentPeer::doSelect($c);
    $i=1;
    foreach($smsuslageDetails as $smsuslageDetail){
    if($i%2==0){
        $class= 'class="even"';
    }else{
        $class= 'class="odd"';
    }
?>

        <tr <?php echo $class;?>>
            <td><?php echo $i;  ?></td>
            <td><?php echo $smsuslageDetail->getCustomerName();   ?></td>
            <td><?php echo $smsuslageDetail->getMobileNumber();   ?> </td>
            <td><?php echo $smsuslageDetail->getCustomerEmail();   ?> </td>
            <td><?php echo $smsuslageDetail->getMessageDescerption();   ?> </td>
            <td><?php echo $smsuslageDetail->getCustomerProduct();   ?> </td>
            <td><?php echo $smsuslageDetail->getAgentName();   ?> </td>
            <td><?php echo $smsuslageDetail->getRegistrationType();   ?> </td>
            <td><?php echo ($smsuslageDetail->getAlertActivated()==0) ? 'No' :'Yes';   ?> </td>
            <td><?php echo ($smsuslageDetail->getAlertSent()==0) ? 'No' :'Yes';   ?> </td>
        </tr>
<?php

$i++;

}  ?>
  </tbody>
</table>

<div id="sf_admin_container"><h1><?php echo  __('Email Detail Report') ?></h1></div>
<table width="75%" cellspacing="0" cellpadding="2" class="tblAlign">
    <thead>
        <tr class="headings">
            <th>Id</th>
            <th>Customer name</th>
            <th>Phone No.</th>
            <th>Email</th>
            <th>Description</th>
            <th>Product</th>
            <th>Agent name</th>
            <th>Registration type</th>
            <th>Alert Activated</th>
            <th>Sent</th>
         </tr>
      <thead>
      <tfoot>
        <tr>
            <td colspan="10" style="text-align:center;font-weight: bold;">
                Total Email sent : <?php
                $c = new Criteria();
		$c->add(EmailAlertSentPeer::CREATED_AT, $startdate,CRITERIA::GREATER_EQUAL);
		$c->addAnd(EmailAlertSentPeer::CREATED_AT, "$enddate 23:59:59", CRITERIA::LESS_EQUAL);
                echo $email=EmailAlertSentPeer::doCount($c); ?>
            </td>
        </tr>
    </tfoot>
      <tbody>
<?php
    $c = new Criteria();
    $c->add(EmailAlertSentPeer::CREATED_AT, $startdate,CRITERIA::GREATER_EQUAL);
    $c->addAnd(EmailAlertSentPeer::CREATED_AT, "$enddate 23:59:59", CRITERIA::LESS_EQUAL);
    $EmailuslageDetails=EmailAlertSentPeer::doSelect($c);
    $j=1;
    foreach($EmailuslageDetails as $EmailuslageDetail){
    if($j%2==0){
        $class= 'class="even"';
    }else{
        $class= 'class="odd"';
    }
?>
        <tr <?php echo $class;?>>
            <td><?php echo $j;  ?></td>
            <td><?php echo $EmailuslageDetail->getCustomerName();   ?></td>
            <td><?php echo $EmailuslageDetail->getMobileNumber();   ?> </td>
            <td><?php echo $EmailuslageDetail->getCustomerEmail();   ?> </td>
            <td><?php echo $EmailuslageDetail->getMessageDescerption();   ?> </td>
            <td><?php echo $EmailuslageDetail->getCustomerProduct();   ?> </td>
            <td><?php echo $EmailuslageDetail->getAgentName();   ?> </td>
            <td>&nbsp; <?php echo $EmailuslageDetail->getRegistrationType();   ?> </td>
            <td><?php echo ($EmailuslageDetail->getAlertActivated()==0) ? 'No' :'Yes';   ?> </td>
            <td>&nbsp; <?php echo ($EmailuslageDetail->getAlertSent()==0) ? 'No' :'Yes';   ?> </td>
        </tr>
<?php $j++;}?>
      <tbody>
</table>

