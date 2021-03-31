<?php

namespace Craft\CraftCommand;

use Craft\Loader;

use pocketmine\block\Block;
use pocketmine\block\BlockIds;
use pocketmine\block\CraftingTable;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\inventory\CraftingGrid;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\InventoryContentPacket;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;

class CraftCommand extends Command
{

    public function __construct()
    {
        parent::__construct("craft", "Send CraftInventory");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!($sender instanceof Player)) return;

        $vector = new Vector3($sender->x, $sender->y - 2, $sender->z);

        $blockNew = Block::get(BlockIds::CRAFTING_TABLE);

        $sender->getLevel()->setBlock($vector, $blockNew);

        $blockNew = $sender->getLevel()->getBlock($vector);

        $blockNew->onActivate($sender->getInventory()->getItemInHand(), $sender);

    }
}
