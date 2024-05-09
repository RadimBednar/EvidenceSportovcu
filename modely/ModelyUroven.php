<?php
class ModelyUroven{

  //funkce vrati vsechno z tabulky "uroven"
    public function vratVsechnyUroven() {
        $sql = "
            SELECT *
            FROM uroven
        ";
        $uroven = Db::dotazVsechny($sql);
        return $uroven;
      }

    //vrati posledni id v tabulce
    public function vratPosledniId() {
      $sql = "SELECT id_urov
              FROM uroven
              ORDER BY id_urov DESC
              LIMIT 1";
      
      $uroven = Db::dotazJeden($sql);
      if ($uroven === false) return 0;
      else return $uroven['id_urov'];
    }



      /*funkce slouzi k pridani urovne do databaze, parametr bude pole, vyzadujici:
    id_urov
    nazev_urov*/
    public function pridejUroven($uroven) {
        $sql = "
            SELECT id_urov
            FROM uroven
            where id_urov = ?
        ";
        if(Db::dotazJeden($sql,[$uroven["id_urov"]])){
        return 0;
        }
        $sql = "
            SELECT nazev_urov	
            FROM uroven
            where nazev_urov	 = ?
        ";
        if(Db::dotazJeden($sql,[$uroven["id_urov"]])){
          return 0;
        }
      Db::vloz("uroven",$uroven);
      return 1;
    }//vrati 0 pokud v uz databazi uroven uz je, vrati 1 pokud v databazi "uroven" jeste neni a prida tam
      
      
    //funkce slouzi k odebrani uroven z databaze, parametrem bude jen id z databaze(id_urov)
    public function odeberUroven($id){
        $sql = "
          DELETE FROM uroven
          where id_urov = ?
      ";
      if(Db::dotaz($sql,[$id])){
        return 1;
      }
    return 0;
    }//vrati 1 pokud v databazi uroven odebere, 0 pokud se akce nepodarila


    /*funkce slouzi ke zmene sloupcu v tabulce "uroven", parametry:
    $hodnoty - pole asociativni pro nazev sloupcu a jeji nove hodnoty ["nazev_urov"] => "Amatér"
    $id - id z databaze(id_urov), čili id konkretni uroven
    */
    public function zmenUroven($hodnoty, $id){
      $sql = "
      SELECT nazev_urov
      FROM uroven
      where nazev_urov = ?
      ";
      if(Db::dotazJeden($sql,[$hodnoty["nazev_urov"]])){
        return 0;
      }


      $sql = "
      where id_urov = ?
      ";
      if(Db::zmen("uroven",$hodnoty,$sql,[$id])){
        return 1;
      }
      return 0;

      }//vrati 1 pokud v databazi uspesne provedl zmenu, 0 pokud se akce nepodarila



    
}

?>