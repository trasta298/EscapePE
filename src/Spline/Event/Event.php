<?php

namespace Spline\Event;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\block\Block;

use pocketmine\item\Item;

use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\entity\PrimedTNT;
use pocketmine\entity\Creeper;
use pocketmine\entity\Skeleton;

use pocketmine\event\Listener;
use pocketmine\event\TranslationContainer;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDespawnEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\entity\ProjectileHitEvent;

use pocketmine\event\inventory\InventoryOpenEvent;

use pocketmine\event\level\LevelLoadEvent;

use pocketmine\event\player\PlayerBucketEmptyEvent;
use pocketmine\event\player\PlayerBucketFillEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\event\player\PlayerAnimationEvent;

use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\event\server\QueryRegenerateEvent;

use pocketmine\math\Math;
use pocketmine\math\Vector3;

use pocketmine\level\Location;
use pocketmine\level\Position;

use pocketmine\level\particle\DestroyBlockParticle;
use pocketmine\level\particle\TerrainParticle;
use pocketmine\level\sound\LaunchSound;
use pocketmine\level\sound\SplashSound;

use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ByteTag;

use pocketmine\utils\MainLogger;

use Spline\System\Chat;
use Spline\Game\Field;

class Event implements Listener{

	function __construct($main){
		$this->main = $main;
	}

	public function PlayerJoinEvent(PlayerJoinEvent $event){
		$joinMessage = $event->getJoinMessage();
		$serverInstance = Server::getInstance();
		$serverInstance->broadcastPopup($serverInstance->getLanguage()->translateString($joinMessage->getText(), $joinMessage->getParameters()));
		$event->setJoinMessage("");

		$player = $event->getPlayer();
		$name = $player->getName();
		$pdata = $this->main->dataLoad($name);
		$this->main->game->onJoin($player);

	}

	public function PlayerQuitEvent(PlayerQuitEvent $event){
		$player = $event->getPlayer();
		$name = $player->getName();
		$this->main->dataSave($name);
	}

	public function onPlayerInteract(PlayerInteractEvent $event){
		$hand_id = $event->getItem()->getID();
		$player = $event->getPlayer();
		$name = $player->getName();
		$block = $event->getBlock();
		$block_id = $block->getId();
		if($block->y == 0){
			return false;
		}
		//手持ちが本
		switch ($hand_id){
			case 340://本
				switch ($block_id) {
					case 133: //エメラルド
						$out = $this->main->entry->addEntry($name);
						$player->sendMessage(Chat::System($out));
						break;

					case 57: //ダイヤモンド
						$out = $this->main->entry->removeEntry($name);
						$player->sendMessage(Chat::System($out));
						break;

					default:
						# code...
						break;
				}
				break;

			case 260: //りんごdebug
				$player->sendMessage(Chat::Debug($block->x." ".$block->y." ".$block->z." ".$block_id));
				break;

			case 280: //棒
				$this->main->entry->addEntry("trasta334");
				$this->main->entry->addEntry("trasta333");
				$player->sendMessage(Chat::Debug("とらにゃん集団エントリー"));
				break;

			case 281: //ボウル
				Field::geneLaputa([$block->x, $block->y+25, $block->z], 20);
				break;

			case 283: //金剣
				break;
		}
	}

}