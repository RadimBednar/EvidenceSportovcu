<?php
class ModelyDisciplina{

  //vrati vsechno z tabulky "disciplina"
    public function vratVsechnyDiscipliny() {
        $sql = "
            SELECT *
            FROM disciplina
        ";
        $disciplina = Db::dotazVsechny($sql);
        return $disciplina;
      }

    //vrati posledni id v tabulce
    public function vratPosledniId() {
      $sql = "SELECT id_disc
              FROM disciplina
              ORDER BY id_disc DESC
              LIMIT 1";
      
      $disciplina = Db::dotazJeden($sql);
      if ($disciplina === false) return 0;
      else return $disciplina['id_disc'];
    }

      /*slouzi k pridani discipliny do databaze, parametr bude pole, vyzadujici:
    id_disc
    id_sport (pro atribut id_sport)
    nazev_disc*/
      public function pridejDisciplinu($disc) {
      //kontrola id
        $sql = "
            SELECT id_disc
            FROM disciplina
            where id_disc = ?
        ";
        if(Db::dotazJeden($sql,[$disc["id_disc"]])){
        return 0;
        }
        //kontrola nazvu
        $sql = "
            SELECT nazev_disc
            FROM disciplina
            where nazev_disc = ?
        ";
        if(Db::dotazJeden($sql,[$disc["nazev_disc"]])){
          return 0;
        }
      Db::vloz("disciplina",$disc);
      return 1;
    }
    //vrati 0 pokud v databazi disciplina uz je, vrati 1 pokud v databazi "disciplina" jeste neni a prida tam disciplinu
      

    //slouzi k odebrani discipliny z databaze, parametrem bude jen id z databaze(id_disc)
    public function odeberDisciplinu($id){
        $sql = "
          DELETE FROM disciplina
          where id_disc = ?
      ";
      if(Db::dotaz($sql,[$id])){
        return 1;
      }
    return 0;
    }//vrati 1 pokud v databazi disciplinu odebere, 0 pokud se akce nepodarila


    /*funkce slouzi ke zmene sloupcu v tabulce "disciplina", parametry:
    $hodnoty - pole asociativni pro nazev sloupcu a jeji nove hodnoty ["id_sport"] => 5
                                                                      ["nazev_disc"] => "sprint"
    $id - id z databaze(id_disc), čili id konkretni discipliny
    */
    public function zmenDisciplinu($hodnoty, $id){
    //kontrola nazvu discipliny
          $sql = "
              SELECT nazev_disc
              FROM disciplina
              where nazev_disc = ?
          ";
          if(Db::dotazJeden($sql,[$hodnoty["nazev_disc"]])){
            return 0;
          }
          
          $sql = "
          where id_disc = ?
          ";

        if(Db::zmen("disciplina",$hodnoty,$sql,[$id])){
          return 1;
        }
        return 0;

      }//vrati 1 pokud v databazi uspesne provedl zmenu, 0 pokud se akce nepodarila



    
}

?>