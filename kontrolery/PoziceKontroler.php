<?php
class PoziceKontroler extends Kontroler {
    public function zpracuj($parametry) {

         $modelPozice= new ModelyPozice();
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

           
            
            
                $poz = [
                    'nazev_poz' => $_POST['nazev_poz'],
                    // Další potřebné údaje pozic
                ];
    
                $pridejPozici= $modelPozice->pridejPozice($poz);
    
                if ($pridejPozici === 1) {
                    // Sport byl úspěšně přidán
                    $this->pridejZpravu("Pozice byl úspěšně přidán.");
                    $this->presmeruj("pozice");
                    
                    exit;
                } else if ($pridejPozici === 0) {
                    // Sport již existuje
                    $this->pridejZpravu("Pozice již existuje!");
                    $this->presmeruj("pozice");
                    exit;
                    
                } else {
                    // Nějaká jiná chyba
                    // Můžete zde zobrazit chybovou hlášku uživateli
                    $this->pridejZpravu("Chyba při přidání pozice.");
                    $this->presmeruj("pozice");
                    exit;
                }
            
            
            
        }
        else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ulozit']))   {
            
            $hodnoty= [
                'id_poz' => $_POST['editovana_pozice_id'],
                'nazev_poz' => $_POST['novy_nazev_pozice'],
                
            ];

            $editPozice= $modelPozice->zmenPozici($hodnoty, $_POST['editovana_pozice_id']);

            if ($editPozice === 1) {
                // Sport byl úspěšně editován
                $this->pridejZpravu("Pozice byl úspěšně editován.");
                $this->presmeruj("pozice");
                
                exit;
            } 
            else {
                // Nějaká jiná chyba
                // Můžete zde zobrazit chybovou hlášku uživateli
                $this->pridejZpravu("Chyba při editaci pozice.");
                
                $this->presmeruj("pozice");
                exit;   
            } 
            }
            else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['smazat']))   {
            
                
    
                $smazPozici= $modelPozice->odeberPozice($_POST['smazana_pozice_id']);
    
                if ($smazPozici === 1) {
                    // Sport byl úspěšně editován
                    $this->pridejZpravu("Pozice byl úspěšně smazán.");
                    $this->presmeruj("pozice");
                    
                    exit;
                } 
                else {
                    // Nějaká jiná chyba
                    // Můžete zde zobrazit chybovou hlášku uživateli
                    $this->pridejZpravu("Chyba při smazání pozice.");
                    
                    $this->presmeruj("pozice");
                    exit;   
                } 
                }

          $this->pohled = "pozice";

        $pozice=$modelPozice->vratVsechnyPozice();
        $this->data["poz"] = $pozice;  

       
        
        
    }
}