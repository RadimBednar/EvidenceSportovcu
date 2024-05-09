<?php
class ModelyAkce{

    //vrati vsechno z tabulky "akce"
    public function vratVsechnyAkce() {
        $sql = "
            SELECT *
            FROM akce
        ";
        $akce = Db::dotazVsechny($sql);
        return $akce;
      }


    //vrati posledni id v tabulce
    public function vratPosledniId() {
      $sql = "SELECT id_akce
              FROM akce
              ORDER BY id_akce DESC
              LIMIT 1";
      
      $akce = Db::dotazJeden($sql);
      if ($akce === false) return 0;
      else return $akce['id_akce'];
    } 


    /*slouzi k pridani záznamu do databaze, parametr bude pole, vyzadujici:
    id_akce 
    nazev_akce
    datum_zahajeni
    *datum_konce
    misto_kon
    *poradatel
    popisek_akce
    *pritomni_uc
    *shrnuti
    *archivovano
    *id_opak
    *id_kolo
    
    * - nepovinny atribut
    
    */
    public function pridejAkci($akce) {
        $sql = "
            SELECT id_akce
            FROM akce
            where id_akce = ?
        ";
        if(Db::dotazJeden($sql,[$akce["id_akce"]])){
        return 0;
        }
      Db::vloz("akce",$akce);
      return 1;
    }//vrati 0 pokud v uz databazi záznam uz je, vrati 1 pokud v databazi "akce" jeste neni a prida tam
      
      
    //slouzi k odebrani záznamu z databaze, parametrem bude jen id z databaze(id_akce)
    public function odeberAkci($id){
        $sql = "
          DELETE FROM akce
          where id_akce = ?
      ";
      if(Db::dotaz($sql,[$id])){
        return 1;
      }
    return 0;
    }//vrati 1 pokud v databazi akce odebere, 0 pokud se akce nepodarila


    /*slouzi ke zmene sloupcu v tabulce "akce", parametry:
    $hodnoty - pole asociativni pro nazev sloupcu a jeji nove hodnoty
    $id - id z databaze(id_akce), čili id konkretni akci
    */
    public function zmenAkci($hodnoty, $id){
      $sql = "
      where id_akce = ?
      ";

      $pocetZmenenychRadku = Db::zmen("akce",$hodnoty,$sql,[$id]);
    
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



    public function vratCiselniky($id_akce){
      $sql = "SELECT  opakovanost.nazev_opak, kolo.nazev_kolo
      from akce INNER join kolo on akce.id_kolo = kolo.id_kolo INNER join opakovanost on akce.id_opak = opakovanost.id_opak
      where akce.id_akce = ?";
      $ciselniky = Db::dotazJeden($sql,[$id_akce]);
      if($ciselniky === false){
        return null;
      }
      return $ciselniky;
    }


  public function vratVsechnyDisciplinyAkce($id_akce){
    $sql = "SELECT disciplina.nazev_disc, akce_disc.id_akce_disc, disciplina.id_disc
    from disciplina INNER join akce_disc on akce_disc.id_disc = disciplina.id_disc INNER JOIN akce on akce_disc.id_akce = akce.id_akce
    where akce.id_akce = ?";
    $discipliny = Db::dotazVsechny($sql,[$id_akce]);
    return $discipliny;
  }

  public function vratVsechnySoupiskyAkce($id_akce){
    $sql = "SELECT soupiska.nazev_skupiny, soupiska.id_soup
    from akce INNER JOIN soupiska on soupiska.id_akce = akce.id_akce
    where soupiska.id_akce = ?";
    $soupisky = Db::dotazVsechny($sql,[$id_akce]);
    return $soupisky;
  }

  public function vyberDisciplinMimoAkci($id_akce){
    $sql = "SELECT disciplina.nazev_disc, disciplina.id_disc
    FROM disciplina
    LEFT JOIN akce_disc ON disciplina.id_disc = akce_disc.id_disc AND akce_disc.id_akce = ?
    WHERE akce_disc.id_akce is null;";
    $vyber_disc = Db::dotazVsechny($sql,[$id_akce]);
    return $vyber_disc;
  }



  }

?>