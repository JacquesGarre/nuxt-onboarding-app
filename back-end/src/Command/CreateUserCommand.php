<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Question\Question;

#[AsCommand(
    name: 'create:user',
    description: 'This command helps you to create an user',
)]
class CreateUserCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setName('create:user')
            ->setDescription('This command helps you to create an user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $helper = $this->getHelper('question');
        $firstName = $helper->ask($input, $output, new Question('firstname :'.PHP_EOL));
        $lastName = $helper->ask($input, $output, new Question('lastName :'.PHP_EOL));
        $email = $helper->ask($input, $output, new Question('email address :'.PHP_EOL));
        $password = $helper->ask($input, $output, new Question('password :'.PHP_EOL));

        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);
       
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $user->setIsVerified(true);
        $em = $this->entityManager;
        $repo = $em->getRepository(User::class);
        $repo->save($user, true);

        $io->success('New user created!');

        return Command::SUCCESS;
    }
}
