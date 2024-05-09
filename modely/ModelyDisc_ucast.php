<?php
class ModelyDisc_ucast{

  //vrati vsechno z tabulky "disc_ucast"
    public function vratVsechnyDisc_ucast() {
        $sql = "
            SELECT *
            FROM disc_ucast
        ";
        $disc_ucast = Db::dotazVsechny($sql);
        return $disc_ucast;
      }


    //vrati posledni id v tabulce
    public function vratPosledniId() {
      $sql = "SELECT id_disc_ucast
              FROM disc_ucast
              ORDER BY id_disc_ucast DESC
              LIMIT 1";
      
      $disc_ucast = Db::dotazJeden($sql);
      if ($disc_ucast === false) return 0;
      else return $disc_ucast['id_disc_ucast'];
    }


      /*slouzi k pridani záznamu do databaze, parametr bude pole, vyzadujici:
    id_disc_ucast
    id_ucast
    id_disc
    *vys_du
    
    * - nepovinny atribut
    
    */
    public function pridejDisc_ucast($disc_ucast) {
        $sql = "
            SELECT id_disc_ucast
            FROM disc_ucast
            where id_disc_ucast = ?
        ";
        if(Db::dotazJeden($sql,[$disc_ucast["id_disc_ucast"]])){
        return 0;
        }
      Db::vloz("disc_ucast",$disc_ucast);
      return 1;
    }//vrati 0 pokud v uz databazi záznam uz je, vrati 1 pokud v databazi "disc_ucast" jeste neni a prida tam
      
      
    //slouzi k odebrani záznamu z databaze, parametrem bude jen id z databaze(id_disc_ucast)
    public function odeberDisc_ucast($id){
        $sql = "
          DELETE FROM disc_ucast
          where id_disc_ucast = ?
      ";
      if(Db::dotaz($sql,[$id])){
        return 1;
      }
    return 0;
    }//vrati 1 pokud v databazi záznam odebere, 0 pokud se akce nepodarila


    /*slouzi ke zmene sloupcu v tabulce "disc_ucast", parametry:
    $hodnoty - pole asociativni pro nazev sloupcu a jeji nove hodnoty ["id_ucast"] => 19
                                                                      ["id_disc"] => 5
                                                                      ["vys_du"] => "Rekord v behu na 1km - 21 sekund"
    $id - id z databaze(id_disc_ucast), čili id konkretniho záznamu
    */
    public function zmenDisc_ucast($hodnoty, $id){
      $sql = "
      where id_disc_ucast = ?
      ";
      if(Db::zmen("disc_ucast",$hodnoty,$sql,[$id])){
        return 1;
      }
      return 0;

      }//vrati 1 pokud v databazi uspesne provedl zmenu, 0 pokud se akce nepodarila



    
}

?>