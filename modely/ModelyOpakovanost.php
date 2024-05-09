<?php
class ModelyOpakovanost{

  //vrati vsechno z tabulky "opakovanost"
    public function vratVsechnyOpak() {
        $sql = "
            SELECT *
            FROM opakovanost
        ";
        $opak = Db::dotazVsechny($sql);
        return $opak;
      }


    //vrati posledni id v tabulce
    public function vratPosledniId() {
      $sql = "SELECT id_opak
              FROM opakovanost
              ORDER BY id_opak DESC
              LIMIT 1";
      
      $opak = Db::dotazJeden($sql);
      if ($opak === false) return 0;
      else return $opak['id_opak'];
    }


      /*slouzi k pridani pozice do databaze, parametr bude pole, vyzadujici:
    id_opak
    nazev_opak*/
    public function pridejOpak($opak) {
      //kontrola id
        $sql = "
            SELECT id_opak
            FROM opakovanost
            where id_opak = ?
        ";
        if(Db::dotazJeden($sql,[$opak["id_opak"]])){
        return 0;
        }
        //kontrola nazvu
        $sql = "
            SELECT nazev_opak	
            FROM opakovanost
            where nazev_opak	 = ?
        ";
        if(Db::dotazJeden($sql,[$opak["id_opak"]])){
          return 0;
        }
      Db::vloz("opakovanost",$opak);
      return 1;
    }//vrati 0 pokud v uz databazi opakovanost uz je, vrati 1 pokud v databazi "opakovanost" jeste neni a prida tam
      
      
    //slouzi k odebrani opakovanosti z databaze, parametrem bude jen id z databaze(id_poz)
    public function odeberOpak($id){
        $sql = "
          DELETE FROM opakovanost
          where id_opak = ?
      ";
      if(Db::dotaz($sql,[$id])){
        return 1;
      }
    return 0;
    }//vrati 1 pokud v databazi opakovanost odebere, 0 pokud se akce nepodarila


    /*slouzi ke zmene sloupcu v tabulce "opakovanost", parametry:
    $hodnoty - pole asociativni pro nazev sloupcu a jeji nove hodnoty ["nazev_opak"] => "Každoročně"
    $id - id z databaze(id_poz), čili id konkretni opakovanost
    */
    public function zmenOpak($hodnoty, $id){
      //kontrola nazvu
      $sql = "
      SELECT nazev_opak
      FROM opakovanost
      where nazev_opak = ?
      ";
      if(Db::dotazJeden($sql,[$hodnoty["nazev_opak"]])){
        return 0;
      }


      $sql = "
      where id_opak = ?
      ";
      if(Db::zmen("opakovanost",$hodnoty,$sql,[$id])){
        return 1;
      }
      return 0;

      }//vrati 1 pokud v databazi uspesne provedl zmenu, 0 pokud se akce nepodarila



    
}

?>