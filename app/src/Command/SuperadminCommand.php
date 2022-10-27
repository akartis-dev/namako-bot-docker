<?php

namespace App\Command;

use App\Entity\User;
use App\ObjectManager\EntityObjectManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:superadmin',
    description: 'Add a short description for your command',
)]
class SuperadminCommand extends Command
{
    public function __construct(private EntityObjectManager $em, private UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct("app:superadmin");
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->success("Create a new superadmin");
        $email = $io->askQuestion(new Question("Email"));

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $io->error("Email is not valid format");
            return Command::INVALID;
        }
        $password = $io->askQuestion(new Question("Password"));
        $user = (new User())
            ->setEmail($email)
            ->setRoles(['ROLE_SUPERADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $this->em->saveUnique($user);

        $io->success('Save new superadmin successful');

        return Command::SUCCESS;
    }
}
