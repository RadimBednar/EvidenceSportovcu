<?php
class DisciplinaKontroler extends Kontroler {
    public function zpracuj($parametry) {

        $modelDisciplin= new ModelyDisciplina();
        $modelSporty= new ModelySport();
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
           
           
            $discipliny = [
                'id_sport' => $_POST['id_sport'],
                'nazev_disc' => $_POST['nazev_disc'],
               
            ];

            $pridejDisc= $modelDisciplin->pridejDisciplinu($discipliny);

            if ($pridejDisc === 1) {
                // Disciplina  byla úspěšně přidán
                $this->pridejZpravu("Disciplína byla úspěšně přidán.");
                $this->presmeruj("disciplina");
                
                exit;
            } else if ($pridejDisc === 0) {
                // Disciplína již existuje
                $this->pridejZpravu("Disciplína již existuje!");
                $this->presmeruj("disciplina");
                exit;
                
            } else {
                // Nějaká jiná chyba
                // Můžete zde zobrazit chybovou hlášku uživateli
                $this->pridejZpravu("Chyba při přidání disciplíny.");
                $this->presmeruj("disciplina");
                exit;
            }
        }
        else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ulozit']))   {
            
            $hodnoty= [
                'id_sport' => $_POST['id_sport'],
                'nazev_disc' => $_POST['novy_nazev_discipliny'],
                
            ];

            $editDisc= $modelDisciplin->zmenDisciplinu($hodnoty, $_POST['editovana_disciplina_id']);

            if ($editDisc === 1) {
                // Sport byl úspěšně editován
                $this->pridejZpravu("Disciplína byla úspěšně editována.");
                $this->presmeruj("disciplina");
                
                exit;
            } 
            else {
                // Nějaká jiná chyba
                // Můžete zde zobrazit chybovou hlášku uživateli
                $this->pridejZpravu("Chyba při editaci disciplíny.");
                
                $this->presmeruj("disciplina");
                exit;   
            } 
            }
            else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['smazat']))   {
            
                
    
                $smazDisc= $modelDisciplin->odeberDisciplinu($_POST['smazana_disciplina_id']);
            
    
                if ($smazDisc === 1) {
                    // Sport byl úspěšně editován
                    $this->pridejZpravu("Disciplína byl úspěšně smazán.");
                    $this->presmeruj("disciplina");
                    
                    exit;
                } 
                else {
                    // Nějaká jiná chyba
                    // Můžete zde zobrazit chybovou hlášku uživateli
                    $this->pridejZpravu("Chyba při smazání disciplíny.");
                    
                    $this->presmeruj("disciplina");
                    exit;   
                } 
                }
        


        $this->pohled = "disciplina";
        $discipliny=$modelDisciplin->vratVsechnyDiscipliny();
        $this->data["discipliny"] = $discipliny; 
        
        $sporty=$modelSporty->vratVsechnySporty();
        $this->data["sporty"] = $sporty; 
    }
}