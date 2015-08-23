<?php

namespace ifteam\example\listener;

use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\plugin\Plugin;
use onebone\economyapi\event\money\AddMoneyEvent;
use onebone\economyapi\event\money\ReduceMoneyEvent;
use onebone\economyapi\event\money\SetMoneyEvent;

class EconomyAPIListener implements Listener {
	/** @var EconomyAPIListener[] */
	private static $instance = [];

	/** @var null|\onebone\economyapi\EconomyAPI */
	private static $economyAPI = null;

	/**
	 * @param Plugin $plugin
	 *
	 * @return EconomyAPIListener
	 */
	public static function getInstance(Plugin $plugin){
		if(!isset(self::$instance[$plugin->getName()])){
			self::$instance[$plugin->getName()] = new EconomyAPIListener($plugin);
		}

		return self::$instance[$plugin->getName()];
	}

	private function __construct(Plugin $plugin){
		Server::getInstance()->getPluginManager()->registerEvents($this, $plugin);
	}

	/**
	 * Get EconomyAPI instance
	 *
	 * @return \onebone\economyapi\EconomyAPI|null
	 */
	public static function getEconomyAPI(){
		if(self::$economyAPI === null and (Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI") !== null)){
			self::$economyAPI = \onebone\economyapi\EconomyAPI::getInstance();
		}

		return self::$economyAPI;
	}

	// TODO: 아래의 이벤트들을 사용하지 않는다면 모두 지우셔도 됩니다

	// -------------------------------------------------------------
	public function onAddMoney(AddMoneyEvent $event){
		// TODO: 유저에게 돈을 줄 때 이 함수가 호출됨
	}

	public function onReduceMoney(ReduceMoneyEvent $event){
		// TODO: 유저의 돈을 빼앗을 때 이 함수가 호출됨
	}

	public function onSetMoney(SetMoneyEvent $event){
		// TODO: 유저의 돈을 설정했을 때 이 함수가 호출됨
	}
	// -------------------------------------------------------------
}

?>