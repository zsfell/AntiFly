<?php

namespace zs;

use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class AntiFly extends PluginBase implements Listener {

    public function onEnable(): void {
        $this->getLogger()->info("AntiFly has been enabled");

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();

        if (!$player instanceof Player) {
            return;
        }

        if ($player->isFlying() && !$player->hasPermission("allow.fly")) {
            $player->kick("Flying without permission");
        }
    }

    public function onPlayerToggleFlight(PlayerToggleFlightEvent $event): void {
        $player = $event->getPlayer();

        if (!$player instanceof Player) {
            return;
        }

        if ($event->isFlying() && !$player->hasPermission("allow.fly")) {
            $event->setCancelled(true); // Prevent the player from toggling flight
            $player->kick("Flying without permission");
        }
    }
}
