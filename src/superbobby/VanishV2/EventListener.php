<?php

namespace superbobby\VanishV2;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\TextFormat;
use pocketmine\event\player\PlayerCommandPreprocessEvent;

use function array_search;
use function in_array;

class EventListener implements Listener {

    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();
        if(in_array($name, VanishV2::$vanish)){
            unset(VanishV2::$vanish[array_search($name, VanishV2::$vanish)]);
        }
    }
	
	public function onChat(PlayerCommandPreprocessEvent $event) {
		$player = $event->getPlayer();
		$command = explode(" ", strtolower($event->getMessage()));
		$blockedCommands = array("/w", "/tell", "/msg");
		if(in_array($command[0], $blockedCommands)) {
			if($player->hasPermission("vanish.use")) {
				return;
			} else if((in_array($command[1], VanishV2::$vanish)) and (isset($command[2]))) {
				$event->setCancelled(true);
				$player->sendMessage(TextFormat::WHITE . "That player cannot be found");
			} else {
				return;
			}
		} else if($command[0] === "/vanish") {
			if(!$player->hasPermission("vanish.use")) {
				$event->setCancelled(true);
				$player->sendMessage(TextFormat::RED . "Unknown command. Try /help for a list of commands");
			}
		}
	}
}
