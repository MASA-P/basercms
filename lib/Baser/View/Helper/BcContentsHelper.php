<?php
/**
 * baserCMS :  Based Website Development Project <http://basercms.net>
 * Copyright (c) baserCMS Users Community <http://basercms.net/community/>
 *
 * @copyright		Copyright (c) baserCMS Users Community
 * @link			http://basercms.net baserCMS Project
 * @package			Baser.View.Helper
 * @since			baserCMS v 4.0.0
 * @license			http://basercms.net/license/index.html
 */

/**
 * コンテンツヘルパ
 *
 * @package Baser.View.Helper
 * @property Content $_Content
 * @property Permission $_Permission
 */
class BcContentsHelper extends AppHelper {

/**
 * Helper
 *
 * @var array
 */
	public $helpers = ['BcBaser'];

/**
 * Content Model
 * 
 * @var Content
 */
	protected $_Content = null;
	protected $_Permission = null;
	
/**
 * Constructor.
 *
 * @return	void
 * @access	public
 */
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		$this->_Content = ClassRegistry::init('Content');
		$this->_Permission = ClassRegistry::init('Permission');
		if(BcUtil::isAdminSystem()) {
			$this->setup();
		}
	}

/**
 * セットアップ 
 */
	public function setup() {
		$settings = $this->_View->get('contentsSettings');
		if(!$settings) {
			return;
		}
		
		$existsTitles = $this->_getExistsTitles();
		$user = BcUtil::loginUser('admin');
		
		foreach($settings as $type => $setting) {

			// title
			if (empty($setting['title'])) {
				$setting['title'] = $type;
			}
			
			// omitViewAction
			if(empty($setting['omitViewAction'])) {
				$setting['omitViewAction'] = false;
			}
			
			// exists
			if (empty($setting['multiple'])) {
				$setting['multiple'] = false;
				if (array_key_exists($setting['plugin'] . '.' . $type, $existsTitles)) {
					$setting['exists'] = true;
					$setting['existsTitle'] = $existsTitles[$setting['plugin'] . '.' . $type];
				} else {
					$setting['exists'] = false;
					$setting['existsTitle'] = '';
				}
			}

			// icon
			if (!empty($setting['icon'])) {
				$setting['url']['icon'] = $this->_getIconUrl($setting['plugin'], $setting['type'], $setting['icon']);
			} else {
				$setting['url']['icon'] = $this->_getIconUrl($setting['plugin'], $setting['type'], null);
			}
			// routes
			foreach (['manage', 'add', 'edit', 'delete', 'copy'] as $method) {
				if (empty($setting['routes'][$method]) && !in_array($method, ['copy', 'manage'])) {
					$setting['routes'][$method] = ['admin' => true, 'controller' => 'contents', 'action' => $method];
				}
				if (!empty($setting['routes'][$method])) {
					$route = $setting['routes'][$method];
					$setting['url'][$method] = Router::url($route);
				}
			}
			// disabled
			$setting['addDisabled'] = !($this->_Permission->check($setting['url']['add'], $user['user_group_id']));
			$settings[$type] = $setting;
		}
		$this->settings = $settings;
	}
	
	public function isActionAvailable($type, $action, $entityId) {
		$user = BcUtil::loginUser('admin');
		$url = $this->settings[$type]['url'][$action] . '/' . $entityId;
		return $this->_Permission->check($url, $user['user_group_id']);
	}

/**
 * シングルコンテンツで既に登録済のタイトルを取得する
 * @return array
 */
	protected function _getExistsTitles() {
		$items = Configure::read('BcContents.items');
		// シングルコンテンツの存在チェック
		$conditions = [];
		foreach($items as $name => $settings) {
			foreach ($settings as $type => $setting) {
				if(empty($setting['multiple'])) {
					$conditions['or'][] = [
						'Content.plugin' => $name,
						'Content.type' => $type,
						'Content.alias_id'=> null
					];
				}
			}
		}
		$this->_Content->Behaviors->unload('SoftDelete');
		$contents = $this->_Content->find('all', array('fields' => array('plugin', 'type', 'title'), 'conditions' => $conditions, 'recursive' => -1));
		$this->_Content->Behaviors->load('SoftDelete');
		$existContents = [];
		foreach($contents as $content) {
			$existContents[$content['Content']['plugin'] . '.' . $content['Content']['type']] = $content['Content']['title'];
		}
		return $existContents;
	}

/**
 * アイコンのURLを取得する
 * @param $type
 * @param $file
 * @param null $suffix
 * @return string
 */
	public function _getIconUrl ($plugin, $type, $file, $suffix = null) {
		$imageBaseUrl = Configure::read('App.imageBaseUrl');
		if($file) {
			if($plugin != 'Core') {
				$file = $plugin . '.' . $file;
			}
		} else {
			$icon = 'admin/icon_' . Inflector::underscore($type) . $suffix . '.png';
			$defaultIcon = 'admin/icon_content' . $suffix . '.png';
			if($plugin == 'Core') {
				$iconPath = WWW_ROOT . $imageBaseUrl . DS . $icon;
				if(file_exists($iconPath)) {
					$file = $icon;
				} else {
					$file = $defaultIcon;
				}
			} else {
				try {
					$pluginPath = CakePlugin::path($plugin) . 'webroot' . DS;
				}catch(Exception $e) {
					throw new ConfigureException('プラグインの BcContent 設定が間違っています。');
				}
				$iconPath = $pluginPath . str_replace('/', DS, $imageBaseUrl) . $icon;
				if(file_exists($iconPath)) {
					$file = $plugin . '.' . $icon;
				} else {
					$file = $defaultIcon;
				}
			}
		}
		return $this->assetUrl($file, array('pathPrefix' => $imageBaseUrl));
	}

