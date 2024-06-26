<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contato - Suky Sorveteria</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .header img {
            width: 150px;
        }
        .content {
            padding: 20px;
        }
        .content h2 {
            color: #333333;
            margin-bottom: 10px;
        }
        .content h2:first-child {
            margin-top: 0;
        }
        blockquote {
            margin-bottom: 20px; /* Espaço entre cada bloco de informação */
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 4px solid #ccc;
        }
        blockquote p {
            margin: 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #999999;
            background-color: #f4f4f4;
            margin-top: 20px; /* Espaço entre o conteúdo e o rodapé */
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #999999;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Suky Sorveteria (Mensagem recebida via site)</h1>
            <img src="{{ asset('img/logo_suky.png') }}" alt="Logo da Suky Sorveteria">
        </div>
        <div class="content">
            <blockquote>
                <p><strong>Nome:</strong> {{ $contato->nomeContato }}</p>
            </blockquote>
            <blockquote>
                <p><strong>Email:</strong> {{ $contato->emailContato }}</p>
            </blockquote>
            <blockquote>
                <p><strong>Fone:</strong> {{ $contato->foneContato }}</p>
            </blockquote>
            <blockquote>
                <p><strong>Assunto:</strong> {{ $contato->assuntoContato }}</p>
            </blockquote>
            <blockquote>
                <p><strong>Mensagem:</strong> {{ $contato->mensagemContato }}</p>
            </blockquote>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Suky Sorveteria. Todos os direitos reservados.</p>
            <p>
                <a href="https://sukysorveteria.com">Visite nosso site</a> |
                <a href="mailto:sukysorveteria@gmail.com">sukysorveteria@gmail.com</a>
            </p>
            <p>Av. Rosária - Vila Rosaria, São Paulo - SP, 08021-070</p>
        </div>
    </div>
</body>
</html>
