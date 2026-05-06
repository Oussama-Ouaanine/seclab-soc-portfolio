# Google Form : Diagnostic de Sécurité & Validation de Marché (SOC-in-a-Box)

*Ce questionnaire est maintenant parfaitement aligné avec votre Cahier des Charges (PFA). Son but n'est pas juste de demander "Voulez-vous acheter ?", mais de prouver techniquement que les entreprises manquent de Défense en Profondeur (NIDS, HIDS, MAC, SIEM) et que votre architecture "SOC-in-a-Box" est la réponse exacte à ce vide.*

---

# 🇫🇷 VERSION FRANÇAISE

**Titre du formulaire :** Enquête : Supervisez-vous efficacement vos serveurs face aux cyberattaques ?
**Description :** Bonjour ! Dans le cadre de notre Projet de Fin d'Année en ingénierie de la Cybersécurité, nous concevons une architecture complète de "Défense en Profondeur" pré-packagée (SOC-in-a-Box) pour les entreprises. Ce rapide diagnostic de 2 minutes nous aidera à valider l'état actuel des défenses dans les organisations. Vos réponses sont strictement anonymes.

### Section 1 : Profil de l'Organisation
**1. Quel est votre poste actuel ?** (Choix multiple)
- Administrateur Système / Réseau
- Analyste / Ingénieur Cybersécurité
- DSI / CTO / Responsable IT
- Développeur / DevSecOps
- Autre : ________

**2. Quelle est la taille de votre organisation ?** (Cœur de cible : PME)
- TPE (1 à 10 employés)
- PME (11 à 50 employés)
- PME / ETI (51 à 250 employés)
- Grande entreprise (+ de 250 employés)

**3. Hébergez-vous des applications web ou des services en ligne ?** (Choix unique)
- Oui, en interne (On-premise)
- Oui, dans le Cloud (AWS, Azure, VPS...)
- Non

### Section 2 : Diagnostic Technique (Prouver l'absence de Défense en Profondeur)
*Cette section montre que la plupart des entreprises s'arrêtent au pare-feu et n'ont pas la stack technique de votre PFA.*

**4. Avez-vous mis en place un système de détection d'intrusions RÉSEAU (NIDS comme Suricata ou Snort) ?** (Choix unique)
- Oui, et il est opérationnel
- Non, c'est trop complexe à configurer et maintenir
- Non, nous n'en voyons pas l'utilité
- Je ne sais pas

**5. Surveillez-vous les comportements suspects au niveau des APPELS SYSTÈME (HIDS comme Falco, auditd) ?** (Choix unique)
- Oui, nous détectons par exemple si un shell est ouvert par le serveur web
- Non, nous n'avons pas la visibilité sur les processus internes (syscalls)
- Je ne sais pas

**6. Utilisez-vous des profils de Contrôle d'Accès Obligatoire (MAC) pour restreindre vos applications (ex: AppArmor, SELinux) ?** (Choix unique)
- Oui (en mode enforce/bloquant)
- Oui (mais seulement en mode audit/complain)
- Non, c'est trop complexe de créer des profils sans casser l'application
- Je ne sais pas ce que c'est

**7. Vos logs (réseau, système, applications) sont-ils centralisés et corrélés (SIEM comme la stack ELK) ?** (Choix unique)
- Oui, avec des tableaux de bord (Dashboards) clairs et des alertes configurées
- Partiellement (les logs sont collectés, mais peu ou pas exploités)
- Non, tout est dispersé sur les différentes machines

### Section 3 : Les Frustrations (Le problème métier)
**8. De manière générale, qu'est-ce qui vous empêche aujourd'hui de déployer une défense multicouche complète sur vos serveurs ?** (Plusieurs choix)
- Le prix exorbitant des licences des solutions SOC/SIEM commerciales
- Le manque de temps et de personnel qualifié en interne
- La complexité d'intégration (faire communiquer Suricata, Falco et ELK ensemble est difficile)
- La "fatigue des alertes" (trop de faux positifs, impossible de distinguer la vraie menace)

