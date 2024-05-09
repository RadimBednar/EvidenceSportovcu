<?php
class ModelyUzivatel {

    public function prihlas($prihlasovaciUdaje) {
        
      $sql = "
         SELECT *
         FROM uzivatel
         WHERE email = ?
        ";

      $uzivatel = Db::dotazJeden($sql, 
          [
            $prihlasovaciUdaje["email"],
        //$this->vratHashHesla($prihlasovaciUdaje["heslo"])
          ]  
      );
          
      if ($uzivatel) {
          $_SESSION["uzivatel"] = $uzivatel;
          return 1;
      }
      return 0;    

    }
    
    public function odhlas() {

        if ($this->vratPrihlasenehoUzivatele()) {
          unset($_SESSION["loggedIn"]);
          return 1;
        }
        return 0;

    }
    
    public function vratPrihlasenehoUzivatele() {

        if (isset($_SESSION["loggedIn"]))
          return $_SESSION["loggedIn"];
        else
          return false;    
    }
    
/*
    private function vratHashHesla($heslo) {
    
        return hash("sha256", $heslo);
        //return hash("sha512", $heslo);


    }
*/
    public function vratVsechnyUzivatele() {
      $sql = "
          SELECT *
          FROM uzivatel
      ";
      $uzivatel = Db::dotazVsechny($sql);
      return $uzivatel;
    }

    public function vratVsechnyStudenty() {
      $sql = "
          SELECT *
          FROM uzivatel
          WHERE opravneni = 0
      ";
      $uzivatel = Db::dotazVsechny($sql);
      return $uzivatel;
    }

    /*funkce slouzi k pridani studenta do uzivatele, parametr bude pole, vyzadujici:
    id_uziv
    *id_trid
    *isic
    *email
    opravneni
    *jmeno
    *prijmeni
    *dat_nar
    *pohlavi

    * - nepovinny atribut
    */
    public function pridejStudenta($uzivatel) {
      if($uzivatel["opravneni"]==0){
      $sql = "
            SELECT id_uziv
            FROM uzivatel
            where id_uziv = ?
        ";
        if(Db::dotazJeden($sql,[$uzivatel["id_uziv"]])){
        return 0;
        }

      $sql = "
          SELECT isic
          FROM uzivatel
          where isic = ?
      ";
      if(Db::dotazJeden($sql,[$uzivatel["isic"]])){
      return 0;
      }
    Db::vloz("uzivatel",$uzivatel);
    
    return 1;
      }
      else return 2;
    }//vrati 0 pokud v uz databazi uzivatel uz je, vrati 1 pokud v databazi uzivatel jeste neni a prida tam studenta


    /*funkce slouzi k pridani ucitele do uzivatelu, parametr bude pole, vyzadujici:
    id_uziv
    *id_trid - null
    *isic - null
    *email
    opravneni
    *jmeno
    *prijmeni
    *dat_nar
    *pohlavi
    
    * - nepovinny atribut
    */
    public function pridejUcitele($uzivatel) {
      if($uzivatel["opravneni"]!=0){
      $sql = "
          SELECT email
          FROM uzivatel
          where email = ?
      ";
      if(Db::dotazJeden($sql,[$uzivatel["email"]])){
      return 0;
      }
    Db::vloz("uzivatel",$uzivatel);
    return 1;
      }
      else return 2;
    }//vrati 0 pokud v uz databazi uzivatel uz je, vrati 1 pokud v databazi uzivatel jeste neni a prida tam ucitele

    //vrati posledni id v tabulce
    public function vratPosledniIdUcitele() {
      $sql = "SELECT id_uziv
              FROM uzivatel
              WHERE opravneni = 1 or opravneni = 2
              ORDER BY id_uziv DESC
              LIMIT 1";
      
      
      $ucitel = Db::dotazJeden($sql);
      if ($ucitel === false) return 0;
      else return $ucitel['id_uziv'];
    }
    
