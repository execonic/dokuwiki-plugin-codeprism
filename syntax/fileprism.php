<?php

class syntax_plugin_codeprism_fileprism extends syntax_plugin_codeprism_codeprism
{
	protected $entry_pattern = '<fileprism\b.*/>';
	protected $exit_pattern = '/>';
	protected $match_pattern = '/<fileprism (.+?)( \[(.+?)\])* *\/>/';

	public function connectTo($mode)
	{
		if (isset($_REQUEST['comment'])) return false;

		$this->Lexer->addEntryPattern($this->entry_pattern, $mode, 'plugin_codeprism_fileprism');
	}

	public function postConnect()
	{
		$this->Lexer->addExitPattern($this->exit_pattern, 'plugin_codeprism_fileprism');
	}

	public function render($mode, Doku_Renderer $renderer, $data)
	{
		if ($mode != 'xhtml') return false;

		list($state) = $data;

		switch ($state) {
		case DOKU_LEXER_ENTER:
			list(, $pre_opt_arr, $code_opt_arr) = $data;

			$renderer->doc .= '<pre class="dokuwiki-plugin-codeprism-'.$pre_opt_arr['css'].' '.$pre_opt_arr['line-numbers'] . '"';
			unset($pre_opt_arr['line-numbers']);
			unset($pre_opt_arr['css']);

			foreach($pre_opt_arr as $key => $val) {
				$renderer->doc .= ' ' . $key . '="' . $val . '"';
			}

			$renderer->doc .='>';
			break;

		case DOKU_LEXER_EXIT:
			$renderer->doc .= '</pre>';
			break;
		}

		return false;
	}
}
