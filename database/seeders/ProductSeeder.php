<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'title' => 'Specchio Barocco Dorato',
                'description' => 'Magnifico specchio in stile barocco con cornice dorata. Perfetto per eventi eleganti e servizi fotografici. Dimensioni: 180x90cm, in ottime condizioni.',
                'tags' => 'specchio,barocco,arredamento,oro',
                'price' => 450.00,
                'sold_out' => false,
            ],
            [
                'title' => 'Console Mixer Vintage Yamaha',
                'description' => 'Console mixer professionale Yamaha anni \'80. Funzionante, 16 canali, suono analogico caldo. Ideale per eventi vintage o studi di registrazione.',
                'tags' => 'audio,mixer,vintage,professionale',
                'price' => 850.00,
                'sold_out' => false,
            ],
            [
                'title' => 'Set Sedie Industrial',
                'description' => 'Set di 12 sedie in stile industrial con struttura in metallo e seduta in legno. Perfette per eventi, matrimoni e feste aziendali.',
                'tags' => 'arredamento,sedie,industrial,eventi',
                'price' => 320.00,
                'sold_out' => false,
            ],
            [
                'title' => 'Lampadario Cristallo Anni \'60',
                'description' => 'Elegante lampadario in cristallo originale degli anni \'60. Perfetto stato di conservazione, crea un\'atmosfera magica in ogni ambiente.',
                'tags' => 'illuminazione,cristallo,vintage,lusso',
                'price' => 680.00,
                'sold_out' => true,
            ],
            [
                'title' => 'Casse Acustiche JBL Professionali',
                'description' => 'Coppia di casse acustiche JBL professionali. Potenza 500W ciascuna, perfette per eventi all\'aperto e concerti. Include supporti e cavi.',
                'tags' => 'audio,casse,JBL,professionale',
                'price' => 1200.00,
                'sold_out' => false,
            ],
            [
                'title' => 'Divano Chesterfield in Pelle',
                'description' => 'Divano Chesterfield a 3 posti in vera pelle color cognac. Capitonné classico, perfetto per lounge area ed eventi eleganti.',
                'tags' => 'arredamento,divano,pelle,chesterfield',
                'price' => 950.00,
                'sold_out' => false,
            ],
            [
                'title' => 'Colonne Romane Decorative',
                'description' => 'Coppia di colonne decorative in stile romano. Altezza 220cm, in resina resistente. Perfette per scenografie teatrali e matrimoni a tema.',
                'tags' => 'scenografia,colonne,decorazioni,classico',
                'price' => 380.00,
                'sold_out' => false,
            ],
            [
                'title' => 'Microfoni Shure SM58 Set',
                'description' => 'Set di 6 microfoni dinamici Shure SM58, lo standard dell\'industria. Include custodie e cavi XLR. Perfetti per qualsiasi evento live.',
                'tags' => 'audio,microfoni,Shure,professionale',
                'price' => 720.00,
                'sold_out' => false,
            ],
            [
                'title' => 'Tavolini Rotondi Vintage',
                'description' => 'Set di 10 tavolini rotondi vintage in legno massello. Diametro 80cm, ideali per cocktail party e ricevimenti.',
                'tags' => 'arredamento,tavoli,vintage,eventi',
                'price' => 420.00,
                'sold_out' => false,
            ],
            [
                'title' => 'Specchiera Art Nouveau',
                'description' => 'Splendida specchiera liberty con decorazioni floreali. Epoca 1920, restaurata con cura. Un pezzo unico per eventi esclusivi.',
                'tags' => 'specchio,liberty,antiquariato,esclusivo',
                'price' => 1100.00,
                'sold_out' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['title' => $product['title']],
                $product
            );
        }
    }
}

