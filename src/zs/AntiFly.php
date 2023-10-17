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

        $this->saveDefaultConfig();
        $this->reloadConfig();

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();

        if (!$player instanceof Player) {
            return;
        }

        $purePerms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");

        if ($player->isFlying() && !$this->canFly($player, $purePerms)) {
            $player->kick("Flying without permission");
        }
    }

    public function onPlayerToggleFlight(PlayerToggleFlightEvent $event): void {
        $player = $event->getPlayer();

        if (!$player instanceof Player) {
            return;
        }

        $purePerms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");

        if ($event->isFlying() && !$this->canFly($player, $purePerms)) {
            $event->setCancelled(true);
            $player->kick("Flying without permission");
        }
    }

private function canFly(Player $player, $purePerms): bool {
    $group = $purePerms->getUserDataMgr()->getGroup($player);
    if ($group === null) {
        return false;
    }

    $flightPermission = $this->getConfig()->get("flight_permission");

    return $purePerms->hasGroupPermission($group, $flightPermission);
}
}
