<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>

<form action="<?php echo url_for('handsets/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields() ?>
          &nbsp;<a href="<?php echo url_for('handsets/index') ?>">Cancel</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Delete', 'handsets/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input type="submit" value="Save" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['brand_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['brand_name']->renderError() ?>
          <?php echo $form['brand_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['model_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['model_name']->renderError() ?>
          <?php echo $form['model_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['auto_rebot']->renderLabel() ?></th>
        <td>
          <?php echo $form['auto_rebot']->renderError() ?>
          <?php echo $form['auto_rebot'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['dailer_mode']->renderLabel() ?></th>
        <td>
          <?php echo $form['dailer_mode']->renderError() ?>
          <?php echo $form['dailer_mode'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['tested_by']->renderLabel() ?></th>
        <td>
          <?php echo $form['tested_by']->renderError() ?>
          <?php echo $form['tested_by'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['comments']->renderLabel() ?></th>
        <td>
          <?php echo $form['comments']->renderError() ?>
          <?php echo $form['comments'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['supported']->renderLabel() ?></th>
        <td>
          <?php echo $form['supported']->renderError() ?>
          <?php echo $form['supported'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
