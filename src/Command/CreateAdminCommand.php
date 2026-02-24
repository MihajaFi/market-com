<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Crée un administrateur initial si aucun admin n’existe'
)]
class CreateAdminCommand extends Command
{
    private ManagerRegistry $doctrine;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
        $this->passwordHasher = $passwordHasher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $em = $this->doctrine->getManager();
        $userRepository = $em->getRepository(User::class);

        $existingAdmin = $userRepository->findOneBy(['roles' => ['ROLE_ADMIN']]);
        if ($existingAdmin) {
            $output->writeln('Un administrateur existe déjà : ' . $existingAdmin->getEmail());
            return Command::SUCCESS;
        }

        $admin = new User();
        $admin->setEmail('admin3@example.com');
        $admin->setUsername('admin3@example.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'Admin123!'));
        $admin->setRoles(['ROLE_ADMIN']);

        $em->persist($admin);
        $em->flush();

        $output->writeln('Administrateur initial créé : admin@example.com avec mot de passe Admin123!');
        return Command::SUCCESS;
    }
}