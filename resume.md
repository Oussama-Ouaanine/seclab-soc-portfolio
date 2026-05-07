# Résumé du Projet / Project Summary

Ce document offre une vue d'ensemble du projet **SecLab SOC**, expliquée pour deux publics différents : les professionnels de l'informatique (Technique) et les personnes non-techniques (Vulgarisation).

---

## 1. Explication Technique (Pour les Professionnels de l'IT / Ingénieurs)

**Le concept :** 
Nous développons un **"SOC-in-a-Box"** (Security Operations Center clé en main) pensé comme un Produit Minimum Viable (MVP) pour les PME, testé et validé à travers un environnement académique de type Projet de Fin d'Année (PFA). 

**L'architecture :**
Le projet repose sur la mise en place d'une infrastructure réseau complète et isolée contenant **3 Machines Virtuelles (VMs)** :
1. **VM Cible (Target)** : Héberge une application web PHP/PostgreSQL délibérément vulnérable ("SecLab") ainsi qu'un serveur SSH. Elle est protégée par un pare-feu (UFW), un agent de surveillance système (Falco utilisant eBPF) et du contrôle d'accès obligatoire (AppArmor). Un agent de collecte de logs (Filebeat) y est déployé.
2. **VM NIDS (Network Intrusion Detection System)** : Agit comme une sonde réseau passive. Elle reçoit une copie du trafic via un port mirroring (SPAN) et utilise **Suricata** pour analyser les paquets en temps réel.
3. **VM Supervision (SIEM)** : Centralise les alertes réseau (Evebox/Suricata) et les logs système (Filebeat/Elasticsearch/Kibana), permettant la corrélation des événements.

**Objectif de Sécurité (L'Axe de Défense) :**
L'objectif est d'implémenter et de valider une approche de **Défense en Profondeur** (Defense in Depth). Nous prouvons que les PME ne peuvent pas compter sur une sécurité périmétrique (un simple pare-feu) face aux cyberattaques modernes. L'environnement simule des scénarios d'attaque de bout en bout (basés sur la **Cyber Kill Chain** de Lockheed Martin), allant de la reconnaissance (Nmap) jusqu'à l'exfiltration et au mouvement latéral (Exploitation de failles web, SSH bruteforce).

Chaque couche de l'architecture doit démontrer sa capacité à détecter ou bloquer une étape spécifique de la Kill Chain.

---

## 2. Explication Non-Technique (Pour le Grand Public / Investisseurs B2B)

**Le problème que nous résolvons :**
Aujourd'hui, beaucoup de petites et moyennes entreprises (PME) pensent qu'être protégées par un mot de passe ou un antivirus de base est suffisant. Or, les pirates informatiques utilisent des techniques beaucoup plus sophistiquées, comparables à des cambrioleurs qui étudient non seulement la porte d'entrée, mais aussi les fenêtres, le sous-sol et les habitudes des propriétaires. Quand les pirates s'introduisent dans le réseau d'une PME, ils ne sont souvent remarqués que lorsqu'il est trop tard (par exemple, lors d'un vol de données ou d'une attaque par ransomware).

**Notre solution (Ce que nous avons construit) :**
Nous avons créé un environnement de test ultra-sécurisé, comme un "laboratoire d'observation", où nous avons placé une application web volontairement fragile (c'est l'entreprise simulée). 

Autour de cette application, nous avons installé notre "SOC-in-a-Box" (Un centre de contrôle de sécurité "tout inclus"). Ce système fonctionne comme un système de sécurité de maison intelligent, mais pour les réseaux informatiques :
- **Des caméras de sécurité invisibles (NIDS) :** Elles surveillent chaque donnée qui entre ou sort du réseau sans ralentir le fonctionnement de l'entreprise.
- **Des alarmes internes (HIDS/Falco) :** Si un cambrioleur (hacker) arrive à rentrer, ce système détecte s'il essaie de faire quelque chose de bizarre à l'intérieur de la maison (comme ouvrir un coffre-fort).
- **Le poste de commandement (SIEM) :** Un tableau de bord affiche toutes les alertes en temps réel, permettant à l'équipe de sécurité de voir exactement d'où vient la menace et comment l'arrêter avant qu'elle ne cause des dommages.

**Pourquoi c'est important ?**
Notre projet démontre qu'une petite entreprise peut avoir accès à une sécurité de "niveau grande banque" d'une façon simple et centralisée. Nous prouvons que peu importe comment un pirate tente d'entrer, il y aura toujours une alarme prête à sonner. C'est l'avenir de la protection clé en main pour les PME.

