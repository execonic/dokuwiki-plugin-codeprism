<?php

class action_plugin_codeprism extends DokuWiki_Action_Plugin
{
	private function getTheme()
	{
		$theme = $this->getConf('theme');

		if ($theme != 'default') {
			$theme = '-' . $theme;
		}

		return $theme;
	}

	private function getCdn()
	{
		return $this->getConf('cdn');
	}

	public function register(Doku_Event_Handler $controller)
	{
		$controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, '_hookjs');
	}

	public function _hookjs(Doku_Event $event, $param)
	{
		$pluginBase = DOKU_BASE.'lib/plugins';

		$theme = $this->getTheme();
		$cdn = $this->getCdn();

		/** CSS  */
		$event->data['link'][] = array(
			'rel' => 'stylesheet',
			'href' => $pluginBase.'/codeprism/codeprism.css'
		);

		$css_hrefs = array(
			'themes/prism' . $theme . '.min.css',
			'plugins/line-numbers/prism-line-numbers.min.css',
			'plugins/line-highlight/prism-line-highlight.min.css',
			'plugins/toolbar/prism-toolbar.min.css',
			'plugins/command-line/prism-command-line.min.css',
		);

		/** Scripts */
		$scripts = array(
			'prism.js',
			'components.js',
			'plugins/autoloader/prism-autoloader.min.js',
			'components/prism-core.min.js',
			'plugins/line-numbers/prism-line-numbers.min.js',
			'plugins/line-highlight/prism-line-highlight.min.js',
			'plugins/toolbar/prism-toolbar.min.js',
			'plugins/copy-to-clipboard/prism-copy-to-clipboard.js',
			'plugins/show-language/prism-show-language.min.js',
			'plugins/command-line/prism-command-line.min.js',
			'plugins/file-highlight/prism-file-highlight.min.js'
		);

		if ($this->getConf('show-invis')) {
			$css_hrefs[] = 'plugins/show-invisibles/prism-show-invisibles.min.css';
			$scripts[] = 'plugins/show-invisibles/prism-show-invisibles.min.js';
		}

		if ($this->getConf('hl-brace')) {
			$css_hrefs[] = 'plugins/match-braces/prism-match-braces.min.css';
			$scripts[] = 'plugins/match-braces/prism-match-braces.min.js';
		}

		if ($this->getConf('previewer')) {
			$css_hrefs[] = 'plugins/previewers/prism-previewers.min.css';
			$scripts[] = 'plugins/previewers/prism-previewers.min.js';
		}

		foreach($css_hrefs as $href) {
			$event->data['link'][] = array(
				'rel' => 'stylesheet',
				'href' => $cdn . $href
			);
		}

		foreach($scripts as $script) {
			$event->data['script'][] = array(
				'src' => $cdn . $script,
				'_data' => ''
			);
		}
	}
}
