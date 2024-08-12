<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$job->title}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #0056b3;
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
        }
        .logo {
            width: 60px;
            height: 60px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 20px;
        }
        .logo span {
            color: #0056b3;
            font-weight: bold;
            font-size: 14px;
        }
        h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .company {
            text-align: center;
            margin-bottom: 20px;
        }
        h2, h3 {
            margin: 10px 0;
        }
        .description, .profile {
            margin-bottom: 20px;
        }
        ul {
            padding-left: 20px;
        }
        .apply-section {
            margin-top: 20px;
            text-align: center;
        }
        .apply-section button {
            background-color: #0056b3;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .apply-section button:hover {
            background-color: #004494;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <span>EMPLOI</span>
            </div>
            <h1>Offre d'emploi</h1>
        </div>
        <div class="content">
            <table>
                <tr>
                    <td>Métier(s):</td>
                    <td>Commerce/Ventes</td>
                </tr>
                <tr>
                    <td>Niveau(x):</td>
                    <td>Terminale</td>
                </tr>
                <tr>
                    <td>Expérience:</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Lieu:</td>
                    <td>ABIDJAN</td>
                </tr>
                <tr>
                    <td>Date de publication:</td>
                    <td>{{$job->created_at}}</td>
                </tr>
                <tr>
                    <td>Date limite:</td>
                    <td>{{$job->deadline}}</td>
                </tr>
            </table>
            
            <div class="company">
                <h2>{{$job->company->name}}</h2>
                <p>recrute</p>
                <h3>PARTENAIRES COMMERCIAUX</h3>
            </div>
            
            <div class="profile">
                <h4>Profil du poste</h4>
                <ul>
                    <li>{{$job->keywords}}</li>
                    <li>{!! $formatted_description ?: 'Aucune description disponible' !!}</li>
                </ul>
            </div>

            <div class="application-form">
                <h4>Postulez ici</h4>
                <form action="{{ route('apply', ['job' => $job->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="cv">Télécharger votre CV:</label>
                    <input type="file" id="cv" name="cv" accept=".pdf" required>
        
                    <label for="cover_letter">Télécharger votre lettre de motivation (facultatif):</label>
                    <input type="file" id="cover_letter" name="cover_letter" accept=".pdf">
        
                    <button type="submit">Postuler</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
