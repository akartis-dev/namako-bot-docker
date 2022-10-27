<?php
/**
 * @author <Akartis>
 */

namespace App\Services\Application;


use App\Entity\ActionHistory;
use App\Entity\Customer;
use App\ObjectManager\EntityObjectManager;
use App\Repository\ActionHistoryRepository;
use App\Services\Constants;

class ActionHistoryService
{
    public const MP3 = "MUSIC_MP3";
    public const MP4 = "MUSIC_MP4";

    public function __construct(private EntityObjectManager $em)
    {
    }

    /**
     * Create and save new bot history
     */
    public function createAndSaveNewHistory(
        string $fileName,
        Customer $customer,
        string $url,
        string $type = self::MP3,
        string $quality = self::MP3
    ): ActionHistory
    {
        $history = (new ActionHistory())
            ->setTitle($fileName)
            ->setCustomer($customer)
            ->setType($type)
            ->setUrl($url)
            ->setQuality($quality);

        $this->em->save($history);

        return $history;
    }

    /**
     * Create and save new bot history
     */
    public function success(ActionHistory $actionHistory, string $filename): void
    {
        $actionHistory->setTitle($filename);
        $actionHistory->setStatus(Constants::STATUS_DONE);
        $this->em->update();
    }

    /**
     * Create and save new bot history
     */
    public function errorFail(ActionHistory $actionHistory): void
    {
        $actionHistory->setStatus(Constants::STATUS_ERROR);
        $this->em->update();
    }

    /**
     * Return last customer history
     *
     * @param Customer $customer
     * @return ActionHistory
     */
    public function getLastCustomerHistory(Customer $customer): ActionHistory|null
    {
        /** @var ActionHistoryRepository $repo */
        $repo = $this->em->getEm()->getRepository('App:ActionHistory');
        $result = $repo->findBy(['customer' => $customer], ['id' => 'DESC'], 1);

        return $result[0] ?? null;
    }
}
