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
            ->addArgument('action', InputArgument::OPTIONAL, "Création d'utilisateur")
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $actionArg = $input->getArgument('action');

        // dd($actionArg, $actionArg === 'createBook');


        // -------------- CREATE A BOOK -------------------
        if($actionArg === 'createBook'){

            $title = $io->ask('Entrez le titre de votre livre', '1984');
            $author = $io->ask("Entrez l'auteur de votre livre", 'George Orwell');


            $book = new Book();
            $book->setISBN("978026452278");
            $book->setTitle($title);  
            $book->setAuthor($author);

            $this->em->persist($book);
            $this->em->flush();

            $io->success('You have a new book!');
       }


        // -------------- CREATE A USER -------------------
        if($actionArg === 'createUser'){

            $email = $io->ask('Entrez  un email', 'email@gmail.com');
            $plainPassword = $io->ask('Entrez  un mot de passe', '1234');

            $user = new User();

            // hash the plain password
            $hashedPasword = $this->passwordHasher->hashPassword($user, $plainPassword);

            $user->setEmail($email);
            $user->setPassword($hashedPasword);
            $user->setRoles(['ROLE_ADMIN']);

            $this->em->persist($user);
            $this->em->flush();

            $io->success('You have a new user!');
        };

        return Command::SUCCESS;
    }
}
