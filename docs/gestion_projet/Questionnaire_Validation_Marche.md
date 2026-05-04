# Google Form : The "Perfect" Market Validation Survey (SOC-in-a-Box)

*This is the ultimate version, combining the best profiling questions, problem validation, and feature-testing from all our previous drafts.*
*Voici la version ultime, combinant le meilleur profilage, l'étude des problèmes, et le test de fonctionnalités de tous nos brouillons.*

---

# 🇺🇸 ENGLISH VERSION
**Form Title:** Security Monitoring & SOC Challenges in SMEs  
**Description:** Hello! As part of an academic entrepreneurship project, we are developing a "SOC-in-a-Box" (turnkey, Open Source Security Operations Center) designed to make intrusion detection accessible without the massive enterprise price tag. This 2-minute survey will help us understand your daily challenges. All responses are strictly confidential.

### Section 1: Your Profile
**1. What is your current role?** (Multiple choice)
- System / Network Administrator
- Cybersecurity Analyst / Engineer
- DevOps / DevSecOps
- CTO / IT Manager
- Developer
- Other: ________

**2. What is your organization's primary sector?** (Multiple choice)
- Tech / SaaS / Software
- Finance / Banking / Insurance
- Healthcare / Medical
- E-commerce / Retail
- Public Sector / Education
- Manufacturing / Industry
- Other

**3. What is the size of the infrastructure/company you manage?** (Multiple choice)
- 1 to 10 employees
- 11 to 50 employees
- 51 to 250 employees
- 250+ employees

**4. What is the nature of your IT infrastructure?** (Multiple choice)
- 100% On-premise (local servers)
- 100% Cloud (AWS, Azure, GCP...)
- Hybrid (On-prem + Cloud)
- Primarily SaaS / Web Hosting

### Section 2: Your Current Challenges
**5. How do you currently monitor your server and application security?** (Checkboxes)
- No continuous monitoring (we react after incidents)
- Manual checking of basic logs (syslog, apache logs...)
- Open-source tools (but operated separately / not correlated)
- Commercial SIEM / SOC (Splunk, Datadog, etc.)
- Outsourced to a managed security provider (MSSP)

**6. What is your biggest frustration with current security tools (IDS, SIEM)?** (Checkboxes - Max 3)
- Licensing costs are too expensive
- Open-source tools are too complex to integrate together
- Too many false positive alerts ("alert fatigue")
- Lack of a clean, single unified dashboard
- We lack the qualified staff to analyze the logs

**7. On a scale of 1 to 5, how difficult/time-consuming is it to deploy and correlate security tools like Suricata or Falco "from scratch"?** (Linear Scale)
- 1 (Very easy) -------- 5 (Highly complex, requires deep expertise)

**8. Is regulatory compliance a major driver for improving your security?** (Checkboxes)
- Yes, GDPR (Data Privacy) dictates our choices
- Yes, NIS2 / similar directives
- Yes, ISO 27001 / SOC 2 requirements
- No, we improve security for operational reasons, not compliance

### Section 3: Validating the "SOC-in-a-Box"
**9. If a pre-packaged "SOC-in-a-Box" (covering Network, OS, and App layers with a unified dashboard) existed, would this address a real pain point for your team?** (Multiple choice)
- Yes, absolutely
- Somewhat, depending on ease of setup
- No, we are satisfied with what we have

**10. What feature would be the MOST important to you in such a solution?** (Checkboxes - Max 2)
- Automated rapid deployment (Docker/Ansible)
- Open Source transparency (No black box)
- A clean, noise-free dashboard focusing only on critical correlated alerts
- SME-friendly pricing (No excessive per-GB log fees)
- Out-of-the-box threat intelligence rules

**11. Would you trust a solution based on established Open Source standards (Suricata, Falco, AppArmor) packaged into a managed pipeline?** (Multiple choice)
- Yes, these are solid industry standards
- No, I prefer enterprise proprietary solutions
- I would need to test/evaluate the interface first

### Section 4: Conclusion
**12. Any advice, feature requests, or remarks on this project?** (Paragraph - Optional)
- [ Free text answer ]

**13. Would you be interested in acting as a beta tester or receiving news when our MVP is ready?** (Checkboxes)
- Yes, please contact me (leave email below)
- No thanks

**Email address:** (Short answer - Optional)
- [ ______ ]


<br><br>
---
---
<br><br>


# 🇫🇷 VERSION FRANÇAISE
**Titre du formulaire :** Défis de la Supervision de Sécurité (SOC) dans les PME  
**Description :** Bonjour ! Dans le cadre d'un projet académique d'entrepreneuriat, nous concevons un "SOC-in-a-Box" (Security Operations Center clé en main et Open Source) pensé pour rendre la détection d'intrusions accessible sans les prix exorbitants des outils d'entreprise. Ce sondage (2 min) nous aidera à comprendre vos défis quotidiens. Vos réponses sont strictement confidentielles.

