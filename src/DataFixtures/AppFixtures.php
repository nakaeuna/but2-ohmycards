<?php

namespace App\DataFixtures;

use App\Entity\EntiteAdresse;
use App\Entity\EntiteAvis;
use App\Entity\EntiteImageProduit;
use App\Entity\EntiteJeuxDeSociete;
use App\Entity\EntiteProduit;
use App\Entity\EntiteTCG;
use App\Entity\EntiteJeuxDeCartes;
use App\Entity\TypeTCG;
use App\Entity\Utilisateur;
use App\Form\nbCarte2;
use App\Form\theme;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class AppFixtures extends Fixture
{

    public function __construct(private PasswordHasherFactoryInterface $passwordHasherFactory, private UploaderHelper $uploaderHelper, private KernelInterface $appKernel)
    {
    }

    private function random_pic($dir)
    {
        $files = glob($dir . '/*.*');
        $file = array_rand($files);
        return [$files[$file], $file];
    }

    public function load(ObjectManager $manager): void
    {
        $produitsClassique = [
            [
                'nom' => 'Cyberpunk',
                'description' => 'Le jeu de cartes Bicycle Cyberpunk Cyber City est le résultat de la fusion entre la technologie de pointe et le paysage urbain du futur. Avec des figures cybernétiques personnalisées, ces cartes sont le mélange parfait entre le traditionnel jeu de cartes et des éléments futuristes inspirés de la science-fiction.',
                'prixBaseHT' => 10,
                'quantite' => 4,
                'poids' => 12,
                'enVente' => true,
                'theme' => theme::CLASSIQUE,
            ],
            [
                'nom' => 'Dark Mode',
                'description' => 'Découvrez le jeu de carte Bicycle, cartes iconiques pour les magiciens. La carte à jouer Bicycle est la marque de confiance pour une expérience de jeu exceptionnelle et cela depuis plus de 135 ans ! Des performances supérieures dans vos mains et sur la table grâce à notre finition exclusive "Air cushion" qui offre une jouabilité inégalée.',
                'prixBaseHT' => 9,
                'quantite' => 4,
                'poids' => 25,
                'enVente' => true,
                'theme' => theme::CLASSIQUE,
            ],
            [
                'nom' => 'Hanafuda',
                'description' => 'Cartes Hanafuda Japanese Flowers. Jeu de 48 cartes traditionnelles japonaises avec instruction en anglais via un QR code au dos du paquet.',
                'prixBaseHT' => 28,
                'quantite' => 8,
                'poids' => 15,
                'enVente' => true,
                'theme' => theme::CLASSIQUE,
            ],
            [
                'nom' => 'Ambraser',
                'description' => 'Cartes de Collection Ambraser Hofjagdspiel, reproduction du « Jeu de cartes de chasse de la Cour d\'Ambras », également appelé « jeu de cartes du fauconnier d\'Ambras1 ». Il s\'agit à l\'origine d\'un jeu de cartes peint vers 1440-1445 et dont la paternité est attribuée à Konrad Witz et à son atelier à Bâle.',
                'prixBaseHT' => 125,
                'quantite' => 7,
                'poids' => 15,
                'enVente' => true,
                'theme' => theme::CLASSIQUE,
            ],
            [
                'nom' => 'Sparrow',
                'description' => 'Un jeu de cartes à haut potentiel qui vous permettra de jouer à vos jeux de cartes habituels (poker, bridge, etc) mais également au jeu de cartes japonais Hanafuda grâce à son design saisonnier autour de la thématique des moineaux. Un jeu qui conviendra également aux adeptes de tours de magie !',
                'prixBaseHT' => 14,
                'quantite' => 5,
                'poids' => 5,
                'enVente' => true,
                'theme' => theme::CLASSIQUE,
            ],
            [
                'nom' => 'Divinatoire',
                'description' => 'Coffret 3 en 1 raffiné, innovant et ludique pour s’initier à la cartomancie qui se pratique avec un simple jeu de 32 cartes. L\'univers graphique pop vintage offre un voyage à travers le temps où la voyance était l’activité prisée de tous les salons et consultée par les « Grands Influents » de l’époque !',
                'prixBaseHT' => 26,
                'quantite' => 15,
                'poids' => 15,
                'enVente' => true,
                'theme' => theme::CLASSIQUE,
            ],
            [
                'nom' => 'Patience',
                'description' => 'Coffret contenant 2 jeux de cartes de petit format élégamment illustrées. Idéales pour faire des réussites ou patiences. Convient également pour d\'autres jeux nécessitant 2 x 54 cartes.',
                'prixBaseHT' => 15,
                'quantite' => 9,
                'poids' => 78,
                'enVente' => true,
                'theme' => theme::CLASSIQUE,
            ],
            [
                'nom' => 'Hanami',
                'description' => 'Cartes à jouer Hanafuda Hanami (Format Poker). 54 cartes de haute qualité sur l\'univers du Japon.',
                'prixBaseHT' => 19,
                'quantite' => 20,
                'poids' => 66,
                'enVente' => true,
                'theme' => theme::CLASSIQUE,
            ],
            [
                'nom' => 'Poker Texas',
                'description' => '54 cartes à jouer Poker Texas Modiano - Rouge. Cartes avec deux Index Jumbo au format Poker 100% plastique.',
                'prixBaseHT' => 12,
                'quantite' => 8,
                'poids' => 71,
                'enVente' => true,
                'theme' => theme::CLASSIQUE,
            ],
        ];



        foreach ($produitsClassique as $data) {
            $src = $this->random_pic($this->appKernel->getProjectDir().'/public/assets/images/produits/classique');
            $i = $src[1];
            $src = $src[0];
            copy($src, $this->appKernel->getProjectDir().'/public/images/products/classique/classique_'.$i.'.jpg');
            $image = new EntiteImageProduit();
            $image->setTaille(filesize($src));
            $image->setNomFichier('classique/classique_'.$i.'.jpg');

            $produit = EntiteJeuxDeCartes::builder()
                ->setNom($data['nom'])
                ->setDescription($data['description'])
                ->setPrixBaseHT($data['prixBaseHT'])
                ->setQuantite($data['quantite'])
                ->setNbVente(rand(0, 100))
                ->setPoids($data['poids'])
                ->setEnVente($data['enVente'])
                ->setTheme($data['theme'])
                ->addImage($image);

            $src = $this->random_pic($this->appKernel->getProjectDir().'/public/assets/images/produits/classique');
            $i = $src[1];
            $src = $src[0];
            copy($src, $this->appKernel->getProjectDir().'/public/images/products/classique/classique_'.$i.'.jpg');
            $image = new EntiteImageProduit();
            $image->setTaille(filesize($src));
            $image->setNomFichier('classique/classique_'.$i.'.jpg');

            $produit = $produit->addImage($image)->build();

            $manager->persist($image);
            $manager->persist($produit);
        }

        $produitsHelloKitty = [
            [
                'c' => 1,
                'nom' => "Birthday",
                'description' => "Jeu de cartes personnalisées Bicycle, aux couleurs de Hello Kitty à l'occasion du 50ème anniversaire de ce chaton très populaire !",
                'prixBaseHT' => 27,
                'quantite' => 10,
                'poids' => 52,
                'enVente' => true,
                'theme' => theme::HELLO_KITTY,
            ],
            [
                'c' => 1,
                'nom' => "Fluo",
                'description' => "Hello Kitty (ハローキティ) est un personnage fictif, et l'un des nombreux personnages créés par la société japonaise Sanrio. Les produits Hello Kitty utilisent généralement des couleurs rose vif et sont reconnaissables par le logo représentant un petit chat.",
                'prixBaseHT' => 24,
                'quantite' => 10,
                'poids' => 14,
                'enVente' => true,
                'theme' => theme::HELLO_KITTY,
            ],
            [
                'c' => 2,
                'nom' => "Uno",
                'description' => "UNO Hello Kitty : Un jeu de cartes adorable pour des parties endiablées !",
                'prixBaseHT' => 10,
                'quantite' => 12,
                'poids' => 14,
                'enVente' => true,
                'theme' => theme::HELLO_KITTY,
            ],
            [
                'c' => 2,
                'nom' => "Jeux",
                'description' => "Une collection de jeux de société traditionnels et modernes pour vivre des heures de détente avec Hello Kitty.",
                'prixBaseHT' => 15,
                'quantite' => 25,
                'poids' => 48,
                'enVente' => true,
                'theme' => theme::HELLO_KITTY,
            ],
            [
                'c' => 2,
                'nom' => "Memori",
                'description' => "Cette passionnante chasse aux images demande mémoire et concentration. Qui retrouvera le premier les paires de cartes identiques ? Un jeu où les plus jeunes, et leur remarquable mémoire visuelle, sont souvent les meilleurs !",
                'prixBaseHT' => 6,
                'quantite' => 40,
                'poids' => 13,
                'enVente' => true,
                'theme' => theme::HELLO_KITTY,
            ],
            [
                'c' => 2,
                'nom' => "Famille",
                'description' => "France Cartes : Jeu de 7 Familles - Hello Kitty",
                'prixBaseHT' => 9,
                'quantite' => 12,
                'poids' => 58,
                'enVente' => true,
                'theme' => theme::HELLO_KITTY,
            ],
            [
                'c' => 2,
                'nom' => "Chevaux",
                'description' => "Hello Kitty - Les Petits Chevaux - Mini Jeux",
                'prixBaseHT' => 8,
                'quantite' => 15,
                'poids' => 55,
                'enVente' => true,
                'theme' => theme::HELLO_KITTY,
            ],
            [
                'c' => 2,
                'nom' => "Domino",
                'description' => "Jeu Éducatif et Scientifique - Domino Hello Kitty",
                'prixBaseHT' => 7,
                'quantite' => 13,
                'poids' => 12,
                'enVente' => true,
                'theme' => theme::HELLO_KITTY,
            ],
            [
                'c' => 2,
                'nom' => "Tamagochi",
                'description' => "TAMAGOTCHI HELLO KITTY - Le jouet incontournable des années 90 est de retour dans une édition Tamagotchi nano Hello Kitty rouge, une collaboration officielle. Hello Kitty vous aidera à élever vos animaux électroniques.",
                'prixBaseHT' => 32,
                'quantite' => 18,
                'poids' => 24,
                'enVente' => true,
                'theme' => theme::HELLO_KITTY,
            ]
        ];

        foreach ($produitsHelloKitty as $data) {
            $src = $this->random_pic($this->appKernel->getProjectDir().'/public/assets/images/produits/helloKitty');
            $i = $src[1];
            $src = $src[0];
            copy($src, $this->appKernel->getProjectDir().'/public/images/products/helloKitty/helloKitty_'.$i.'.jpg');
            $image = new EntiteImageProduit();
            $image->setTaille(filesize($src));
            $image->setNomFichier('helloKitty/helloKitty_'.$i.'.jpg');

            if ($data['c'] == 1) {
                $produit = EntiteJeuxDeCartes::builder()
                    ->setNom($data['nom'])
                    ->setDescription($data['description'])
                    ->setPrixBaseHT($data['prixBaseHT'])
                    ->setQuantite($data['quantite'])
                    ->setNbVente(rand(0, 100))
                    ->setPoids($data['poids'])
                    ->setEnVente($data['enVente'])
                    ->setTheme($data['theme'])
//                    ->setNbCartes($data['nbCartes'])
                    ->addImage($image)
                    ->build($manager);

                $manager->persist($produit);
            } else {
                $produit = EntiteJeuxDeSociete::builder()
                    ->setNom($data['nom'])
                    ->setDescription($data['description'])
                    ->setPrixBaseHT($data['prixBaseHT'])
                    ->setQuantite($data['quantite'])
                    ->setNbVente(rand(0, 100))
                    ->setPoids($data['poids'])
                    ->setEnVente($data['enVente'])
                    ->setTheme($data['theme'])
//                    ->setAgeRecommander('7 ans et +')
                    ->addImage($image)
                    ->build($manager);

                $manager->persist($produit);
            }
        }

        $produitsPokemon = [
            [
                'nom' => 'Pokéball',
                'description' => 'Découvre le Pokémon – Bundle 5 Pokéball Tin – Q4 – en français contenant : - 3 boosters du JCC Pokemon - Astre radieux, Faille Paradoxe et Origine Perdue - 1 pièce Pokémon',
                'prixBaseHT' => 113,
                'quantite' => 25,
                'poids' => 15,
                'enVente' => true,
                'theme' => theme::POKEMON,
                'image' => '{{ asset(\'assets/images/pokemon/pokeball.webp\') }}',
            ],
            [
                'nom' => 'Mini Tin',
                'description' => 'Découvre la Pokémon – Display de 10 Mini tin Magnifique Paldéa – Juin 2024, une boîte contenant 10 Mini Tins, parfaite pour les collectionneurs et les joueurs passionnés du Jeu de Cartes à Collectionner Pokémon (JCC) ! Cette display te permet d’ajouter des cartes uniques, des boosters variés, et des accessoires exclusifs à ta collection.',
                'prixBaseHT' => 102,
                'quantite' => 15,
                'poids' => 15,
                'enVente' => true,
                'theme' => theme::POKEMON,
                'image' => '{{ asset(\'assets/images/pokemon/miniTin.webp\') }}',
            ],
            [
                'nom' => 'Boîtes',
                'description' => 'Découvre la Pokémon – Mini tin Magnifique Paldéa – Juin 2024, un mini coffret qui combine style et contenu exclusif pour tous les fans du Jeu de Cartes à Collectionner Pokémon (JCC) !',
                'prixBaseHT' => 11.2,
                'quantite' => 2,
                'poids' =>15,
                'enVente' => true,
                'theme' => theme::POKEMON,
                'image' => '{{ asset(\'assets/images/pokemon/boites.webp\') }}',
            ],
            [
                'nom' => 'Cleopsytra',
                'description' => 'Découvre le Pokémon – DuoPack – Cléopsytra – Août 2024 – Deux Boosters Pack en français et enrichis ta collection avec des cartes rares et une carte brillante exclusive à l’effigie de Cléopsytra !',
                'prixBaseHT' => 19.14,
                'quantite' => 14,
                'poids' => 45,
                'enVente' => true,
                'theme' => theme::POKEMON,
                'image' => '{{ asset(\'assets/images/pokemon/cleopsytra.webp\') }}',
            ],
            [
                'nom' => 'Teracristal',
                'description' => 'Découvre la Pokémon – Valisette 6 Boosters Pikachu Téracristal – Coffre aux Trésors en français, l’ultime coffret pour les passionnés du Jeu de Cartes à Collectionner Pokémon ! Et cette valisette contient six boosters de différentes extensions du JCC Pokémon en français !',
                'prixBaseHT' => 33.25,
                'quantite' => 25,
                'poids' => 74,
                'enVente' => true,
                'theme' => theme::POKEMON,
                'image' => '{{ asset(\'assets/images/pokemon/teracristal.webp\') }}',
            ],
            [
                'nom' => 'Duopack',
                'description' => 'Le Pokémon – Duopack Latios, Zekrom et Palkia – EB11 et EV02 – Octobre 2024 – en français contient deux boosters de 10 cartes des séries EB11 et EV02, pour compléter ta collection ou renforcer ton deck',
                'prixBaseHT' => 13.12,
                'quantite' => 12,
                'poids' => 45,
                'enVente' => true,
                'theme' => theme::POKEMON,
                'image' => '{{ asset(\'assets/images/pokemon/duopack.webp\') }}',
            ],
            [
                'nom' => 'Stellaire',
                'description' => 'Le Pokémon – Booster Blister Couronne Stellaire – EV07 – en français est le must-have pour tous les collectionneurs et joueurs de JCC Pokémon.',
                'prixBaseHT' => 6.5,
                'quantite' => 5,
                'poids' => 44,
                'enVente' => true,
                'theme' => theme::POKEMON,
                'image' => '{{ asset(\'assets/images/pokemon/stellaire.webp\') }}',
            ],
            [
                'nom' => 'Poké Ball Bleue',
                'description' => 'Pokémon – Pokéball Tin – Q4 – Super Ball – en français contient : - 3 boosters du JCC Pokemon - Astre radieux, Faille Paradoxe et Origine Perdue - 1 pièce Pokémon',
                'prixBaseHT' => 23.25,
                'quantite' => 25,
                'poids' => 47,
                'enVente' => true,
                'theme' => theme::POKEMON,
                'image' => '{{ asset(\'assets/images/pokemon/pokeballBleue.webp\') }}',
            ],
            [
                'nom' => 'Paradoxe',
                'description' => 'La Pokémon – Pokébox Destinée Paradoxe – Chef-de-Fer – en français t’offre une expérience unique avec l’un des Pokémon les plus redoutables du futur. Grâce à cette Pokébox, Chef-de-Fer-ex brille de mille feux dans ta collection avec une carte promo exclusive. En plus de cette carte phare, la boîte contient 4 boosters du Jeu de Cartes à Collectionner Pokémon (JCC), tous en français, qui te permettront d’enrichir ton deck et de relever de nouveaux défis.',
                'prixBaseHT' => 26.43,
                'quantite' => 25,
                'poids' => 47,
                'enVente' => true,
                'theme' => theme::POKEMON,
                'image' => '{{ asset(\'assets/images/pokemon/paradoxe.webp\') }}',
            ],
        ];

        foreach ($produitsPokemon as $data) {

            $src = $this->random_pic($this->appKernel->getProjectDir().'/public/assets/images/produits/pokemon');
            $i = $src[1];
            $src = $src[0];
            copy($src, $this->appKernel->getProjectDir().'/public/images/products/pokemon/pokemon_'.$i.'.jpg');
            $image = new EntiteImageProduit();
            $image->setTaille(filesize($src));
            $image->setNomFichier('pokemon/pokemon_'.$i.'.jpg');

            $produit = EntiteTCG::builder()
                ->setNom($data['nom'])
                ->setDescription($data['description'])
                ->setPrixBaseHT($data['prixBaseHT'])
                ->setQuantite($data['quantite'])
                ->setNbVente(rand(0, 100))
                ->setPoids($data['poids'])
                ->setEnVente($data['enVente'])
                ->setTheme($data['theme'])
                ->addImage($image)
                ->build($manager);

            $manager->persist($produit);
        }

        $produitsYuGiOh = [
            [
                'nom' => 'Bataille',
                'description' => 'Plongez dans l’univers épique de Yu-Gi-Oh avec le Coffret Batailles de Légende : Chapitre 1, une expérience incomparable pour les collectionneurs et les duellistes passionnés. Célébrant les légendaires affrontements de Batailles de Légende : Vengeance de la Lumière, Vengeance Impitoyable, La Vengeance du Héros et Armageddon, ce coffret offre une plongée inégalée dans l’univers du jeu de cartes à collectionner.',
                'prixBaseHT' => 16.25,
                'quantite' => 25,
                'poids' => 474,
                'enVente' => true,
                'theme' => theme::YU_GI_OH,
                'image' => '{{ asset(\'assets/images/yuGiOh/bataille.webp\') }}',
            ],
            [
                'nom' => 'Tapis',
                'description' => 'Tapis YCS Sydney 2017 “Dark Magician” – Sealed',
                'prixBaseHT' => 130.72,
                'quantite' => 465,
                'poids' => 14,
                'enVente' => true,
                'theme' => theme::YU_GI_OH,
                'image' => '{{ asset(\'assets/images/yuGiOh/tapis.webp\') }}',
            ],
            [
                'nom' => 'Démarrage',
                'description' => 'Votre passerelle vers l’univers passionnant de Yu-Gi-Oh! JCJ. Que vous soyez un débutant enthousiaste ou que vous souhaitiez initier un ami ou un membre de votre famille à ce jeu de cartes légendaire.',
                'prixBaseHT' => 16.25,
                'quantite' => 25,
                'poids' => 12,
                'enVente' => true,
                'theme' => theme::YU_GI_OH,
                'image' => '{{ asset(\'assets/images/yuGiOh/demarrage.webp\') }}',
            ],
            [
                'nom' => 'Rage',
                'description' => 'Le Booster Yu-Gi-Oh – La Rage des Abysses – ROTA – En Français est enfin arrivé, apportant avec lui une vague dévastatrice de nouvelles cartes puissantes. Dès la première ouverture, tu seras émerveillé par les 100 nouvelles cartes exclusives, incluant la deuxième vague du thème mondialement populaire qui a débuté dans L’Infini Interdit.',
                'prixBaseHT' => 4.8,
                'quantite' => 8,
                'poids' => 44,
                'enVente' => true,
                'theme' => theme::YU_GI_OH,
                'image' => '{{ asset(\'assets/images/yuGiOh/rage.webp\') }}',
            ],
            [
                'nom' => 'Lumière',
                'description' => 'Le Seigneur Lumière, avec Michael Le Grand en tête, est prêt à bannir tes adversaires d’un simple coup de synchro, tout en renforçant ton deck et tes points de vie. En utilisant des stratégies basées sur des monstres synchros et des invocations rapides, tu peux rapidement retourner la situation en ta faveur. Ce deck est idéal pour ceux qui aiment jouer des cartes aux effets puissants et dévastateurs.',
                'prixBaseHT' => 26.13,
                'quantite' => 13,
                'poids' => 415,
                'enVente' => true,
                'theme' => theme::YU_GI_OH,
                'image' => '{{ asset(\'assets/images/yuGiOh/lumiere.webp\') }}',
            ],
            [
                'nom' => 'Booster',
                'description' => 'Plonge dans le monde de Yu-Gi-Oh – Display – Crossovers Breakers – Boite de 24 boosters – en français et découvre les nouveaux decks innovants et des cartes rares prêtes à enrichir ta collection. Ce display te propose 24 boosters pleins de surprises et de possibilités pour dominer tes prochains duels.',
                'prixBaseHT' => 90.68,
                'quantite' => 68,
                'poids' => 45,
                'enVente' => true,
                'theme' => theme::YU_GI_OH,
                'image' => '{{ asset(\'assets/images/yuGiOh/booster.webp\') }}',
            ],
            [
                'nom' => 'Tactique',
                'description' => 'Préparez-vous à du duel stratégique avec “Les Maîtres Tactiques”! Ce booster de 60 cartes introduit trois nouvelles stratégies révolutionnaires, permettant aux joueurs de contrôler les règles du jeu d’une manière totalement inédite. Voici un aperçu des nouvelles possibilités qui vous attendent !',
                'prixBaseHT' => 55.16,
                'quantite' => 16,
                'poids' => 44,
                'enVente' => true,
                'theme' => theme::YU_GI_OH,
                'image' => '{{ asset(\'assets/images/yuGiOh/tactique.webp\') }}',
            ],
            [
                'nom' => 'Mirroir',
                'description' => 'Hey là ! Tu veux vivre une aventure dans le monde de Yu-Gi-Oh ? Découvre la Tin Box 25e Anniversaire : Les Miroirs du Duel – FR ! Dans cette édition spéciale, Yami Yugi et Seto Kaiba se dressent face à face, entourés des monstres emblématiques de l’univers Yu-Gi-Oh. Laisse-toi emporter par la magie du duel alors que ces légendes s’affrontent dans une quête pour la gloire et la victoire.',
                'prixBaseHT' => 16.89,
                'quantite' => 74,
                'poids' => 45,
                'enVente' => true,
                'theme' => theme::YU_GI_OH,
                'image' => '{{ asset(\'assets/images/yuGiOh/mirroir.webp\') }}',
            ],
            [
                'nom' => 'Eyes',
                'description' => 'Plonge dans l’univers des duellistes « aux yeux bleus » avec le Yu-Gi-Oh! – Deck de Structure – Blue-Eyes White Destiny en français ! Ce deck de 50 cartes est l’outil parfait pour renforcer ta stratégie avec des cartes emblématiques telles que le Dragon Alternatif aux Yeux Bleus et le Dragon Jet aux Yeux Bleus.',
                'prixBaseHT' => 12.45,
                'quantite' => 44,
                'poids' => 454,
                'enVente' => true,
                'theme' => theme::YU_GI_OH,
                'image' => '{{ asset(\'assets/images/yuGiOh/eyes.webp\') }}',
            ],
        ];

        foreach ($produitsYuGiOh as $data) {

            $src = $this->random_pic($this->appKernel->getProjectDir().'/public/assets/images/produits/yuGiOh');
            $i = $src[1];
            $src = $src[0];
            copy($src, $this->appKernel->getProjectDir().'/public/images/products/yuGiOh/yuGiOh_'.$i.'.jpg');
            $image = new EntiteImageProduit();
            $image->setTaille(filesize($src));
            $image->setNomFichier('yuGiOh/yuGiOh_'.$i.'.jpg');

            $produit = EntiteTCG::builder()
                ->setNom($data['nom'])
                ->setDescription($data['description'])
                ->setPrixBaseHT($data['prixBaseHT'])
                ->setQuantite($data['quantite'])
                ->setNbVente(rand(0, 100))
                ->setPoids($data['poids'])
                ->setEnVente($data['enVente'])
                ->setTheme($data['theme'])
                ->addImage($image)
                ->build($manager);

            $manager->persist($produit);
        }

        $addresse = (new EntiteAdresse())
            ->setVille("Nantes")
            ->setPays("France")
            ->setRegion("Pays de la Loire")
            ->setCodePost(44852)
            ->setNomRue("Rue du Loup")
            ->setNumeroRue(45);

        $admin = new Utilisateur();
        $admin->setRoles(['ROLE_ADMIN'])
            ->setPrenom("ADMIN")
            ->setNom("ADMIN")
            ->setNumTel("+33000000000")
            ->setPassword($this->passwordHasherFactory->getPasswordHasher(Utilisateur::class)->hash("123456"))
            ->setEmail("admin@admin.com")
            ->setAdresse($addresse);

        $avis1 = (new EntiteAvis())->setProduit($produit)->setUtilisateur($admin)->setAvis("Super Produit")->setNote(4);

        $manager->persist($addresse);
        $manager->persist($avis1);
        $manager->persist($admin);
        $manager->flush();
    }
}