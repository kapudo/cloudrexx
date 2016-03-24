<?php

/**
 * Cloudrexx
 *
 * @link      http://www.cloudrexx.com
 * @copyright Cloudrexx AG 2007-2015
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Cloudrexx" is a registered trademark of Cloudrexx AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Cx\Core_Modules\TemplateEditor\Model\Entity;

/**
 * class FontOption
 *
 * @copyright   Cloudrexx AG
 * @author      Robin Glauser <robin.glauser@cloudrexx.com>
 * @package     contrexx
 */
class FontOption extends SelectOption
{

    /**
     * List with Google Fonts
     * @var array
     */
    protected $fonts
        = array(
            'ABeeZee' => array(), 'Abel' => array(), 'Abril Fatface' => array(),
            'Aclonica' => array(), 'Acme' => array(), 'Actor' => array(),
            'Adamina' => array(), 'Advent Pro' => array(),
            'Aguafina Script' => array(), 'Akronim' => array(),
            'Aladin' => array(), 'Aldrich' => array(), 'Alef' => array(),
            'Alegreya' => array(), 'Alegreya SC' => array(),
            'Alegreya Sans' => array(), 'Alegreya Sans SC' => array(),
            'Alex Brush' => array(), 'Alfa Slab One' => array(),
            'Alice' => array(), 'Alike' => array(), 'Alike Angular' => array(),
            'Allan' => array(), 'Allerta' => array(),
            'Allerta Stencil' => array(), 'Allura' => array(),
            'Almendra' => array(), 'Almendra Display' => array(),
            'Almendra SC' => array(), 'Amarante' => array(),
            'Amaranth' => array(), 'Amatic SC' => array(),
            'Amethysta' => array(), 'Amiri' => array(), 'Amita' => array(),
            'Anaheim' => array(), 'Andada' => array(), 'Andika' => array(),
            'Angkor' => array(), 'Annie Use Your Telescope' => array(),
            'Anonymous Pro' => array(), 'Antic' => array(),
            'Antic Didone' => array(), 'Antic Slab' => array(),
            'Anton' => array(), 'Arapey' => array(), 'Arbutus' => array(),
            'Arbutus Slab' => array(), 'Architects Daughter' => array(),
            'Archivo Black' => array(), 'Archivo Narrow' => array(),
            'Arial' => array(), 'Arimo' => array(), 'Arizonia' => array(),
            'Armata' => array(),
            'Artifika' => array(), 'Arvo' => array(), 'Arya' => array(),
            'Asap' => array(), 'Asar' => array(), 'Asset' => array(),
            'Astloch' => array(), 'Asul' => array(), 'Atomic Age' => array(),
            'Aubrey' => array(), 'Audiowide' => array(),
            'Autour One' => array(), 'Average' => array(),
            'Average Sans' => array(), 'Averia Gruesa Libre' => array(),
            'Averia Libre' => array(), 'Averia Sans Libre' => array(),
            'Averia Serif Libre' => array(), 'Bad Script' => array(),
            'Balthazar' => array(), 'Bangers' => array(), 'Basic' => array(),
            'Battambang' => array(), 'Baumans' => array(), 'Bayon' => array(),
            'Belgrano' => array(), 'Belleza' => array(), 'BenchNine' => array(),
            'Bentham' => array(), 'Berkshire Swash' => array(),
            'Bevan' => array(), 'Bigelow Rules' => array(),
            'Bigshot One' => array(), 'Bilbo' => array(),
            'Bilbo Swash Caps' => array(), 'Biryani' => array(),
            'Bitter' => array(), 'Black Ops One' => array(), 'Bokor' => array(),
            'Bonbon' => array(), 'Boogaloo' => array(), 'Bowlby One' => array(),
            'Bowlby One SC' => array(), 'Brawler' => array(),
            'Bree Serif' => array(), 'Bubblegum Sans' => array(),
            'Bubbler One' => array(), 'Buda' => array(), 'Buenard' => array(),
            'Butcherman' => array(), 'Butterfly Kids' => array(),
            'Cabin' => array(), 'Cabin Condensed' => array(),
            'Cabin Sketch' => array(), 'Caesar Dressing' => array(),
            'Cagliostro' => array(), 'Calligraffitti' => array(),
            'Cambay' => array(), 'Cambo' => array(), 'Candal' => array(),
            'Cantarell' => array(), 'Cantata One' => array(),
            'Cantora One' => array(), 'Capriola' => array(), 'Cardo' => array(),
            'Carme' => array(), 'Carrois Gothic' => array(),
            'Carrois Gothic SC' => array(), 'Carter One' => array(),
            'Catamaran' => array(), 'Caudex' => array(), 'Caveat' => array(),
            'Caveat Brush' => array(), 'Cedarville Cursive' => array(),
            'Ceviche One' => array(), 'Changa One' => array(),
            'Chango' => array(), 'Chau Philomene One' => array(),
            'Chela One' => array(), 'Chelsea Market' => array(),
            'Chenla' => array(), 'Cherry Cream Soda' => array(),
            'Cherry Swash' => array(), 'Chewy' => array(), 'Chicle' => array(),
            'Chivo' => array(), 'Chonburi' => array(), 'Cinzel' => array(),
            'Cinzel Decorative' => array(), 'Clicker Script' => array(),
            'Coda' => array(), 'Coda Caption' => array(), 'Codystar' => array(),
            'Combo' => array(), 'Comfortaa' => array(),
            'Coming Soon' => array(), 'Concert One' => array(),
            'Condiment' => array(), 'Content' => array(),
            'Contrail One' => array(), 'Convergence' => array(),
            'Cookie' => array(), 'Copse' => array(), 'Corben' => array(),
            'Courgette' => array(), 'Cousine' => array(), 'Coustard' => array(),
            'Covered By Your Grace' => array(), 'Crafty Girls' => array(),
            'Creepster' => array(), 'Crete Round' => array(),
            'Crimson Text' => array(), 'Croissant One' => array(),
            'Crushed' => array(), 'Cuprum' => array(), 'Cutive' => array(),
            'Cutive Mono' => array(), 'Damion' => array(),
            'Dancing Script' => array(), 'Dangrek' => array(),
            'Dawning of a New Day' => array(), 'Days One' => array(),
            'Dekko' => array(), 'Delius' => array(),
            'Delius Swash Caps' => array(), 'Delius Unicase' => array(),
            'Della Respira' => array(), 'Denk One' => array(),
            'Devonshire' => array(), 'Dhurjati' => array(),
            'Didact Gothic' => array(), 'Diplomata' => array(),
            'Diplomata SC' => array(), 'Domine' => array(),
            'Donegal One' => array(), 'Doppio One' => array(),
            'Dorsa' => array(), 'Dosis' => array(), 'Dr Sugiyama' => array(),
            'Droid Sans' => array(), 'Droid Sans Mono' => array(),
            'Droid Serif' => array(), 'Duru Sans' => array(),
            'Dynalight' => array(), 'EB Garamond' => array(),
            'Eagle Lake' => array(), 'Eater' => array(), 'Economica' => array(),
            'Eczar' => array(), 'Ek Mukta' => array(), 'Electrolize' => array(),
            'Elsie' => array(), 'Elsie Swash Caps' => array(),
            'Emblema One' => array(), 'Emilys Candy' => array(),
            'Engagement' => array(), 'Englebert' => array(),
            'Enriqueta' => array(), 'Erica One' => array(),
            'Esteban' => array(), 'Euphoria Script' => array(),
            'Ewert' => array(), 'Exo' => array(), 'Exo 2' => array(),
            'Expletus Sans' => array(), 'Fanwood Text' => array(),
            'Fascinate' => array(), 'Fascinate Inline' => array(),
            'Faster One' => array(), 'Fasthand' => array(),
            'Fauna One' => array(), 'Federant' => array(), 'Federo' => array(),
            'Felipa' => array(), 'Fenix' => array(), 'Finger Paint' => array(),
            'Fira Mono' => array(), 'Fira Sans' => array(),
            'Fjalla One' => array(), 'Fjord One' => array(),
            'Flamenco' => array(), 'Flavors' => array(),
            'Fondamento' => array(), 'Fontdiner Swanky' => array(),
            'Forum' => array(), 'Francois One' => array(),
            'Freckle Face' => array(), 'Fredericka the Great' => array(),
            'Fredoka One' => array(), 'Freehand' => array(),
            'Fresca' => array(), 'Frijole' => array(), 'Fruktur' => array(),
            'Fugaz One' => array(), 'GFS Didot' => array(),
            'GFS Neohellenic' => array(), 'Gabriela' => array(),
            'Gafata' => array(), 'Galdeano' => array(), 'Galindo' => array(),
            'Gentium Basic' => array(), 'Gentium Book Basic' => array(),
            'Geo' => array(), 'Geostar' => array(), 'Geostar Fill' => array(),
            'Germania One' => array(), 'Gidugu' => array(),
            'Gilda Display' => array(), 'Give You Glory' => array(),
            'Glass Antiqua' => array(), 'Glegoo' => array(),
            'Gloria Hallelujah' => array(), 'Goblin One' => array(),
            'Gochi Hand' => array(), 'Gorditas' => array(),
            'Goudy Bookletter 1911' => array(), 'Graduate' => array(),
            'Grand Hotel' => array(), 'Gravitas One' => array(),
            'Great Vibes' => array(), 'Griffy' => array(), 'Gruppo' => array(),
            'Gudea' => array(), 'Gurajada' => array(), 'Habibi' => array(),
            'Halant' => array(), 'Hammersmith One' => array(),
            'Hanalei' => array(), 'Hanalei Fill' => array(),
            'Handlee' => array(), 'Hanuman' => array(),
            'Happy Monkey' => array(), 'Headland One' => array(),
            'Henny Penny' => array(), 'Herr Von Muellerhoff' => array(),
            'Hind' => array(), 'Hind Siliguri' => array(),
            'Hind Vadodara' => array(), 'Holtwood One SC' => array(),
            'Homemade Apple' => array(), 'Homenaje' => array(),
            'IM Fell DW Pica' => array(), 'IM Fell DW Pica SC' => array(),
            'IM Fell Double Pica' => array(),
            'IM Fell Double Pica SC' => array(), 'IM Fell English' => array(),
            'IM Fell English SC' => array(), 'IM Fell French Canon' => array(),
            'IM Fell French Canon SC' => array(),
            'IM Fell Great Primer' => array(),
            'IM Fell Great Primer SC' => array(), 'Iceberg' => array(),
            'Iceland' => array(), 'Imprima' => array(),
            'Inconsolata' => array(), 'Inder' => array(),
            'Indie Flower' => array(), 'Inika' => array(),
            'Inknut Antiqua' => array(), 'Irish Grover' => array(),
            'Istok Web' => array(), 'Italiana' => array(),
            'Italianno' => array(), 'Itim' => array(),
            'Jacques Francois' => array(), 'Jacques Francois Shadow' => array(),
            'Jaldi' => array(), 'Jim Nightshade' => array(),
            'Jockey One' => array(), 'Jolly Lodger' => array(),
            'Josefin Sans' => array(), 'Josefin Slab' => array(),
            'Joti One' => array(), 'Judson' => array(), 'Julee' => array(),
            'Julius Sans One' => array(), 'Junge' => array(), 'Jura' => array(),
            'Just Another Hand' => array(),
            'Just Me Again Down Here' => array(), 'Kadwa' => array(),
            'Kalam' => array(), 'Kameron' => array(), 'Kantumruy' => array(),
            'Karla' => array(), 'Karma' => array(), 'Kaushan Script' => array(),
            'Kavoon' => array(), 'Kdam Thmor' => array(),
            'Keania One' => array(), 'Kelly Slab' => array(),
            'Kenia' => array(), 'Khand' => array(), 'Khmer' => array(),
            'Khula' => array(), 'Kite One' => array(), 'Knewave' => array(),
            'Kotta One' => array(), 'Koulen' => array(), 'Kranky' => array(),
            'Kreon' => array(), 'Kristi' => array(), 'Krona One' => array(),
            'Kurale' => array(), 'La Belle Aurore' => array(),
            'Laila' => array(), 'Lakki Reddy' => array(), 'Lancelot' => array(),
            'Lateef' => array(), 'Lato' => array(), 'League Script' => array(),
            'Leckerli One' => array(), 'Ledger' => array(), 'Lekton' => array(),
            'Lemon' => array(), 'Libre Baskerville' => array(),
            'Life Savers' => array(), 'Lilita One' => array(),
            'Lily Script One' => array(), 'Limelight' => array(),
            'Linden Hill' => array(), 'Lobster' => array(),
            'Lobster Two' => array(), 'Londrina Outline' => array(),
            'Londrina Shadow' => array(), 'Londrina Sketch' => array(),
            'Londrina Solid' => array(), 'Lora' => array(),
            'Love Ya Like A Sister' => array(), 'Loved by the King' => array(),
            'Lovers Quarrel' => array(), 'Luckiest Guy' => array(),
            'Lusitana' => array(), 'Lustria' => array(), 'Macondo' => array(),
            'Macondo Swash Caps' => array(), 'Magra' => array(),
            'Maiden Orange' => array(), 'Mako' => array(),
            'Mallanna' => array(), 'Mandali' => array(), 'Marcellus' => array(),
            'Marcellus SC' => array(), 'Marck Script' => array(),
            'Margarine' => array(), 'Marko One' => array(),
            'Marmelad' => array(), 'Martel' => array(),
            'Martel Sans' => array(), 'Marvel' => array(), 'Mate' => array(),
            'Mate SC' => array(), 'Maven Pro' => array(), 'McLaren' => array(),
            'Meddon' => array(), 'MedievalSharp' => array(),
            'Medula One' => array(), 'Megrim' => array(),
            'Meie Script' => array(), 'Merienda' => array(),
            'Merienda One' => array(), 'Merriweather' => array(),
            'Merriweather Sans' => array(), 'Metal' => array(),
            'Metal Mania' => array(), 'Metamorphous' => array(),
            'Metrophobic' => array(), 'Michroma' => array(),
            'Milonga' => array(), 'Miltonian' => array(),
            'Miltonian Tattoo' => array(), 'Miniver' => array(),
            'Miss Fajardose' => array(), 'Modak' => array(),
            'Modern Antiqua' => array(), 'Molengo' => array(),
            'Molle' => array(), 'Monda' => array(), 'Monofett' => array(),
            'Monoton' => array(), 'Monsieur La Doulaise' => array(),
            'Montaga' => array(), 'Montez' => array(), 'Montserrat' => array(),
            'Montserrat Alternates' => array(),
            'Montserrat Subrayada' => array(), 'Moul' => array(),
            'Moulpali' => array(), 'Mountains of Christmas' => array(),
            'Mouse Memoirs' => array(), 'Mr Bedfort' => array(),
            'Mr Dafoe' => array(), 'Mr De Haviland' => array(),
            'Mrs Saint Delafield' => array(), 'Mrs Sheppards' => array(),
            'Muli' => array(), 'Mystery Quest' => array(), 'NTR' => array(),
            'Neucha' => array(), 'Neuton' => array(), 'New Rocker' => array(),
            'News Cycle' => array(), 'Niconne' => array(),
            'Nixie One' => array(), 'Nobile' => array(), 'Nokora' => array(),
            'Norican' => array(), 'Nosifer' => array(),
            'Nothing You Could Do' => array(), 'Noticia Text' => array(),
            'Noto Sans' => array(), 'Noto Serif' => array(),
            'Nova Cut' => array(), 'Nova Flat' => array(),
            'Nova Mono' => array(), 'Nova Oval' => array(),
            'Nova Round' => array(), 'Nova Script' => array(),
            'Nova Slim' => array(), 'Nova Square' => array(),
            'Numans' => array(), 'Nunito' => array(),
            'Odor Mean Chey' => array(), 'Offside' => array(),
            'Old Standard TT' => array(), 'Oldenburg' => array(),
            'Oleo Script' => array(), 'Oleo Script Swash Caps' => array(),
            'Open Sans' => array(), 'Open Sans Condensed' => array(),
            'Oranienbaum' => array(), 'Orbitron' => array(),
            'Oregano' => array(), 'Orienta' => array(),
            'Original Surfer' => array(), 'Oswald' => array(),
            'Over the Rainbow' => array(), 'Overlock' => array(),
            'Overlock SC' => array(), 'Ovo' => array(), 'Oxygen' => array(),
            'Oxygen Mono' => array(), 'PT Mono' => array(),
            'PT Sans' => array(), 'PT Sans Caption' => array(),
            'PT Sans Narrow' => array(), 'PT Serif' => array(),
            'PT Serif Caption' => array(), 'Pacifico' => array(),
            'Palanquin' => array(), 'Palanquin Dark' => array(),
            'Paprika' => array(), 'Parisienne' => array(),
            'Passero One' => array(), 'Passion One' => array(),
            'Pathway Gothic One' => array(), 'Patrick Hand' => array(),
            'Patrick Hand SC' => array(), 'Patua One' => array(),
            'Paytone One' => array(), 'Peddana' => array(),
            'Peralta' => array(), 'Permanent Marker' => array(),
            'Petit Formal Script' => array(), 'Petrona' => array(),
            'Philosopher' => array(), 'Piedra' => array(),
            'Pinyon Script' => array(), 'Pirata One' => array(),
            'Plaster' => array(), 'Play' => array(), 'Playball' => array(),
            'Playfair Display' => array(), 'Playfair Display SC' => array(),
            'Podkova' => array(), 'Poiret One' => array(),
            'Poller One' => array(), 'Poly' => array(), 'Pompiere' => array(),
            'Pontano Sans' => array(), 'Poppins' => array(),
            'Port Lligat Sans' => array(), 'Port Lligat Slab' => array(),
            'Pragati Narrow' => array(), 'Prata' => array(),
            'Preahvihear' => array(), 'Press Start 2P' => array(),
            'Princess Sofia' => array(), 'Prociono' => array(),
            'Prosto One' => array(), 'Puritan' => array(),
            'Purple Purse' => array(), 'Quando' => array(),
            'Quantico' => array(), 'Quattrocento' => array(),
            'Quattrocento Sans' => array(), 'Questrial' => array(),
            'Quicksand' => array(), 'Quintessential' => array(),
            'Qwigley' => array(), 'Racing Sans One' => array(),
            'Radley' => array(), 'Rajdhani' => array(), 'Raleway' => array(),
            'Raleway Dots' => array(), 'Ramabhadra' => array(),
            'Ramaraja' => array(), 'Rambla' => array(),
            'Rammetto One' => array(), 'Ranchers' => array(),
            'Rancho' => array(), 'Ranga' => array(), 'Rationale' => array(),
            'Ravi Prakash' => array(), 'Redressed' => array(),
            'Reenie Beanie' => array(), 'Revalia' => array(),
            'Rhodium Libre' => array(), 'Ribeye' => array(),
            'Ribeye Marrow' => array(), 'Righteous' => array(),
            'Risque' => array(), 'Roboto' => array(),
            'Roboto Condensed' => array(), 'Roboto Mono' => array(),
            'Roboto Slab' => array(), 'Rochester' => array(),
            'Rock Salt' => array(), 'Rokkitt' => array(),
            'Romanesco' => array(), 'Ropa Sans' => array(),
            'Rosario' => array(), 'Rosarivo' => array(),
            'Rouge Script' => array(), 'Rozha One' => array(),
            'Rubik' => array(), 'Rubik Mono One' => array(),
            'Rubik One' => array(), 'Ruda' => array(), 'Rufina' => array(),
            'Ruge Boogie' => array(), 'Ruluko' => array(),
            'Rum Raisin' => array(), 'Ruslan Display' => array(),
            'Russo One' => array(), 'Ruthie' => array(), 'Rye' => array(),
            'Sacramento' => array(), 'Sahitya' => array(), 'Sail' => array(),
            'Salsa' => array(), 'Sanchez' => array(), 'Sancreek' => array(),
            'Sansita One' => array(), 'Sarala' => array(), 'Sarina' => array(),
            'Sarpanch' => array(), 'Satisfy' => array(), 'Scada' => array(),
            'Scheherazade' => array(), 'Schoolbell' => array(),
            'Seaweed Script' => array(), 'Sevillana' => array(),
            'Seymour One' => array(), 'Shadows Into Light' => array(),
            'Shadows Into Light Two' => array(), 'Shanti' => array(),
            'Share' => array(), 'Share Tech' => array(),
            'Share Tech Mono' => array(), 'Shojumaru' => array(),
            'Short Stack' => array(), 'Siemreap' => array(),
            'Sigmar One' => array(), 'Signika' => array(),
            'Signika Negative' => array(), 'Simonetta' => array(),
            'Sintony' => array(), 'Sirin Stencil' => array(),
            'Six Caps' => array(), 'Skranji' => array(),
            'Slabo 13px' => array(), 'Slabo 27px' => array(),
            'Slackey' => array(), 'Smokum' => array(), 'Smythe' => array(),
            'Sniglet' => array(), 'Snippet' => array(),
            'Snowburst One' => array(), 'Sofadi One' => array(),
            'Sofia' => array(), 'Sonsie One' => array(),
            'Sorts Mill Goudy' => array(), 'Source Code Pro' => array(),
            'Source Sans Pro' => array(), 'Source Serif Pro' => array(),
            'Special Elite' => array(), 'Spicy Rice' => array(),
            'Spinnaker' => array(), 'Spirax' => array(),
            'Squada One' => array(), 'Sree Krushnadevaraya' => array(),
            'Stalemate' => array(), 'Stalinist One' => array(),
            'Stardos Stencil' => array(), 'Stint Ultra Condensed' => array(),
            'Stint Ultra Expanded' => array(), 'Stoke' => array(),
            'Strait' => array(), 'Sue Ellen Francisco' => array(),
            'Sumana' => array(), 'Sunshiney' => array(),
            'Supermercado One' => array(), 'Sura' => array(),
            'Suranna' => array(), 'Suravaram' => array(),
            'Suwannaphum' => array(), 'Swanky and Moo Moo' => array(),
            'Syncopate' => array(), 'Tangerine' => array(), 'Taprom' => array(),
            'Tauri' => array(), 'Teko' => array(), 'Telex' => array(),
            'Tenali Ramakrishna' => array(), 'Tenor Sans' => array(),
            'Text Me One' => array(), 'The Girl Next Door' => array(),
            'Tienne' => array(), 'Tillana' => array(), 'Timmana' => array(),
            'Tinos' => array(), 'Titan One' => array(),
            'Titillium Web' => array(), 'Trade Winds' => array(),
            'Trocchi' => array(), 'Trochut' => array(), 'Trykker' => array(),
            'Tulpen One' => array(), 'Ubuntu' => array(),
            'Ubuntu Condensed' => array(), 'Ubuntu Mono' => array(),
            'Ultra' => array(), 'Uncial Antiqua' => array(),
            'Underdog' => array(), 'Unica One' => array(),
            'UnifrakturCook' => array(), 'UnifrakturMaguntia' => array(),
            'Unkempt' => array(), 'Unlock' => array(), 'Unna' => array(),
            'VT323' => array(), 'Vampiro One' => array(), 'Varela' => array(),
            'Varela Round' => array(), 'Vast Shadow' => array(),
            'Vesper Libre' => array(), 'Vibur' => array(),
            'Vidaloka' => array(), 'Viga' => array(), 'Voces' => array(),
            'Volkhov' => array(), 'Vollkorn' => array(), 'Voltaire' => array(),
            'Waiting for the Sunrise' => array(), 'Wallpoet' => array(),
            'Walter Turncoat' => array(), 'Warnes' => array(),
            'Wellfleet' => array(), 'Wendy One' => array(),
            'Wire One' => array(), 'Work Sans' => array(),
            'Yanone Kaffeesatz' => array(), 'Yantramanav' => array(),
            'Yellowtail' => array(), 'Yeseva One' => array(),
            'Yesteryear' => array(), 'Zeyada' => array(),
        );

    /**
     * @param String $name Name of the option
     * @param array  $translations Array with translations for option.
     * @param array  $data
     * @param String $type         the type of the option
     * @param bool   $series       handel the elements as series if true
     */
    public function __construct(
        $name,
        $translations,
        $data,
        $type,
        $series = false
    )
    {
        parent::__construct($name, $translations, $data, $type, $series);
        $this->activeChoice = isset($data['activeChoice'])
            ? $data['activeChoice'] : '';
        $this->choice       = $this->fonts;
    }

    /**
     * Get the data in a serializable format.
     *
     * @return array
     */
    public function yamlSerialize()
    {
        $option             = parent::yamlSerialize();
        $option['specific'] = array();
        return $option;
    }

}