### Section 1 : Votre Profil
**1. Quel est votre poste actuel ?** (Choix multiple)
- Administrateur Système / Réseau
- Analyste / Ingénieur Cybersécurité
- DevOps / DevSecOps
- DSI / CTO / Responsable IT
- Développeur
- Autre : ________

**2. Quel est le secteur d'activité principal de votre organisation ?** (Choix multiple)
- Tech / Éditeur Logiciel / SaaS
- Finance / Banque / Assurance
- Santé / Médical
- E-commerce / Distribution
- Secteur Public / Éducation
- Industrie / Manufacture
- Autre

**3. Quelle est la taille de l'infrastructure / de l'entreprise que vous gérez ?** (Choix multiple)
- 1 à 10 employés
- 11 à 50 employés
- 51 à 250 employés
- Plus de 250 employés

**4. Quelle est la nature de votre infrastructure IT ?** (Choix multiple)
- 100% On-premise (serveurs locaux)
- 100% Cloud (AWS, Azure, GCP...)
- Hybride (On-premise + Cloud)
- Principalement hébergement web standard / SaaS

### Section 2 : Vos Défis Actuels
**5. Actuellement, comment surveillez-vous la sécurité de votre infrastructure ?** (Cases à cocher)
- Aucune supervision proactive (nous réagissons après incident)
- Vérification manuelle de logs basiques (syslog, logs apache...)
- Outils Open Source (mais séparés / non corrélés)
- SIEM / SOC commercial en interne (Splunk, Datadog, etc.)
- Infogérance de sécurité (MSSP externalisé)

**6. Quelle est votre plus grande frustration avec les outils de sécurité actuels (IDS, SIEM) ?** (Cases à cocher - Max 3)
- Le coût des licences est trop élevé
- La complexité d'intégration et de configuration (les outils ne communiquent pas entre eux)
- La surcharge d'alertes ("alert fatigue") et les faux positifs
- Le manque d'un tableau de bord unique et clair
- Le manque de personnel qualifié en interne pour analyser les logs

**7. Sur une échelle de 1 à 5, évaluez la difficulté/la perte de temps pour déployer et corréler des solutions comme Suricata ou Falco "from scratch" :** (Échelle linéaire)
- 1 (Très facile) -------- 5 (Très complexe, nécessite une forte expertise)

**8. La conformité réglementaire est-elle un moteur majeur pour l'amélioration de votre sécurité ?** (Cases à cocher)
- Oui, le RGPD dicte nos choix
- Oui, la directive NIS2 / équivalent
- Oui, les exigences ISO 27001 / SOC 2
- Non, nous nous améliorons pour des raisons opérationnelles, pas normatives

### Section 3 : Validation de notre Solution (Le SOC-in-a-Box)
**9. Si un "SOC-in-a-Box" pré-packagé (couvrant le Réseau, l'OS et l'Applicatif sur un tableau de bord unique) existait, cela résoudrait-il une vraie difficulté pour votre équipe ?** (Choix multiple)
- Oui, absolument
- Partiellement, à voir selon la facilité de mise en place
- Non, notre solution actuelle nous satisfait

**10. Quelle fonctionnalité serait LA PLUS importante pour vous dans ce type de solution ?** (Cases à cocher - Max 2)
- Un déploiement automatisé et rapide (Docker/Ansible)
- La transparence de l'Open Source (pas de boîte noire)
- Un tableau de bord épuré, focalisé uniquement sur les alertes critiques corrélées
- Un tarif adapté aux PME (pas de facturation excessive au Go de log)
- Des règles de détection pré-configurées (Threat Intelligence intégrée)

**11. Ferez-vous confiance à une solution s'appuyant sur des standards Open Source reconnus (Suricata, Falco, AppArmor) packagés dans une interface gérée ?** (Choix multiple)
- Oui, ce sont des standards ultra-solides
- Non, je préfère les solutions propriétaires d'entreprise
- Je voudrais d'abord tester/évaluer l'interface avant de me prononcer

### Section 4 : Conclusion
**12. Avez-vous des conseils, des requêtes de fonctionnalités ou des remarques sur ce projet ?** (Paragraphe - Facultatif)
- [ Réponse libre ]

**13. Seriez-vous intéressé(e) pour tester en avant-première notre MVP ou recevoir des nouvelles de notre projet ?** (Cases à cocher)
- Oui, contactez-moi (laissez votre email ci-dessous)
- Non merci

**Adresse email :** (Réponse courte - Facultatif)
- [ ______ ]
