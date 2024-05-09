<?php
class AkceKontroler extends Kontroler {
    public function zpracuj($parametry) {

        $idTetoAkce=$_GET['ia'];

        $this->data["ia"]= $_GET['ia'];

        $modelAkce= new ModelyAkce;
        $modelAkcedisc = new ModelyAkce_disc;
        $modelDisciplin = new ModelyDisciplina;
        $modelSoupiska  = new ModelySoupiska;
        $modelUcastnici = new ModelyUcastnik();
        $modelUzivatele = new ModelyUzivatel();
        $modelKolo = new ModelyKolo();

//--------------------------------- Session ------------------------------------------------------------------

        // Zkontroluj, zda je uživatel přihlášen
        if (!isset($_SESSION['loggedIn']) || !$_SESSION['loggedIn']) {
            $this->data['session']['opravneni'] = null;
           
        }

        else {
    
            // Získání emailu přihlášeného uživatele z session
            $emailUzivatele = $_SESSION['email'];
        
            // Získání informací o přihlášeném uživateli z databáze
            $uzivatelInfo = $modelUzivatele->vratInfoPodleEmailu($emailUzivatele);
        
            // Kontrola, zda byl uživatel nalezen v databázi
            
            if ($uzivatelInfo) {
                $this->data['session'] = $uzivatelInfo; 
            }
        }

//--------------------------------- Session ------------------------------------------------------------------
    

        
        $akce=$modelAkce->vratVsechnyAkce();
        foreach($akce as $a){
            if($idTetoAkce == $a["id_akce"]){
                $konkretniAkce= [
                    'id_akce' => $a["id_akce"],
                    'nazev_akce' => $a['nazev_akce'],
                    'datum_zahajeni' => $a['datum_zahajeni'],
                    'datum_konce' => $a['datum_konce'],
                    'misto_kon' => $a['misto_kon'],
                    'popisek_akce' => $a['popisek_akce'],
                    'pritomni_uc' => $a['pritomni_uc'],
                    'archivovano' => $a['archivovano'],
                    'shrnuti' => $a['shrnuti'],
                    'poradatel' => $a['poradatel'],
                    'id_opak' => $a['id_opak'],
                    'id_kolo' => $a['id_kolo'],
                ];
            }
        }

        $ciselniky =  $modelAkce->vratCiselniky($konkretniAkce['id_akce']);
        if($ciselniky !== null){
        $konkretniAkce['nazev_opak'] = $ciselniky['nazev_opak'];
        $konkretniAkce['nazev_kolo'] = $ciselniky['nazev_kolo'];
        }
        else {
            $konkretniAkce['nazev_opak'] = null;
            $konkretniAkce['nazev_kolo'] = null;
        }


//Edit ---------------------------------------------------------------------------------------------------------------


        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ulozit'])) {
            
        //pridani akce_disc --------------------------------------------------------------------------------

            if (isset($_POST['pridat_id_disc']) && is_array($_POST['pridat_id_disc'])) { 
                $disc = $_POST['pridat_id_disc'];
                //zjištění podledního ID v tabulce
                $akce_discId = $modelAkcedisc->vratPosledniId()+1;
                //Průchod pole
                foreach ($disc as $selectedDisc) {
                    $akcedisc = [
                        'id_akce_disc' => $akce_discId++,
                        'id_akce' => $idTetoAkce,
                        'id_disc' => $selectedDisc,
                        // Další potřebné údaje
                        ];

                    //pridani zaznamu do databaze(akce_disc), poté následné zajištění nastavení ID
                    $pridejAkcedisc= $modelAkcedisc->pridejAkce_disc($akcedisc);

                    if ($pridejAkcedisc === 1) {
                        // Záznam byl úspěšně přidán
                        $this->pridejZpravu("Záznam byl úspěšně přidán.");
                    } else if ($pridejAkcedisc === 0) {
                        // Záznam již existuje
                        $this->pridejZpravu("Záznam již existuje!");
                    }else {
                        // Nějaká jiná chyba
                        // Můžete zde zobrazit chybovou hlášku uživateli
                        $this->pridejZpravu("Chyba při přidání záznamu.");
                    }
                }
            }

//edit akce --------------------------------------------------------------------------------
                
                $hodnoty= [
                    'nazev_akce' => $_POST['nazev_akce'],
                    'datum_zahajeni' => $_POST['datum_zahajeni'],
                    'datum_konce' => $_POST['datum_konce'],
                    'misto_kon' => $_POST['misto_kon'],
                    'popisek_akce' => $_POST['popisek_akce'],
                    'poradatel' => $_POST['poradatel'],
                    'id_opak' => $_POST['id_opak'],
                    'id_kolo' => $_POST['id_kolo'],
                    // Další potřebné údaje
                ];
    
                $editAkce= $modelAkce->zmenAkci($hodnoty, $idTetoAkce);
    
                if ($editAkce == 1) {
                    // Záznam byl úspěšně editován
                    $this->pridejZpravu("Záznam byla úspěšně editována.");    
                    header("Refresh:0");                

                } 
                else if ($editAkce == 2) {
                    // Záznam byl úspěšně editován
                    $this->pridejZpravu("Záznam byl zachován.");    
                    header("Refresh:0");                

                } 
                
                else {
                    // Nějaká jiná chyba
                    // Můžete zde zobrazit chybovou hlášku uživateli
                    $this->pridejZpravu("Chyba při editaci záznamu."); 
                } 
                //header("Refresh:0");

            }  

//Delete Disciplín (pres checkboxy)--------------------------------------------------------------------------------------------------

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cb_smazat'])) {
            for($i = 0 ;$i<count($_POST['selected_disc']);$i++){

                $smazAkcedisc= $modelAkcedisc->odeberAkce_disc($_POST['selected_disc'][$i]);

                if ($smazAkcedisc === 1) {
                    // Sport byl úspěšně editován
                    $this->pridejZpravu("Záznamu byl úspěšně smazán.");
                } 
                else {
                    // Nějaká jiná chyba
                    // Můžete zde zobrazit chybovou hlášku uživateli
                    $this->pridejZpravu("Chyba při smazání záznamu.");
                    exit;   
                } 
            }
            //header("Refresh:0");    
        }

