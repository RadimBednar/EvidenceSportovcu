<?php
class SportovciKontroler extends Kontroler {
    public function zpracuj($parametry) {

        $modelySportuje = new ModelySportuje();
        $modelyUzivatel = new ModelyUzivatel();
        $modelyDisciplin = new ModelyDisciplina();
        $modelyPozice = new ModelyPozice();
        $modelyUroven = new ModelyUroven();




//--------------------------------- Session ------------------------------------------------------------------

        // Zkontroluj, zda je uživatel přihlášen
        if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
            $this->data['session']['opravneni'] = null;
           
        }

        else {
    
            // Získání emailu přihlášeného uživatele z session
            $emailUzivatele = $_SESSION['email'];
        
            // Získání informací o přihlášeném uživateli z databáze
            $uzivatelInfo = $modelyUzivatel->vratInfoPodleEmailu($emailUzivatele);
        
            // Kontrola, zda byl uživatel nalezen v databázi
            
            if ($uzivatelInfo) {
                $this->data['session'] = $uzivatelInfo; 
            }
        }

//--------------------------------- Session ------------------------------------------------------------------









    // Delete záznamu
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['smazat'])) {
        $radek = $_POST['radek'];
        var_dump($radek);
        $smazSportuje= $modelySportuje->odeberSportuje($radek);

        if ($smazSportuje === 1) {
            //Úspěch
            $this->pridejZpravu("Záznam byl úspěšně smazán.");
            header("Refresh:0"); 
               
        } 
        else {
            // Nějaká jiná chyba
            // Můžete zde zobrazit chybovou hlášku uživateli
            $this->pridejZpravu("Chyba při smazání záznamu."); 
        } 
    }

    // Zobrazení profilu
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['zobrazit'])) {
        $uzivatelId = $_POST['uziv'];
        // Assuming you have a method to retrieve user profile data by ID
        $profilUzivatele = $modelyUzivatel->projedVsechnyuzivatele($uzivatelId);
        // Do something with $profilUzivatele, such as passing it to a view
    }
    
    $this->data["sportovci"] = $modelySportuje->vratVsechySportuje();
    $this->data["sportuje"] = $modelyUzivatel->vratVsechnyUzivatele();
    $this->data["disc"] = $modelyDisciplin->vratVsechnyDiscipliny();
    $this->data["poz"] = $modelyPozice->vratVsechnyPozice();
    $this->data["urov"] = $modelyUroven->vratVsechnyUroven();
    $this->pohled = "sportovci";
    }
}