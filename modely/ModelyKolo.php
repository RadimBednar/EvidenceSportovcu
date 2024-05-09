<?php
class ModelyKolo{

  //vrati vsechno z tabulky "kolo"
    public function vratVsechnyKolo() {
        $sql = "
            SELECT *
            FROM kolo
        ";
        $kolo = Db::dotazVsechny($sql);
        return $kolo;
      }


    //vrati posledni id v tabulce
    public function vratPosledniId() {
      $sql = "SELECT id_kolo
              FROM kolo
              ORDER BY kolo DESC
              LIMIT 1";
      
      $kolo = Db::dotazJeden($sql);
      if ($kolo === false) return 0;
      else return $kolo['id_kolo'];
    }


      /*slouzi k pridani kola do databaze, parametr bude pole, vyzadujici:
    id_kolo
    nazev_kolo*/
    public function pridejKolo($kolo) {
      //kontrola id
        $sql = "
            SELECT id_kolo
            FROM kolo
            where id_kolo = ?
        ";
        if(Db::dotazJeden($sql,[$kolo["id_kolo"]])){
        return 0;
        }
        //kontrola nazvu
        $sql = "
            SELECT nazev_kolo	
            FROM kolo
            where nazev_kolo	 = ?
        ";
        if(Db::dotazJeden($sql,[$kolo["id_kolo"]])){
          return 0;
        }
      Db::vloz("kolo",$kolo);
      return 1;
    }//vrati 0 pokud v uz databazi pozice uz je, vrati 1 pokud v databazi "kolo" jeste neni a prida tam
      
      
    //slouzi k odebrani pozice z databaze, parametrem bude jen id z databaze(id_kolo)
    public function odeberKolo($id){
        $sql = "
          DELETE FROM kolo
          where id_kolo = ?
      ";
      if(Db::dotaz($sql,[$id])){
        return 1;
      }
    return 0;
    }//vrati 1 pokud v databazi pozice odebere, 0 pokud se akce nepodarila


    /*slouzi ke zmene sloupcu v tabulce "pozice", parametry:
    $hodnoty - pole asociativni pro nazev sloupcu a jeji nove hodnoty ["nazev_kolo"] => "školní"
    $id - id z databaze(id_poz), čili id konkretni pozice
    */
    public function zmenKolo($hodnoty, $id){
      //kontrola nazvu
      $sql = "
      SELECT nazev_kolo
      FROM kolo
      where nazev_kolo = ?
      ";
      if(Db::dotazJeden($sql,[$hodnoty["nazev_kolo"]])){
        return 0;
      }


      $sql = "
      where id_kolo = ?
      ";
      if(Db::zmen("kolo",$hodnoty,$sql,[$id])){
        return 1;
      }
      return 0;

      }//vrati 1 pokud v databazi uspesne provedl zmenu, 0 pokud se akce nepodarila



    
}

?>