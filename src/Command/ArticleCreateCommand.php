<?php

namespace App\Command;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:article:create',
    description: 'Create a sample article in the database',
)]
class ArticleCreateCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $io = new SymfonyStyle($input, $output);

        $article = new Article();

        $article
            ->setTitle('Bienvenue sur Blogify')
            ->setSlug('bienvenue-sur-blogify')
            ->setExcerpt('Découvrez notre premier article.')
            ->setContent(
                'Bienvenue sur Blogify, notre plateforme de publication.'
            )
            ->setPublishedAt(new \DateTimeImmutable());

        $this->entityManager->persist($article);
        $this->entityManager->flush();

        $io->success(sprintf(
            'Article créé avec succès ! ID : %d',
            $article->getId()
        ));

        return Command::SUCCESS;
    }
}
