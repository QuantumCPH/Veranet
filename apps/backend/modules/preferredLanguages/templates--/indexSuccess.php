<h1>PreferredLanguages List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Language</th>
      <th>Language code</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($preferred_languages_list as $preferred_languages): ?>
    <tr>
      <td><a href="<?php echo url_for('preferredLanguages/edit?id='.$preferred_languages->getId()) ?>"><?php echo $preferred_languages->getId() ?></a></td>
      <td><?php echo $preferred_languages->getLanguage() ?></td>
      <td><?php echo $preferred_languages->getLanguageCode() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('preferredLanguages/new') ?>">New</a>
