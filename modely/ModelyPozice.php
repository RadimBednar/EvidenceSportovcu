<?php
class ModelyPozice{

  //vrati vsechno z tabulky "pozice"
    public function vratVsechnyPozice() {
        $sql = "
            SELECT *
            FROM pozice
        ";
        $pozice = Db::dotazVsechny($sql);
        return $pozice;
      }


    //vrati posledni id v tabulce
    public function vratPosledniId() {
      $sql = "SELECT id_poz
              FROM pozice
              ORDER BY id_poz DESC
              LIMIT 1";
      
      $pozice = Db::dotazJeden($sql);
      if ($pozice === false) return 0;
      else return $pozice['id_poz'];
    }


      /*slouzi k pridani pozice do databaze, parametr bude pole, vyzadujici:
    id_poz
    nazev_poz*/
    public function pridejPozice($pozice) {
      //kontrola id
        $sql = "
            SELECT id_poz
            FROM pozice
            where id_poz = ?
        ";
        if(Db::dotazJeden($sql,[$pozice["id_poz"]])){
        return 0;
        }
        //kontrola nazvu
        $sql = "
            SELECT nazev_poz	
            FROM pozice
            where nazev_poz	 = ?
        ";
        if(Db::dotazJeden($sql,[$pozice["id_poz"]])){
          return 0;
        }
      Db::vloz("pozice",$pozice);
      return 1;
    }//vrati 0 pokud v uz databazi pozice uz je, vrati 1 pokud v databazi "pozice" jeste neni a prida tam
      
      
    //slouzi k odebrani pozice z databaze, parametrem bude jen id z databaze(id_poz)
    public function odeberPozice($id){
        $sql = "
          DELETE FROM pozice
          where id_poz = ?
      ";
      if(Db::dotaz($sql,[$id])){
        return 1;
      }
    return 0;
    }//vrati 1 pokud v databazi pozice odebere, 0 pokud se akce nepodarila


    /*slouzi ke zmene sloupcu v tabulce "pozice", parametry:
    $hodnoty - pole asociativni pro nazev sloupcu a jeji nove hodnoty ["nazev_poz"] => "Levé křídlo"
    $id - id z databaze(id_poz), čili id konkretni pozice
    */
    public function zmenPozici($hodnoty, $id){
      //kontrola nazvu
      $sql = "
      SELECT nazev_poz
      FROM pozice
      where nazev_poz = ?
      ";
      if(Db::dotazJeden($sql,[$hodnoty["nazev_poz"]])){
        return 0;
      }


      $sql = "
      where id_poz = ?
      ";
      if(Db::zmen("pozice",$hodnoty,$sql,[$id])){
        return 1;
      }
      return 0;

      }//vrati 1 pokud v databazi uspesne provedl zmenu, 0 pokud se akce nepodarila



    
}

?>