<?php 

namespace soradore\ai;

use pocketmine\entity\Entity;

class CustomZombie {

	const SPEED = 0.3;

	public $target = NULL;

	public function __construct(Entity $zombie){
		$this->zombie = $zombie;
	}


	public function getX(){
		return $this->zombie->x;
	}

	public function getY(){
		return $this->zombie->y;
	}

	public function getZ(){
		return $this->zombie->z;
	}



	public function setTarget(){
		$level = $this->zombie->getLevel();
		$target = $level->getNearestEntity($this->zombie, 10); 
		if($target instanceof $this){
			$target = NULL;
		}
        $this->target = $target;
		return $this->target;
	}


	public function getTarget(){
		return $this->target;
	}


	public function move($x, $y, $z){
		$this->zombie->move($x, $y, $z);
	}

}