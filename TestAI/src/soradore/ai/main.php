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

use pocketmine\scheduler\PluginTask;

use pocketmine\entity\Entity;

class main extends PluginBase implements Listener{

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onDamage(\pocketmine\event\entity\EntityDamageEvent $ev){
		$entity = $ev->getEntity();
		if($entity instanceof \pocketmine\entity\Zombie){
			$zombie = new CustomZombie($entity);
			$task = new ZombieTask($this, $zombie);
			$this->getServer()->getScheduler()->scheduleRepeatingTask($task, 1);
		}
	}
}



class ZombieTask extends PluginTask{

	public function __construct(PluginBase $plugin, CustomZombie $zombie){
		$this->zombie = $zombie;
		parent::__construct($plugin);
	}

	public function onRun(int $currentTick){
		$target = $this->zombie->setTarget();
		if($target == NULL) return;

		$tx = $target->x;
		$tz = $target->z;

	    $cx = $this->zombie->getX();
	    $cz = $this->zombie->getZ();

        if($cx < 0){
        	$x = $tx + $cx;
        }else{
        	$x = $tx - $cx;
        }

        if($cz < 0){
        	$z = $tz + $cz;
        }else{
        	$z = $tz - $cz;
        }

        $rad = atan2($z, $x);

        $x = CustomZombie::SPEED * cos($rad);
        $y = 0;
        $z = CustomZombie::SPEED * sin($rad);

        $this->zombie->move($x, $y, $z);

	}
}
