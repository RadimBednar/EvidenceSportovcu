<?php
class KoloKontroler extends Kontroler {
    public function zpracuj($parametry) {

         $modelyKolo= new ModelyKolo();
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

           
            
            
                $kolo = [
                    'nazev_kolo' => $_POST['nazev_kolo'],
                    // Další potřebné údaje pozic
                ];
    
                $pridejKolo= $modelyKolo->pridejKolo($kolo);
    
                if ($pridejKolo === 1) {
                    // Sport byl úspěšně přidán
                    $this->pridejZpravu("Kolo byl úspěšně přidán.");
                    $this->presmeruj("kolo");
                    
                    exit;
                } else if ($pridejKolo === 0) {
                    // Sport již existuje
                    $this->pridejZpravu("Kolo již existuje!");
                    $this->presmeruj("kolo");
                    exit;
                    
                } else {
                    // Nějaká jiná chyba
                    // Můžete zde zobrazit chybovou hlášku uživateli
                    $this->pridejZpravu("Chyba při přidání kolo.");
                    $this->presmeruj("kolo");
                    exit;
                }
            
            
            
        }
        else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ulozit']))   {
            
            $hodnoty= [
                'id_kolo' => $_POST['editovana_kolo_id'],
                'nazev_kolo' => $_POST['novy_nazev_kolo'],
                
            ];

            $editKolo= $modelyKolo->zmenKolo($hodnoty, $_POST['editovana_kolo_id']);

            if ($editKolo === 1) {
                // Sport byl úspěšně editován
                $this->pridejZpravu("Kolo byl úspěšně editován.");
                $this->presmeruj("kolo");
                
                exit;
            } 
            else {
                // Nějaká jiná chyba
                // Můžete zde zobrazit chybovou hlášku uživateli
                $this->pridejZpravu("Chyba při editaci kolo.");
                
                $this->presmeruj("kolo");
                exit;   
            } 
            }
            else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['smazat']))   {
            
                
    
                $smazKolo= $modelyKolo->odeberKolo($_POST['smazana_kolo_id']);
    
                if ($smazKolo === 1) {
                    // Sport byl úspěšně editován
                    $this->pridejZpravu("Kolo byl úspěšně smazán.");
                    $this->presmeruj("kolo");
                    
                    exit;
                } 
                else {
                    // Nějaká jiná chyba
                    // Můžete zde zobrazit chybovou hlášku uživateli
                    $this->pridejZpravu("Chyba při smazání kolo.");
                    
                    $this->presmeruj("kolo");
                    exit;   
                } 
                }

          $this->pohled = "kolo";

        $kolo=$modelyKolo->vratVsechnyKolo();
        $this->data["kolo"] = $kolo;

       
        
        
    }
}