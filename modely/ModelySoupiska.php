<?php
class ModelySoupiska{

  //vrati vsechno z tabulky "soupiska"
    public function vratVsechnySoupisky() {
        $sql = "
            SELECT *
            FROM soupiska
        ";
        $soupiska = Db::dotazVsechny($sql);
        return $soupiska;
    }

    //vrati posledni id v tabulce
    public function vratPosledniId() {
      $sql = "SELECT id_soup
              FROM soupiska
              ORDER BY id_soup DESC
              LIMIT 1";
      
      $soupiska = Db::dotazJeden($sql);
      if ($soupiska === false) return 0;
      else return $soupiska['id_soup'];
    }

      /*slouzi k pridani záznamu do databaze, parametr bude pole, vyzadujici:
    id_soup 
    id_akce
    nazev_skupiny
    *vys_s
    
    * - nepovinny atribut
    */
    public function pridejSoupisku($soupiska) {
      /*
        $sql = "
            SELECT id_soup
            FROM soupiska
            where id_soup = ?
        ";
        if(Db::dotazJeden($sql,[$soupiska["id_soup"]])){
        return 0;
        }*/
        $sql = "
            SELECT nazev_skupiny
            FROM soupiska
            where nazev_skupiny = ? and  id_akce = ?
        ";
        if(Db::dotazJeden($sql,[$soupiska["nazev_skupiny"], $soupiska["id_akce"]])){
          return 0;
        }
      Db::vloz("soupiska",$soupiska);
      return 1;
    }//vrati 0 pokud v uz databazi záznam uz je, vrati 1 pokud v databazi "soupiska" jeste neni a prida tam
      
      
    //slouzi k odebrani záznamu z databaze, parametrem bude jen id z databaze(id_soup)
    public function odeberSoupisku($id){
        $sql = "
          DELETE FROM soupiska
          where id_soup = ?
      ";
      if(Db::dotaz($sql,[$id])){
        return 1;
      }
    return 0;
    }//vrati 1 pokud v databazi soupisku odebere, 0 pokud se akce nepodarila


    /*slouzi ke zmene sloupcu v tabulce "soupiska", parametry:
    $hodnoty - pole asociativni pro nazev sloupcu a jeji nove hodnoty ["id_akce"] => 3
                                                                      ["nazev_skupiny"] => "Skupina A"
                                                                      ["vys_s"] => 

    $id - id z databaze(id_soup), čili id konkretni soupisku
    */
    public function zmenSoupisku($hodnoty, $id){
      $sql = "
      where id_soup = ?
      ";

      $pocetZmenenychRadku = Db::zmen("soupiska",$hodnoty,$sql,[$id]);
    
      if ($pocetZmenenychRadku > 0) {
          // Pokud byla provedena alespoň jedna změna
          return 1;
      } elseif ($pocetZmenenychRadku === 0) {
          // Pokud nebyla provedena žádná změna
          return 2;
      } else {
          // Pokud nastala chyba při provádění změn
          return 0;
      }

      }//vrati 1 pokud v databazi uspesne provedl zmenu, 0 pokud se akce nepodarila 


      //metoda vraci vsechny ucastniky (jmeno, prijmeni, tridu a jejich discipliny), vyzaduje id_soup čili id soupisky
      public function vratVsechnyUcastnikyZeSoupisky($id_soup){
        $sql = "SELECT ucastnik.id_ucast ,uzivatel.jmeno, uzivatel.prijmeni, uzivatel.id_trid, disciplina.nazev_disc 
        FROM uzivatel INNER JOIN ucastnik USING (email) 
        INNER JOIN soupiska ON ucastnik.id_soup = soupiska.id_soup 
        INNER JOIN disc_ucast ON ucastnik.id_ucast = disc_ucast.id_ucast 
        inner JOIN disciplina on disc_ucast.id_disc = disciplina.id_disc 
        WHERE soupiska.id_soup = ? and ucastnik.potrvzeni = 1";
    
        $ucastnci = Db::dotazVsechny($sql,[$id_soup]);
        return $ucastnci;
      }

      public function vratVsechnyDisciplinyAkce($id_akce){
      $sql = "SELECT disciplina.id_disc, disciplina.nazev_disc
      from akce_disc INNER join disciplina on akce_disc.id_disc = disciplina.id_disc
      where akce_disc.id_akce = ?";

      $discipliny = Db::dotazvsechny($sql,[$id_akce]);
      return $discipliny;
      }


      public function vyberUcastnikuNaSoupisku($id_soup){
        $sql = "SELECT uzivatel.jmeno, uzivatel.prijmeni, uzivatel.id_trid, uzivatel.email
        FROM uzivatel LEFT JOIN ucastnik ON uzivatel.email = ucastnik.email AND ucastnik.id_soup = ?
        WHERE ucastnik.id_ucast IS NULL and uzivatel.opravneni = 0";
  
        $ucastnci = Db::dotazvsechny($sql,[$id_soup]);
        return $ucastnci;
        }

        public function vratVsechnyUcastnikyZeSoupiskyPending($id_soup){
          $sql = "SELECT ucastnik.id_ucast ,uzivatel.jmeno, uzivatel.prijmeni, uzivatel.id_trid, disciplina.nazev_disc 
          FROM uzivatel INNER JOIN ucastnik USING (email) 
          INNER JOIN soupiska ON ucastnik.id_soup = soupiska.id_soup 
          INNER JOIN disc_ucast ON ucastnik.id_ucast = disc_ucast.id_ucast 
          inner JOIN disciplina on disc_ucast.id_disc = disciplina.id_disc 
          WHERE soupiska.id_soup = ? and ucastnik.potrvzeni = 0";
          $ucastnci = Db::dotazVsechny($sql,[$id_soup]);
          return $ucastnci;
        }

  
}
?>