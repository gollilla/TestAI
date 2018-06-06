<?php

namespace soradore\ai\Task;

use pocketmine\scheduler\Task;
//use pocketmine\entity\Zombie;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\math\Vector3;

class SpawnTask extends Task {

    const MAX_ENTITY_COUNT = 10;
    public function __construct($plugin){
        $this->owner = $plugin;
    }

    public function getOwner(){
        return $this->owner;
    }
    public function onRun(int $tick){
        /*$entityCount = $this->getOwner()->getEntityCount();
        if($entityCount == self::MAX_ENTITY_COUNT) return;*/
        //echo $tick.PHP_EOL;
        $level = $this->getOwner()->getServer()->getDefaultLevel();
        $entities = $level->getEntities();
        $this->getOwner()->getServer()->broadcastMessage("お掃除...");
        $i=0;
        foreach ($entities as $entity) {
            if(!$entity instanceof Human){
               $entity->kill();
               ++$i;
            }         
        }
        $this->getOwner()->getServer()->broadcastMessage("お掃除しました [ ". $i . " ]体");
        $lpos = $level->getSafeSpawn();
        for($i=0;$i<4;$i++){
            $pos = new Vector3($lpos->x + 4, $lpos->y, $lpos->z + rand(0, 4));
            $nbt = Entity::createBaseNBT($pos);
            $zombie = Entity::createEntity("Zombie", $level, $nbt);
            $zombie->spawnToAll();
        }
        //$this->getOwner()->addEntity(/*$zombie->getId()*/);
    }
}  