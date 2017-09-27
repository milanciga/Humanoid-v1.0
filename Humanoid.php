<?php

/**
 * @author 
 * @copyright 2017
 */
include_once "food.inc";
include_once "actions.inc";

abstract class SEX {
    const UNDEFINED = -1;
    const MALE = 0;
    const FEMALE = 1;
}

class Humanoid {
    private $name = "";                         // ime osobe 
    private $age = 0;                           // broj godina
    private $dateofbirth = null;                // datum rodjenja
    private $weight = 0;                        // tezina u kilogramima
    private $sex = SEX::UNDEFINED;              // pol  1 - muskarac  2 - zena
    private $energy_level = 0;                  // nivo energije racunan u zavisnosti od dnevne potrebe u kalorijama
                                                // i nivo spavanja, Prazan 0, 100% broj dnevne potrebe za kalorijama
    private $food_saturation = 0;               // MIN 0 MAX broj kalorija potrebnih za jedan dan                                
    private $sleepiness = 0;                    // Nivo pospanosti 0d 0 do 100
                                                // Prvi trenutak nakon budjenja je 0
                                                // Nakon 16 sati x 60 min je 100
                                                
    
    /** ***************************************************************************************************************** 
     * Odeljak za prenos poruka iz akcija koje izvodi osoba sa mogucim dodavanjem odeljka za podizanje alarma/gresaka
     *  *****************************************************************************************************************/
    private $message = null;                      // string koji skuplja poruke nakon akcija osobe
    private function hasMessage() { return is_string($this->$message);}
    public function DeleteMessage() { $this->message = null; }
    public function ReadMessage() { 
        if($this->hasMessage())
            return $this->message;
        else
            return "";    
    }
    public function PrintMessage() { echo $this->ReadMessage();}
    /** *****************************************************************************************************************/
    
    
    /** Flagovi - privatne promenljive koje ukazuju na odredjena stanja osobe
     *  - postavljaju ih ostale javne metode a citaju IS_... iskazi **/
    private $overeaten = false;
    private $starving = false;
    private $tired = false;
    
    
    
    /** Konstruktori                                     **/
    public function __construct($tmpName){
        $this->SetName($tmpName);
    }
    
    
    /** ********************************************************************************************************
     *  Odeljak sa metodama koje predstavljaju ponasanje i akcije osobe u realnom svetu
     *  ********************************************************************************************************
    */
    
    public function Eat($food = null){
        if(get_class($food) != "Food")
        {
            // ovde dodati kod za javljanje greske
            return false;
        }
        else
        {
            // osoba je pojela namirnicu $food;
            return (bool) $this->addFoodSaturation($food->Kcal());
        }
    }
    
    /** Privatna funkcija koja predstavlja opstu akciju osobe koja trosi kalorije 
     *  Pozivaju je ostale akcije koje simuliraju radnje iz realnog sveta */
    private function DoIt($action = null, $howmuch){
        if(get_class($action) != "Action")
        {
            // ovde dodati kod za javljanje greske
            return false;
        }
        else
        {
            // osoba je pojela namirnicu $food;
            return (bool) $this->subFoodSaturation(($action->Kcal())*$howmuch);
        }
    }
    
    public function Run($minutes = 0){
        if(is_int($minutes)&&$minutes>0)
        {
            $running = $ACTIONS['Running'];
            return $this->DoIt($running, $minutes);
        }
        else
            return false;
    }
    
    public function Read($doc = null, $minutes){
        if(is_int($minutes)&&$minutes>0)
        {
            $reading = $ACTIONS['Reading'];
            return $this->DoIt($reading, $minutes);
        }
        else
            return false;
    }
    
    public function Walk($km){
        if($km>0)
        {
            $walking = $ACTIONS['Walking'];
            return $this->DoIt($walking, $km);
        }
        else
            return false;
    }
    
    public function GoToShopping($hours){
        if($hours>0)
        {
            $shopping = $ACTIONS['Shopping'];
            return $this->DoIt($shopping, $hours);
        }
        else
            return false;
    }
    
    public function Sleep($hours){
        if($hours>0)
        {
            $sleeping = $ACTIONS['Sleeping'];
            $this->TakeARest($hours*60);                    // spavanjem smanjuje pospanost $sleppines
            return $this->DoIt($sleeping, $hours);
        }
        else
            return false;
    }
    
    
    
    
    /** ********************************************************************************************************
     *  Odeljak sa Get, Set i ostalim pomocnim metodama
     *  ********************************************************************************************************
    */
    
    
    
    /** GetSet za $age                                   **/
    public function SetAge($tmpAge){
        if(is_int($tmpAge))    
            $this->age = $tmpAge;
        else
            return false;
    }
    
    public function GetAge() { return $this->age;}
    
    /** GetSet za $name                                  **/
    public function SetName($tmpName = ""){
        $this->name = $tmpName; 
    }
    public function GetName() { return $this->name;}
    
    // test metoda
    public function PrintName(){ echo $this->GetName();}
    
    /** GetSet za $dateofbirth                                  **/
    public function SetBirthday($day, $month, $year){
        if(checkdate($month, $day,$year)) 
        {
            $strDate = $year."-".$month."-".$day;
            $this->dateofbirth = new DateTime($strDate);
            
            // azuriramo broj godina osobe
            $today = new DateTime("now");
            $dif_date = $today->diff($this->GetBirthday());
            $this->SetAge($dif_date->y);
        } 
        else
            return false;
    }
    
    public function GetBirthday(){ return $this->dateofbirth;}
    
    /** GetSet za $dateofbirth                                  **/
    private function SetSex($tmpSex){
        if(($tmpSex==0)||($tmpSex==1))
            { 
                $this->sex = $tmpSex;
                return $tmpSex;
            }
        else
            return false;
    } 
    
