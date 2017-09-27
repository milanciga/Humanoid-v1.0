<?php

/**
 * @author 
 * @copyright 2017
 */

class Action {
    private $action = null;     
    private $unit = null;   // jedinica za izvodjenje aktivnosti, recimo za trcanje je metara, za sedenje minuta itd
                            // sluzi za izracunavanje ukupne potrosnje kalorija kao $unit*$calorie
    private $calorie = 0;

    public function __construct($tmpAction, $tmpUnit = "-", $tmpCalorie = 0){
        $this->action = $tmpAction;
        $this->unit = $tmpUnit;
        if(is_int($tmpCalorie)&&($tmpCalorie > 0))
            $this->calorie = $tmpCalorie;
        else
            $this->calorie = 0; // ili mozemo da stavimo na -1 ako budemo zeleli da detektujemo gresku         
    }

    public function Kcal(){ return (int) $this->calorie;}
}

/**
 * ovde ide kraci drajver program koji formira niz objekata tipe Action koje cemo kasnije koristiti
 */
 $array = array(["Running"] => array("min",8),
                 ["Reading"] => array("min",0.5),
                 ["Walking"]=> array("km",115),
                 ["Shopping"]=> array("hour",180),
                 ["Sleeping"]=> array("hour",45));
 
 // kreiramo niz namernica $ACTIONS
                 
 foreach($array as $action => $args)
 {
    $ACTIONS[$action] = new Action($action,$args[0],$args[1]);
 };

?>