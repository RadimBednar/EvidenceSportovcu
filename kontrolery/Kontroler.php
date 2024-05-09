<?php
abstract class Kontroler {
    protected $prihlasenyUzivatel;
    protected $pohled = ""; // název souboru s pohledem (bez přípony .phtml)
    protected $data = []; // pole dat pro sdílení mezi metodami kontroleru (např. pro předání z metody zpracuj do pohledu = vypisPohled())
    public function __construct() {
        $spravceUzivatelu = new ModelyUzivatel;
        $this->prihlasenyUzivatel = 
            $spravceUzivatelu->vratPrihlasenehoUzivatele();
    }
    abstract public function zpracuj($parametry);

    public function vypisPohled() {
        extract($this->data); // extrahuje prvky pole do samostatných proměnných (s názvem podle klíče prvku pole)
        // např. z $this->data["faktury"] udělá $faktury
        require "pohledy/{$this->pohled}.phtml";
    } 
    public function presmeruj($url) {
        header("Location: /$url");
        exit;
    }

    protected function pridejZpravu($zprava) {
        $_SESSION["zpravy"][] = $zprava;
    }

    protected function vratZpravy() {
        $zpravy = isset($_SESSION["zpravy"]) ? $_SESSION["zpravy"] : [];
        unset($_SESSION["zpravy"]);
        return $zpravy;
    }
}