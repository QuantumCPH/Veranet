

<center><h2>The News & Updates set for Agents </h2></center>
<p> </p>
<table width="100%" border="2" cellspacing="0" cellpadding="0" class="callhistory">
    <tr>
    <td>ID</td>
	<td>Active?</td>
    <td>Starting Date</td>
    <td>Heading</td>
    <td>Message</td>
    <td> </td>
    
    </tr>
<?php
foreach($messages as $message)
{?>
    <tr>
        <td><?php echo $message->getId() ?></td>
		<td><?php 
		$currentDate = date('Y-m-d');
		if($currentDate>=$message->getStartingDate() ){
			echo "Yes";
		}
		?> </td>
        <td><?php echo $message->getStartingDate() ?></td>
        <td><?php echo $message->getHeading() ?></td>
        <td><?php echo $message->getMessage() ?></td>
        <td> <a href='<?php echo url_for('agent_company/newsEdit')?>?id=<?php echo $message->getId()?>'>Update</a> &nbsp;
        <a href='<?php echo url_for('agent_company/newsDelete')?>?id=<?php echo $message->getId()?>''>Delete</a> </td>
    </tr>

<?php
}
?>

</table>

