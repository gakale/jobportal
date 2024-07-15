from flask import Flask, request, jsonify
import google.generativeai as genai
import datetime

app = Flask(__name__)

# Configurez votre clé API Gemini
genai.configure(api_key='AIzaSyADK33JZ8pvoJu2ESdlcn4PIqh_ixp2pw4')

@app.route('/generate_job_posting', methods=['POST'])
def generate_job_posting():
    data = request.json
    try:
        company_name = data['company_name']
        title = data['title']
        description = data['description']
        location = data['location']
        salary = data['salary']
        keywords = data['keywords']
        score_threshold = data['score_threshold']
        application_link = data['application_link']
        deadline = data['deadline']
        publication_date = data.get('publication_date', None)

        if not publication_date:
            publication_date = datetime.datetime.now().strftime("%d/%m/%Y")

        # Préparer les informations pour l'IA Gemini
        prompt = f"""
        Vous êtes chargé de créer une offre d'emploi basée sur les informations suivantes :

        Nom de l'entreprise : {company_name}
        Titre du poste : {title}
        Description du poste : {description}
        Localisation : {location if location else 'Non spécifiée'}
        Salaire : {salary if salary else 'Non spécifié'}
        Mots-clés : {', '.join(keywords)}
        Seuil de score : {score_threshold}
        Lien de candidature : {application_link if application_link else 'Non spécifié'}
        Date limite : {deadline}
        Date de publication : {publication_date}

        Créez une offre d'emploi professionnelle avec ces informations. Voici un exemple de structure :

        ## Offre d'emploi : {title}

        **Entreprise :** {company_name}

        **Lieu :** {location}

        **Date limite de candidature :** {deadline}

        **Rejoignez notre équipe dynamique et passionnée !**

        **{company_name}** recherche un(e) {title} talentueux(se) pour rejoindre notre équipe et contribuer à la création de solutions innovantes.

        **Description du poste :**

        {description}

        **Responsabilités :**

        * [Listez les responsabilités du poste]

        **Compétences et qualifications :**

        * [Listez les compétences et qualifications requises pour le poste]

        **Profil recherché :**

        * [Décrivez le profil idéal du candidat]

        **Avantages :**

        * [Listez les avantages offerts par l'entreprise]

        **Candidature :**

        Si vous êtes intéressé(e) par ce poste et que vous avez les compétences et qualifications requises, veuillez soumettre votre candidature via le lien suivant : {application_link}

        **Mots-clés :**

        {', '.join(keywords)}

        **Nous sommes impatients de recevoir votre candidature !**
        """

        # Utiliser l'IA Gemini pour générer le texte de l'offre d'emploi
        model = genai.GenerativeModel('gemini-1.5-flash')
        response = model.generate_content(prompt)

        job_posting_text = response.text.strip()

        # Afficher la réponse dans la console
        print("Réponse de l'API Gemini:")
        print(job_posting_text)

        # Envoyer la réponse à Laravel
        return jsonify({"job_posting": job_posting_text})

    except KeyError as e:
        return jsonify({"error": f"Missing field {str(e)}"}), 400

if __name__ == '__main__':
    app.run(debug=True)
