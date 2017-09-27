<?php

/**
 * @author 
 * @copyright 2017
 */

abstract class TYPE_OF_FOOD {
    const UNDEFINED = -1;
    const FRUITS = 0;
    const MEATS = 1;
    const VEGETABLES = 2;
    const BEVERAGES = 3;
    const SWEETS = 4;
    const OTHER = 5;
    
    const LAST_INDEX = 5;
}

class Food {
    private $name = "";     
    private $calorie = 0;   // vrednost namirnice u kalorijama
    private $type = -1;
    
    /** konstruktor je jedini nacin kako postavljamo vrednosti $name i $calorie i njih postavljamo
     * na pocetku jer se one ne menjaju kasnije
     */
    public function __construct($tmpName,$tmpType = -1,$tmpCalorie = 0){
        $this->name = $tmpName;
        $this->SetType($tmpType);
        if(is_int($tmpCalorie)&&($tmpCalorie > 0))
            $this->calorie = $tmpCalorie;
        else
            $this->calorie = 0; // ili mozemo da stavimo na -1 ako budemo zeleli da detektujemo gresku         
    }
    
    public function Kcal(){ return (int) $this->calorie;}
    
    public function SetType($tmpType){
        if(($tmpType >= -1)&&($tmpType <= TYPE_OF_FOODS::LAST_INDEX))
        {
            // argument funkcije je ispravan tip
            $this->type = $tmpType;
            return true;   
        }
        else
            return false;
    }
}

/**
 * ovde ide kraci drajver program koji formira niz objekata tipe Food koje cemo kasnije koristiti
 */
 $array = array(["Banana"] => array(TYPE_OF_FOODS::FRUITS,110),
                 ["Apple"] => array(TYPE_OF_FOODS::FRUITS,80),
                 ["Chicken Drumstick"]=> array(TYPE_OF_FOODS::MEATS,230),
                 ["Icecream"]=> array(TYPE_OF_FOODS::SWEETS,360),
                 ["Peace of cake"]=> array(TYPE_OF_FOODS::SWEETS,560));
 
 // kreiramo niz namernica $FOODS
                 
 foreach($array as $name => $args)
 {
    $FOODS[$name] = new Food($name,$args[0],$args[1]);
 };
                 


?>