    public function SetAsMale(){ $this->SetSex(SEX::MALE);}
    public function SetAsFemale(){ $this->SetSex(SEX::FEMALE);}
    public function GetSex(){ return $this->sex; } 
    
    /** GetSet za $weight                                       **/
    public function SetWeight($tmpWeight){
        if((is_int($tmpWeight))&&($tmpWeight>0))
        {
            $this->weight = $tmpWeight;
            return $tmpWeight;
        }
        else
            return false;
    }
    
    
    /** funkcija racuna dnevnu potrebu u kalorijama
     *  weight*35 za muskarca
     *  weight*25 za zenu
     */
     private function dailyCalorieIntake(){
        if($this->IsMale())
            return ($this->weight)*35;  // 35 - broj kaloorija po kilogramu za muskarce
        else
            return ($this->weight)*25;  // 25 - broj kaloorija po kilogramu za zenw
     } 
     
     
     /** GetSet za $food_saturation 
         - ova metoda unosi odredjeni broj kalorija koje je pojela osoba 
         - najcesce je poziva funkcija Eat() koja predstavlja uzimanje hrane   
         
     **/
     private function addFoodSaturation($calorie_taken = 0){
        if(is_int($calorie_taken)&&($calorie_taken > 0))
            {
                $new_level = $this->food_saturation + $calorie_taken;
                /*  dozvolicemo da novi nivo saturacije moze da bude samo 10% veci od 
                    maksimalnog nivoa dnevnog unosa kalorija i javicemo status da je osoba
                    prejela */
                $max_saturation_level = intval($this->dailyCalorieIntake()*(110/100));
                if($new_level > $max_saturation_level)
                {
                    // osoba se prejela
                    $this->food_saturation = $max_saturation_level;
                    $this->RefreshEnergyLevel();
                    $this->overeaten = true;    // postavlja flag koji kazuje da je osoba prejedena na true;
                }
                else
                {
                    $this->food_saturation = $new_level;
                    $this->RefreshEnergyLevel();
                    return $this->GetFoodSaturation();
                }    
                
            }
        else
            return false;   // argument funkcije nije prirodan pozitivan broj
     }
     
     /** - ova metoda oduzima odredjeni broj kalorija koje je potrosila osoba 
         - pozivaju je funkcije koje predstavljaju odredjene aktivnosti osobe
           iz realnog sveta: hodanje, trcanje, citanje, sedenje,...itd 
         
     **/
     private function subFoodSaturation($calorie_burned = 0){
        if(is_int($calorie_burned)&&($calorie_burned > 0))
        {
            $new_level = $this->food_saturation - $calorie_burned;
            /*  novi nivo saturacije moze da bude najmanje 0 inace se javlja glad */
            if($new_level <= 0)
            {
                // osoba gladuje
                $this->food_saturation = 0;
                $this->RefreshEnergyLevel();
                $this->starving = true;    // postavlja flag koji kazuje da je osoba prejedena na true;
            }
            else
            {
                $this->food_saturation = $new_level;
                $this->RefreshEnergyLevel();
                return $this->GetFoodSaturation();
            }
        }
        else
            return false;   // argument funkcije nije prirodan pozitivan broj
     }
     
     private function GetFoodSaturation() { return $this->food_saturation;}
     
     
     /** GetSet za $sleepiness     **/
     private function GetSleepy($minutes = 0){
        // racunamo pospanost nakon $minutes minuta ako znamo da je 0-0 a 16*60 min 100%
        $tmp = (100)/(16*60)*$minutes;
        if(($this->sleepiness += $tmp) > 100)   // sad smo pospaniji za dodatno vreme i
            $this->tired = true;                // proverava da li je pospanost postigla 100%
        $this->RefreshEnergyLevel();
     }
     
     /** Metoda utice na pospanost osobe. Osoba se odmara $minutes minuta sa faktorom inteziteta 1f
      *  Spavanje ce recimo imati intezitet 1 a sedenje recimo 0.5  */
     private function TakeARest($minutes = 0, $intensity = 1.0){
        $tmp = (100)/(8*60)*$minutes*$intensity;    // sada je 8*60 jer za stoprocentni odmor nam treba 8 sati spavanja
        if($tmp >= $this->sleepiness)
            {
                // Osoba se potpuno odmorila
                $this->sleepiness = 0;
                $this->RefreshEnergyLevel();
                $this->tired = false;
                
            } 
        else
            {
                $this->sleepiness -= $tmp;
                $this->RefreshEnergyLevel();
            }
     }
     
     
     /** GetSet za energy_level     **/
     private function RefreshEnergyLevel(){
        // metoda se poziva svaki put kada se menja bilo $foodsaturation bilo $sleppines
        // dakle sa svakom akcijom
        $tmpFoodSaturationPerCent = (int) (($this->food_saturation)/($this->dailyCalorieIntake))*100;
        $tmpSlippines = max(100-($this->sleepiness),0); // oduzimamo od 100 jer je osoba maksimalno 100% odmorna
                                                        // ako je 0 pospana i obrnuto a kako smo ostavili da pospanost
                                                        // moze ici preko 100 onda mora da predvidimo slucaj kada je ova
                                                        // razlika manja od nule jer onda uzimamo nulu
        $tmpEnergy = ($tmpFoodSaturationPerCent + $tmpSlippines) / 2;
        $this->energy_level = (int) $tmpEnergy;
     }
     
     /**
      *  Sekcija za metode koje citaju flagove
      * 
      */
     public function IsOvereaten() { return $this->overeaten; }
     public function IsStarving() { return $this->starving; }
     public function IsMale(){ return !$this->sex;}
     public function IsFemale() { return $this->sex;}
     public function IsTired() { return $this->tired;}
}  // class Humanoid

?>