<?php

namespace App\Command;

use App\Database;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'script2',
    description: 'Création des deux nouvelles tables triées selon la demande',
)]
class Script2Command extends Command
{
    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $db = new Database();
        $pdo = $db->Pdo();
          $pdo->query("CREATE TABLE trieDonParNum (
            tel int NOT NULL,
            don int NOT NULL,
            CONSTRAINT UC_Tel UNIQUE (tel)

        )");

          $sommeDonsTriParTel= $pdo->query("SELECT tel,
           sum(montant) as montant
    from contact
    group by tel")->fetchAll();

        foreach ($sommeDonsTriParTel as $item){
            $pdo->query("INSERT INTO triedonparnum (tel, don) VALUES ($item[tel], $item[montant])");
        }

          $pdo->query("CREATE TABLE trieNumCodePostal (
            tel int NOT NULL,
            code_postal int NOT NULL,
            CONSTRAINT UC_TelCodePo UNIQUE (tel,code_postal)
        )");;

          $listeCodePostalParNum = $pdo->query("SELECT tel,
          code_postal
           from contact
           group by tel")->fetchAll();

        foreach ($listeCodePostalParNum as $item){
            $pdo->query("INSERT INTO trieNumCodePostal (tel,code_postal) VALUES ($item[tel], $item[code_postal])");
        }
        $output->write("Données créées");

        return Command::SUCCESS;
    }
}
