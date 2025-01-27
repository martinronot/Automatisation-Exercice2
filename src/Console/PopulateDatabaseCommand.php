<?php

namespace App\Console;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Office;
use Illuminate\Support\Facades\Schema;
use Slim\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDatabaseCommand extends Command
{
    private App $app;
    private array $companies = [
        'TechVision Solutions',
        'DataCraft Innovations',
        'CloudPeak Systems',
        'DigitalEdge Technologies'
    ];

    private array $cities = [
        'France' => [
            ['Paris', '75000'],
            ['Lyon', '69000'],
            ['Marseille', '13000'],
            ['Bordeaux', '33000'],
            ['Lille', '59000'],
            ['Toulouse', '31000']
        ],
        'Allemagne' => [
            ['Berlin', '10115'],
            ['Munich', '80331'],
            ['Hambourg', '20095'],
            ['Francfort', '60311']
        ]
    ];

    private array $jobTitles = [
        'Développeur Full Stack',
        'Ingénieur DevOps',
        'Architecte Solution',
        'Chef de Projet',
        'Data Scientist',
        'Designer UX/UI',
        'Administrateur Système',
        'Responsable Sécurité',
        'Product Owner',
        'Scrum Master'
    ];

    private array $firstNames = [
        'Emma', 'Lucas', 'Léa', 'Hugo', 'Chloé',
        'Louis', 'Sarah', 'Gabriel', 'Inès', 'Jules',
        'Anna', 'Thomas', 'Louise', 'Arthur', 'Alice'
    ];

    private array $lastNames = [
        'Martin', 'Bernard', 'Dubois', 'Thomas', 'Robert',
        'Richard', 'Petit', 'Durand', 'Leroy', 'Moreau',
        'Simon', 'Laurent', 'Lefebvre', 'Michel', 'Garcia'
    ];

    public function __construct(App $app)
    {
        $this->app = $app;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('db:populate');
        $this->setDescription('Populate database');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Populate database...');

        /** @var \Illuminate\Database\Capsule\Manager $db */
        $db = $this->app->getContainer()->get('db');

        $db->getConnection()->statement("SET FOREIGN_KEY_CHECKS=0");
        $db->getConnection()->statement("TRUNCATE `employees`");
        $db->getConnection()->statement("TRUNCATE `offices`");
        $db->getConnection()->statement("TRUNCATE `companies`");
        $db->getConnection()->statement("SET FOREIGN_KEY_CHECKS=1");

        // Générer 2-4 sociétés
        $numCompanies = rand(2, 4);
        $companies = [];
        $offices = [];
        $employees = [];

        for ($i = 1; $i <= $numCompanies; $i++) {
            $companyName = $this->companies[$i - 1];
            $phone = '0' . rand(600000000, 699999999);
            $email = strtolower(str_replace(' ', '.', $companyName)) . '@example.com';
            $website = 'https://www.' . strtolower(str_replace(' ', '-', $companyName)) . '.com';
            $image = 'https://picsum.photos/800/600?random=' . $i;

            $companies[] = "($i,'$companyName','$phone','$email','$website','$image', now(), now(), null)";

            // Générer 2-3 bureaux par société
            $numOffices = rand(2, 3);
            $officeId = count($offices) + 1;

            for ($j = 1; $j <= $numOffices; $j++) {
                $country = array_rand($this->cities);
                $cityData = $this->cities[$country][array_rand($this->cities[$country])];
                $city = $cityData[0];
                $zipCode = $cityData[1];
                $address = rand(1, 100) . ' rue ' . $this->lastNames[array_rand($this->lastNames)];
                $officeName = "Bureau de $city";
                $officeEmail = strtolower("$city@" . str_replace(' ', '-', $companyName) . ".com");

                $offices[] = "($officeId,'$officeName','$address','$city','$zipCode','$country','$officeEmail',NULL,$i, now(), now())";

                // Générer 3-4 employés par bureau
                $numEmployees = rand(3, 4);
                for ($k = 1; $k <= $numEmployees; $k++) {
                    $employeeId = count($employees) + 1;
                    $firstName = $this->firstNames[array_rand($this->firstNames)];
                    $lastName = $this->lastNames[array_rand($this->lastNames)];
                    $jobTitle = $this->jobTitles[array_rand($this->jobTitles)];
                    $empEmail = strtolower("$firstName.$lastName@" . str_replace(' ', '-', $companyName) . ".com");

                    $employees[] = "($employeeId,'$firstName','$lastName',$officeId,'$empEmail',NULL,'$jobTitle', now(), now())";
                }

                $officeId++;
            }
        }

        // Insérer les données
        if (count($companies) > 0) {
            $db->getConnection()->statement("INSERT INTO `companies` VALUES " . implode(',', $companies));
        }
        if (count($offices) > 0) {
            $db->getConnection()->statement("INSERT INTO `offices` VALUES " . implode(',', $offices));
        }
        if (count($employees) > 0) {
            $db->getConnection()->statement("INSERT INTO `employees` VALUES " . implode(',', $employees));
        }

        // Définir le siège social pour chaque entreprise
        for ($i = 1; $i <= $numCompanies; $i++) {
            $firstOfficeId = $i * 2 - 1; // Premier bureau de chaque entreprise comme siège social
            $db->getConnection()->statement("update companies set head_office_id = $firstOfficeId where id = $i;");
        }

        $output->writeln('Database populated successfully!');
        return 0;
    }
}
