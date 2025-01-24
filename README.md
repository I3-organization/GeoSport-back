# Group : I3

**Members :**
- DEVISME Valentin
- METAHRI Nafaa
- OBEIN Thomas

**Needs :**
Connaître les lieux de sport

Decathlon souhaite fournir à ses clients un nouveau service pour connaître l'ensemble des lieux permettant la pratique de sport (salle de sport, terrain de foot, parc, ...).
Les critères auxquels le service doit répondre
* Fournir une liste des points d'intérêt sportif autour d'une position
* Les lieux Decathlon font parties de la liste des points d'intérêt
* Recherche par critères
* L'affichage doit pouvoir se faire dans plusieurs langues
* Le service doit permettre de gérer des notes et des commentaires sur ces lieux

En critères optionnels
* Fournir les horaires d'ouvertures/fermeture
* Fournir un moyen de contact


___

## Docker

Un docker est mis à disposition en tant que submodule, pour le récupérer avec le projet, pensez à lancer 

`git submodule update --init`

Il existe un Makefile avec plusieurs commandes utiles pour le développement, pour les voir, lancez un `make` sur la racine du projet.

Par exemple, pour lancer tout l'environnement de dev sous docker, il suffit de lancer un `make up`

