# Réponses au TP N°1 : Origine et Test de l'Idée

Ce document présente le compte rendu des réflexions du groupe, guidant logiquement notre choix vers la conception d'une solution de **"SOC-in-a-Box" (Security Operations Center packagé)** pour les PME.

---

## PHASE 1 – IMMERSION PROBLÈME

### Exercice 1 : Cartographie des frustrations

#### A. Frustrations numériques vécues (personnelles)
- **Complexité des outils de cyberdéfense :** La mise en place de systèmes de détection d'intrusions (SIEM, IDS) nécessite de configurer de multiples briques disparates qui ne communiquent pas nativement.
- **Perte de temps :** Essayer de corréler manuellement des logs systèmes, des logs réseaux et des alertes de sécurité dispersés sur différents serveurs est chronophage.
- **Failles de sécurité applicatives :** Les applications web mal protégées sont la porte d'entrée principale des fuites de données sans que personne ne s'en rende compte en temps réel.

#### B. Frustrations observées chez :
- **PME :** Elles n'ont pas les budgets colossaux nécessaires pour s'offrir des équipes de sécurité dédiées ni du matériel propriétaire très cher. Elles subissent les ransomwares de plein fouet.
- **Étudiants/Développeurs :** Il est très difficile de trouver des laboratoires pratiques (hands-on) réunissant à la fois une application réaliste et une chaîne complète de défense fonctionnelle (prévention et détection).

---

### Exercice 2 : Défectuologie (Innovation par le défaut)

- **Quel système fonctionne mal ?** 
  Les solutions de sécurité Open Source (Suricata, Falco, Elastic) sont extrêmement puissantes, mais leur intégration "out-of-the-box" est très complexe. Sans interface unifiée, leur adoption échoue.
- **Transformons le défaut en opportunité :** 
  *Défaut* : Les PME ne peuvent pas analyser leurs logs car c'est trop technique. 
  *Opportunité* : Créer une architecture "tout-en-un" préconfigurée, regroupant la prévention (AppArmor) et la détection (Suricata, Falco), visualisable sur un seul tableau de bord unifié (Kibana).

#### Livrable intermédiaire (3 problèmes clairs)
1. **Les PME** rencontrent des difficultés à **détecter les intrusions en temps réel** parce que **les solutions traditionnelles (SOC) sont financièrement inaccessibles et trop complexes à maintenir pour leurs petites équipes.**
2. **Les administrateurs systèmes** rencontrent des difficultés à **réagir rapidement aux cyberattaques** parce que **leurs logs systèmes et réseaux sont dispersés et ne sont pas corrélés automatiquement.**
3. **Le milieu académique** rencontre des difficultés à **former efficacement aux architectures défensives** parce que **les laboratoires pratiques complets (Defense in Depth) sont complexes à déployer de zéro.**

---

## PHASE 2 – GÉNÉRATION D'IDÉES

### Exercice 3 : Brainwriting 6-3-5
Face aux problèmes posés (notamment le manque de supervision des PME), notre groupe a convergé vers ces pistes :
- **Piste 1 :** Un cabinet de conseil réalisant des audits aléatoires (trop ponctuel, pas de temps réel).
- **Piste 2 :** Un boîtier physique vendu avec un abonnement de surveillance (coûts matériels logistiques importants).
- **Piste 3 :** **Un SOC-as-a-Service (SOC-in-a-Box) basé 100% sur des outils Open Source**, déployable facilement, centralisant l'analyse réseau (NIDS) et l'analyse comportementale (HIDS) vers un tableau de bord pré-digéré.

### Exercice 4 : SCAMPER technologique (Appliqué à la Piste 3)
- **S (Substituer) :** Substituer les boîtiers IDS matériels coûteux par des sondes logicielles déployables sur les serveurs existants (Suricata / Falco).
- **C (Combiner) :** Combiner l'analyse réseau, l'analyse comportementale (appels systèmes via eBPF avec Falco) et le contrôle d'accès proactif (AppArmor) au sein du même pipeline d'analyse Elastic.
- **E (Éliminer) :** Éliminer la barrière tarifaire des licences propriétaires en s'appuyant uniquement sur des standards Open Source de l'industrie.

### Exercice 5 : Matrice Impact / Faisabilité
- **Idée finale choisie :** Déploiement d'une architecture SOC-in-a-Box (Prévention + Détection).
- **Impact Utilisateur :** ÉLEVÉ (Permet enfin aux PME de voir ce qui se passe sur leurs serveurs et de détecter préventivement les attaques).
- **Faisabilité Technique :** RAISONNABLE / ÉLEVÉE (C'est notre cœur de compétence technique ; nous maîtrisons la pile ELK, Docker, et la configuration des IDS).

---

## PHASE 3 – STRUCTURATION DE L'IDÉE

- **Quel est le problème précis ?**
  Les entreprises de taille moyenne manquent de visibilité et de protection continue ("Monitoring") sur leurs infrastructures, les rendant vulnérables aux attaques applicatives et systèmes, car un SOC classique coûte trop cher.
- **Qui est l'utilisateur cible ?**
  Les TPE/PME hébergeant leurs propres services Web (comme une application applicative de type "SecLab"), ainsi que les PME souhaitant sécuriser et surveiller leur infrastructure interne sans y dédier un budget de multinationale.
- **Quelle est la solution proposée ?**
  Une **architecture SOC-in-a-Box clé en main**, s'articulant autour d'un pipeline SIEM (Elasticsearch, Kibana, Beats). Elle associe des mécanismes de prévention (AppArmor) à une double couche de détection (Suricata pour le réseau, Falco pour les événements hôtes).
- **Quelle est votre différenciation ?**
  L'application du principe de **Défense en Profondeur (Defense in Depth)**. Nous n'apportons pas juste un outil, nous unifions la détection de plusieurs couches d'abstractions (Réseau + OS + App) de manière abordable.
- **Pourquoi votre solution est-elle meilleure que l'existant ?**
  Contrairement aux boîtes noires opaques, notre solution est transparente, open source, hautement modulable, légère (pas d'overhead massif) et conçue nativement pour réduire le bruit (fausses alertes) en ne remontant que les événements de sécurité corrélés sur des tableaux de bord visuels et exploitables.