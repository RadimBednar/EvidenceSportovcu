<?php

 class LdapKontroler extends Kontroler {
    public function zpracuj($parametry)
    {
        
        
        
        $modelUzivatel = new ModelyUzivatel;
        $this->data["uzivatel"]=$modelUzivatel->vratVsechnyUzivatele();
        $this->pohled="ldap";
    }
 }


?>
