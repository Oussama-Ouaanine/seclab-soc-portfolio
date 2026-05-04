# Table des matières

**1. Introduction** ...... 8
- 1.1 Contexte général ...... 8
- 1.2 Problématique et motivation du projet ...... 8
- 1.3 Motivation métier ...... 9
- 1.4 Objectifs du projet ...... 10
- 1.5 Méthodologie adoptée ...... 11
- 1.6 Plan de développement ...... 11

**2. État de l'art** ...... 12
- 2.1 Présentation du protocole SMPP ...... 12
  - 2.1.1 Architecture et Communication SMPP ...... 12
  - 2.1.2 Types de PDUs SMPP ...... 13
  - 2.1.3 Structure des PDUs SMPP ...... 14
  - 2.1.4 Exemple de PDU ...... 14
- 2.2 Outils et solutions existantes d'analyse de logs SMPP ...... 17
  - 2.2.1 Approches d'analyse manuelle traditionnelle ...... 17
  - 2.2.2 Solutions de décodage via outils tiers spécialisés ...... 18
  - 2.2.3 Analyse comparative et synthèse critique ...... 20

**3. Analyse des besoins et spécifications** ...... 22
- 3.1. Besoins fonctionnels ...... 22
- 3.2 Besoins techniques ...... 23

**4. Conception globale et architecture** ...... 25
- 4.1 Objectifs de l'architecture ...... 25
- 4.2 Description des composants ...... 25
- 4.3 Schéma d'architecture proposé ...... 27
- 4.4 Flux opérationnel détaillé ...... 28
- 4.5 Exemple concret ...... 28
- 4.6 Mapping Elasticsearch minimal recommandé ...... 29
- 4.7 Conclusion de la conception ...... 30

**5. Implémentation et développement** ...... 31
- 5.1 Développement du script SMPP Parser ...... 31
  - 5.1.1 Architecture et choix de conception ...... 32
  - 5.1.2 Logique de fonctionnement et workflow détaillé ...... 33
  - 5.1.3 Exemples concrets d'utilisation et résultats ...... 37
  - 5.1.4 Configuration et paramètres du parser SMPP ...... 44
- 5.2 Intégration avec ELK Stack ...... 46
  - 5.2.1 Installation et préparation de l'environnement ...... 47
  - 5.2.2 Configuration et démarrage d'Elasticsearch ...... 48
  - 5.2.3 Configuration et démarrage de Kibana ...... 52
  - 5.2.4 Configuration de Logstash et création du pipeline ...... 55
- 5.3 Visualisation avec Kibana ...... 59
  - 5.3.1 Accès à Kibana et vérification des index ...... 59
  - 5.3.2 Création de la Data View ...... 60
  - 5.3.3 Exploration des données avec Discover ...... 61
  - 5.3.4 Création de dashboards ...... 64
- 5.4 Synthèse de l'implémentation ...... 67

**6. Résultats et validation** ...... 69
- 6.1 Jeux de données et scénario de test ...... 69
- 6.2 Résultats obtenus ...... 69
- 6.3 Validation ...... 70
- 6.4 Limites et améliorations possibles ...... 70

**7. Conclusion et perspectives** ...... 71
- 7.1 Synthèse des réalisations ...... 71
- 7.2 Apports du projet ...... 71
- 7.4. Retour d'expérience ...... 71

**Bibliographie** ...... 72
