<?php


namespace App\Services;


use App\Entity\User;
use App\Repository\TileRepository;

class MapService
{

    public function tileExists(int $x, int $y, TileRepository $tileRepository): bool
    {
        $tile = $tileRepository->findOneBy(['coordX' => $x, 'coordY' => $y]);
        $result = true;
        if (!$tile OR !$tile->getType()) {
            $result = false;
        }
        return $result;
    }

    public function getHideouts(TileRepository $tileRepository)
    {
        $whiteTiles = $tileRepository->findBy(['type' => true]);
        shuffle($whiteTiles);
        return array_slice($whiteTiles, 0, 4);
    }

    public function getRandomTile(TileRepository $tileRepository)
    {
        $hideouts = $tileRepository->findBy(['isHideout' => true]);
        return $hideout = $hideouts[array_rand($hideouts)];
    }


   public function checkTicket(User $user, TileRepository $tileRepository)
    {
       $whereIsUser = $tileRepository->findOneBy([
           'coordX' => $user->getCoordX(),
           'coordY' => $user->getCoordY()
       ]);
       return $whereIsUser->hasTicket();

    }
}