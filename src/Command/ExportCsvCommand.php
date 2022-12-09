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
    name: 'export_csv',
    description: 'Envoyer les data du client en base de données',
)]
class ExportCsvCommand extends Command
{
    protected function configure(): void
    {

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $pdo = new Database();
          $pdo->Pdo()->query("CREATE TABLE contact (
            date varchar(255) NOT NULL,
            montant int NOT NULL,
            tel int NOT NULL,
            code_postal int NOT NULL
        )");


        $file = fopen("../contact.csv","r");

        fgetcsv($file);
        ini_set('max_execution_time', 0);
        while (($getData = fgetcsv($file, 1000000, ";")) !== FALSE)
        {

            $pdo->Pdo()->query("INSERT INTO contact (date,montant,tel,code_postal) 
                                VALUES ('$getData[0]', '$getData[1]', '$getData[2]', '$getData[3]')");
        }
        fclose($file);

        $output->write("Données du contact créées en base de données");
        return Command::SUCCESS;
    }
}
