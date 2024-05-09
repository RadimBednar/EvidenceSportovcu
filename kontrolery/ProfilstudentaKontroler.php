<?php

class ProfilstudentaKontroler extends Kontroler {

    public function zpracuj($parametry) {
        $this->pohled = 'profilstudenta';
    

        


        // Zkontroluj, zda je uživatel přihlášen
        if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
            //$this->data['uzivatel'] = null;
           
            //return;
        }
    
        // Získání emailu přihlášeného uživatele z session
        $emailUzivatele = $_SESSION['email'];
    
        // Vytvoření instance modelu pro práci s uživateli
        $modelUzivatel = new ModelyUzivatel();
    
        // Získání informací o přihlášeném uživateli z databáze
        $uzivatelInfo = $modelUzivatel->vratInfoPodleEmailuDI($emailUzivatele);
    
        // Kontrola, zda byl uživatel nalezen v databázi
        
        if ($uzivatelInfo) {
            $this->data['uzivatel'] = $uzivatelInfo; 
        } else {
            // Uživatel nenalezen v databázi
            $this->data['uzivatel'] = null;
        }
    
        // Vytvoření instance modelu pro práci s disciplínami
        $modelDisciplin = new ModelyDisciplina();
    
        // Získání dat o sportovních aktivitách
        $discipliny = $modelDisciplin->vratVsechnyDiscipliny();
    
        // Předání dat o disciplínách do pohledu
        $this->data['discipliny'] = $discipliny;
    
        // Zpracování formuláře pro úpravu dodatečných údajů
        //if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Zkontroluj, zda byly odeslány údaje z formuláře
         //   if (isset($_POST['kontaktni_udaje']) && isset($_POST['odkaz_na_web']) && isset($_POST['zdravotni_omezeni']) ) {
                // Získání hodnot z formuláře   
             //   $kontaktniUdaje = $_POST['kontaktni_udaje'];
                //$odkazNaWeb = $_POST['odkaz_na_web'];
               // $zdravotniOmezeni = $_POST['zdravotni_omezeni'];
               // Uložení nových údajů do databáze
               //  $modelUzivatel->pridaniDodatecnychUdaju($emailUzivatele,$kontaktniUdaje,$odkazNaWeb,$zdravotniOmezeni);
                // Získání aktualizovaných informací o uživateli
                //$uzivatelInfo = $modelUzivatel->vratInfoPodleEmailuDI($emailUzivatele);
    
                // Aktualizace dat pro zobrazení v pohledu
               // $this->data['uzivatel'] = $uzivatelInfo;
  
           // }
        //}
        $modelyUzivatel = new ModelyUzivatel;// var_dump($_POST['uziv']);
        //var_dump($modelyUzivatel->projedVsechnyUzivatele($_POST['uziv']));
       

        if(isset($_POST['zobrazit'])){
        $this->data["sportovci"] = $modelyUzivatel->projedVsechnyUzivatele($_POST['uziv']);
           }

    
        $modelySportuje = new ModelySportuje;
        $modelyPozice= new ModelyPozice;
        $modelyUroven= new ModelyUroven;
        
        
        $this->data["sportuje"] = $modelySportuje->vratVsechySportuje();
        $this->data["pozice"] = $modelyPozice->vratVsechnyPozice();
        $this->data["uroven"] = $modelyUroven->vratVsechnyUroven(); 
    
    
    
        if(isset($_POST["pridej_sport"])){
            header("Refresh:0"); 
            ModelySportuje::pridejZFormulare($_POST ["pozice"], $_POST ["uroven"], $_POST ["sport"]);
        }
    }   
}


