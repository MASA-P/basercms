<?php
/**
 * baserCMS :  Based Website Development Project <http://basercms.net>
 * Copyright (c) baserCMS Users Community <http://basercms.net/community/>
 *
 * @copyright		Copyright (c) baserCMS Users Community
 * @link			http://basercms.net baserCMS Project
 * @package			Baser.View
 * @since			baserCMS v 4.0.0
 * @license			http://basercms.net/license/index.html
 */

/**
 * サブサイトフォーム
 */
$agents = Configure::read('BcAgent');
$devices = ['' => '指定しない'];
foreach($agents as $key => $agent) {
	$devices[$key] = $agent['name'];
}
$languages = Configure::read('BcLang');
$langs = ['' => '指定しない'];
foreach($languages as $key => $lang) {
	$langs[$key] = $lang['name'];
}
?>


<table class="form-table">
<?php if($this->request->action == 'admin_edit'): ?>
	<tr>
		<th><?php echo $this->BcForm->label('Site.id', 'NO') ?></th>
		<td>
			<?php echo $this->BcForm->value('Site.id') ?>
			<?php echo $this->BcForm->hidden('Site.id') ?>
		</td>
	</tr>
<?php endif ?>
	<tr>
		<th><?php echo $this->BcForm->label('Site.name', '識別名称') ?>&nbsp;<span class="required">*</span></th>
		<td>
			<?php echo $this->BcForm->input('Site.name', array('type' => 'input', 'size' => '50', 'autofocus' => true)) ?>
			<?php echo $this->Html->image('admin/icn_help.png', array('class' => 'btn help', 'alt' => 'ヘルプ')) ?>
			<div class="helptext">サブサイトを特定する事ができる識別名称を入力します。半角英数とハイフン（-）・アンダースコア（_）のみが利用できます。エイリアスを入力しない場合は、URLにも利用されます。</div>
			　<small>[<?php echo $this->BcForm->label('Site.alias', 'エイリアス') ?>]</small>
			<?php echo $this->BcForm->input('Site.alias', array('type' => 'input', 'size' => '10')) ?>
			<?php echo $this->Html->image('admin/icn_help.png', array('class' => 'btn help', 'alt' => 'ヘルプ')) ?>
			<div class="helptext">サブサイトの識別名称とは別のURLにしたい場合、別名を入力する事ができます。エイリアスは半角英数に加えハイフン（-）・アンダースコア（_）・スラッシュ（/）・ドット（.）が利用できます。</div>
			<?php echo $this->BcForm->error('Site.name') ?>
			<?php echo $this->BcForm->error('Site.alias') ?>
		</td>
	</tr>
	<tr>
		<th><?php echo $this->BcForm->label('Site.display_name', 'サイト名') ?>&nbsp;<span class="required">*</span></th>
		<td>
			<?php echo $this->BcForm->input('Site.display_name', array('type' => 'input', 'size' => '60')) ?>
			<?php echo $this->Html->image('admin/icn_help.png', array('class' => 'btn help', 'alt' => 'ヘルプ')) ?>
			<div class="helptext">サブサイト名を入力します。管理システムでの表示に利用されます。日本語の入力が可能ですのでわかりやすい名前をつけてください。</div>
			<?php echo $this->BcForm->error('Site.display_name') ?>
		</td>
	</tr>
	<tr>
		<th><?php echo $this->BcForm->label('Site.title', 'サイトタイトル') ?>&nbsp;<span class="required">*</span></th>
		<td>
			<?php echo $this->BcForm->input('Site.title', array('type' => 'input', 'size' => '60')) ?>
			<?php echo $this->Html->image('admin/icn_help.png', array('class' => 'btn help', 'alt' => 'ヘルプ')) ?>
			<div class="helptext">サブサイトのタイトルを入力します。タイトルタグに利用されます。</div>
			<?php echo $this->BcForm->error('Site.title') ?>
		</td>
	</tr>
	<tr>
		<th><?php echo $this->BcForm->label('Site.main_site_id', 'メインサイト') ?></th>
		<td>
			<?php echo $this->BcForm->input('Site.main_site_id', array('type' => 'select', 'options' => $mainSites)) ?>
			<?php echo $this->BcForm->input('Site.relate_main_site', array('type' => 'checkbox', 'label' => 'エイリアスを利用してメインサイトと自動連携する')) ?>
			<?php echo $this->Html->image('admin/icn_help.png', array('class' => 'btn help', 'alt' => 'ヘルプ')) ?>
			<div class="helptext">
				<p>サブサイトの主として連携させたいサイトを選択します。</p>
				<p>
					「エイリアスを利用してメインサイトと自動連携する」にチェックを入れておくと、メインサイトでコンテンツの追加や削除が発生した場合、
					エイリアスを利用して自動的にサブサイトで同様の処理を実行します。
				</p>
			</div>
			<?php echo $this->BcForm->error('Site.main_site_id') ?>
		</td>
	</tr>
	<tr>
		<th><?php echo $this->BcForm->label('Site.device', 'デバイス・言語') ?></th>
		<td>
			<p>
				<small>[デバイス]</small><?php echo $this->BcForm->input('Site.device', array('type' => 'select', 'options' => $devices)) ?>　
				<small>[言語]</small><?php echo $this->BcForm->input('Site.lang', array('type' => 'select', 'options' => $langs)) ?>
				<p id="SectionAccessType" style="display:none">
					<small>[アクセス設定]</small><br>
					<span id="SpanSiteSameMainUrl"><?php echo $this->BcForm->input('Site.same_main_url', array('type' => 'checkbox', 'label' => 'メインサイトと同一URLでアクセス')) ?>　</span><br>
					<span id="SpanSiteAutoRedirect"><?php echo $this->BcForm->input('Site.auto_redirect', array('type' => 'checkbox', 'label' => 'メインサイトから自動的にリダイレクト')) ?>　</span>
					<span id="SpanSiteAutoLink"><?php echo $this->BcForm->input('Site.auto_link', array('type' => 'checkbox', 'label' => '全てのリンクをサブサイト用に変換する')) ?>　</span>
				</p>
			</p>
			<?php echo $this->BcForm->error('Site.device') ?>
			<?php echo $this->BcForm->error('Site.lang') ?>
		</td>
	</tr>
	<tr>
		<th><?php echo $this->BcForm->label('Site.theme', 'テーマ') ?></th>
		<td>
			<?php echo $this->BcForm->input('Site.theme', array('type' => 'select', 'options' => $themes)) ?>
			<?php echo $this->Html->image('admin/icn_help.png', array('class' => 'btn help', 'alt' => 'ヘルプ')) ?>
			<div class="helptext">サブサイトのテンプレートは、各テンプレートの配置フォルダ内にサイト名のサブフォルダを作成する事で別途配置する事ができますが、テーマフォルダ自体を別にしたい場合はここでテーマを指定します。</div>
			<?php echo $this->BcForm->error('Site.theme') ?>
		</td>
	</tr>
	<tr>
		<th><?php echo $this->BcForm->label('Site.status', '公開状態') ?></th>
		<td>
			<?php echo $this->BcForm->input('Site.status', array('type' => 'radio', 'options' => array(0 => '公開しない', 1 => '公開する'))) ?>
			<?php echo $this->BcForm->error('Site.status') ?>
		</td>
	</tr>
</table>
