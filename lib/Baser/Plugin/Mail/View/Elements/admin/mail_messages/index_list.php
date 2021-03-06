<?php
/**
 * baserCMS :  Based Website Development Project <http://basercms.net>
 * Copyright (c) baserCMS Users Community <http://basercms.net/community/>
 *
 * @copyright		Copyright (c) baserCMS Users Community
 * @link			http://basercms.net baserCMS Project
 * @package			Baser.View
 * @since			baserCMS v 0.1.0
 * @license			http://basercms.net/license/index.html
 */

/**
 * [ADMIN] 受信メール一覧　テーブル
 * @var \BcAppView $this
 */
$this->BcListTable->setColumnNumber(6);
?>


<!-- pagination -->
<?php $this->BcBaser->element('pagination') ?>

<!-- list -->
<table cellpadding="0" cellspacing="0" class="list-table sort-table" id="ListTable">
<thead>
	<tr>
		<th style="white-space: nowrap" class="list-tool">
			<?php if ($this->BcBaser->isAdminUser()): ?>
			<div>
				<?php echo $this->BcForm->checkbox('ListTool.checkall', array('title' => __d('baser', '一括選択'))) ?>
				<?php echo $this->BcForm->input('ListTool.batch', array('type' => 'select', 'options' => array('del' => __d('baser', '削除')), 'empty' => __d('baser', '一括処理'))) ?>
				<?php echo $this->BcForm->button(__d('baser', '適用'), array('id' => 'BtnApplyBatch', 'disabled' => 'disabled')) ?>
			</div>
			<?php endif; ?>
		</th>
		<th style="white-space: nowrap"><?php echo $this->Paginator->sort('id', array('asc' => $this->BcBaser->getImg('admin/blt_list_down.png', array('alt' => __d('baser', '昇順'), 'title' => __d('baser', '昇順'))) . ' NO', 'desc' => $this->BcBaser->getImg('admin/blt_list_up.png', array('alt' => __d('baser', '降順'), 'title' => __d('baser', '降順'))) . ' NO'), array('escape' => false, 'class' => 'btn-direction')) ?></th>
		<th style="white-space: nowrap" colspan="2"><?php echo $this->Paginator->sort('created', array('asc' => $this->BcBaser->getImg('admin/blt_list_down.png', array('alt' => __d('baser', '昇順'), 'title' => __d('baser', '昇順'))) . __d('baser', '受信日時'), 'desc' => $this->BcBaser->getImg('admin/blt_list_up.png', array('alt' => __d('baser', '降順'), 'title' => __d('baser', '降順'))) . __d('baser', '受信日時')), array('escape' => false, 'class' => 'btn-direction')) ?></th>
		<th style="white-space: nowrap"><?php echo __d('baser', '受信内容')?></th>
		<th style="white-space: nowrap"><?php echo __d('baser', '添付')?></th>
		<?php echo $this->BcListTable->dispatchShowHead() ?>
	</tr>
</thead>
<tbody>
<?php if ($messages): ?>
		<?php $count = 0; ?>
		<?php foreach ($messages as $data): ?>
			<?php $this->BcBaser->element('mail_messages/index_row', array('data' => $data, 'count' => $count)) ?>
			<?php $count++; ?>
		<?php endforeach; ?>
	<?php else: ?>
		<tr><td colspan="<?php echo $this->BcListTable->getColumnNumber() ?>"><p class="no-data"><?php echo __d('baser', 'データが見つかりませんでした。')?></p></td></tr>
<?php endif ?>
</tbody>
</table>

<!-- list-num -->
<?php $this->BcBaser->element('list_num') ?>
