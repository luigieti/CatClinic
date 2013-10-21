<?php

final class Utilisateur extends CorrespondanceTable implements CorrespondanceTableInterface
{
	private $_S_login;

	private $_S_motPasse;

	private $_I_admin;

	private $_O_proprietaire = null;

	public function __construct()
	{
		parent::__construct(Constantes::TABLE_USER);
	}

	// Mes mutateurs (setters)
	public function changeIdentifiant ($identifiant)
	{
		$this->_I_identifiant = $identifiant;
	}

	public function changeLogin ($login)
	{
		$this->_S_login = $login;
	}

	public function changeMotDePasse ($motPasse)
	{
		$this->_S_motPasse = $motPasse;
	}

	public function changeAdmin ($admin)
	{
		$this->_I_admin = $admin;
	}

	// Mes accesseurs (getters)
	public function donneMotDePasse ()
	{
		return $this->_S_motPasse;
	}

	public function donneLogin ()
	{
		return $this->_S_login;
	}

	public function estAdministrateur ()
	{
		return (1 == $this->_I_admin);
	}

	public function estProprietaire ()
	{
		return !is_null($this->_O_proprietaire);
	}

	public function donneProprietaire ()
	{
		return $this->_O_proprietaire;
	}

	public function trouverParIdentifiant ($I_identifiant)
	{
		$S_requete    = "SELECT id, login, motdepasse, admin FROM " . $this->_S_nomTable .
                        " WHERE id = $I_identifiant";

		$O_connexion  = ConnexionMySQL::recupererInstance();

		if ($A_utilisateur = $O_connexion->projeter($S_requete))
		{
			// on sait donc qu'on aura 1 seul enregistrement dans notre tableau au max
			$O_utilisateur = $A_utilisateur[0];
			// Je récupère son identifiant et je le stocke
			// dans la variable d'instance prévue à cet effet
			$this->_I_identifiant = $O_utilisateur->id; // ou bien $I_identifiant
			$this->_S_login       = $O_utilisateur->login;
			$this->_I_admin       = $O_utilisateur->admin;
		}
		else
		{
			// Je n'ai rien trouvé, je lève une exception pour le signaler au client de ma classe
			throw new Exception ("Il n'existe pas d'utilisateur d'identifiant '$I_identifiant'");
		}
	}

	public function trouver ()
	{
		$S_login        = $this->donneLogin();

		$S_requete    = "SELECT id, login, motdepasse, admin FROM " . $this->_S_nomTable .
                        " WHERE login = '$S_login'";

		$O_connexion  = ConnexionMySQL::recupererInstance();

		if ($A_utilisateur = $O_connexion->projeter($S_requete))
		{
			// on sait donc qu'on aura 1 seul enregistrement dans notre tableau, car login est unique
			$O_utilisateur = $A_utilisateur[0];

			$this->_I_identifiant = $O_utilisateur->id;
			$this->_S_motPasse    = $O_utilisateur->motdepasse;
			$this->_I_admin       = $O_utilisateur->admin;

			// je regarde si un propriétaire est relié à mon compte utilisateur
            // mais seulement si je ne suis pas admin

			if (!$this->estAdministrateur())
			{
				// Un utilisateur n'est pas forcément un propriétaire, mais s'il l'est
				// il faut récupérer ses données de propriétaire !
				$O_proprietaire = new Proprietaire();

				try {
					$O_proprietaire->trouverParIdentifiantUtilisateur ($this->_I_identifiant);
				} catch (Exception $O_exception) {
					$O_proprietaire = null;
				}

				$this->_O_proprietaire = $O_proprietaire;
			}
		}
		else
		{
			throw new Exception ("Il n'existe pas d'utilisateur pour ce login");
		}
	}

	public function creer()
	{
	}

	public function actualiser()
	{
		if (!is_null($this->_I_identifiant))
		{
			if (empty($this->_S_login))
			{
				throw new Exception ("L'utilisateur n'a pas de login");
			}

			$S_login     = $this->_S_login;

			$S_requete   = "UPDATE " . $this->_S_nomTable . " SET login = '$S_login' WHERE id = " . $this->_I_identifiant;
			$O_connexion = ConnexionMySQL::recupererInstance();
			$O_connexion->modifier($S_requete);

			return; // j'ai ce que je voulais...je débranche ici
		}

		throw new Exception ("Impossible de mettre a jour un utilisateur qui n'existe pas");
	}

	// Attention : dans notre schéma de base de données, nous avons mis une clause de suppression de type
	// "cascade" au niveau de la table des propriétaires. Ce qui signifie qu'une suppression d'un utilisateur
	// entraine celle du propriétaire associé !

	public function supprimer()
	{
		if (!is_null($this->_I_identifiant))
		{
			// il me faut absolument un identifiant pour faire une suppression
			$S_requete   = "DELETE FROM " . $this->_S_nomTable . " WHERE id = " . $this->_I_identifiant;
			$O_connexion = ConnexionMySQL::recupererInstance();

			// si modifier echoue elle me renvoie false, si aucun enregistrement n'est supprimé, elle renvoie zéro
			// attention donc à bien utiliser l'égalité stricte ici !
			if (false === $O_connexion->modifier($S_requete))
			{
				throw new Exception ("Impossible d'effacer l'utilisateur d'identifiant " . $this->_I_identifiant);
			}

			return;
		}

		throw new Exception ("Impossible d'effacer un utilisateur sans identifiant");
	}
}