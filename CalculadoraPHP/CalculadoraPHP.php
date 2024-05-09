<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora Simples</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            text-align: center;
            margin-top: 20px;
        }

        input[type="number"], select, button {
            padding: 10px;
            margin: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
            border: none;
        }

        #resultado {
            margin-top: 20px;
            text-align: center;
        }

        .flex-container {
            display: flex;
            justify-content: center;
        }

        .flex-item {
            margin: 10px;
        }
    </style>
</head>
<body>
    <h1>Calculadora</h1>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="flex-container">
            <div class="flex-item">
                <input type="number" name="num1" id="num1" placeholder="Número 1" required>
            </div>
            <div class="flex-item">
                <select name="operacao" required>
                    <option value="+">+</option>
                    <option value="-">-</option>
                    <option value=""></option>
                    <option value="/">/</option>
                    <option value="!">!</option>
                    <option value="^">^</option>
                </select>
            </div>
            <div class="flex-item">
                <input type="number" name="num2" id="num2" placeholder="Número 2" required>
            </div>
        </div>
        <br>
        <div class="flex-container">
            <div class="flex-item">
                <button type="submit" name="calcular">Calcular</button>
            </div>
            <div class="flex-item">
                <button type="submit" name="salvar">Salvar</button>
            </div>
            <div class="flex-item">
                <button type="submit" name="pegarHistorico">Pegar valores</button>
            </div>
            <div class="flex-item">
                <button type="submit" name="limparHistorico">Apagar Histórico</button>
            </div>
            <div class="flex-item">
                <button type="submit" name="memoria_recuperar">M</button>
            </div>
        </div>
        
        <br>
        <div id="resultado">
        <?php
        session_start();
        if (isset($_SESSION['memoria'])) {
            echo "Memória: " . $_SESSION['memoria'];
        }
        ?>
    </div>
</form>

</body>
</html>

<?php
if (!isset($_SESSION['historico'])) {
    $_SESSION['historico'] = array();
}

if (isset($_POST['calcular']) || isset($_POST['salvar']) || isset($_POST['pegarHistorico']) || isset($_POST['limparHistorico']) || isset($_POST['memoria_recuperar'])) {

    $num1 = $_POST['num1'];
    $num2 = $_POST['num2'];
    $operacao = $_POST['operacao'];

    switch ($operacao) {
        case '+':
            $resultado = $num1 + $num2;
            break;
        case '-':
            $resultado = $num1 - $num2;
            break;
        case '*':
            $resultado = $num1 * $num2;
            break;
        case '/':
            if ($num2 == 0) {
                $resultado = "Erro: Divisão por zero.";
            } else {
                $resultado = $num1 / $num2;
            }
            break;
        case '!':
            $resultado = 1;
            for ($i = 1; $i <= $num1; $i++) {
                    $resultado *= $i;
                }
            break;
        case '^':
            $resultado = pow($num1, $num2);
            break;
        default:
            $resultado = "Operação inválida.";
    }

    if (isset($_POST['salvar'])) {
        $_SESSION['memoria'] = "$num1 $operacao $num2";
        echo "<p>Valores salvos na memória!</p>";
    } elseif (isset($_POST['pegarHistorico'])) {
        if (isset($_SESSION['historico'])) {
            echo "<h2>Histórico</h2>";
            echo "<ul>";
            foreach ($_SESSION['historico'] as $item) {
                echo "<li>{$item['operacao']} = {$item['resultado']}</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Nenhum valor no histórico!</p>";
        }
    } elseif (isset($_POST['limparHistorico'])) {
        $_SESSION['historico'] = array(); // Limpa o histórico
        echo "<p>Histórico limpo!</p>";
    } elseif (isset($_POST['memoria_recuperar'])) {
        if (isset($_SESSION['memoria'])) {
            $operacao_memoria = $_SESSION['memoria'];
            echo "<p>Valores recuperados da memória: $operacao_memoria</p>";
        } else {
            echo "<p>Nenhum valor na memória!</p>";
        }
    }

    $_SESSION['historico'][] = array('operacao' => "$num1 $operacao $num2", 'resultado' => $resultado);

    echo "<p id='resultado'>Resultado: $resultado</p>";
}
?>