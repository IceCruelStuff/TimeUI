<?php

namespace TimeUI;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\Player;
use pocketmine\Server;

use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI;

class TimeUI extends PluginBase implements Listener {

    public function onEnable() : void {
        $this->getLogger()->info(TextFormat::GREEN . "TimeUI has been enabled");
    }

    public function onDisable() : void {
        $this->getLogger()->info(TextFormat::RED . "TimeUI has been disabled")
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
        switch ($command->getName()) {
            case "timeui":
                if ($sender instanceof Player) {
                    if ($sender->hasPermission("timeui.command")) {
                        $this->timeSet($sender);
                        return true;
                    } else {
                        $sender->sendMessage("You do not have the permission to use this command");
                        return true;
                    }
                    return false;
                } else {
                    $sender->sendMessage("Please use this command in-game");
                    return true;
                }
        }
    }

    public function setTime($sender) {
        $formAPI = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $fromAPI->createSimpleForm(function (Player $sender, int $data = null) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $sender->getLevel()->setTime(0);
                    $sender->sendMessage("[TimeUI] Successfully set time to day");
                    $sender->addTitle("Changed time to day");
                    break;
                case 1:
                    $sender->getLevel()->setTime(15000);
                    $sender->sendMessage("[TimeUI] Successfully set time to night");
                    $sender->addTitle("Changed time to night");
                    break;
                case 2:
                    $this->openMenu($sender);
                    break;
            }
            $form->setTitle("TimeUI");
            $form->setContent("Select time:");
            $form->addButton("Day");
            $form->addButton("Night");
            $form->addButton("Back");
            $form->sendToPlayer($sender);
            return $form;
        }
    }

}
