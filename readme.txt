Klasa Humanoid simulira dnevne aktivnosti coveka, osobe sa osnovnim fizioloskim potrebama
kakve su ishrana, odmor i izvodjenje dnevnih aktivnosti.

Objekat Covek izveden iz ove klase ima osnovne podatke ime, pol,datum rodjenja, broj godina koje se izvode
iz datuma rodjenja i poznavanja trenutnog datuma, tezinu.

Objekat Covek ima 3 osnovna pokazatelja trenutnog statusa osnovnih funkcija:
    
    $foodsaturation predstavlja trenutnu uhranjenost, dakle ukupan broj unetih kalorija iz objekata klase Food
                    bez onih kalorija koje smo potrosili. 
                    
                    Ukoliko ovaj broj padne na 0 onda se javlja glad i postavlja se marker $starving na TRUE. 
                    
                    Ukoliko je ova velicina iznad potrebnog dnevnog unosa kalorija za taj pol i tu odredjenu tezinu 
                    Coveka onda se javlja prejedenost i postavlja se marker $overeaten na TRUE.
                    
                    Metode koje uticu na $foodsaturation su:
                    
                    * private function addFoodSaturation($calorie_taken = 0)
                    * private function subFoodSaturation($calorie_burned = 0)
                    * private function GetFoodSaturation()
                    
    $sleepines      predstavlja trenutnu pospanost. Odmah nakon budjenja pospanost Coveka je 0 (nula procenata).
                    Kako je radni dan 16 sati to znaci da ce nakon 16 sati x 60 minuta ova vrednost dostici 100.
                    
                    Ukoliko ova vrednost dostigne 100 postavlja se flag $tired na TRUE.
                    
                    Metode koje uticu na $sleepines su:
                    
                    * private function GetSleepy($minutes = 0)
                        Dodatno umara Coveka i povecava mu pospanost nakon $minutes minuta budnosti
                        
                    * private function TakeARest($minutes = 0, $intensity = 1.0)
                        Odmara Coveka i smanjuje pospanost sa intezitetom 1 ako spava, sa manjim
                        intezitetom ako recimo sedi ili lezi
                        
    $energy_level   predstavlja ukupan energetski nivo za izvodjenje aktivnosti i predstavlja srednju vrednost izrazenu u
                    procentima predhodne dveju velicina. Sto je veca zasicenost hranom a manja pospanost energetski nivo
                    Coveka je veci.
                    
                    Metode koje uticu na $energy_level su:
                    
                    * private function RefreshEnergyLevel()
                        Poziva se svaki put kada su promenjene vrednosti predhodne dve promenljive i to u metodima koji
                        ih menjaju.
    

Objekat Covek trenutno moze da izvodi sledece osnovne akcije:

    *   Jede                    public function Eat(Food $food = null)
    *   Trci                    public function Run($minutes = 0)
    *   Cita                    public function Read($doc = null, $minutes)
    *   Hoda                    public function Walk($km)
    *   Odlazi u kupovinu       public function GoToShopping($hours)
    *   Spava                   public function Sleep($hours)
    
 Prve 5 akcije trose energiju Coveka jer povecavaju pospanost i trose kalorije. 
 Takodje pozivaju pomocnu privatnu metodu:
 
    *   private function DoIt($action = null, $howmuch)
 
 Spavanje smanjuje pospanost ali i trosi kalorije.
 
 Klase Food i Action nalaze se u ukljucenim fajlovima food.inc i actions.inc i predstavljaju objekte odredjenih namirnica
 sa svojim imenom, kalorijama koje sadrze i tipom, i objekte akcija koje moze izvoditi Covek u stvarnom svetu sa imenom,
 jedinicu resursa koje trose (sat, minut, predjeni km itd...) i potrosenim kalorijama po datoj jedinici.
 
 Pomocne funkcije klase Humanoid su:
 
    *   private function DoIt($action = null, $howmuch)
    *   private function dailyCalorieIntake()
 
 
 DODATAK A
 LISTA JAVNIH METODA I OSOBINA
 
 * Eat($food = null)                    $banana = new Food("Banana", TYPE_OF_FOODS::FRUITS, 110);
                                        $MyHumanoid.Eat($banana);
                                        
 * Run($minutes = 0)                    $MyHumanoid.Run(60)         Trci jedan sat
 
 * Read($doc = null, $minutes = 0)      $MyHumanoid.Read(null,60)   Cita jedan sat. Klasu objekata za citanje tek treba
                                                                    implementirati
                                                                    
 * Walk($km)                            $MyHumanoid.Walk(2)         Hoda 2 km
 
 * GoToShopping($hours)                 $MyHumanoid.GoToShopping(3) Ide u kupovinu sledeca 3 sata
 
 * Sleep($hours)                        $MyHumanoid.Sleep(8)        Spava sledecih 8 sati
 
 * SetAge($tmpAge)
   GetAge()    
 
 * SetName($tmpName = "")
   GetName()
   PrintName()
 
 * SetBirthday($day, $month, $year) 
   GetBirthday()
 
 * SetSex($tmpSex)
   SetAsMale()
   SetAsFemale()
   GetSex()
 
 * SetWeight($tmpWeight)
 
 * IsOvereaten()
 * IsStarving()
 * IsMale()
 * IsFemale()
 * IsTired()
 
    