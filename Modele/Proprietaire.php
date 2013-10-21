<?php

final class Proprietaire extends CorrespondanceTable implements CorrespondanceTableInterface
{
	private $_S_nom;

	private $_S_prenom;

	private $_O_chat = null;

	public function __construct()
	{
		parent::__construct(Constantes::TABLE_PROPRIETAIRE);
	}

	// Mes mutateurs (setters)
	public function changeIdentifiant ($identifiant)
	{
		$this->_I_identifiant = $identifiant;
	}

	public function changeNom ($nom)
	{
		$this->_S_nom = $nom;
	}

	public function changePrenom ($prenom)
	{
		$this->_S_prenom = $prenom;
	}

	// Mes accesseurs (getters)
	public function donneNom ()
	{
		return $this->_S_nom;
	}

	public function donnePrenom ()
	{
		return $this->_S_prenom;
	}

	public function donneChat ()
	{
		return $this->_O_chat;
	}

    public function trouverParIdentifiantUtilisateur ($I_identifiant)
	{
		$S_requete    = "SELECT id, nom, prenom, id_chat  FROM " . $this->_S_nomTable .
                        " WHERE id_utilisateur = '$I_identifiant'";

		$O_connexion  = ConnexionMySQL::recupererInstance();

		if ($A_proprietaire = $O_connexion->projeter($S_requete))
		{
			// On sait donc qu'on aura 1 seul enregistrement dans notre tableau
			$O_proprietaire = $A_proprietaire[0];
			// Je récupère son identifiant et je le stocke
			// dans la variable d'instance prévue à cet effet
			$this->_I_identifiant = $O_proprietaire->id;
			$this->_S_nom         = $O_proprietaire->nom;
			$this->_S_prenom      = $O_proprietaire->prenom;

			// je cherche le chat relié à ce propriétaire

			$O_chat = new Chat();
			$O_chat->trouverParIdentifiant((integer)$O_proprietaire->id_chat);

			// je le range dans ma variable d'instance
			$this->_O_chat = $O_chat;
		}
		else
		{
			// Je n'ai rien trouvé, je lève une exception pour le signaler au client de ma classe
			throw new Exception ("Il n'existe pas d'utilisateur d'identifiant '$I_identifiant'");
		}
	}

	public function trouver()
	{
        // à implémenter
	}

	public function creer()
	{
        // à implémenter
	}

	public function actualiser()
	{
        // à implémenter
	}

	public function supprimer()
	{
        // à implémenter
	}

    public function trouverParIdentifiant ($I_identifiant)
    {
        // à implémenter
    }
}