<?php 
//echo $_SERVER['PHP_SELF']; 
?><div id="sf_admin_container">

<h1>Documents list</h1>

<div id="sf_admin_header">


</div>

<div id="sf_admin_bar">
</div>
<div id="sf_admin_content">
<table cellspacing="0" cellpadding="2" class="tblAlign" width="100%">
<thead>
<tr class="headings">
  <th id="sf_admin_list_th_id">Id</th>
  <th id="sf_admin_list_th_question">Document Title</th>
  <th id="sf_admin_list_th_answer">File Path</th>
  <th id="sf_admin_list_th_status">Downlaod</th>
  <th id="sf_admin_list_th_sf_actions">Actions</th>
</tr>
</thead>
<tfoot>
<tr>
</tr>
</tfoot>
<?php 

$Qry = mysql_query("select * from clientdocuments");
if(mysql_num_rows($Qry)){
while($qryObj = mysql_fetch_object($Qry)){?>
<tr class="sf_admin_row_0">
    <td><a href="<?php echo $_SERVER['PHP_SELF'];?>/edit/id/<?php echo $qryObj->id;?>"><?php echo $qryObj->id;?></a></td>
    <td><?php echo $qryObj->title;?></td>
      
      <td><?php echo sfConfig::get('app_web_url');?>uploads/documents/<?php echo $qryObj->filename;?></td>
      <td><a href="<?php echo sfConfig::get('app_web_url');?>uploads/documents/<?php echo $qryObj->filename;?>" target="_blank">DOWNLOAD DOCUMENT</a></td>
  <td>
<ul class="sf_admin_td_actions">
  <li><a href="<?php echo $_SERVER['PHP_SELF'];?>/delete/id/<?php echo $qryObj->id;?>" onclick="if (confirm('Are you sure?')) { var f = document.createElement('form'); f.style.display = 'none'; this.parentNode.appendChild(f); f.method = 'post'; f.action = this.href;f.submit(); };return false;"><img src="<?php echo sfConfig::get('app_web_url');?>sf/sf_admin/images/delete_icon.png" title="delete" alt="delete" /></a></li>
  <li><a href="<?php echo $_SERVER['PHP_SELF'];?>/edit/id/<?php echo $qryObj->id;?>"><img src="<?php echo sfConfig::get('app_web_url');?>sf/sf_admin/images/edit_icon.png" title="edit" alt="edit"></a></li>
</ul>
</td>
</tr>
<?php }}else{
		echo '<tr class="sf_admin_row_0">
    <td colspan=5><b>No Record Exist<b></td></tr>';
} ?>
</table>
<ul class="sf_admin_actions">
  <li><input type="button" onclick="document.location.href='<?php echo sfConfig::get('app_admin_url');?>client_documents/create';" value="create" class="sf_admin_action_create"></li>
</ul>
</div>

<div id="sf_admin_footer">
</div>

</div>