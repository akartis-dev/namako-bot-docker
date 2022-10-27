# Symfony facebook bot

Ce projet est un bot youtube permettant de télécharger des fichiers audio trouvé sur youtube

Avec une version de php 8.1 ça sert aussi à une étude de la nouvelle syntaxe de php 8

### Techno utilisé

* **Symfony** pour la partie interaction avec facebook messenger
* **Nodejs** pour la recherche sur youtube
* **Symfony messenger** pour gérer les parties asynchrones(recherche sur l'api node, téléchargement du fichier mp3,
  envoie du fichier).
* **Youtube-dl** pour le téléchargement direct du fichier
