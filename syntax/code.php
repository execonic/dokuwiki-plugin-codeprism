<?php

class syntax_plugin_codeprism_code extends syntax_plugin_codeprism_codeprism
{
	protected $entry_pattern = '<code\b.*?>(?=.*?</code>)';
	protected $exit_pattern = '</code>';
	protected $match_pattern = '/<code (.+?)( \[(.+?)\])* *>/';

	private function override_syntax_code()
	{
		return $this->getConf('override-code');
	}

	public function connectTo($mode)
	{
		if (isset($_REQUEST['comment']) || 'false' == $this->override_syntax_code()) {
				return false;
		}

		$this->Lexer->addEntryPattern($this->entry_pattern, $mode, 'plugin_codeprism_code');
	}

	public function postConnect()
	{
		$this->Lexer->addExitPattern($this->exit_pattern, 'plugin_codeprism_code');
	}
}
