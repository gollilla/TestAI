<?php 

namespace soradore\ai\Inventory;

use pocketmine\inventory\BaseInventory;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\Server;
use pocketmine\event\entity\EntityInventoryChangeEvent;
use pocketmine\network\mcpe\protocol\InventoryContentPacket;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\types\ContainerIds;

class CustomEntityInventory extends BaseInventory {

	protected $itemInHandIndex = 0;

	public function __construct(Entity $holder){
		parent::__construct();
		$this->holder = $holder;
	}


	protected function doSetItemEvents(int $index, Item $newItem) : ?Item{
		Server::getInstance()->getPluginManager()->callEvent($ev = new EntityInventoryChangeEvent($this->getHolder(), $this->getItem($index), $newItem, $index));
		if($ev->isCancelled()){
			return null;
		}

		return $ev->getNewItem();
	}

	/**
	 * @return Entity
	 */
	public function getHolder(){
		return $this->holder;
	}


	public function getName() : string{
		return "CustomEntity";
	}

	public function getDefaultSize() : int{
		return 36;
	}


	public function sendHeldItem($target){
		$item = $this->getItemInHand();

		$pk = new MobEquipmentPacket();
		$pk->entityRuntimeId = $this->getHolder()->getId();
		$pk->item = $item;
		$pk->inventorySlot = $pk->hotbarSlot = $this->getHeldItemIndex();
		$pk->windowId = ContainerIds::INVENTORY;

		if(!is_array($target)){
			$target->dataPacket($pk);
			if($target === $this->getHolder()){
				$this->sendSlot($this->getHeldItemIndex(), $target);
			}
		}else{
			$this->getHolder()->getLevel()->getServer()->broadcastPacket($target, $pk);
			if(in_array($this->getHolder(), $target, true)){
				$this->sendSlot($this->getHeldItemIndex(), $this->getHolder());
			}
		}
	}


	private function isHotbarSlot(int $slot) : bool{
		return $slot >= 0 and $slot <= $this->getHotbarSize();
	}

	/**
	 * @param int $slot
	 * @throws \InvalidArgumentException
	 */
	private function throwIfNotHotbarSlot(int $slot){
		if(!$this->isHotbarSlot($slot)){
			throw new \InvalidArgumentException("$slot is not a valid hotbar slot index (expected 0 - " . ($this->getHotbarSize() - 1) . ")");
		}
	}

	/**
	 * Returns the item in the specified hotbar slot.
	 *
	 * @param int $hotbarSlot
	 * @return Item
	 *
	 * @throws \InvalidArgumentException if the hotbar slot index is out of range
	 */
	public function getHotbarSlotItem(int $hotbarSlot) : Item{
		$this->throwIfNotHotbarSlot($hotbarSlot);
		return $this->getItem($hotbarSlot);
	}

	/**
	 * @deprecated
	 * @return int
	 */
	public function getHeldItemSlot() : int{
		return $this->getHeldItemIndex();
	}

	/**
	 * Returns the hotbar slot number the holder is currently holding.
	 * @return int
	 */
	public function getHeldItemIndex() : int{
		return $this->itemInHandIndex;
	}

	/**
	 * Sets which hotbar slot the player is currently loading.
	 *
	 * @param int  $hotbarSlot 0-8 index of the hotbar slot to hold
	 * @param bool $send Whether to send updates back to the inventory holder. This should usually be true for plugin calls.
	 *                    It should only be false to prevent feedback loops of equipment packets between client and server.
	 *
	 * @throws \InvalidArgumentException if the hotbar slot is out of range
	 */
	public function setHeldItemIndex(int $hotbarSlot){
		$this->throwIfNotHotbarSlot($hotbarSlot);

		$this->itemInHandIndex = $hotbarSlot;

		$this->sendHeldItem($this->getHolder()->getViewers());
	}

	/**
	 * Returns the currently-held item.
	 *
	 * @return Item
	 */
	public function getItemInHand() : Item{
		return $this->getHotbarSlotItem($this->itemInHandIndex);
	}

	/**
	 * Sets the item in the currently-held slot to the specified item.
	 *
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function setItemInHand(Item $item) : bool{
		return $this->setItem($this->getHeldItemIndex(), $item);
	}


	public function getHotbarSize() : int{
		return 9;
	}
}