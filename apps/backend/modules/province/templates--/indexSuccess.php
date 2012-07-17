<h1>Province List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Country</th>
      <th>Province</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($province_list as $province): ?>
    <tr>
      <td><a href="<?php echo url_for('province/edit?id='.$province->getId()) ?>"><?php echo $province->getId() ?></a></td>
      <td><?php echo $province->getCountryId() ?></td>
      <td><?php echo $province->getProvince() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('province/new') ?>">New</a>
