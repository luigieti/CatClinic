<?php

// Toute classe qui prétend authentifier doit se conformer à cette interface !

interface Authentificateur
{
    public function authentifier(Utilisateur $user, $password);
}