    //funkce slouzi k odebrani uzivatele z databaze, parametrem bude jen id z databaze(id_uziv)
    public function odeberUzivatele($email){
      $sql = "
      select email
      FROM uzivatel
      where email = ?
      ";

  if(Db::dotazJeden($sql,[$email])){
    Db::dotaz("SET foreign_key_checks = 0;");
    $sql = "
            DELETE FROM uzivatel
            where email = ?
            ";
    Db::dotaz($sql,[$email]);
    Db::dotaz("SET foreign_key_checks = 1;");
    return 1;
    }
return 0;
}//vrati 1 pokud v databazi uzivatel odebere, 0 pokud se akce nepodarila


    /*funkce slouzi ke zmene sloupcu v tabulce "uzivatel", parametry:
    $hodnoty - pole asociativni pro nazev sloupcu a jeji nove hodnoty ["id_trid"] =>
                                                                      ["isic"] => "4df56154sef612564"
                                                                      ["email"] => "s.fabisz.st@spseiostrava.cz"
                                                                      ["opravneni"] => 3
                                                                      ["jmeno"] =>"Sami"
                                                                      ["prijmeni"] =>"Fabi"
                                                                      ["dat_nar"] =>
                                                                      ["pohlavi"] =>"ž"
    $id - id z databaze(id_uziv), čili id konkretniho uzivatele
    */
      public function zmenUzivatele($hodnoty){
        $sql = "
        where id_uziv = ?
        ";
        try{
        if(Db::zmen("uzivatel",$hodnoty,$sql,[$hodnoty["id_uziv"]])){
          return 1;
        }
        return 0;
      }catch(PDOException $e){
        var_dump($e->getMessage());
      } 

      }//vrati 1 pokud v databazi uspesne provedl zmenu, 0 pokud se akce nepodarila

//vrati informace vsech ucitelu vsechny ucitele
    public function vratInfoVsechUcitelu(){
      $data = array();
    for ($i = 0; $i < 100; $i++) {
      $sql = "SELECT id_uziv FROM uzivatel WHERE id_uziv = ?";
      $id = Db::dotazJeden($sql, [$i]);
      if($id!=NULL){
      $sql = "SELECT id_uziv, jmeno, prijmeni, email, opravneni FROM uzivatel WHERE id_uziv = ?";
      $data[] = Db::dotazJeden($sql, [$i]);
      }
    }
    return $data;
}


//vrati info o studentovi, vyzaduje isic
    public function vratInfoStudenta($isic){
      $sql = "SELECT jmeno, prijmeni, id_trid, email,  FROM uzivatel WHERE isic = ?";
      $data = Db::dotazJeden($sql, [$isic]);

      return $data;

    }
    
    public function vratInfoPodleEmailu($email) {
      $sql = "SELECT * FROM uzivatel WHERE email = ?";
      return Db::dotazJeden($sql, [$email]);
  }

  public function vratInfoPodleEmailuDI($email) {
    $sql = "SELECT * FROM uzivatel left join dodatecne_info using(email) WHERE email like ?";
    return Db::dotazJeden($sql, [$email]);
  }

