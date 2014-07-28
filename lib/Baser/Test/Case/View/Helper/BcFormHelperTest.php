<?php

/**
 * test for BcFormHelper
 *
 * baserCMS : Based Website Development Project <http://basercms.net>
 * Copyright 2008 - 2014, baserCMS Users Community <http://sites.google.com/site/baserusers/>
 *
 * @copyright		Copyright 2008 - 2014, baserCMS Users Community
 * @link			http://basercms.net baserCMS Project
 * @since			baserCMS v 3.1.0
 * @license			http://basercms.net/license/index.html
 */
App::uses('View', 'View');
App::uses('BcFormHelper', 'View/Helper');
App::uses('AppHelper', 'View/Helper');

/**
 * text helper library.
 *
 * @package Baser.Test.Case.View.Helper
 * @property BcTextHelper $Helper
 */
class BcFormHelperTest extends CakeTestCase {

	public function setUp() {
		parent::setUp();
		$this->Helper = new BcFormHelper(new View(null));
	}

	public function tearDown() {
		unset($this->Helper);
		parent::tearDown();
	}

/**
 * Addon用のフォーム挿入ヘルパー(登録)
 *
 * */
	public function testAddonsInput() {
		$this->Helper->addAddonsInput('テスト1', 'テストフォーム1');
		$this->Helper->addAddonsInput('テスト2', 'テストフォーム2', 'options');
		$this->Helper->addAddonsInput('テスト3', 'テストフォーム3', 'default', 90);
		$this->Helper->addAddonsInput('テスト4', 'テストフォーム4');
		$this->Helper->addAddonsInput('テスト5', 'テストフォーム5', 'default', 110);
		$this->Helper->addAddonsInput('テスト6', 'テストフォーム6', 'original');

		$check = $this->Helper->getAddonsInput();
		$expect = "<tr>\n\t\t\t<th class=\"col-head\">テスト3</th>\n\t\t\t<td class=\"col-input\">テストフォーム3</td>\n\t\t</tr>\n";
		$expect .= "\t\t<tr>\n\t\t\t<th class=\"col-head\">テスト1</th>\n\t\t\t<td class=\"col-input\">テストフォーム1</td>\n\t\t</tr>\n";
		$expect .= "\t\t<tr>\n\t\t\t<th class=\"col-head\">テスト4</th>\n\t\t\t<td class=\"col-input\">テストフォーム4</td>\n\t\t</tr>\n";
		$expect .= "\t\t<tr>\n\t\t\t<th class=\"col-head\">テスト5</th>\n\t\t\t<td class=\"col-input\">テストフォーム5</td>\n\t\t</tr>\n";
		$this->assertEquals($expect, $check);

		$check = $this->Helper->getAddonsInput('options');
		$expect = "<tr>\n\t\t\t<th class=\"col-head\">テスト2</th>\n\t\t\t<td class=\"col-input\">テストフォーム2</td>\n\t\t</tr>\n";
		$this->assertEquals($expect, $check);

		$check = $this->Helper->getAddonsInput('original');
		$expect = "<tr>\n\t\t\t<th class=\"col-head\">テスト6</th>\n\t\t\t<td class=\"col-input\">テストフォーム6</td>\n\t\t</tr>\n";
		$this->assertEquals($expect, $check);
	}

}
