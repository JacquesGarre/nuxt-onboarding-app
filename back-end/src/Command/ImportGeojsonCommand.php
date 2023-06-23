<?php

namespace App\Command;

use App\Entity\Continent;
use App\Entity\Country;
use App\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Question\Question;
use CrEOF\Spatial\PHP\Types\Geometry\MultiPolygon;
use geoPHP;

#[AsCommand(
    name: 'import:geojson',
    description: '',
)]
class ImportGeojsonCommand extends Command
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
            ->setName('import:geojson')
            ->setDescription('')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $continentRepository = $this->entityManager->getRepository(Continent::class);
        $continents = $continentRepository->findAll();

        foreach($continents as $continent){

            $json = $continent->getGeojson();
            $multipolygon = geoPHP::load($json, 'json');
            $wkt = $multipolygon->out('wkt');
            $continent->setWkt($wkt);
            $continentRepository->save($continent, true);
        }

        $io->success('Geojson imported!');

        return Command::SUCCESS;
    }
}
