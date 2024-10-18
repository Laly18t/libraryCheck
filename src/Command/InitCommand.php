<?php

namespace App\Command;

use App\Entity\User;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:init',
    description: 'Add a short description for your command',
)]
class InitCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('createUser', InputArgument::OPTIONAL, "Création d'utilisateur")
            ->addArgument('createBook', InputArgument::OPTIONAL, 'Création de livre')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        // $arg = $input->getArgument('arg');
        $createUser = $input->getArgument('createUser');
        $createBook = $input->getArgument('createBook');


        // -------------- CREATE A BOOK -------------------
        if($createBook === $input->getArgument('createBook')){
            $book = new Book();
            $book->setISBN("978026452218");
            $book->setTitle("Harry Potter 2");  
            $book->setAuthor("J.K Rowling");

            $this->em->persist($book);
            $this->em->flush();

            $io->success('You have a new book!');
       }

        // -------------- CREATE A USER -------------------
        if($createUser === $input->getArgument('createUser')){
            $user = new User();

            // hash the plain password
            $plainPassword = '1234';
            $hashedPasword = $this->passwordHasher->hashPassword($user, $plainPassword);

            $user->setEmail('email@gmail.com');
            $user->setPassword($hashedPasword);
            $user->setRoles(['ROLE_ADMIN']);

            $this->em->persist($user);
            $this->em->flush();

            $io->success('You have a new user!');
        };

        return Command::SUCCESS;
    }
}
