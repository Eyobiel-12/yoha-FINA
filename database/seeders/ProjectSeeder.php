<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectEntry;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all clients
        $clients = Client::all();
        
        $projectsCreated = 0;
        $entriesCreated = 0;

        // Create projects for each client
        foreach ($clients as $client) {
            // Create 1-3 projects per client
            $numProjects = rand(1, 3);
            
            for ($i = 1; $i <= $numProjects; $i++) {
                $projectType = $this->getRandomProjectType();
                $startDate = Carbon::now()->subDays(rand(0, 180));
                $projectNumber = date('Y') . '-' . str_pad($client->id, 2, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                
                // Check if project with this number already exists
                if (Project::where('project_number', $projectNumber)->exists()) {
                    continue;
                }
                
                // Create project
                $project = Project::create([
                    'client_id' => $client->id,
                    'project_number' => $projectNumber,
                    'name' => $projectType['name'] . ' - ' . $client->company_name,
                    'location' => $this->getRandomLocation(),
                    'description' => $projectType['description'],
                    'start_date' => $startDate,
                    'end_date' => rand(0, 1) ? $startDate->copy()->addDays(rand(14, 90)) : null,
                ]);
                
                $projectsCreated++;
                
                // Create project entries
                $numEntries = rand(3, 8);
                for ($j = 1; $j <= $numEntries; $j++) {
                    $entryDate = $project->start_date->copy()->addDays($j * 2);
                    
                    // Don't create entries past the end date
                    if ($project->end_date && $entryDate->gt($project->end_date)) {
                        break;
                    }
                    
                    ProjectEntry::create([
                        'project_id' => $project->id,
                        'description' => $this->getRandomWork($projectType),
                        'hours' => rand(3, 8) + (rand(0, 1) ? 0.5 : 0),
                        'rate' => rand(45, 75),
                        'entry_date' => $entryDate,
                    ]);
                    
                    $entriesCreated++;
                }
            }
        }
        
        $this->command->info("Created {$projectsCreated} new projects with {$entriesCreated} project entries.");
    }
    
    /**
     * Get a random project type
     */
    private function getRandomProjectType(): array
    {
        $projectTypes = [
            [
                'name' => 'Tuin renovatie',
                'description' => 'Volledige renovatie van de tuin met nieuwe beplanting, bestrating en tuinmeubilair.',
            ],
            [
                'name' => 'Onderhoudscontract',
                'description' => 'Regelmatig onderhoud van de tuin inclusief snoeien, onkruid verwijderen en seizoensgebonden werkzaamheden.',
            ],
            [
                'name' => 'Aanleg nieuwe tuin',
                'description' => 'Ontwerp en aanleg van een nieuwe tuin volgens klantspecificaties, inclusief irrigatiesysteem.',
            ],
            [
                'name' => 'Bestrating',
                'description' => 'Het aanleggen van nieuwe bestrating voor terras, oprit en paden.',
            ],
            [
                'name' => 'Boomverzorging',
                'description' => 'Professionele boomverzorging inclusief snoeien, behandelen en advies over gezondheid van bomen.',
            ],
        ];
        
        return $projectTypes[array_rand($projectTypes)];
    }
    
    /**
     * Get a random location
     */
    private function getRandomLocation(): string
    {
        $locations = [
            'Amsterdam-Zuid',
            'Amstelveen',
            'Haarlem',
            'Utrecht-Oost',
            'Rotterdam-Noord',
            'Wassenaar',
            'Bloemendaal',
            'Blaricum',
            'Laren',
            'Hilversum',
        ];
        
        return $locations[array_rand($locations)];
    }
    
    /**
     * Get random work description based on project type
     */
    private function getRandomWork(array $projectType): string
    {
        $renovationWorks = [
            'Verwijderen oude beplanting',
            'Grondwerk en voorbereiding',
            'Plaatsen nieuwe beplanting',
            'Aanleg bestrating',
            'Plaatsen tuinmeubilair',
            'Aanleg verlichting',
            'Irrigatiesysteem installeren',
        ];
        
        $maintenanceWorks = [
            'Maandelijks onderhoud',
            'Snoeiwerkzaamheden',
            'Onkruidbestrijding',
            'Bemesten planten',
            'Seizoensgebonden onderhoud',
            'Gazon onderhoud',
            'Bladeren opruimen',
        ];
        
        $newGardenWorks = [
            'Ontwerp bespreken',
            'Grondwerk en voorbereiding',
            'Aanleg drainage',
            'Plaatsen beplanting',
            'Aanleg gazon',
            'Installatie irrigatiesysteem',
            'Plaatsen schutting of hekwerk',
        ];
        
        $pavingWorks = [
            'Verwijderen oude bestrating',
            'Grondwerk en nivelleren',
            'Aanbrengen zandbed',
            'Leggen bestrating',
            'Afwerken randen',
            'Voegen vullen',
        ];
        
        $treeWorks = [
            'Boomonderzoek',
            'Snoeiwerkzaamheden',
            'Behandeling ziektes',
            'Verwijderen dood hout',
            'Kroonreductie',
            'Veiligheidssnoei',
        ];
        
        if (strpos($projectType['name'], 'renovatie') !== false) {
            return $renovationWorks[array_rand($renovationWorks)];
        } elseif (strpos($projectType['name'], 'Onderhoudscontract') !== false) {
            return $maintenanceWorks[array_rand($maintenanceWorks)];
        } elseif (strpos($projectType['name'], 'nieuwe tuin') !== false) {
            return $newGardenWorks[array_rand($newGardenWorks)];
        } elseif (strpos($projectType['name'], 'Bestrating') !== false) {
            return $pavingWorks[array_rand($pavingWorks)];
        } elseif (strpos($projectType['name'], 'Boomverzorging') !== false) {
            return $treeWorks[array_rand($treeWorks)];
        }
        
        // Fallback
        $allWorks = array_merge($renovationWorks, $maintenanceWorks, $newGardenWorks, $pavingWorks, $treeWorks);
        return $allWorks[array_rand($allWorks)];
    }
}
