<?php

namespace ifteam\example\task;

use pocketmine\scheduler\PluginTask;
use pocketmine\plugin\Plugin;

class AutoSaveTask extends PluginTask {
	public function __construct(Plugin $owner){
		parent::__construct($owner);
	}

	public function onRun($currentTick){
		/** @var $owner \ifteam\example\ExamplePlugin */
		$owner = $this->getOwner();

		$owner->save(true);
	}
}

?>