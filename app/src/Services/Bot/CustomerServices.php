<?php
/**
 * @author <Akartis>
 */

namespace App\Services\Bot;


use App\Entity\Customer;
use App\ObjectManager\EntityObjectManager;
use App\Repository\CustomerRepository;

class CustomerServices
{
    public function __construct(
        private EntityObjectManager $em,
        private CustomerRepository $repository
    )
    {
    }

    /**
     * Find customer by id in database
     *
     * @param string $id
     */
    public function getCustomerById(string $facebookId): Customer
    {
        return $this->repository->findOneBy(['facebookId' => $facebookId]);
    }
}
