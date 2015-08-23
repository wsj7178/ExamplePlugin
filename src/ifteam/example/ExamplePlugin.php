<?php

namespace ifteam\example;

use ifteam\example\database\PluginDatabase;
use ifteam\example\listener\EventListener;
use ifteam\example\task\AutoSaveTask;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

class ExamplePlugin extends PluginBase implements Listener {
	/** @var PluginDatabase */
	private $database;

	/** @var EventListener */
	private $eventListener;

	/**
	 * Called when the plugin is enabled
	 *
	 * @see \pocketmine\plugin\PluginBase::onEnable()
	 */
	public function onEnable(){
		$this->database = new PluginDatabase($this);
		$this->eventListener = new EventListener($this);

		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new AutoSaveTask($this), 12000);
	}

	/**
	 * Called when the plugin is disabled
	 * Use this to free open things and finish actions
	 *
	 * @see \pocketmine\plugin\PluginBase::onDisable()
	 */
	public function onDisable(){
		$this->save();
	}

	/**
	 * Save plug-in configs
	 *
	 * @param bool $async
	 */
	public function save($async = false){
		$this->database->save($async);
	}

	/**
	 * Handles the received command
	 * @see \pocketmine\plugin\PluginBase::onCommand()
	 *
	 * @param CommandSender $sender
	 * @param Command $command
	 * @param string $label
	 * @param array $args
	 *
	 * @return bool
	 */
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		return $this->eventListener->onCommand($sender, $command, $label, $args);
	}

	/**
	 * Return Plug-in Database
	 */
	public function getDatabase(){
		return $this->database;
	}

	/**
	 * Returns plugin EventListener
	 */
	public function getEventListener(){
		return $this->eventListener;
	}
}

?>