/**
 * コンテンツ設定を Json 形式で取得する
 * @return string
 */
	public function getJsonSettings() {
		return json_encode($this->settings);
	}

/**
 * データが公開状態にあるか確認する
 *
 * @param array $data コンテンツデータ
 * @param bool $self コンテンツ自身の公開状態かどうか 
 * @return mixed
 */
	public function isAllowPublish($data, $self = false) {
		return $this->_Content->isAllowPublish($data, $self);
	}

/**
 * コンテンツIDよりフルURLを取得する
 *
 * @param $id
 * @return mixed
 */
	public function getUrlById($id, $full = false) {
		return $this->_Content->getUrlById($id, $full);
	}

/**
 * フルURLを取得する
 *
 * @param $url
 * @param bool $useSubDomain
 */
	public function getUrl($url, $full = false, $useSubDomain = false) {
		return $this->_Content->getUrl($url, $full, $useSubDomain);
	}

/**
 * プレフィックスなしのURLを取得する
 *
 * @param string $url
 * @param string $prefix
 * @param string $alias
 * @return mixed
 */
	public function getPureUrl($url, $siteId) {
		return $this->_Content->pureUrl($url, $siteId);
	}

/**
 * 現在のURLを元に指定したサブサイトのURLを取得する
 *
 * @param string $siteName
 * @return mixed|string
 */
	public function getCurrentRelatedSiteUrl($siteName) {
		if(empty($this->request->params['Site'])) {
			return '';
		}
		$url = $this->getPureUrl('/' . $this->request->url, $this->request->params['Site']['id']);
		$Site = ClassRegistry::init('Site');
		$site = $Site->find('first', ['conditions' => ['Site.name' => $siteName], 'recursive' => -1]);
		if(!$site) {
			return '';
		}
		$prefix = $Site->getPrefix($site);
		if($prefix) {
			$url = '/' . $prefix . $url;
		}
		return $url;
	}

/**
 * コンテンツリストをツリー構造で取得する
 * 
 * @param $id
 * @param null $level
 * @param array $options
 * @return array
 */
	public function getTree($id = 1, $level = null, $options = []) {
		$options = array_merge([
			'type' => '',
			'order' => ['Content.site_id', 'Content.lft']
		], $options);
		$conditions = array_merge($this->_Content->getConditionAllowPublish(), ['Content.id' => $id]);
		$content = $this->_Content->find('first', ['conditions' => $conditions, 'cache' => false]);
		if (!$content) {
			return [];
		}
		$conditions = array_merge($this->_Content->getConditionAllowPublish(), [
			'Content.site_root' => false,
			'rght <' => $content['Content']['rght'],
			'lft >' => $content['Content']['lft']
		]);
		if ($level) {
			$level = $level + $content['Content']['level'] + 1;
			$conditions['Content.level <'] = $level;
		}
		if(!empty($options['type'])) {
			$conditions['Content.type'] = ['ContentFolder', $options['type']];
		}
		if(!empty($options['conditions'])) {
			$conditions = array_merge($conditions, $options['conditions']);
		}
		// CAUTION CakePHP2系では、fields を指定すると正常なデータが取得できない
		return $this->_Content->find('threaded', [
			'order' => $options['order'], 
			'conditions' => $conditions, 
			'recursive' => 0,
			'cache' => false
		]);
	}

/**
 * 親コンテンツを取得する
 * 
 * @param $contentId
 * @return mixed
 */
	public function getParent($contentId) {
		return $this->_Content->getParentNode($contentId);
	}

/**
 * サイト連携データかどうか確認する
 * 
 * @param array $data
 * @return bool
 */
	public function isSiteRelated($data) {
		if(($data['Site']['relate_main_site'] && $data['Content']['main_site_content_id'] && $data['Content']['alias_id']) ||
			$data['Site']['relate_main_site'] && $data['Content']['main_site_content_id'] && $data['Content']['type'] == 'ContentFolder') {
			return true;
		} else {
			return false;
		}
	}

/**
 * 関連サイトのコンテンツを取得
 * 
 * @param int $id コンテンツID
 * @return array | false
 */
	public function getRelatedSiteContents($id = null, $options = []) {
		$options = array_merge([
			'excludeIds' => []
		], $options);
		$this->_Content->unbindModel(['belongsTo' => ['User']]);
		if(!$id && !empty($this->request->params['Content'])) {
			$content = $this->request->params['Content'];
			if($content['main_site_content_id']) {
				$id = $content['main_site_content_id'];
			} else {
				$id = $content['id'];
			}
		} else {
			return false;
		}
		return $this->_Content->getRelatedSiteContents($id, $options);
	}

/**
 * 関連サイトのリンク情報を取得する
 * 
 * @param int $id
 * @return array
 */
	public function getRelatedSiteLinks($id = null, $options = []) {
		$options = array_merge([
			'excludeIds' => []
		], $options);
		$contents = $this->getRelatedSiteContents($id, $options);
		$urls = [];
		if($contents) {
			foreach($contents as $content) {
				$urls[] = [
					'prefix' => $content['Site']['name'],
					'name' => $content['Site']['display_name'],
					'url' => $content['Content']['url']
				];
			}
		}
		return $urls;		
	}

/**
 * フォルダリストを取得する
 * 
 * @param int $siteId
 * @param array $options
 * @return array|bool
 */
	public function getContentFolderList($siteId = null, $options = array()) {
		return $this->_Content->getContentFolderList($siteId, $options);
	}
	
	public function getSiteRoot($siteId) {
		return $this->_Content->getSiteRoot($siteId);
	}
	
	public function getSiteRootId($siteId) {
		$content = $this->getSiteRoot($siteId);
		if($content) {
			return $content['Content']['id'];
		} else {
			return false;
		}
	}
	
}