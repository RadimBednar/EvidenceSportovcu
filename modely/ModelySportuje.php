<?php
class ModelySportuje {

    public function vratVsechySportuje() {
        $sql = "
            SELECT *
            FROM sportuje inner join uzivatel using(email)
        ";
        $sportuje = Db::dotazVsechny($sql);
        return $sportuje;
    }


      //vrati posledni id v tabulce
      public function vratPosledniId() {
        $sql = "SELECT id_sportuje
                FROM sportuje
                ORDER BY id_sportuje DESC
                LIMIT 1";
        
        $sportuje = Db::dotazJeden($sql);
        if ($sportuje === false) return 0;
        else return $sportuje['id_sportuje'];
      }


      /*funkce slouzi k pridani hodnot do databaze, parametr bude pole, vyzadujici:
      id_sportuje
      id_disc (id discipliny)
      email (email uzivatele)
      *id_poz
      *id_urov
      *tym
      *rekord

      * - nepovinny atribut
      */
    public function pridejSportuje($sportuje) {
        $sql = "
            SELECT id_sportuje
            FROM sportuje
            where id_sportuje = ?
        ";
        if(Db::dotazJeden($sql,[$sportuje["id_sportuje"]])){
        return 0;
        }
        $sql = "
            SELECT id_sportuje
            FROM sportuje
            where id_disc = ? and  email = ?
        ";
        if(Db::dotazJeden($sql,[$sportuje["id_disc"],$sportuje["email"]])){
        return 0;
        }
      Db::vloz("sportuje",$sportuje);
      return 1;
    }//vrati 0 pokud v uz databazi hodnoty uz jsou, vrati 1 pokud v databazi "sportuje" jeste neni a prida tam hodnoty
      

    //funkce slouzi k odebrani hodnoty z databaze, parametrem bude jen id z databaze(id_sportuje)
    public function odeberSportuje($id){
        $sql = "
          DELETE FROM sportuje
          where id_sportuje = ?
      ";
      if(Db::dotaz($sql,[$id])){
        return 1;
      }
    return 0;
    }//vrati 1 pokud v databazi hodnoty odebere, 0 pokud se akce nepodarila


    /*funkce slouzi ke zmene sloupcu v tabulce "sportuje", parametry:
    $hodnoty - pole asociativni pro nazev sloupcu a jeji nove hodnoty(["id_disc"] => 3
                                                                      ["email"] => "s.fabisz.st@spseiostrava.cz"
                                                                      ["id_poz"] => 5
                                                                      ["id_urov"] => 9
                                                                      ["tym"] => "Baník"
                                                                      ["rekord"] => 5
    $id - id z databaze(id_sportuje), čili id konkretniho řádku
    */
    public function zmenSportuje($hodnoty, $id){
        $sql = "
        where id_sportuje = ?
        ";
        if(Db::zmen("sportuje",$hodnoty,$sql,[$id])){
          return 1;
        }
        return 0;
    }

    public static function pridejZFormulare($id_urov, $id_poz , $id_disc){
        // Příprava SQL dotazu

        $parameters = array();
        $parameters[":id_urov"] = $id_urov;
        $parameters[":id_poz"] = $id_poz;
        
        $parameters[":id_disc"] = $id_disc;
                $parameters[":email"] = $_SESSION["email"];
                echo  $_SESSION["email"];

        $sql = "INSERT INTO sportuje (id_urov, id_poz, id_disc,email) VALUES (:id_urov, :id_poz, :id_disc,:email)";
    
     Db::dotaz($sql, $parameters);
    
        
    }

}
?>