//Delete akce i archivu------------------------------------------------------------------------------------------------------------------        
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cele_smazat'])) {
            $smazAkce= $modelAkce->odeberAkci($idTetoAkce);

            if ($smazAkce === 1) {
                //Úspěch
                $this->pridejZpravu("Záznam byl úspěšně smazán.");
                $this->presmeruj("vypisakci");
                exit;   
            } 
            else {
                // Nějaká jiná chyba
                // Můžete zde zobrazit chybovou hlášku uživateli
                $this->pridejZpravu("Chyba při smazání záznamu."); 
                header("Refresh:0"); 
            } 
        }

       

        
        
    

        $this->data["konkretniAkce"] = $konkretniAkce;
        
        $discipliny =  $modelAkce->vratVsechnyDisciplinyAkce($konkretniAkce['id_akce']);
        $this->data["discipliny"] = $discipliny;

        $soupisky =  $modelAkce->vratVsechnySoupiskyAkce($konkretniAkce['id_akce']);
        $this->data["soupisky"] = $soupisky;

        $vyber_disc =  $modelAkce->vyberDisciplinMimoAkci($konkretniAkce['id_akce']);
        $this->data["vyber_disciplin"] = $vyber_disc;

        $disc=$modelDisciplin->vratVsechnyDiscipliny();
        $this->data["disc"] = $disc; 

        $soup=$modelSoupiska->vratVsechnySoupisky();
        $this->data["soup"] = $soup;

        $ucast = $modelUcastnici->vratVsechnyUcastniky();
        $this->data["ucastnici"] = $ucast;

        $uziv = $modelUzivatele->vratVsechnyUzivatele();
        $this->data["uzivatele"] = $uziv;


        $modelDiscUcast = new ModelyDisc_ucast();
        $discucast = $modelDiscUcast->vratVsechnyDisc_ucast();
        $this->data["du"] = $discucast;

        $modelOpak = new ModelyOpakovanost();
        $opak = $modelOpak->vratVsechnyOpak();
        $this->data["opak"] = $opak;

        
        $kolo = $modelKolo->vratVsechnyKolo();
        $this->data["kolo"] = $kolo;


        $this->pohled = "akce";
    }
}
