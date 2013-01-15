<?php
/* SVN FILE: $Id$ */
/**
 * [MOBILE] メールフォーム確認ページ
 *
 * PHP versions 5
 *
 * baserCMS :  Based Website Development Project <http://basercms.net>
 * Copyright 2008 - 2012, baserCMS Users Community <http://sites.google.com/site/baserusers/>
 *
 * @copyright		Copyright 2008 - 2012, baserCMS Users Community
 * @link			http://basercms.net baserCMS Project
 * @package			baser.plugins.mail.views
 * @since			baserCMS v 0.1.0
 * @version			$Revision$
 * @modifiedby		$LastChangedBy$
 * @lastmodified	$Date$
 * @license			http://basercms.net/license/index.html
 */
if($freezed){
	$mailform->freeze();
}
?>

<hr size="1" style="width:100%;height:1px;margin:2px 0;padding:0;color:#CCCCCC;background:#CCCCCC;border:1px solid #CCCCCC;" />
<div style="text-align:center;background-color:#8ABE08;"><span style="color:white;">
	<?php $this->bcBaser->contentsTitle() ?>
	</span></div>
<hr size="1" style="width:100%;height:1px;margin:2px 0;padding:0;color:#CCCCCC;background:#CCCCCC;border:1px solid #CCCCCC;" />
<br />

<?php if($freezed): ?>
入力内容の確認<br />
<hr size="1" style="width:100%;height:1px;margin:2px 0;padding:0;color:#CCCCCC;background:#CCCCCC;border:1px solid #CCCCCC;" />
<font size="1">入力した内容に間違いがなければ「送信する」ボタンをクリックしてください。</font>
<?php else: ?>
入力フォーム
<?php endif ?>

<?php $this->bcBaser->flash() ?>
<?php $this->bcBaser->element('mail_form') ?>