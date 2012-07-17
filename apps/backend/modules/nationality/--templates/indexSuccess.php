<h1>Nationality List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Title</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($nationality_list as $nationality): ?>
    <tr>
      <td><a href="<?php echo url_for('nationality/edit?id='.$nationality->getId()) ?>"><?php echo $nationality->getId() ?></a></td>
      <td><?php echo $nationality->getTitle() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('nationality/new') ?>">New</a>