### Section 4 : Validation de la Solution (L'offre SOC-in-a-Box SecLab)
**9. Si une solution clé-en-main ("SOC-in-a-Box" 100% open-source) était disponible, intégrant nativement la détection réseau, l'analyse comportementale hôte, et un tableau de bord unifié sans frais de licence, cela vous intéresserait-il ?** (Choix unique)
- Oui, absolument. Cela résoudrait un grand manque de visibilité.
- Peut-être, si le déploiement ne perturbe pas nos services actuels.
- Non, nos systèmes nous suffisent actuellement.

**10. Laquelle de ces garanties serait la plus décisive pour adopter une telle solution ?** (Plusieurs choix)
- Aucun impact ou coupure sur le trafic métier existant (écoute en mode passif/miroir)
- Transparence totale (outils basés sur des standards reconnus)
- Alertes claires et triées pour ne pas inonder les équipes
- Coût prévisible adapté au budget d'une PME

**11. Avez-vous des commentaires, conseils ou attentes concernant un tel développement ?** (Texte libre)
- [ Réponse libre ]


<br><br>
---
---
<br><br>


# 🇺🇸 ENGLISH VERSION

**Form Title:** Survey: Are your servers actively monitored against cyberattacks?
**Description:** Hello! As part of our Cybersecurity Engineering End-of-Year Project, we are designing a complete, pre-packaged "Defense in Depth" architecture (SOC-in-a-Box) for organizations. This quick 2-minute diagnostic will help us validate the current state of defenses in the real world. Your responses are strictly anonymous.

### Section 1: Organization Profile
**1. What is your current role?** (Multiple choice)
- System / Network Administrator
- Cybersecurity Analyst / Engineer
- CTO / IT Manager
- Developer / DevSecOps
- Other: ________

**2. What is the size of your organization?** (Multiple choice)
- Micro-business (1 to 10 employees)
- Small business (11 to 50 employees)
- Medium enterprise (51 to 250 employees)
- Large enterprise (+250 employees)

**3. Do you host web applications or online services?** (Single choice)
- Yes, On-premise
- Yes, in the Cloud (AWS, Azure, VPS...)
- No

### Section 2: Technical Diagnostic (Validating the lack of Defense-in-Depth)
**4. Do you use a NETWORK Intrusion Detection System (NIDS like Suricata or Snort)?** (Single choice)
- Yes, and it's operational
- No, it's too complex to configure and maintain
- No, we don't see the need
- I don't know

**5. Do you monitor suspicious behavior at the SYSTEM CALL level (HIDS like Falco, auditd)?** (Single choice)
- Yes, we can detect if a shell is spawned by a web server
- No, we lack visibility into internal processes/syscalls
- I don't know

**6. Do you enforce Mandatory Access Control (MAC) policies for your applications (e.g., AppArmor, SELinux)?** (Single choice)
- Yes (in enforce/blocking mode)
- Yes (but only in audit/complain mode)
- No, writing profiles without breaking the app is too complex
- I don't know what this is

**7. Are your logs (network, system, applications) centralized and correlated (SIEM like the ELK stack)?** (Single choice)
- Yes, with clear dashboards and alerts
- Partially (logs are collected but rarely exploited)
- No, everything is scattered across different machines

### Section 3: Frustrations (The Business Problem)
**8. Generally, what prevents you from deploying a comprehensive multi-layer defense on your servers today?** (Checkboxes)
- The exorbitant licensing cost of commercial SOC/SIEM solutions
- Lack of time and qualified internal staff
- Integration complexity (making Suricata, Falco, and ELK communicate is hard)
- "Alert fatigue" (too many false positives, hard to identify real threats)

### Section 4: Validating the Solution (SecLab SOC-in-a-Box)
**9. If a turnkey, 100% open-source "SOC-in-a-Box" solution was available—natively integrating network detection, host behavioral analysis, and a unified dashboard with zero licensing fees—would you be interested?** (Single choice)
- Yes, absolutely. It would fill a major visibility gap.
- Maybe, if deployment doesn't disrupt our current services.
- No, our current setup is sufficient.

**10. Which guarantee would be the most decisive factor for adopting such a solution?** (Checkboxes)
- No impact or disruption to existing business traffic (passive/mirror listening mode)
- Total transparency (tools based on recognized industry standards)
- Clear, filtered alerts to avoid overwhelming the team
- Predictable cost tailored to an SME budget

**11. Do you have any comments, advice, or expectations regarding this project?** (Paragraph)
- [ Free text answer ]
