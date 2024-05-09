<?php
class ModelyAkce_disc{

    //vrati vsechno z tabulky "akce_disc"
    public function vratVsechnyAkce_disc() {
        $sql = "
            SELECT *
            FROM akce_disc
        ";
        $akce_disc = Db::dotazVsechny($sql);
        return $akce_disc;
    }

    //vrati posledni id v tabulce
    public function vratPosledniId() {
      $sql = "SELECT id_akce_disc
              FROM akce_disc
              ORDER BY id_akce_disc DESC
              LIMIT 1";
      
      $akce_disc = Db::dotazJeden($sql);
      if ($akce_disc === false) return 0;
      else return $akce_disc['id_akce_disc'];
    }
  


      /*slouzi k pridani záznamu do databaze, parametr bude pole, vyzadujici:
    id_akce_disc
    id_akce
    id_disc
    
    * - nepovinny atribut
    
    */
    public function pridejAkce_disc($akce_disc) {
        $sql = "
            SELECT id_akce_disc
            FROM akce_disc
            where id_akce_disc = ?
        ";
        if(Db::dotazJeden($sql,[$akce_disc["id_akce_disc"]])){
        return 0;
        }
      Db::vloz("akce_disc",$akce_disc);
      return 1;
    }//vrati 0 pokud v uz databazi záznam uz je, vrati 1 pokud v databazi "akce_disc" jeste neni a prida tam
      

    //slouzi k odebrani záznamu z databaze, parametrem bude jen id z databaze(id_akce_disc)
    public function odeberAkce_disc($id){
        $sql = "
        DELETE FROM akce_disc
        where id_akce_disc = ?
    ";
    if(Db::dotaz($sql,[$id])){
        return 1;
    }
    return 0;
    }//vrati 1 pokud v databazi záznam odebere, 0 pokud se akce nepodarila


    /*slouzi ke zmene sloupcu v tabulce "akce_disc", parametry:
    $hodnoty - pole asociativni pro nazev sloupcu a jeji nove hodnoty ["id_akce"] => 2
                                                                      ["id_disc"] => 5
    $id - id z databaze(id_akce_disc), čili id konkretniho záznamu
    */
    public function zmenAkce_disc($hodnoty, $id){
        $sql = "
        where id_akce_disc = ?
        ";
        if(Db::zmen("akce_disc",$hodnoty,$sql,[$id])){
          return 1;
        }
        return 0;
  
        }//vrati 1 pokud v databazi uspesne provedl zmenu, 0 pokud se akce nepodarila
  




}

?>