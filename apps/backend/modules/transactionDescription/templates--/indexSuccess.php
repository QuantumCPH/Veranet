<h1>TransactionDescription List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Title</th>
      <th>Transaction type</th>
      <th>Transaction section</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($transaction_description_list as $transaction_description): ?>
    <tr>
      <td><a href="<?php echo url_for('transactionDescription/edit?id='.$transaction_description->getId()) ?>"><?php echo $transaction_description->getId() ?></a></td>
      <td><?php echo $transaction_description->getTitle() ?></td>
      <td><?php echo $transaction_description->getTransactionTypeId() ?></td>
      <td><?php echo $transaction_description->getTransactionSectionId() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('transactionDescription/new') ?>">New</a>
