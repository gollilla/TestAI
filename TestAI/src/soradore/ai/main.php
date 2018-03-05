<?php



namespace soradore\ai;


/* Base */
use pocketmine\plugin\PluginBase;

/* Events */
use pocketmine\event\Listener;


/* Level and Math */
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;

use pocketmine\entity\Entity;

use soradore\ai\Zombie\Task\ZombieTask;
use soradore\ai\Zombie\CustomZombie;

class main extends PluginBase implements Listener{

    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->id = [];
    }

    public function onDamage(\pocketmine\event\entity\EntityDamageEvent $ev){
        $entity = $ev->getEntity();
        if($entity instanceof \pocketmine\entity\Zombie && $ev instanceof \pocketmine\event\entity\EntityDamageByEntityEvent){
            $id = $entity->getId();
            if(!isset($this->id[$id])){
                $zombie = new CustomZombie($entity);
                $task = new ZombieTask($this, $zombie);
                $this->getServer()->getScheduler()->scheduleRepeatingTask($task, 1);
                $this->id[$id] = $task;
            }
        }
    }

    public function onDeath(\pocketmine\event\entity\EntityDeathEvent $ev){
        $entity = $ev->getEntity();
        $id = $entity->getId();
        if(isset($this->id[$id])){
            $this->getServer()->getScheduler()->cancelTask($this->id[$id]->getTaskId());
            unset($this->id[$id]);
        }
    }
}

