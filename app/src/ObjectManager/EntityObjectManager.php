<?php
/**
 * @author <Akartis>
 */

namespace App\ObjectManager;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class EntityObjectManager
{
    public function __construct(private EntityManagerInterface $em, private KernelInterface $kernel)
    {
    }

    public function update(): void
    {
        $this->getEm()->flush();
    }


    public function save($entity): void
    {
        $this->getEm()->persist($entity);
        $this->getEm()->flush();
    }

    public function delete($entity): void
    {
        $this->getEm()->remove($entity);
        $this->getEm()->flush();
    }

    public function getEm(): EntityManagerInterface
    {
        if(!$this->em->isOpen()){
            $this->em->close();
            $this->kernel->getContainer()->get('doctrine')->reset();

            return $this->kernel->getContainer()->get('doctrine.orm.entity_manager');
        }

        return $this->em;
    }

    public function saveUnique($entity)
    {
        try {
            $this->getEm()->persist($entity);
            $this->getEm()->flush();

            return $entity;
        } catch (\Exception) {
            return null;
        }
    }
}
