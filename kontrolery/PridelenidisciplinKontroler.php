<?php
class PridelenidisciplinKontroler extends Kontroler  {

    public function zpracuj($parametry){

        $modelyUzivatelu= new ModelyUzivatel;
        $modelyDisciplin= new ModelyDisciplina;
        $modelySportuje = new ModelySportuje;
        $modelyPozice= new ModelyPozice;
        $modelyUroven= new ModelyUroven;
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pridej'])) {
            // Zpracujte data z formuláře
           // $id_sportuje = $_POST['id_sportuje'];
            $id_disc = $_POST['id_disc'];
            $email = $_POST['email'];
            $pozice = isset($_POST['id_poz']) ? $_POST['id_poz'] : null;
            $tym = isset($_POST['tym']) ? $_POST['tym'] : null;
            $uroven = isset($_POST['id_urov']) ? $_POST['id_urov'] : null;
            
             

            // Validace a další zpracování dat, pokud je to potřeba

            // Volání metody pro přidání hodnot do databáze
             // Upravte název modelu podle vaší implementace
            $result = $modelySportuje->pridejSportuje([
               // 'id_sportuje' => $id_sportuje,
                'id_disc' => $id_disc,
                'email' => $email,
                'id_poz' => $pozice,
                'tym' => $tym,
                'id_urov' => $uroven,
                'rekord' => $_POST['rekord'],
            ]);

            // Zpracování výsledku a přesměrování nebo zobrazení odpovědi uživateli
            if ($result === 1) {
                // Přidání úspěšné
                $this->pridejZpravu("Sportovec byl úspěšně přidán.");
                $this->presmeruj("pridelenidisciplin");
                
                exit;
            }
            else if($result === 1){
                $this->pridejZpravu("Záznam již existuje!");
                $this->presmeruj("pridelenidisciplin");
            }
            else {
                // Chyba při přidávání
                $this->pridejZpravu("Chyba při přiřazení");
                $this->presmeruj("pridelenidisciplin");
            }
        } else {
            // Formulář nebyl odeslán, můžete provést další akce, pokud jsou potřeba
           /* $this->pridejZpravu("Formulář nebyl odeslán");
                $this->presmeruj("pridelenidisciplin");*/
        }
        
       
       
    
      
        $this->pohled="pridelenidisciplin";
        $this->data["studenti"]= $modelyUzivatelu->vratVsechnyStudenty();
        $this->data["disc"]= $modelyDisciplin->vratVsechnyDiscipliny();
        $this->data["pozice"] = $modelyPozice->vratVsechnyPozice();
        $this->data["uroven"] = $modelyUroven->vratVsechnyUroven();

    }

        
        
    }
