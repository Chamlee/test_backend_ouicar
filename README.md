# Exercice technique OuiCar

### Consignes générales

Pour résoudre cet exercice, veuillez :

- Forker ce repository
- Répondre aux questions une à une __sur des branche distinctes__. La branche N démarre bien entendu du code de la branche N-1.
- Envoyer votre repository ainsi que les explications pour lancer votre serveur par mail
- N'oubliez pas de nous dire combien de temps vous avez pris à faire cet exercice et comment vous l'avez trouvé. Cela n'est pas pris en compte dans l'évaluation mais nous servira pour l'améliorer :-)

Dans cet exercice vous devrez reproduire le coeur du système le location de OuiCar : le dépôt de véhicules ainsi que leur réservation. À vous d'utiliser les technologies qu'il convient afin d'implémenter votre solution. Les contraintes sont les suivantes :
- Le language de programmation devra être PHP 7
- Utilisation du framework Symphony
- La base de donnée devra être de type relationnelle.

Il n'est pas nécessaire d'implémenter la logique d'authentification, on considèrera que l'API permet d'exécuter des actions pour n'importe quel utilisateur.


#### BONUS POINTS* (si vous avez trouvé ce test trop facile) :
- 3pts : Documentation de l'API (swagger ou autre...)
- 3pts : Tests unitaires - TDD
- 2pt : Déployer votre serveur en ligne (heroku, aws...)

*\*Les Bonus Points sont très appréciés mais ne sont pas obligatoires*

## Question n°1 :

Écrire une API pour créer une voiture sur OuiCar. Une voiture a les paramètres suivant des contraintes (à vérifier) listés ci-dessous :

- mileage : kilométrage (1 = entre 0 et 50 000 km, 2 = entre 50K et 100K, 3 = entre 100K et 150K, 4 = + de 150K)
- price_day_1 : prix en centimes du premier jour et du second jour
- price_day_3 : prix en centimes du jour 3 au jour 6 inclus, inférieur à price_day_1
- price_day_7 : prix en centimes à partir du 7ème jour de location, inférieur à price_day_3

## Question n°2 :

Écrire une API pour signifier qu'une voiture est indisponible et qu'un locataire ne peut pas la louer.

- Les indisponibilités sont des périodes qui partent d'une date/heure de début jusqu'à une date/heure de fin
- Une indisponibilité s'applique à une voiture donnée
- La date/heure de début doit être inférieure à la date/heure de fin et supérieure à la date/heure d'aujourd'hui

## Question n°3 :

Écrire une API pour réserver un véhicule à la location. Une location s'effectue entre deux dates/heures (début - fin) sur une voiture donnée pour un locataire inscrit dans notre base utilisateur, et il faut bien sûr que la voiture soit disponible à la location sur cette période.

Une voiture est indisponible si le propriétaire l'a signifié via l'API de la question n°2, ou si elle a déjà été réservée sur ces créneaux.

Le prix de la réservation est calculé grâce à sa durée en jours et le prix journalier de la voiture, qui est dégressif. Un jour comptabilisé est un jour entammé (ex: 01/01 à 14h --> 01/02 à 13h = 1 jour, 01/01 à 14h --> 01/02 à 15h = 2 jours).

Exemple : Je fais une réservation du 01/01 15h au 10/01 16h. Cela correspond à 10 jours de location, et le prix calculé sera :
```
booking->price =  2 * car->price_day_1 + 4 * car->price_day_3 + 4 * car->price_day_7
```

## Question n°4 :

Voilà 3 ans que notre service de réservation existe et que notre magnifique API est utilisée par de nombreux terminaux front (l'app ios, l'app android, le web, le webmobile, ...)

Nous souhaitons changer le mileage qui n'est pas assez précis (valeur discrète pour le moment) et permettre à nos propriétaires de mettre le kilométrage exact de leur véhicule.

Mettre à jour l'API en conséquence
