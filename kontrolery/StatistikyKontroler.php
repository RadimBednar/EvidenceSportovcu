<?php
use Mpdf\Mpdf;
class StatistikyKontroler extends Kontroler {
    public function zpracuj($parametry) {




        $modelAkce= new ModelyAkce;
        $modelAkcedisc = new ModelyAkce_disc;
        $modelDisciplin = new ModelyDisciplina;
        $modelSoupiska  = new ModelySoupiska;
        $modelUcastnici = new ModelyUcastnik();
        $modelUzivatele = new ModelyUzivatel();

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
        $this->data["akce"]=$akce;

        $akcedisc =  $modelAkcedisc->vratVsechnyAkce_disc();
        $this->data["akcedisc"] = $akcedisc;

        $disc=$modelDisciplin->vratVsechnyDiscipliny();
        $this->data["disc"] = $disc; 

        $soup=$modelSoupiska->vratVsechnySoupisky();
        $this->data["soup"] = $soup;

        $ucast = $modelUcastnici->vratVsechnyUcastniky();
        $this->data["ucast"] = $ucast;

        $uziv = $modelUzivatele->vratVsechnyUzivatele();
        $this->data["uzivatele"] = $uziv;


        $modelDiscUcast = new ModelyDisc_ucast();
        $discucast = $modelDiscUcast->vratVsechnyDisc_ucast();
        $this->data["du"] = $discucast;

        $modelOpak = new ModelyOpakovanost();
        $opak = $modelOpak->vratVsechnyOpak();
        $this->data["opak"] = $opak;

        $modelKolo = new ModelyKolo();
        $kolo = $modelKolo->vratVsechnyKolo();
        $this->data["kolo"] = $kolo;


        //---------------------------PDF-----------------------------------//
        if (isset($_POST['genPDF'])) {
            // Váš kód pro generování PDF zde...
    
            // Vytvoření PDF pomocí Mpdf
            $mpdf = new \Mpdf\Mpdf();
            $mpdf->debug = true;
            $mpdf->WriteHTML('<h1>Vaše PDF</h1>');
            // ... (zbytek kódu pro generování PDF)
            $htmlTabulka1 = '
            <h1>Statistiky</h1>
            <table border="1">
                <tr>
                    <th>Název akce</th>
                    <th>Disciplíny</th>
                    <th>Počet účastněných tříd</th>
                    <th>Třídy</th>
                </tr>';
    
        // Projdi všechny akce
        foreach ($this->data["akce"] as $akceItem) {
            // Přidejte podmínku pro filtrování archivovaných akcí
            if ($akceItem['archivovano'] == 1) {
                // Přidejte podmínku pro filtrování akcí podle datum_zahajeni
                $datumZahajeni = strtotime($akceItem['datum_zahajeni']);
                $aktualniRokZahajeni = date('Y', $datumZahajeni);
    
                // Přidejte podmínku pro filtrování akcí podle datum_konce
                $datumKonce = strtotime($akceItem['datum_konce']);
                $aktualniRokKonce = date('Y', $datumKonce);
    
                // Kontrola, zda se v datum_zahajeni a datum_konce nachází aktuální rok
                if ($aktualniRokZahajeni == date('Y') && $aktualniRokKonce == date('Y')) {
                    // Inicializuj pole pro aktuální akci
                    $pocetUcastnenychTridNaAkci[$akceItem['id_akce']] = 0;
    
                    // Inicializuj pole pro sledování již zaznamenaných tříd
                    $zaznamenaneTridy = array();
    
                    // Pole pro uchování konkrétních tříd na dané akci
                    $ucastneneTridyNaAkci = array();
    
                    // Pole pro uchování disciplín akce bez duplicit
                    $disciplinyNaAkci = array();
    
                    // Projdi všechny účastníky
                    foreach ($this->data["ucast"] as $ucastnik) {
                        // Zkontroluj, jestli účastník je přítomen na aktuální akci
                        if ($ucastnik['id_soup'] == $akceItem['id_akce']) {
                            // Získáme ID uživatele a pomocí něj získáme ID třídy
                            $emailUzivatele = $ucastnik['email'];
    
                            // Najdeme uživatele v seznamu všech uživatelů
                            foreach ($this->data["uzivatele"] as $uzivatel) {
                                if ($uzivatel['email'] == $emailUzivatele) {
                                    $idTridy = $uzivatel['id_trid'];
    
                                    // Pokud třída ještě není zaznamenána, inkrementujeme počet účastněných tříd a přidáme do pole
                                    if (!in_array($idTridy, $zaznamenaneTridy)) {
                                        $zaznamenaneTridy[] = $idTridy;
                                        $ucastneneTridyNaAkci[] = $idTridy;
                                        $pocetUcastnenychTridNaAkci[$akceItem['id_akce']]++;
                                    }
                                    break;  // Ukončíme smyčku, protože jsme našli odpovídajícího uživatele
                                }
                            }
    
                            // Projdi všechny disciplíny spojené s akcí
                            foreach ($this->data["akcedisc"] as $akcediscItem) {
                                if ($akcediscItem['id_akce'] == $akceItem['id_akce']) {
                                    // Najdeme disciplínu v seznamu všech disciplín
                                    foreach ($this->data["disc"] as $discItem) {
                                        if ($discItem['id_disc'] == $akcediscItem['id_disc'] && !in_array($discItem['nazev_disc'], $disciplinyNaAkci)) {
                                            $disciplinyNaAkci[] = $discItem['nazev_disc'];
                                            break;  // Ukončíme smyčku, protože jsme našli odpovídající disciplínu
                                        }
                                    }
                                }
                            }
                        }
                    }
    
                    // Výpis do tabulky
                    $htmlTabulka1 .= "<tr>";
                    $htmlTabulka1 .= "<td>{$akceItem['nazev_akce']}</td>";
                    $htmlTabulka1 .= "<td>" . implode(", ", $disciplinyNaAkci) . "</td>";
                    $htmlTabulka1 .= "<td>{$pocetUcastnenychTridNaAkci[$akceItem['id_akce']]}</td>";
                    $htmlTabulka1 .= "<td>" . implode(", ", $ucastneneTridyNaAkci) . "</td>";
                    $htmlTabulka1 .= "</tr>";
                }
            }
        }
    
        $htmlTabulka1 .= '
            </table>
        ';
    
    
    
    
        // HTML obsah pro druhou tabulku
        $htmlTabulka2 = '
        <h1>Statistiky podle soutěže</h1>
        <table border="1">
            <tr>
                <th>Kolo</th>
                <th>Počet účastněných tříd</th>
                <th>Třídy</th>
            </tr>
        ';
        // Projdi všechna kola
        foreach ($this->data["kolo"] as $koloItem) {
            // Inicializuj pole pro aktuální kolo
            $pocetUcastnenychTridNaKolo[$koloItem['nazev_kolo']] = 0;
    
            // Inicializuj pole pro sledování již zaznamenaných tříd
            $zaznamenaneTridyKola = array();
    
            // Pole pro uchování konkrétních tříd na dané akci
            $ucastneneTridyNaKolo = array();
    
            // Projdi všechny akce
            foreach ($this->data["akce"] as $akceItem) {
                if ($akceItem['archivovano'] == 1) {
    
                
                // Zjisti kolo aktuální akce
                if ($akceItem['id_kolo'] == $koloItem['id_kolo']) {
                    // Projdi všechny účastníky
                    foreach ($this->data["ucast"] as $ucastnik) {
                        // Zkontroluj, jestli účastník je přítomen na aktuální akci
                        if ($ucastnik['id_soup'] == $akceItem['id_akce']) {
                            // Získáme ID uživatele a pomocí něj získáme ID třídy
                            $emailUzivatele = $ucastnik['email'];
    
                            // Najdeme uživatele v seznamu všech uživatelů
                            foreach ($this->data["uzivatele"] as $uzivatel) {
                                if ($uzivatel['email'] == $emailUzivatele) {
                                    $idTridy = $uzivatel['id_trid'];
    
                                    // Pokud třída ještě není zaznamenána, inkrementujeme počet účastněných tříd a přidáme do pole
                                    if (!in_array($idTridy, $zaznamenaneTridyKola)) {
                                        $zaznamenaneTridyKola[] = $idTridy;
                                        $ucastneneTridyNaKolo[] = $idTridy;
                                        $pocetUcastnenychTridNaKolo[$koloItem['nazev_kolo']]++;
                                    }
                                    break;  // Ukončíme smyčku, protože jsme našli odpovídajícího uživatele
                                }
                            }
                        }
                    }
                }
                }
            }
    
            // Výpis do tabulky
            $htmlTabulka2 .= '<tr>';
            $htmlTabulka2 .= '<td>' . $koloItem['nazev_kolo'] . '</td>';
            $htmlTabulka2.= '<td>' . $pocetUcastnenychTridNaKolo[$koloItem['nazev_kolo']] . '</td>';
            $htmlTabulka2 .= '<td>' . implode(", ", $ucastneneTridyNaKolo) . '</td>';
            $htmlTabulka2 .= '</tr>';
        }
    
        $htmlTabulka2 .= '
            </table>
        ';
    
        // HTML obsah pro třetí tabulku
        $htmlTabulka3 = '
        <h1>Doprovázející učitelé</h1>
        <table border="1">
            <tr>
                <th>Jméno</th>
                <th>Počet zastoupení</th>
            </tr>
        ';
        // Inicializuj pole pro sledování již zaznamenaných učitelů
        $zaznamenaniUcitele = array();
    
        // Projdi všechny akce
        foreach ($this->data["akce"] as $akceItem) {
            // Získáme přítomné učitele ve formátu řetězce
            if ($akceItem['archivovano'] == 1) {
            $pritomniUcitele = $akceItem['pritomni_uc'];
    
            // Rozdělíme řetězec na jednotlivé položky (jména učitelů) pomocí čárky
            $ucitele = explode(",", $pritomniUcitele);
    
            // Projdi všechny učitele
            foreach ($ucitele as $jmenoUcitele) {
                // Ořízneme případné mezery kolem jména
                $jmenoUcitele = trim($jmenoUcitele);
    
                // Pokud je jméno neprázdné, aktualizujeme počet zastoupení
                if (!empty($jmenoUcitele)) {
                    // Pokud učitel ještě není zaznamenán, inicializujeme jeho počet zastoupení na 1, jinak inkrementujeme
                    if (!isset($zaznamenaniUcitele[$jmenoUcitele])) {
                        $zaznamenaniUcitele[$jmenoUcitele] = 1;
                    } else {
                        $zaznamenaniUcitele[$jmenoUcitele]++;
                    }
                }
            }
        }
        }
    
    
        // Výpis do tabulky
        foreach ($zaznamenaniUcitele as $jmeno => $pocetZastoupeni) {
            $htmlTabulka3 .= "<tr>
                <td>{$jmeno}</td>
                <td>{$pocetZastoupeni}</td>
            </tr>";
        }
        $htmlTabulka3 .= "</table>";
    
    
        // HTML obsah pro čtvrtou tabulku
        $htmlTabulka4 = '
        <h1>Nejlepší výsledky</h1>
        <table border="1">
            <tr>
                <th>Jméno účastníka</th>
                <th>Počet prvních míst</th>
                <th>Počet druhých míst</th>
                <th>Počet třetích míst</th>
            </tr>
        ';
        // Inicializuj proměnnou pro uchování počtu prvních míst pro každého účastníka
        $pocetPrvnichMist = array();
        $pocetDruhychMist = array();
        $pocetTretichMist = array();
        $jmenoUcastnika = "";
    
        // Projdi všechny akce s archivovaným stavem
        foreach ($this->data["akce"] as $akceItem) {
            if ($akceItem['archivovano'] == 1) {
                // Projdi všechny soupisky pro aktuální akci
                foreach ($this->data["soup"] as $soupiskaItem) {
                    if ($soupiskaItem['id_akce'] == $akceItem['id_akce']) {
                        // Získáme ID soupisky
                        $idSoupisky = $soupiskaItem['id_soup'];
    
                        // Projdi všechny účastníky přiřazené k soupisce
                        foreach ($this->data["ucast"] as $ucastnik) {
                            if ($ucastnik['id_soup'] == $idSoupisky) {
                                foreach ($this->data["uzivatele"] as $uziv) {
                                    if($ucastnik["email"]== $uziv["email"]) $jmenoUcastnika=$uziv['jmeno'] . " " . $uziv["prijmeni"];
                                }
                            
    
                                // Ořízneme případné mezery kolem výsledku
                                $vysledky = explode(",", $ucastnik['vys_u']);
    
                                // Inicializuj počet prvních míst pro aktuálního účastníka
                                $prvniM = 0;
                                $druheM = 0;
                                $tretiM = 0; 
    
                                // Projdi všechny výsledky
                                foreach ($vysledky as $vysledek) {
                                    // Ořízneme případné mezery kolem výsledku
                                    $vysledek = trim($vysledek);
    
                                    // Pokud se výsledek rovná "první místo", inkrementujeme počet prvních míst
                                    if (strcasecmp($vysledek, "první místo") == 0) {
                                        $prvniM++;
                                    }
                                    if (strcasecmp($vysledek, "druhé místo") == 0) {
                                        $druheM++;
                                    }
                                    if (strcasecmp($vysledek, "třetí místo") == 0) {
                                        $tretiM++;
                                    }
                                }
    
                                // Zaznamenáme počet prvních míst pro aktuálního účastníka
                                if (!isset($pocetPrvnichMist[$jmenoUcastnika])) {
                                    $pocetPrvnichMist[$jmenoUcastnika] = 0;
                                }
                                if (!isset($pocetDruhychMist[$jmenoUcastnika])) {
                                    $pocetDruhychMist[$jmenoUcastnika] = 0;
                                }
                                if (!isset($pocetTretichMist[$jmenoUcastnika])) {
                                    $pocetTretichMist[$jmenoUcastnika] = 0;
                                }
                                $pocetPrvnichMist[$jmenoUcastnika] += $prvniM;
                                $pocetDruhychMist[$jmenoUcastnika] += $druheM;
                                $pocetTretichMist[$jmenoUcastnika] += $tretiM;
                            }
                        }
                    }
                }
            }
        }
    
        // Výpis do tabulky
        foreach ($pocetPrvnichMist as $jmeno => $pocetPrvnichMist) {
            $htmlTabulka4 .= "<tr>
                <td>{$jmeno}</td>
                <td>{$pocetPrvnichMist}</td>
                <td>{$pocetDruhychMist[$jmeno]}</td>
                <td>{$pocetTretichMist[$jmeno]}</td>
            </tr>";
        }
        
    
        $htmlTabulka4 .= '
            </table>
        ';
    
    
        // HTML obsah pro pátou tabulku
        $htmlTabulka5 = '
        <h1>Nejlepší výsledky podle disciplín</h1>
        <table border="1">
            <tr>
                <th>Disciplina</th>
                <th>Počet prvních míst</th>
                <th>Počet druhých míst</th>
                <th>Počet třetích míst</th>
            </tr>
        ';
        // Inicializuj proměnné pro uchování počtu umístění pro každou disciplínu
        $pocetPrvnichMistDisc = array();
        $pocetDruhychMistDisc = array();
        $pocetTretichMistDisc = array();
    
        // Projdi všechny akce s archivovaným stavem
        foreach ($this->data["akce"] as $akceItem) {
            if ($akceItem['archivovano'] == 1) {
                // Projdi všechny disciplíny spojené s akcí
                foreach ($this->data["akcedisc"] as $akcediscItem) {
                    if ($akcediscItem['id_akce'] == $akceItem['id_akce']) {
                        // Najdeme disciplínu v seznamu všech disciplín
                        foreach ($this->data["disc"] as $discItem) {
                            if ($discItem['id_disc'] == $akcediscItem['id_disc']) {
                                $nazevDiscipliny = $discItem['nazev_disc'];
    
                                // Projdi všechny soupisky pro aktuální akci
                                foreach ($this->data["soup"] as $soupiskaItem) {
                                    if ($soupiskaItem['id_akce'] == $akceItem['id_akce']) {
                                        // Získáme ID soupisky
                                        $idSoupisky = $soupiskaItem['id_soup'];
    
                                        // Projdi všechny účastníky přiřazené k soupisce
                                        foreach ($this->data["ucast"] as $ucastnik) {
                                            if ($ucastnik['id_soup'] == $idSoupisky) {
                                                // Ořízneme případné mezery kolem výsledku
                                                $vysledky = explode(",", $ucastnik['vys_u']);
    
                                                // Inicializuj počet umístění pro aktuálního účastníka
                                                $prvniM = 0;
                                                $druheM = 0;
                                                $tretiM = 0;
    
                                                // Projdi všechny výsledky
                                                foreach ($vysledky as $vysledek) {
                                                    // Ořízneme případné mezery kolem výsledku
                                                    $vysledek = strtolower(trim($vysledek));
                                                
    
                                                    // Inkrementujeme počet umístění podle hodnoty výsledku
                                                    switch ($vysledek) {
                                                        case 'první místo':
                                                            $prvniM++;
                                                            break;
                                                        case 'druhé místo':
                                                            $druheM++;
                                                            break;
                                                        case 'třetí místo':
                                                            $tretiM++;
                                                            break;
                                                            case 'prvni misto':
                                                                $prvniM++;
                                                                break;
                                                            case 'druhe misto':
                                                                $druheM++;
                                                                break;
                                                            case 'tretí misto':
                                                                $tretiM++;
                                                                break;
                                                    }
                                                }
    
                                                // Zaznamenáme počet umístění pro aktuální disciplínu
                                                if (!isset($pocetPrvnichMistDisc[$nazevDiscipliny])) {
                                                    $pocetPrvnichMistDisc[$nazevDiscipliny] = 0;
                                                }
                                                $pocetPrvnichMistDisc[$nazevDiscipliny] += $prvniM;
    
                                                if (!isset($pocetDruhychMistDisc[$nazevDiscipliny])) {
                                                    $pocetDruhychMistDisc[$nazevDiscipliny] = 0;
                                                }
                                                $pocetDruhychMistDisc[$nazevDiscipliny] += $druheM;
    
                                                if (!isset($pocetTretichMistDisc[$nazevDiscipliny])) {
                                                    $pocetTretichMistDisc[$nazevDiscipliny] = 0;
                                                }
                                                $pocetTretichMistDisc[$nazevDiscipliny] += $tretiM;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    
        // Výpis do tabulky
        foreach ($pocetPrvnichMistDisc as $nazevDiscipliny => $pocetPrvnichMist) {
            $htmlTabulka5 .= "<tr>
                <td>{$nazevDiscipliny}</td>
                <td>{$pocetPrvnichMist}</td>
                <td>{$pocetDruhychMistDisc[$nazevDiscipliny]}</td>
                <td>{$pocetTretichMistDisc[$nazevDiscipliny]}</td>
            </tr>";
        }
    
        $htmlTabulka5 .= '
            </table>
        ';
    
        $htmlTabulka6 = '
        <h1>Nejčastěji zastoupené disciplíny</h1>
        <table border="1">
            <tr>
                <th>Název disciplíny</th>
                <th>Počet</th>
            </tr>
        ';
        // Inicializuj pole pro sledování výskytů disciplín
        $pocetVyskytuDisciplin = array();
    
        // Projdi všechny akce s archivovaným stavem
        foreach ($this->data["akce"] as $akceItem) {
            if ($akceItem['archivovano'] == 1) {
                // Projdi všechny disciplíny spojené s akcí
                foreach ($this->data["akcedisc"] as $akcediscItem) {
                    if ($akcediscItem['id_akce'] == $akceItem['id_akce']) {
                        // Najdeme disciplínu v seznamu všech disciplín
                        foreach ($this->data["disc"] as $discItem) {
                            if ($discItem['id_disc'] == $akcediscItem['id_disc']) {
                                $nazevDiscipliny = $discItem['nazev_disc'];
    
                                // Inkrementujeme počet výskytů disciplíny
                                if (!isset($pocetVyskytuDisciplin[$nazevDiscipliny])) {
                                    $pocetVyskytuDisciplin[$nazevDiscipliny] = 0;
                                }
                                $pocetVyskytuDisciplin[$nazevDiscipliny]++;
                            }
                        }
                    }
                }
            }
        }
    
        // Výpis do tabulky
        foreach ($pocetVyskytuDisciplin as $nazevDiscipliny => $pocetVyskytu) {
            $htmlTabulka6 .= "<tr>
                <td>{$nazevDiscipliny}</td>
                <td>{$pocetVyskytu}</td>
            </tr>";
        }
        $htmlTabulka6 .= '
            </table>
        ';
    
    
    
    
        // Vložte jednotlivé tabulky do PDF
        $mpdf->WriteHTML($htmlTabulka1);
        $mpdf->AddPage();
        $mpdf->WriteHTML($htmlTabulka2);
        $mpdf->AddPage();
        $mpdf->WriteHTML($htmlTabulka3);
        $mpdf->AddPage();
        /*$mpdf->WriteHTML($htmlTabulka4);
        $mpdf->AddPage();*/
        $mpdf->WriteHTML($htmlTabulka5);
        $mpdf->AddPage();
        $mpdf->WriteHTML($htmlTabulka6);
        
    
            // Uložení PDF do složky pdfs s názvem obsahujícím datum a čas
            $pdfFileName = __DIR__ . '/../pdfs/statistiky_' . date('dmY_Hi') . '.pdf';
          
          
          try {
            
             $mpdf->Output("statistiky". date('dmY_Hi').'.pdf', 'D');
             if (ob_get_level()) {
                ob_end_clean();
            }
        
        
    
            echo "PDF byl úspěšně vygenerován.";
        } catch (\Mpdf\MpdfException $e) {
            // Zachycení výjimky a zpracování chyby
            echo "Došlo k chybě při generování PDF: " . $e->getMessage();
        }
           ob_flush();
    
            //echo 'PDF vygenerováno';
        }

        
        

        $this->pohled = "statistiky";
    }
}