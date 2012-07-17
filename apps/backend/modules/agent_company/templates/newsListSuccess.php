<div id="sf_admin_container">
 <h1>The News & Updates set for Agents </h1>



<div id="sf_admin_content">

<table width="100%" cellspacing="0" cellpadding="2" class="tblAlign">
  

    <thead><tr class="headings">
<th>ID</th>
<th>Active</th>
<th>Starting Date</th>
<th>Heading</th>
<th>Message</th>
<th>Action</th>
    
    </tr>
    </thead><tbody>
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
        <td> <a href='<?php echo url_for('agent_company/newsEdit')?>?id=<?php echo $message->getId()?>'><img src="/sf/sf_admin/images/edit_icon.png" title="edit" alt="edit"></a>
        <a href='<?php echo url_for('agent_company/newsDelete')?>?id=<?php echo $message->getId()?>'><img src="/sf/sf_admin/images/delete_icon.png" title="delete" alt="delete"></a> </td>
    </tr>

<?php
}
?>
    </tbody>
</table>
</div>
</div>
