<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processamento do Formulário</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <?php
        // Configuração do endpoint do webhook
        $webhookUrl = 'https://n8n.jeanmassueyk.site/webhook-test/form'; // Substitua pelo endpoint correto

        // Função para redirecionar para a página inicial ao dar refresh
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php"); // Redireciona para a página inicial
            exit;
        }

        // Verifica se o formulário foi enviado via POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Captura os dados enviados pelo formulário
$nomeCliente = htmlspecialchars($_POST['nomeCliente']);
$endereco = htmlspecialchars($_POST['endereco']);
$complemento = htmlspecialchars($_POST['complemento']);
$numeroPedido = htmlspecialchars($_POST['numeroPedido']);
$telefoneCliente = htmlspecialchars($_POST['telefoneCliente']);
$estabelecimento = htmlspecialchars($_POST['estabelecimento']);
$usuario = htmlspecialchars($_POST['usuario']);
$areaColar = htmlspecialchars($_POST['areaColar']);


// Configura o fuso horário para UTC-3
date_default_timezone_set('America/Sao_Paulo');

// Formata a data/hora no formato DD-MM-YY HH:MM:SS
$dataHora = date('d-m-y H:i:s');

// Monta o payload JSON a ser enviado ao webhook
$payload = json_encode([
    'nomeCliente' => $nomeCliente,
    'endereco' => $endereco,
    'complemento' => $complemento,
    'numeroPedido' => $numeroPedido,
    'telefoneCliente' => "+55" . $telefoneCliente, // Prefixo do telefone
    'estabelecimento' => $estabelecimento,
    'usuario' => $usuario,
    'areaColar' => $areaColar,
    'dataHora' => $dataHora, // Adiciona a data/hora formatada

]);

            // Inicializa o cURL
            $ch = curl_init($webhookUrl);

            // Configurações do cURL
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload)
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

            // Envia os dados e captura a resposta
            $response = curl_exec($ch);

            // Verifica erros de cURL
            if (curl_errno($ch)) {
                // Exibe mensagem de erro com Bootstrap
                echo "<div class='alert alert-danger text-center' role='alert'>
                        Erro ao enviar webhook: " . curl_error($ch) . "
                      </div>";
            } else {
                // Exibe mensagem de sucesso com Bootstrap
                echo "<div class='alert alert-success text-center' role='alert'>
                        Mensagem enviada com sucesso! Você será redirecionado em 5 segundos...
                      </div>";
                // Redirecionamento automático após 5 segundos
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'index.php';
                        }, 5000);
                      </script>";
            }

            // Fecha a conexão cURL
            curl_close($ch);
        } else {
            // Caso o usuário tente acessar diretamente a página
            header("Location: index.php");
            exit;
        }
        ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>