<?php 

namespace soradore\ai\CustomEntities;

use pocketmine\entity\Entity;
use pocketmine\entity\Zombie;
use pocketmine\entity\Human;

use pocketmine\level\Position;
use pocketmine\math\Vector3;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;

class CustomZombie implements CustomEntity{

    const SPEED = 0.2;
    const DEFAULT_HEALTH = 20;
    const DEFAULT_ATTACK_VALUE = 3;

    public $target = null;
    public $randomWalk = true;

    public function __construct(Entity $zombie, Human $target = null, int $lv = 1){
        $this->zombie = $zombie;
        $this->lv = $lv;
        /*$this->zombie->setNameTagVisible(true);
        $this->zombie->setNameTagAlwaysVisible(true);*/
        $this->setMaxHealth();
        $this->setNameTag();
        if($target == NULL){
            $target = $this->setTarget();
        }

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

    public function setPitch($deg){
        $this->zombie->pitch = $deg;
    }


    public function setYaw($deg){
        $this->zombie->yaw = $deg;
    }


    public function getWorld(){
        return $this->zombie->level;
    }


    public function getDirection(){
        return $this->zombie->getDirection();
    }


    public function attack2target(){
        $lv = $this->lv;
        $value = self::DEFAULT_ATTACK_VALUE + ($lv - 1);
        if($this->target == NULL) return;
        $event = new EntityDamageByEntityEvent($this->zombie, $this->target, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $value);
        $this->target->attack($event);
    }


    public function getDistance($target = NULL){
        if($target == NULL) $target = $this->target;
        return sqrt($this->zombie->distance($target));
    }


    public function setTarget(){
        //if($this->target != null) return;
        $level = $this->zombie->getLevel();
        $target = $level->getNearestEntity($this->zombie, 10, Human::class);
        $this->target = $target;
        return $this->getTarget();
    }


    public function getTarget(){
        return $this->target;
    }


    public function move($x, $y, $z){
        $this->zombie->move($x, $y, $z);
    }



    public function getName(){
        return "Zombie";
    }


    
    public function getFrontBlock($y = 0){
        $return = false;
        $level = $this->zombie->getLevel();
        switch($this->zombie->getDirection()){
            case 0:
                $return = $level->getBlockAt(floor($this->zombie->getX()) + 1, $this->zombie->getY() + $y, floor($this->zombie->getZ()))->getId();
                break;
            case 1:
                $return = $level->getBlockAt(floor($this->zombie->getX()), $this->zombie->getY() + $y, floor($this->zombie->getZ()) + 1)->getId();
                break;
            case 2:
                $return = $level->getBlockAt(floor($this->zombie->getX()) - 1, $this->zombie->getY() + $y, floor($this->zombie->getZ()))->getId();
                break;
            case 3:
                $return = $level->getBlockAt(floor($this->zombie->getX()), $this->zombie->getY() + $y, floor($this->zombie->getZ()) - 1)->getId();
                break;
        }
        return $return;
    }


    public function getLevel(){
        return $this->lv;
    }



    public function setMaxHealth(){
        $lv = $this->lv;
        switch ($lv) {
            case 1:
                $this->zombie->setMaxHealth(self::DEFAULT_HEALTH);
                break;
            case 2:
                $this->zombie->setMaxHealth(self::DEFAULT_HEALTH + 3);
                break;
            case 3:
                $this->zombie->setMaxHealth(self::DEFAULT_HEALTH + 5);
                break;
            case 4:
                $this->zombie->setMaxHealth(self::DEFAULT_HEALTH + 7);
                break;
            default:
                # code...
                break;
        }
    }



    public function setNameTag(){
        $lv = $this->lv;
        $tag = "     §c";
        $health = $this->zombie->getHealth();
        for($i=0;$i<$health;$i++){
            $tag .= "|";
        }
        $tag .= "\n§f";
        $tag .= "     [Lv." . $lv . "] " . $this->getName();
        $this->zombie->setNameTag($tag);
    }

}