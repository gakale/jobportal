from flask import Flask, request, jsonify
import fitz  # PyMuPDF pour l'extraction de texte
import google.generativeai as genai
import os
import tempfile
from nltk.stem import WordNetLemmatizer
from nltk.corpus import wordnet
import nltk

nltk.download('wordnet')
nltk.download('omw-1.4')

app = Flask(__name__)

# Configurez votre clé API Gemini
genai.configure(api_key='')

lemmatizer = WordNetLemmatizer()


def lemmatize_words(words):
    return [lemmatizer.lemmatize(word, pos=wordnet.VERB) for word in words]


def extract_text_from_pdf(file_path):
    document = fitz.open(file_path)
    text = ""
    for page_num in range(document.page_count):
        page = document.load_page(page_num)
        text += page.get_text()
    return text


@app.route('/')
def home():
    return "API is running. Use /analyze to analyze CV and cover letter."


@app.route('/analyze', methods=['POST'])
def analyze():
    if 'cv' not in request.files:
        return jsonify({"error": "CV is required"}), 400

    cv = request.files['cv']
    cover_letter = request.files.get('cover_letter', None)
    job_description = request.form.get('job_description', '').strip()
    job_keywords = request.form.get('job_keywords', '').strip()

    if not job_description:
        return jsonify({"error": "Job description is required"}), 400

    if not job_keywords:
        return jsonify({"error": "Job keywords are required"}), 400

    with tempfile.NamedTemporaryFile(delete=False) as temp_cv:
        cv.save(temp_cv.name)
        cv_path = temp_cv.name

    if cover_letter:
        with tempfile.NamedTemporaryFile(delete=False) as temp_cover_letter:
            cover_letter.save(temp_cover_letter.name)
            cover_letter_path = temp_cover_letter.name
        cover_letter_text = extract_text_from_pdf(cover_letter_path)
    else:
        cover_letter_text = ""

    cv_text = extract_text_from_pdf(cv_path)

    # Combine CV, cover letter, and job description text for analysis
    combined_text = f"CV:\n{cv_text}\n\nCover Letter:\n{cover_letter_text}\n\nJob Description:\n{job_description}"

    # Envoi à l'API de Gemini pour analyse
    model = genai.GenerativeModel('gemini-1.5-flash')
    response = model.generate_content(combined_text)

    score = calculate_score(response.text,
                            job_keywords.split(','))  # Fonction de calcul du score basé sur la réponse de Gemini

    # Supprimez les fichiers temporaires
    os.remove(cv_path)
    if cover_letter:
        os.remove(cover_letter_path)

    return jsonify({"score": score, "analysis": response.text})


def calculate_score(analysis_text, job_keywords):
    # Convert analysis text to lowercase for case-insensitive comparison
    analysis_text_lower = analysis_text.lower()
    analysis_words = lemmatize_words(analysis_text_lower.split())

    score = 0
    for keyword in job_keywords:
        lemmatized_keyword = lemmatizer.lemmatize(keyword.lower().strip(), pos=wordnet.VERB)
        print("Checking Keyword:", lemmatized_keyword)  # Debugging print statement
        if lemmatized_keyword in analysis_words:
            score += 1

    # Additional points for structured CV
    if 'education' in analysis_text_lower or 'éducation' in analysis_text_lower:
        score += 1
    if 'work experience' in analysis_text_lower or 'expérience professionnelle' in analysis_text_lower:
        score += 1
    if 'skills' in analysis_text_lower or 'compétence' in analysis_text_lower:
        score += 1
    if 'contact' in analysis_text_lower or 'coordonnées' in analysis_text_lower:
        score += 1

    # Calculate the percentage score
    max_score = len(job_keywords) + 4  # Including the additional points for structure
    return (score / max_score) * 100


if __name__ == '__main__':
    app.run(debug=True)
