<?php
class VypisAkciKontroler extends Kontroler {
    public function zpracuj($parametry) {
          //  session_start();




            $modelAkce= new ModelyAkce;
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

                  $serazeniucast = ModelyUzivatel::serazeniNaAkciPodleUcasti($_SESSION ['email']);
                  if($serazeniucast === 0){
                    $this->data["serazeniUcast"]= [];
                  }else  $this->data["serazeniUcast"]= $serazeniucast;


                $serazenizajem = ModelyUzivatel::serazeniNaAkciPodleZajmu($_SESSION ['email']);
                $this->data["serazeniZajem"] = [];
                  if( $serazenizajem === 0)$this->data["serazeniZajem"] = [];
                else  $this->data["serazeniZajem"] = $serazenizajem;
               
              }
      
      //--------------------------------- Session ------------------------------------------------------------------
    
    
            $this->pohled = "vypisakci";
    
            $akce=$modelAkce->vratVsechnyAkce();
            $this->data["akce"] = $akce; 


            
            
            
            

    


    }
}