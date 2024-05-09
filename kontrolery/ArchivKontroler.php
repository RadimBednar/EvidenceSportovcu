<?php


class ArchivKontroler extends Kontroler
{
    public function zpracuj($parametry)
    {


        $idTentoArchiv = $_GET['iar'];

        $this->data['air']=$_GET['iar'];

      //  $idTetoSoup=$_GET['is'];

        $modelAkce = new ModelyAkce;
        $modelAkcedisc = new ModelyAkce_disc;
        $modelDisciplin = new ModelyDisciplina;
        $modelUzivatel = new ModelyUzivatel();
        $modelSoupiska = new ModelySoupiska();
        $modelUcastnik = new ModelyUcastnik;
        $modelDiscUcast = new ModelyDisc_ucast();
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
                    $uzivatelInfo = $modelUzivatel->vratInfoPodleEmailu($emailUzivatele);
                
                    // Kontrola, zda byl uživatel nalezen v databázi
                    
                    if ($uzivatelInfo) {
                        $this->data['session'] = $uzivatelInfo; 
                    }
                }
        
        //--------------------------------- Session ------------------------------------------------------------------
    
        

        $archiv = $modelAkce->vratVsechnyAkce();
        $soupiska = $modelSoupiska->vratVsechnySoupisky();
        $ucastnici = $modelUcastnik->vratVsechnyUcastniky();
        $uzivatele = $modelUzivatel->vratVsechnyUzivatele();
        $discucast = $modelDiscUcast->vratVsechnyDisc_ucast();

        foreach ($archiv as $ar) {

            if ($idTentoArchiv == $ar["id_akce"]) {
                $konkretniarchiv = [
                    'id_akce' => $ar["id_akce"],
                    'nazev_akce' => $ar['nazev_akce'],
                    'datum_zahajeni' => $ar['datum_zahajeni'],
                    'datum_konce' => $ar['datum_konce'],
                    'misto_kon' => $ar['misto_kon'],
                    'popisek_akce' => $ar['popisek_akce'],
                    'pritomni_uc' => $ar['pritomni_uc'],
                    'shrnuti' =>$ar['shrnuti'],
                    'poradatel' =>$ar["poradatel"],
                    'id_opak' => $ar["id_opak"],
                    'id_kolo' => $ar['id_kolo'],

                ];
            }
        }

     /*  foreach ($soupiska as $soup) {
            $konkretnisoupiska=null;
            if ($idTentoArchiv == $soup["id_akce"]) {
                $konkretnisoupiska = [
                    'id_soup' => $soup["id_soup"],
                    'id_akce' => $soup['id_akce'],
                    'nazev_skupiny' => $soup['nazev_skupiny'],
                    'vys_s' => $soup['vys_s'],
                    


                ];
            }
        }
     /*   $konkretnisoupiska=null;
        $soup=$modelSoupiska->vratVsechnySoupisky();
        foreach($soup as $s){

            if(!($idTetoSoup == $s["id_soup"])){
                continue;
            }
            $konkretnisoupiska= [
                'id_soup' => $s['id_soup'],
                'id_akce' => $s['id_akce'],
                'nazev_skupiny' => $s['nazev_skupiny'],
                'vys_s' => $s['vys_s'],

            ];
        }
*/
//Edit ---------------------------------------------------------------------------------------------------------------


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Byl odeslán formulář

            $archivovano=1;

            if (isset($_POST['archivovat'])) {
                // Zpracování uložení
                $hodnotyAkce= [
                    'pritomni_uc' => $_POST['pritomni_uc'],
                    'archivovano' => $archivovano,
                    'shrnuti' => $_POST['shrnuti'],
                    
                    // Další potřebné údaje
                ];

                $editAkce= $modelAkce->zmenAkci($hodnotyAkce, $idTentoArchiv);



                foreach ($_POST['vys_s'] as $idSoupisky => $vysS) {
                    // Získání hodnoty vys_u a volání metody pro změnu účastníka

                    //  var_dump($vysU);
                    $hodnotySoupisky= [
                        'vys_s' => $vysS,
                    ];

                    $editSoupiska = $modelSoupiska->zmenSoupisku($hodnotySoupisky,$idSoupisky);



                    // Pro každého účastníka by měl být prověřen výsledek operace
                    if ($editSoupiska == 1) {
                        // Něco se nepodařilo, můžete provést odpovídající akci nebo vyvolat chybu
                        //$this->pridejZpravu("Provedeno pro $idSoupisky");
                    }
                    else{
                        $this->pridejZpravu("Chyba při aktualizaci účastníka s ID $idSoupisky");
                    }
                }


                    
                

                foreach ($_POST['vys_u'] as $idUcastnika => $vysU) {
                    // Získání hodnoty vys_u a volání metody pro změnu účastníka

                  //  var_dump($vysU);
                    $hodnotyUcastnika = [
                        'vys_u' => $vysU,
                        // Další údaje z formuláře nebo jinak potřebné údaje
                    ];

                    
                    
                    

                    $editUcastnik = $modelUcastnik->zmenUcastnika($hodnotyUcastnika, $idUcastnika);

                    // Pro každého účastníka by měl být prověřen výsledek operace
                    if ($editUcastnik == 1) {
                        // Něco se nepodařilo, můžete provést odpovídající akci nebo vyvolat chybu
                        $this->pridejZpravu("Provedeno pro $idUcastnika");
                    }
                    else{
                        $this->pridejZpravu("Chyba při aktualizaci účastníka s ID $idUcastnika");
                    }
                }


                if($editSoupiska == 1 && $editAkce == 1 && $editUcastnik == 1){
                    $this->pridejZpravu("Záznamu byl úspěšně archivován.");
                    $this->pohled="vypisakci";
                }
                else{
                    $this->pridejZpravu("Záznamu nebyl archivován");
                }



                
            }






            $this->presmeruj("vypisakci");
        }


        $this->data["konkretniarchiv"] = $konkretniarchiv;

        
        
          //  $this->data["konkretnisoupiska"] = $konkretnisoupiska;

            $this->data["uzivatele"] = $uzivatele;

            $this->data["du"] = $discucast;
        
        
        

        $akcedisc = $modelAkcedisc->vratVsechnyAkce_disc();
        $this->data["akcedisc"] = $akcedisc;

        $disc = $modelDisciplin->vratVsechnyDiscipliny();
        $this->data["disc"] = $disc;

        $this->data["ucitele"] = $modelUzivatel->vratInfoVsechUcitelu();
        $this->data["ucastnici"] = $ucastnici;


        $modelOpak = new ModelyOpakovanost();
        $opak = $modelOpak->vratVsechnyOpak();
        $this->data["opak"] = $opak;

        
        $kolo = $modelKolo->vratVsechnyKolo();
        $this->data["kolo"] = $kolo;


        $this->pohled = "archiv";
    }
}
