<?php
class ModelyDodatecne_info{

    //vrati vsechno z tabulky "dodatecne_info"
    public function vratVsechnyDodatecne_info() {
        $sql = "
            SELECT *
            FROM dodatecne_info
        ";
        $dodatecne_info = Db::dotazVsechny($sql);
        return $dodatecne_info;
      }



    /*slouzi k pridani záznamu do databaze, parametr bude pole, vyzadujici:
    email 
    *komentar_uziv
    *kontaktni_udaje
    *odkaz_na_web
    *zdravotni_omezeni
    
    * - nepovinny atribut
    
    */
    public function pridejDodatecne_info($dodatecne_info) {
        $sql = "
            SELECT email
            FROM dodatecne_info
            where email = ?
        ";
        if(Db::dotazJeden($sql,[$dodatecne_info["email"]])){
        return 0;
        }
      Db::vloz("dodatecne_info",$dodatecne_info);
      return 1;
    }//vrati 0 pokud v uz databazi záznam uz je, vrati 1 pokud v databazi "dodatecne_info" jeste neni a prida tam
      
      
    //slouzi k odebrani záznamu z databaze, parametrem bude jen id z databaze(email)
    public function odeberDodatecne_info($id){
        $sql = "
          DELETE FROM dodatecne_info
          where email = ?
      ";
      if(Db::dotaz($sql,[$id])){
        return 1;
      }
    return 0;
    }//vrati 1 pokud v databazi akce odebere, 0 pokud se akce nepodarila


    /*slouzi ke zmene sloupcu v tabulce "dodatecne_info", parametry:
    $hodnoty - pole asociativni pro nazev sloupcu a jeji nove hodnoty
    $id - id z databaze(email), čili id konkretni akci
    */
    public function zmenDodatecne_info($hodnoty, $id){
      $sql = "
      where email = ?
      ";

      $pocetZmenenychRadku = Db::zmen("dodatecne_info",$hodnoty,$sql,[$id]);
    
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


    
}

?>