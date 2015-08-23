<?php

namespace ifteam\example\listener;

use ifteam\example\ExamplePlugin;
use pocketmine\event\Listener;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Server;

class EventListener implements Listener {
	/** @var ExamplePlugin */
	private $plugin;

	public function __construct(ExamplePlugin $plugin){
		$this->plugin = $plugin;

		//$this->registerCommand("명령어명", "퍼미션명", "명령어_설명", "한줄_명령어_사용법");
		$this->registerCommand("command-name", "example", "command-description", "command-usage");

		Server::getInstance()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function registerCommand($name, $permission, $description, $usage){
		$name        = $this->plugin->getDatabase()->get($name);
		$description = $this->plugin->getDatabase()->get($description);
		$usage       = $this->plugin->getDatabase()->get($usage);

		$this->plugin->getDatabase()->registerCommand($name, $permission, $description, $usage);
	}

	/**
	 * @param CommandSender $sender
	 * @param Command $command
	 * @param string $commandAlias
	 * @param array $args
	 *
	 * @return bool
	 */
	public function onCommand(CommandSender $sender, Command $command, $commandAlias, array $args){
		if(strToLower($command) === $this->plugin->getDatabase()->get("command-name")){

			// TODO: 명령어만 친 경우
			if(!isset($args[0])){
				$sender->sendMessage($this->plugin->getDatabase()->get("help-message"));
				return true;
			}

			switch(strToLower($args[0])){

				//TODO: '/예제 어쩌구'
				case $this->plugin->getDatabase()->get("hello-world"):
					$sender->sendMessage($this->plugin->getDatabase()->get("hello-world-result"));
					break;

				// TODO: '/예제 저쩌구'
				case $this->plugin->getDatabase()->get("dlrow-olleh"):
					$sender->sendMessage($this->plugin->getDatabase()->get("dlrow-olleh-result"));
					break;

				// TODO: 잘못된 명령어를 입력한 경우
				default:
					$sender->sendMessage($this->plugin->getDatabase()->get("wrong-command"));
					break;
			}
		}

		return true;
	}
}

?>