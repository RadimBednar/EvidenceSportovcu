
<?php
class AdmineditaceKontroler extends Kontroler {   
    public function zpracuj($parametry) {
        

        // Instance modelu
        $modelUzivatel = new ModelyUzivatel();

        // Zpracování požadavku na přidání nového uživatele
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pridatUzivatele'])) {
            // Zpracování dat z formuláře
            
            $idUziv = $modelUzivatel->vratPosledniId()+1;

            $data = array(
                'id_uziv' => $idUziv,
                'email' => $_POST['email'],
                'opravneni' => 0,
                'id_trid' =>  $_POST['id_trid'],
                'jmeno' => $_POST['jmeno'],
                'prijmeni' => $_POST['prijmeni'],
                'isic' => $_POST['isic'],
                'dat_nar' => $_POST['dat_nar'],
                'pohlavi' => $_POST['pohlavi']
                // doplňte další pole podle formuláře
            );
               
            // Zavolání metody pro přidání uživatele
            $uspesnost = $modelUzivatel->pridejStudenta($data);

            // Zpracování výsledku
            if ($uspesnost == 1) {
                // Uživatel byl úspěšně přidán
                // přesměrování na nějakou stránku
                //header("Location: nejaka_stranka.php");
                //exit();
                $this->pridejZpravu( "Uživatel byl úspěšně přidán.");
            } elseif ($uspesnost == 0) {
                // Uživatel již existuje
                $this->pridejZpravu("Uživatel již existuje.") ;
                var_dump( $data);
            } elseif ($uspesnost == 2) {
                // Uživatel má neplatné oprávnění
                $this->pridejZpravu("Neplatné oprávnění.");
            }
        }

        // Zpracování požadavku na odstranění uživatele
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['odstranitUzivatele'])) {
            $email = $_POST['email'];

            $infoUzivatele = $modelUzivatel-> vratInfoPodleEmailu($email);

            // Zavolání metody pro odebrání uživatele
            $uspesnost = $modelUzivatel->odeberUzivatele($infoUzivatele['email']);

            // Zpracování výsledku
            if ($uspesnost == 1) {
                // Uživatel byl úspěšně odebrán
                // přesměrování na nějakou stránku
                //header("Location: nejaka_stranka.php");
                //exit();
                echo "Uživatel byl úspěšně odebrán.";
            } else {
                // Něco se nepovedlo
                echo "Chyba při odstraňování uživatele.";
            }
        }
        $this->pohled = 'admineditace';
    }
}
?>