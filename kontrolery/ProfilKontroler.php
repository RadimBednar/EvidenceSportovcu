<?php

class ProfilKontroler extends Kontroler {

    public function zpracuj($parametry) {
        $this->pohled = 'profil';
    

        


        // Zkontroluj, zda je uživatel přihlášen
        //if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
            //$this->data['uzivatel'] = null;
           
            //return;
       // }

        //--------------------------------- Session ------------------------------------------------------------------

        // Zkontroluj, zda je uživatel přihlášen
        if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
            $this->data['session']['opravneni'] = null;
           
        }

        else {
            $modelUzivatel = new ModelyUzivatel;
            // Získání emailu přihlášeného uživatele z session
            $emailUzivatele = $_SESSION['email'];
        
            // Získání informací o přihlášeném uživateli z databáze
            $uzivatelInfo = $modelUzivatel->vratInfoPodleEmailu($emailUzivatele);
        
            // Kontrola, zda byl uživatel nalezen v databázi
            
            if ($uzivatelInfo) {
                $this->data['session'] = $uzivatelInfo; 
            }
        }

//--------------------------------- Session ------------------------------------------------------------------
        
    
        // Získání emailu přihlášeného uživatele z session
        if(isset($_SESSION['email'])){
            $emailUzivatele = $_SESSION['email'];

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
        }
        // Vytvoření instance modelu pro práci s uživateli
        
    
        // Vytvoření instance modelu pro práci s disciplínami
        $modelDisciplin = new ModelyDisciplina();
    
        // Získání dat o sportovních aktivitách
        $discipliny = $modelDisciplin->vratVsechnyDiscipliny();
    
        // Předání dat o disciplínách do pohledu
        $this->data['discipliny'] = $discipliny;
    
        // Zpracování formuláře pro úpravu dodatečných údajů
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Zkontroluj, zda byly odeslány údaje z formuláře
            if (isset($_POST['kontaktni_udaje']) && isset($_POST['odkaz_na_web']) && isset($_POST['zdravotni_omezeni']) ) {
                // Získání hodnot z formuláře
                
                $kontaktniUdaje = $_POST['kontaktni_udaje'];
                $odkazNaWeb = $_POST['odkaz_na_web'];
                $zdravotniOmezeni = $_POST['zdravotni_omezeni'];
                
                
    
               // Uložení nových údajů do databáze
                 $modelUzivatel->pridaniDodatecnychUdaju($emailUzivatele,$kontaktniUdaje,$odkazNaWeb,$zdravotniOmezeni);
                // Získání aktualizovaných informací o uživateli
                $uzivatelInfo = $modelUzivatel->vratInfoPodleEmailuDI($emailUzivatele);
    
                // Aktualizace dat pro zobrazení v pohledu
                $this->data['uzivatel'] = $uzivatelInfo;
            }
        }
        

        if(isset($_POST['zobrazit'])){
        $this->data["sportovci"] = ModelyUzivatel::projedVsechnyUzivatele($_POST['uziv']);
           }

    
        $modelySportuje = new ModelySportuje;
        $modelyPozice= new ModelyPozice;
        $modelyUroven= new ModelyUroven;
        $modelyUzivatel = new ModelyUzivatel;
        
        
        $this->data["sportuje"] = $modelySportuje->vratVsechySportuje();
        $this->data["pozice"] = $modelyPozice->vratVsechnyPozice();
        $this->data["uroven"] = $modelyUroven->vratVsechnyUroven(); 
        $this->data["uzivatele"] = $modelyUzivatel->vratVsechnyUzivatele(); 
    
    
    
        if(isset($_POST["pridej_sport"])){
            header("Refresh:0"); 
            ModelySportuje::pridejZFormulare($_POST ["pozice"], $_POST ["uroven"], $_POST ["sport"]);
        }
    
        // Zavolání metody pro odebrání sportu
        $this->odeberSport();


        

    }

    // Metoda pro odebrání sportu
    public function odeberSport() {
        // Zkontroluj, zda bylo odesláno požadavku na odebrání sportu
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['odeber_sport_button'])) {
            // Získání ID sportu k odebrání
            $idSportuje = $_POST['odeber_sport'];

            // Vytvoření instance modelu pro práci se sportujícími uživateli
            $modelySportuje = new ModelySportuje();

            // Odebrání sportu
            $uspech = $modelySportuje->odeberSportuje($idSportuje);

            // Přidání zprávy o úspěchu/nezdaře odebrání sportu
            if ($uspech) {
                header("Refresh:0"); 
                $this->pridejZpravu('Sport byl úspěšně odebrán.');
            } else {
                $this->pridejZpravu('Odebrání sportu se nezdařilo.');
            }
        }
    }
}


