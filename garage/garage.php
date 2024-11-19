<?php
class Voiture
{
    public string $modele;
    public string $marque;
    public int $vitesse = 0;
    public function setVitesse(int $vitesse){
    	if($vitesse<0){
    		$this->vitesse = 0;
    	}else{
    		$this->vitesse = $vitesse;
    	}
    }
   public function getVitesse(){
    	return $this->vitesse;
    }
    public function accelerer(int $vitesse = 0){
    	$this->setVitesse($this->vitesse += $vitesse);
    }
    
    public function __construct(string $marque, string $modele){
    	$this->marque = $marque;
	$this->modele = $modele;
    }
}