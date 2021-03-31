<?php

namespace Commands\EnderChestCommand;

use hcf\HCF;
use hcf\HCFPlayer;
use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\block\Block;
use pocketmine\tile\{Tile, EnderChest};
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

class EnderChestCommand extends PluginCommand {
	
	/**
	 * EnderChestCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("enderchest", Loader::getInstance());
		$this->setAliases(["ec"]);
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		$nbt = new CompoundTag("", [
			new StringTag("id", Tile::CHEST),
			new StringTag("CustomName", "EnderChest"),
			new IntTag("x", $sender->getFloorX()), 
			new IntTag("y", $sender->getFloorY() + 4),
			new IntTag("z", $sender->getFloorZ())
		]);
		/** @var EnderChest Tile */
		$chest = Tile::createTile("EnderChest", $sender->getLevel(), $nbt);
		$block = Block::get(Block::ENDER_CHEST);
		$block->x = $chest->getFloorX();
		$block->y = $chest->getFloorY();
		$block->z = $chest->getFloorZ();
		$block->level = $chest->getLevel();
		$block->level->sendBlocks([$sender], [$block]);
		$sender->getEnderChestInventory()->setHolderPosition($chest);
		$sender->addWindow($sender->getEnderChestInventory());
	}
}

?>
