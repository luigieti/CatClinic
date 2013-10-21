<?php

abstract class Listeur {

    protected $_S_nomTable;

    public function recupererCible()
    {
        return $this->_S_nomTable;
    }

    public function recupererNbEnregistrements()
    {
        $S_requete      = 'SELECT count(*) AS nb FROM ' . $this->recupererCible();
        $O_connexion    = ConnexionMySQL::recupererInstance();

        $A_enregistrements = $O_connexion->projeter($S_requete);
        $O_enregistrement = $A_enregistrements[0];

        return $O_enregistrement->nb;
    }
}