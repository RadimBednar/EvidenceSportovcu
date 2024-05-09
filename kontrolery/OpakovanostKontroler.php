<?php
class OpakovanostKontroler extends Kontroler {
    public function zpracuj($parametry) {

         $modelyOpak= new ModelyOpakovanost();
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

           
            
            
                $opak = [
                    'nazev_opak' => $_POST['nazev_opak'],
                    // Další potřebné údaje pozic
                ];
    
                $pridejOpak= $modelyOpak->pridejOpak($opak);
    
                if ($pridejOpak === 1) {
                    // Sport byl úspěšně přidán
                    $this->pridejZpravu("Opakovanost byl úspěšně přidán.");
                    $this->presmeruj("opakovanost");
                    
                    exit;
                } else if ($pridejOpak === 0) {
                    // Sport již existuje
                    $this->pridejZpravu("Opakovanost již existuje!");
                    $this->presmeruj("opakovanost");
                    exit;
                    
                } else {
                    // Nějaká jiná chyba
                    // Můžete zde zobrazit chybovou hlášku uživateli
                    $this->pridejZpravu("Chyba při přidání opakovanost.");
                    $this->presmeruj("opakovanost");
                    exit;
                }
            
            
            
        }
        else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ulozit']))   {
            
            $hodnoty= [
                'id_opak' => $_POST['editovana_opak_id'],
                'nazev_opak' => $_POST['novy_nazev_opak'],
                
            ];

            $editOpak= $modelyOpak->zmenOpak($hodnoty, $_POST['editovana_opak_id']);

            if ($editOpak === 1) {
                // Sport byl úspěšně editován
                $this->pridejZpravu("Opakovanost byl úspěšně editován.");
                $this->presmeruj("opakovanost");
                
                exit;
            } 
            else {
                // Nějaká jiná chyba
                // Můžete zde zobrazit chybovou hlášku uživateli
                $this->pridejZpravu("Chyba při editaci opakovanost.");
                
                $this->presmeruj("opakovanost");
                exit;   
            } 
            }
            else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['smazat']))   {
            
                
    
                $smazOpak= $modelyOpak->odeberOpak($_POST['smazana_opak_id']);
    
                if ($smazOpak === 1) {
                    // Sport byl úspěšně editován
                    $this->pridejZpravu("Opakovanost byl úspěšně smazán.");
                    $this->presmeruj("opakovanost");
                    
                    exit;
                } 
                else {
                    // Nějaká jiná chyba
                    // Můžete zde zobrazit chybovou hlášku uživateli
                    $this->pridejZpravu("Chyba při smazání opakovanost.");
                    
                    $this->presmeruj("opakovanost");
                    exit;   
                } 
                }

          $this->pohled = "opakovanost";

        $opak=$modelyOpak->vratVsechnyOpak();
        $this->data["opak"] = $opak;

       
        
        
    }
}