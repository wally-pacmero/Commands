<?php

namespace Commands\CondenseCommand;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;

class CondenseCommand extends Command
{

    public function __construct()
    {
        parent::__construct("condense", "Tranformas minerales en bloques!");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if(!($sender instanceof Player)) return;

        if(!($sender->hasPermission("consense.command")) || !($sender->hasPermission("consenseall.command"))) return;

        $minerals = [
            "emerald" => [
                "count" => 0,
                "id" => 388,
                "slots" => [],
                "block" => 133
            ],
            "redstone" => [
                "count" => 0,
                "id" => 331,
                "slots" => [],
                "block" => 152
            ],
            "blue" => [
                "count" => 0,
                "id" => 351,
                "damage" => 4,
                "slots" => [],
                "block" => 22
            ],
            "iron" => [
                "count" => 0,
                "id" => 265,
                "slots" => [],
                "block" => 42
            ],
            "ore" => [
                "count" => 0,
                "id" => 266,
                "slots" => [],
                "block" => 41
            ],
            "diamond" => [
                "count" => 0,
                "id" => 264,
                "slots" => [],
                "block" => 57
            ]
        ];

        $slots = 0;
        foreach ($sender->getInventory()->getContents() as $item) {
            foreach ($minerals as $mineral => $data) {
                if($item->getId() === $data["id"]) {
                    if(isset($data["damage"]) && $item->getDamage() !== $data["damage"]) {
                        continue;
                    }
                    $minerals[$mineral]["count"] += $item->getCount();
                    $minerals[$mineral]["slots"][] = $slots;
                    continue;
                }
            }
            $slots++;
        }

        if(isset($args[0]) && $args[0] == "all" && $sender->hasPermission("condenseall.command")) {
            foreach ($minerals as $mineral => $data) {
                if($data["count"] >= 9) {
                    $division = floor($data["count"] / 9);
                    for ($i = 1;$i <= $division;++$i) {
                        $sender->getInventory()->removeItem(Item::get($data["id"], (isset($data["damage"]) ? $data["damage"] : 0), 9));
                    }
                    $sender->getInventory()->addItem(Item::get($data["block"], 0, $division));
                }
            }
            return;
        }

        if(!($sender->hasPermission("consense.command"))) return;
        foreach ($minerals as $mineral => $data) {
            if($data["count"] >= 9) {
                foreach ($data["slots"] as $slot) {
                    $sender->getInventory()->removeItem(Item::get($data["id"], (isset($data["damage"]) ? $data["damage"] : 0), 9));
                    $sender->getInventory()->addItem(Item::get($data["block"]));
                }
            }
        }
    }
}
