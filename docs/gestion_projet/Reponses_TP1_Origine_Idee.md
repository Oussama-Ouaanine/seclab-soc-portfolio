# TP N°1 : Origine et Test de l'Idée / Origin and Idea Testing
*This document contains both the English and French versions of the TP1 answers.*
*Ce document contient les réponses au TP1 en Anglais et en Français.*

---

# 🇺🇸 ENGLISH VERSION

This document presents the group's brainstorming report, logically guiding our choice toward the design of a **"SOC-in-a-Box" (Packaged Security Operations Center)** solution for SMEs.

## PHASE 1 – PROBLEM IMMERSION

### Exercise 1: Mapping Frustrations

#### A. Experienced Digital Frustrations (Personal)
- **Complexity of cyber defense tools:** Setting up Intrusion Detection Systems (SIEM, IDS) requires configuring multiple disparate components that do not communicate natively.
- **Time wasted:** Trying to manually correlate system logs, network logs, and security alerts scattered across different servers is highly time-consuming.
- **Application security flaws:** Poorly protected web applications are the main entry point for data leaks, often happening without anyone noticing in real-time.

#### B. Frustrations Observed In:
- **SMEs (Small and Medium Enterprises):** They lack the massive budgets required to afford dedicated security teams or highly expensive proprietary hardware. They suffer from ransomware attacks directly.
- **Students/Developers:** It is extremely difficult to find practical (hands-on) laboratories that combine both a realistic application and a fully functional defensive chain (prevention and detection).

---

### Exercise 2: Defectology (Innovation through defects)

- **What system works poorly?** 
  Open Source security solutions (Suricata, Falco, Elastic) are extremely powerful, but their "out-of-the-box" integration is highly complex. Without a unified interface, their adoption fails.
- **Transforming the defect into an opportunity:** 
  *Defect*: SMEs cannot analyze their logs because it is too technically demanding. 
  *Opportunity*: Create an "all-in-one" preconfigured architecture, grouping prevention (AppArmor) and detection (Suricata, Falco), visualizable on a single unified dashboard (Kibana).

#### Intermediate Deliverable (3 Clear Problems)
1. **SMEs** struggle to **detect intrusions in real-time** because **traditional solutions (SOC) are financially inaccessible and too complex for their small teams to maintain.**
2. **System Administrators** struggle to **react quickly to cyberattacks** because **their system and network logs are scattered and not automatically correlated.**
3. **The academic sector** struggles to **effectively teach defensive architectures** because **building complete practical laboratories (Defense in Depth) from scratch is too complex.**

---

## PHASE 2 – IDEA GENERATION

### Exercise 3: Brainwriting 6-3-5
Faced with the problems raised (notably the lack of SME infrastructure supervision), our group converged on these paths:
- **Path 1:** A consulting firm conducting randomized audits (too sporadic, lacks real-time monitoring).
- **Path 2:** A physical hardware box sold with a monitoring subscription (high material and logistics costs).
- **Path 3:** **A SOC-as-a-Service (SOC-in-a-Box) based 100% on Open Source tools**, easily deployable, centralizing Network IDS and Host IDS toward a pre-configured dashboard.

### Exercise 4: Technological SCAMPER (Applied to Path 3)
- **S (Substitute):** Substitute expensive hardware IDS appliances with deployable software probes on existing servers (Suricata / Falco).
- **C (Combine):** Combine network analysis, behavioral analysis (system calls via eBPF with Falco), and proactive access control (AppArmor) within the same Elastic analysis pipeline.
- **E (Eliminate):** Eliminate the pricing barrier of proprietary licenses by relying solely on Open Source industry standards.

### Exercise 5: Impact / Feasibility Matrix
- **Final Chosen Idea:** Deployment of a SOC-in-a-Box architecture (Prevention + Detection).
- **User Impact:** HIGH (Finally allows SMEs to see what is happening on their servers and preemptively detect attacks).
- **Technical Feasibility:** REASONABLE / HIGH (This aligns with our core technical competencies; we master the ELK stack, Docker, and IDS configuration).

---

## PHASE 3 – IDEA STRUCTURING

- **What is the specific problem?**
  Medium-sized businesses lack continuous visibility and protection ("Monitoring") over their infrastructures, making them vulnerable to application and system attacks, as a traditional SOC is too expensive.
- **Who is the target user?**
  SMEs hosting their own Web services (like an application "SecLab"), as well as SMEs wanting to secure and monitor their internal infrastructure without dedicating a multinational-sized budget.
- **What is the proposed solution?**
  A **turnkey SOC-in-a-Box architecture**, built around a SIEM pipeline (Elasticsearch, Kibana, Beats). It combines prevention mechanisms (AppArmor) with a dual detection layer (Suricata for the network, Falco for host events).
- **What is your differentiation?**
  The application of the **Defense in Depth** principle. We don't just provide an isolated tool; we affordably unify the detection of several abstraction layers (Network + OS + App).
- **Why is your solution better than existing ones?**
  Unlike opaque black boxes, our solution is transparent, open-source, highly modular, lightweight (no massive overhead), and natively designed to reduce noise (false positives) by only forwarding correlated security events to visual and actionable dashboards.


<br><br>
---
---
<br><br>


# 🇫🇷 VERSION FRANÇAISE

Ce document présente le compte rendu des réflexions du groupe, guidant logiquement notre choix vers la conception d'une solution de **"SOC-in-a-Box" (Security Operations Center packagé)** pour les PME.

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
