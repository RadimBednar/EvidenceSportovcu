<?php
class ModelySport{

  //vrati vsechno z tabulky "sport"
    public function vratVsechnySporty() {
        $sql = "
            SELECT *
            FROM sport
        ";
        $sport = Db::dotazVsechny($sql);
        return $sport;
      }


    //vrati posledni id v tabulce
    public function vratPosledniId() {
      $sql = "SELECT id_sport
              FROM sport
              ORDER BY id_sport DESC
              LIMIT 1";
      
      $sport = Db::dotazJeden($sql);
      if ($sport === false) return 0;
      else return $sport['id_sport'];
    }



      /*slouzi k pridani sportu do databaze, parametr bude pole, vyzadujici:
    id_sport
    nazev_sportu*/
    public function pridejSporty($sport) {
      
        $sql = "
            SELECT id_sport
            FROM sport
            where id_sport = ?
        ";
        if(Db::dotazJeden($sql,[$sport["id_sport"]])){
        return 0;
        }
        $sql = "
            SELECT nazev_sportu
            FROM sport
            where nazev_sportu = ?
        ";
        if(Db::dotazJeden($sql,[$sport["nazev_sportu"]])){
          return 0;
        }
      Db::vloz("sport",$sport);
      return 1;
    }//vrati 0 pokud v uz databazi sport uz je, vrati 1 pokud v databazi "sport" jeste neni a prida tam
      
      
    //slouzi k odebrani sportu z databaze, parametrem bude jen id z databaze(id_sport)
    public function odeberSport($id){
        $sql = "
          DELETE FROM sport
          where id_sport = ?
      ";
      if(Db::dotaz($sql,[$id])){
        return 1;
      }
    return 0;
    }//vrati 1 pokud v databazi sport odebere, 0 pokud se akce nepodarila


    /*slouzi ke zmene sloupcu v tabulce "sport", parametry:
    $hodnoty - pole asociativni pro nazev sloupcu a jeji nove hodnoty ["nazev_sportu"] => "Atletika"
    $id - id z databaze(id_sport), čili id konkretniho sportu
    */
    public function zmenSport($hodnoty, $id){

      $sql = "
      SELECT nazev_sportu
      FROM sport
      where nazev_sportu = ?
      ";
      if(Db::dotazJeden($sql,[$hodnoty["nazev_sportu"]])){
        return 0;
      }


      $sql = "
      where id_sport = ?
      ";
      if(Db::zmen("sport",$hodnoty,$sql,[$id])){
        return 1;
      }
      return 0;

      }//vrati 1 pokud v databazi uspesne provedl zmenu, 0 pokud se akce nepodarila



    
}

?>