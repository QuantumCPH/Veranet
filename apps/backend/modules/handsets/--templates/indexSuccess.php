<h1>Handsets List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Brand name</th>
      <th>Model name</th>
      <th>Auto rebot</th>
      <th>Dailer mode</th>
      <th>Tested by</th>
      <th>Comments</th>
      <th>Supported</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($handsets_list as $handsets): ?>
    <tr>
      <td><a href="<?php echo url_for('handsets/edit?id='.$handsets->getId()) ?>"><?php echo $handsets->getId() ?></a></td>
      <td><?php echo $handsets->getBrandName() ?></td>
      <td><?php echo $handsets->getModelName() ?></td>
      <td><?php echo $handsets->getAutoRebot() ?></td>
      <td><?php echo $handsets->getDailerMode() ?></td>
      <td><?php echo $handsets->getTestedBy() ?></td>
      <td><?php echo $handsets->getComments() ?></td>
      <td><?php echo $handsets->getSupported() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('handsets/new') ?>">New</a>
