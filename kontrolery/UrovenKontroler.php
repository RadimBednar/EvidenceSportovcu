<?php
class UrovenKontroler extends Kontroler {
    public function zpracuj($parametry) {

         $modelUroven = new ModelyUroven();
         $modelUzivatel = new ModelyUzivatel;


         
//--------------------------------- Session ------------------------------------------------------------------

        // Zkontroluj, zda je uživatel přihlášen
        if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
            $this->data['session']['opravneni'] = null;
           
        }

        else {
    
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

        
        


        // Zpracování formuláře
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pridej'])) {
            // Zde by mělo dojít k zpracování formuláře
            // a volání metody pridejSporty z vaší třídy

           
            
            
                $urov = [
                    'nazev_urov' => $_POST['nazev_urov'],
                    // Další potřebné údaje pozic
                ];
    
                $pridejUroven= $modelUroven->pridejUroven($urov);
    
                if ($pridejUroven === 1) {
                    // Sport byl úspěšně přidán
                    $this->pridejZpravu("Uroven byl úspěšně přidán.");
                    $this->presmeruj("uroven");
                    
                    exit;
                } else if ($pridejUroven === 0) {
                    // Sport již existuje
                    $this->pridejZpravu("uroven již existuje!");
                    $this->presmeruj("uroven");
                    exit;
                    
                } else {
                    // Nějaká jiná chyba
                    // Můžete zde zobrazit chybovou hlášku uživateli
                    $this->pridejZpravu("Chyba při přidání uroven.");
                    $this->presmeruj("uroven");
                    exit;
                }
            
            
            
        }
        else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ulozit']))   {
            
            $hodnoty= [
                'id_urov' => $_POST['editovana_uroven_id'],
                'nazev_urov' => $_POST['novy_nazev_urovne'],
                
            ];

            $editUroven= $modelUroven->zmenUroven($hodnoty, $_POST['editovana_uroven_id']);

            if ($editUroven === 1) {
                // Sport byl úspěšně editován
                $this->pridejZpravu("uroven byl úspěšně editován.");
                $this->presmeruj("uroven");
                
                exit;
            } 
            else {
                // Nějaká jiná chyba
                // Můžete zde zobrazit chybovou hlášku uživateli
                $this->pridejZpravu("Chyba při editaci urovne.");
                
                $this->presmeruj("uroven");
                exit;   
            } 
            }
            else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['smazat']))   {
            
                
    
                $smazUrovne= $modelUroven->odeberUroven($_POST['smazana_uroven_id']);
    
                if ($smazUrovne === 1) {
                    // Sport byl úspěšně editován
                    $this->pridejZpravu("Uroven byl úspěšně smazán.");
                    $this->presmeruj("uroven");
                    
                    exit;
                } 
                else {
                    // Nějaká jiná chyba
                    // Můžete zde zobrazit chybovou hlášku uživateli
                    $this->pridejZpravu("Chyba při smazání urovne.");
                    
                    $this->presmeruj("uroven");
                    exit;   
                } 
                }

          $this->pohled = "uroven";

        $urovne=$modelUroven->vratVsechnyUroven();
        $this->data["urov"] = $urovne;  

       
        
        
    }
}