    // Uložení nových údajů do databáze
    public function upravDodatecneUdaje($email, $kontaktniUdaje, $odkazNaWeb, $zdravotniOmezeni, $sportovniAktivity) {
      $sql = "
          UPDATE uzivatel
          SET kontaktni_udaje = ?, odkaz_na_web = ?, zdravotni_omezeni = ?, sportovni_aktivity = ?
          WHERE email = ?
      ";
      Db::dotaz($sql, [$kontaktniUdaje, $odkazNaWeb, $zdravotniOmezeni, $sportovniAktivity, $email]);
  }


// Funkce slouží k odebrání všech uživatelů 
public function vymazUzivatele() {
  // Disable foreign key checks
  Db::dotaz("SET foreign_key_checks = 0;");

  $sql = "
      DELETE FROM uzivatel
      
  ";
  $result = Db::dotaz($sql);

  // Enable foreign key checks
  Db::dotaz("SET foreign_key_checks = 1;");

  if ($result) {
      return 1;
  }
  return 0;
  // Vrátí 1 pokud v databázi uživatelé s oprávněním = 0 byli odebráni, 0 pokud se akce nepodaří
}

public static function pridaniDodatecnychUdaju($email, $kontaktniUdaje, $odkazNaWeb, $zdravotniOmezeni) {
  // Zkontroluj existenci uživatele s daným e-mailem
  $sql = "SELECT * FROM dodatecne_info WHERE email = ?";
  $existujeUzivatel = Db::dotazJeden($sql, [$email]);

  if ($existujeUzivatel) {
      // Pokud uživatel existuje, provedeme aktualizaci dat
      $sql = "
          UPDATE dodatecne_info
          SET kontaktni_udaje = :kontaktni_udaje, odkaz_na_web = :odkaz_na_web, zdravotni_omezeni = :zdravotni_omezeni
          WHERE email = :email
      ";
  } else {
      // Pokud uživatel neexistuje, provedeme vložení nových dat
      $sql = "
          INSERT INTO dodatecne_info (email, kontaktni_udaje, odkaz_na_web, zdravotni_omezeni)
          VALUES (:email, :kontaktni_udaje, :odkaz_na_web, :zdravotni_omezeni)
      ";
  }

  // Parametry pro dotaz
  $parameters = [
      ':kontaktni_udaje' => $kontaktniUdaje,
      ':odkaz_na_web' => $odkazNaWeb,
      ':zdravotni_omezeni' => $zdravotniOmezeni,
      ':email' => $email
  ];

  // Spuštění dotazu
  $vysledek = Db::dotaz($sql, $parameters);

  // Kontrola výsledku a výpis zprávy
  if ($vysledek) {
      echo "Data byla úspěšně aktualizována.";
  } else {
      echo "Chyba při aktualizaci dat.";
  }
}


public static function serazeniNaAkciPodleUcasti($email){
  // $parameters = array();
   $parameters["email"] = $_SESSION['email'];
 $sql = "
     SELECT * 
     FROM uzivatel inner join ucastnik using(email) inner join soupiska using(id_soup) inner join akce using(id_akce)
     WHERE uzivatel.email like ?
 ";
 if($vysledek =Db::dotazVsechny($sql,[$_SESSION['email']])){
   return $vysledek;
 }
 return 0;
 }
 
 public static function serazeniNaAkciPodleZajmu($email){
 //$parameters = array();
 $parameters["email"] = $_SESSION['email'];
 $sql = "
 SELECT akce.*, uzivatel.* FROM akce INNER JOIN akce_disc ON akce.id_akce = akce_disc.id_akce INNER JOIN sportuje ON akce_disc.id_disc = sportuje.id_disc INNER JOIN uzivatel ON sportuje.email = uzivatel.email WHERE uzivatel.email = ?;
 ";
 if($vysledek =Db::dotazVsechny($sql,[$_SESSION['email']])){
 return $vysledek;
 }
 return 0;
 }



    //vrati posledni id v tabulce
    public function vratPosledniId() {
      $sql = "SELECT id_uziv
              FROM uzivatel
              WHERE opravneni = 0
              ORDER BY id_uziv DESC
              LIMIT 1";
      
      $uziv = Db::dotazJeden($sql);
      if ($uziv === false) return 100;
      else return $uziv['id_uziv'];
    }


    public static function projedVsechnyUzivatele($vysledek) {
    // $id = intval($vysledek);
      $sql = "SELECT * from uzivatel left join dodatecne_info using(email) where email like ?";
              if($vysledek =Db::dotazJeden($sql,[$vysledek]))
              return $vysledek;
    }

    

}
?>