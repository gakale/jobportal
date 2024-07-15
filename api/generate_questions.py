from flask import Flask, request, jsonify
import google.generativeai as genai
import os
import json
import re

app = Flask(__name__)

# Configurez votre clé API Gemini
genai.configure(api_key='AIzaSyADK33JZ8pvoJu2ESdlcn4PIqh_ixp2pw4')

@app.route('/generate_questions', methods=['POST'])
def generate_questions():
    data = request.json
    text_format = data.get('text_format', '')
    number_of_questions = data.get('number_of_questions', 5)
    total_duration = data.get('duration', 10) * 60  # Duration in seconds
    language = data.get('language', 'en')

    if not text_format:
        return jsonify({"error": "Text format is required"}), 400

    prompt_language = 'English'
    if language == 'fr':
        prompt_language = 'French'

    prompt = f"""
    Based on the following job description, create exactly {number_of_questions} multiple choice questions for a job applicant in {prompt_language}. 
    The questions should start with basic ones and gradually become more complex. Each question should be relevant to the job description, focusing on the required skills, qualifications, and job responsibilities. 
    Provide three answer choices for each question, one of which is correct. Also provide the time needed to answer each question in seconds. 
    The total time for all questions should not exceed {total_duration} seconds.

    It is crucial that you generate exactly {number_of_questions} questions, no more, no less.

    Job Description:
    {text_format}

    Expected response format:
    [
        {{
            "question": "What is the capital of France?",
            "choices": [
                "Paris",
                "London",
                "Berlin"
            ],
            "correct_answer": "Paris",
            "time_to_answer": 60
        }},
        ...
    ]
    """

    try:
        model = genai.GenerativeModel('gemini-1.5-flash')
        response = model.generate_content(prompt)

        # Extract the text from the response
        questions_content = response.candidates[0].content
        questions_text = "".join([part.text for part in questions_content.parts])

        # Log the raw text response to the terminal
        print("Raw Response Text:", questions_text)

        # Extract JSON content from the Markdown block
        json_match = re.search(r'```json\n(.*?)\n```', questions_text, re.DOTALL)
        if json_match:
            questions_json = json_match.group(1)
            questions = json.loads(questions_json)

            # Log the questions to the terminal
            print("Generated Questions:", questions)

            # Validate the number of questions and truncate if necessary
            if len(questions) > number_of_questions:
                questions = questions[:number_of_questions]
            elif len(questions) < number_of_questions:
                raise ValueError(f"Expected {number_of_questions} questions, but got {len(questions)}")

            # Convert time_to_answer to int and validate the total duration
            total_time = 0
            for q in questions:
                q['time_to_answer'] = int(q['time_to_answer'])
                total_time += q['time_to_answer']

            if total_time > total_duration:
                raise ValueError(f"Total time of {total_time} seconds exceeds the limit of {total_duration} seconds")

            return jsonify({"questions": questions})
        else:
            raise ValueError("No JSON content found in the response")

    except Exception as e:
        # Log the error to the terminal
        print("Error:", str(e))
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    app.run(debug=True)