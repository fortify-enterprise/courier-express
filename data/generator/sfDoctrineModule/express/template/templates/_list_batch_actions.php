<?php if ($listActions = $this->configuration->getValue('list.batch_actions')): ?>
<li class="sf_admin_batch_actions_choice">
  <select name="batch_action">
    <option value="">[?php echo __('Choose an action', array(), 'sf_admin') ?]</option>
<?php foreach ((array) $listActions as $action => $params): ?>
    <?php echo $this->addCredentialCondition('<option value="'.$action.'">[?php echo __(\''.$params['label'].'\', array(), \'sf_admin\') ?]</option>', $params) ?>

<?php endforeach; ?>
  </select>
  [?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?]
    <input type="hidden" name="[?php echo $form->getCSRFFieldName() ?]" value="[?php echo $form->getCSRFToken() ?]" />
  [?php endif; ?]
  <button class='fg-button ui-state-default ui-corner-all' type="submit">[?php echo __('Submit', array(), 'sf_admin') ?]</button>
</li>
<?php endif; ?>
