<?php

namespace Spline\Game;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\level\Level;

use Spline\Game\Laputa;


class Field{

	const WORLD = "field";

	//フィールド削除
	public static function remove(){
		$level = Server::getInstance()->getLevelByName(self::WORLD);
		Server::getInstance()->unloadLevel($level);
		Server::getInstance()->loadLevel(self::WORLD);
		$level->setAutoSave(false);
	}

	//フィールド生成
	public static function form($center){
		$laputas = [];
		$laputas[3] = self::geneLaputa($center, 25);
		$dis_base = [70,-5]; //各チーム拠点までの距離行列 xz,y
		for($i=0; $i < 3; $i++){
			$rad = deg2rad(120*$i); //弧度法で
			$trans = [$dis_base[0]*sin($rad), $dis_base[1], $dis_base[0]*cos($rad)];
			$pos = [$center[0]+$trans[0], $center[1]+$trans[1], $center[2]+$trans[2]];
			$laputas[$i] = self::geneLaputa($pos, 15);
		}
		return $laputas;
	}

	//浮島生成
	public static function geneLaputa($pos, $size){
		$level = Server::getInstance()->getLevelByName(self::WORLD);
		$laputa = new Laputa(1, 0, $size, $pos, $level);
		$laputa->placeObject();
		return $laputa;
	}


}