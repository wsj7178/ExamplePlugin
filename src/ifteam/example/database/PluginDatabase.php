<?php

namespace ifteam\example\database;

use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\Plugin;

class PluginDatabase {
	/** @var Plugin */
	private $plugin;

	/** @var array */
	public $messages, $db;

	const MESSAGES_VERSION = 1;

	/**
	 * Initialize the plug-in database
	 *
	 * @param Plugin $plugin
	 * @param string $messageVersion
	 * @param array $defaultDatabase
	 */
	public function __construct(Plugin $plugin, $messageVersion = null, $defaultDatabase = []){
		$this->plugin = $plugin;

		$this->messages = $this->initMessage();
		$this->db = $this->initDatabase();
	}

	/**
	 * Register the plug-in command
	 *
	 * @param string $name
	 * @param string $permission
	 * @param string $description
	 * @param string $usage
	 */
	public function registerCommand($name, $permission, $description = "", $usage = ""){
		$command = new PluginCommand($name, $this->plugin);
		$command->setDescription($description);
		$command->setPermission($permission);
		$command->setUsage($usage);

		Server::getInstance()->getCommandMap()->register($name, $command);
	}

	/**
	 * Gets a translated message
	 *
	 * @param string $var
	 */
	public function get($var){
		$lang = (isset($this->messages[Server::getInstance()->getLanguage()->getLang()])) ? Server::getInstance()->getLanguage()->getLang() : "eng";
		return $this->messages [$lang . "-" . $var];
	}

	/**
	 * Print the message
	 *
	 * @param CommandSender $player
	 * @param string $text
	 * @param string $mark
	 */
	public function info(CommandSender $player, $text = "", $mark = null){
		if($mark == null){
			$mark = $this->get("default-prefix");
		}

		$player->sendMessage(TextFormat::DARK_AQUA . $mark . " " . $text);
	}

	/**
	 * Print the alert
	 *
	 * @param CommandSender $player
	 * @param string $text
	 * @param string $mark
	 */
	public function alert(CommandSender $player, $text = "", $mark = null){
		if($mark == null){
			$mark = $this->get("default-prefix");
		}

		$player->sendMessage(TextFormat::RED . $mark . " " . $text);
	}

	/**
	 * Save the message file to the server
	 *
	 * @return array
	 */
	public function initMessage(){
		$this->getPlugin()->saveResource("messages.yml", false);
		$this->updateMessages("messages.yml");

		return (new Config($this->getPlugin()->getDataFolder(). "messages.yml", Config::YAML))->getAll();
	}

	/**
	 * Save the database file(.json)to the server
	 */
	public function initDatabase(){
		@mkdir($this->getPlugin()->getDataFolder());
		return (new Config($this->getPlugin()->getDataFolder() . "database.json", Config::JSON, []))->getAll();
	}

	/**
	 * Updating the message file stored at the server
	 *
	 * @param string $targetYmlName
	 */
	public function updateMessages($targetYmlName){
		$targetYml = (new Config($this->getPlugin()->getDataFolder() . $targetYmlName, Config::YAML))->getAll();

		if(!isset($targetYml["version"]) or $targetYml ["version"] < self::MESSAGES_VERSION){
			$this->getPlugin()->saveResource($targetYmlName, true);
		}
	}

	/**
	 * Save plug-in database
	 *
	 * @param bool $async
	 */
	public function save($async = false){
		$save = new Config($this->getPlugin()->getDataFolder(). "database.json", Config::JSON);
		$save->setAll($this->db);
		$save->save($async);
	}

	/**
	 * Return the plug-in instance
	 */
	private function getPlugin(){
		return $this->plugin;
	}
}

?>