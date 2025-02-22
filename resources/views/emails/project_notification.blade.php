<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notification de Projet</title>
    <style>
        /* Styles généraux */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        .email-header h1 {
            color: #008080; /* Teal */
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 20px 0;
        }
        .email-body p {
            color: #555;
            line-height: 1.6;
            margin: 10px 0;
        }
        .email-footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #777;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #008080; /* Teal */
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #006666; /* Teal plus foncé */
        }
        .project-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .project-details p {
            margin: 5px 0;
        }
        .project-details strong {
            color: #008080; /* Teal */
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- En-tête de l'e-mail -->
        <div class="email-header">
            <h1>Notification de Projet</h1>
        </div>

        <!-- Corps de l'e-mail -->
        <div class="email-body">
            <p>Bonjour,</p>
            <p>{{ $messageContent }}</p>

            <!-- Détails du projet -->
            <div class="project-details">
                <p><strong>Projet :</strong> {{ $project->title }}</p>
                <p><strong>Description :</strong> {{ $project->description }}</p>
                <p><strong>Date de début :</strong> {{ $project->start_date }}</p>
                <p><strong>Date de fin :</strong> {{ $project->end_date }}</p>
            </div>

            <!-- Bouton d'action -->
            <a href="{{ url('/projects/' . $project->id) }}" class="btn">Voir le projet</a>
        </div>

        <!-- Pied de page de l'e-mail -->
        <div class="email-footer">
            <p>Ceci est un message automatique, merci de ne pas y répondre.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>