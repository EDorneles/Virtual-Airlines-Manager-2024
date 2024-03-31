<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>EXAME DE ADMISSÃO</title>
</head>
<body>
<style>
    #timer {
        position: fixed;
        top: 10px;
        right: 10px;
        background-color: #ffffff;
        border: 1px solid #cccccc;
        padding: 10px;
        font-size: 18px;
    }
</style>

<div id="timer">Tempo restante: 10:00</div>
<?php
// Tempo de 10 minutos em segundos
$tempo_total = 10 * 60;

// Início do timer
$tempo_inicio = isset($_SESSION['tempo_inicio']) ? $_SESSION['tempo_inicio'] : time();

// Tempo decorrido
$tempo_decorrido = time() - $tempo_inicio;

// Tempo restante em segundos
$tempo_restante = $tempo_total - $tempo_decorrido;

// Porcentagem do tempo restante
$porcentagem = round(($tempo_restante / $tempo_total) * 100);

// Formatar minutos e segundos com dois dígitos
$minutos = floor($tempo_restante / 60);
$segundos = $tempo_restante % 60;
$minutos_formatados = str_pad($minutos, 2, '0', STR_PAD_LEFT);
$segundos_formatados = str_pad($segundos, 2, '0', STR_PAD_LEFT);

// Verificar se o tempo acabou
if ($tempo_restante <= 0) {
    // Redirecionar para a página de resultado
    header('Location: corrigir_formulario.php');
    exit;
}

?>


<script>
    // Tempo de 10 minutos em segundos
    var tempo_10_min = 10 * 60;

    // Início do timer
    var tempo_inicio = new Date().getTime();

    // Função para atualizar o timer
    function atualizarTimer() {
        // Tempo restante em segundos
        var tempo_restante = Math.round((tempo_inicio + tempo_10_min * 1000 - new Date().getTime()) / 1000);

        // Converter tempo restante para minutos e segundos
        var minutos = Math.floor(tempo_restante / 60);
        var segundos = tempo_restante % 60;

        // Formatar minutos e segundos com dois dígitos
        minutos = minutos < 10 ? "0" + minutos : minutos;
        segundos = segundos < 10 ? "0" + segundos : segundos;

        // Atualizar o texto do timer
        document.getElementById("timer").innerHTML = "Tempo restante: " + minutos + ":" + segundos;

        // Verificar se o tempo acabou
        if (tempo_restante <= 0) {
            clearInterval(intervalo);
            document.getElementById("timer").innerHTML = "Tempo esgotado!";
            
        }
    }

    // Executar a função de atualização do timer a cada segundo
    var intervalo = setInterval(atualizarTimer, 1000);
</script>

	<h1>Aviação Geral</h1>

	<form action="corrigir_formulario.php" method="post">

		<!-- Pergunta 1 -->
		<p>1. A altitude de pressão é...?</p>
		<input type="radio" name="pergunta1" value="a"> a) A altitude lida diretamente do altímetro definido para QNH local <br>
		<input type="radio" name="pergunta1" value="b"> b) A altitude indicada quando a escala de pressão barométrica é ajustada para 29,92 inHg (1013,2 hPa) <br>
		<input type="radio" name="pergunta1" value="c"> c) A distância vertical da aeronave acima do nível médio do mar (MSL) <br>

		<!-- Pergunta 2 -->
		<p>2. Qual é o nome do instrumento utilizado para medir a altitude?</p>
		<input type="radio" name="pergunta2" value="a"> a) Altímetro <br>
		<input type="radio" name="pergunta2" value="b"> b) Velocímetro <br>
		<input type="radio" name="pergunta2" value="c"> c) Termômetro <br>

		<!-- Pergunta 3 -->
		<p>3. Qual dos seguintes grupos mostra os designadores corretos para três paralelos pistas vistas da direção ou da aproximação?	</p>
		<input type="radio" name="pergunta3" value="a"> a) 29L, 29, 29R <br>
		<input type="radio" name="pergunta3" value="b"> b) 29L, 29C, 29R <br>
		<input type="radio" name="pergunta3" value="c"> c) 29, 29L, 29R <br>

		<!-- Pergunta 4 -->
		<p>4. O que significa a frase Read back?</p>
		<input type="radio" name="pergunta4" value="a"> a) Verifique e confirme com o originador <br>
		<input type="radio" name="pergunta4" value="b"> b) Você recebeu corretamente esta mensagem? <br>
		<input type="radio" name="pergunta4" value="c"> c) Repita todas, ou a parte especificada, desta mensagem de volta para mim exatamente como recebida <br>

		<!-- Pergunta 5 -->
		<p>5. Qual é o nome do sistema que fornece informações sobre o tráfego aéreo?</p>
		<input type="radio" name="pergunta5" value="a"> a) GPS <br>
		<input type="radio" name="pergunta5" value="b"> b) Radar <br>
		<input type="radio" name="pergunta5" value="c"> c) Sonar <br>

		<!-- Pergunta 6 -->
		<p>6. Qual é o nome do instrumento que indica a direção em que o avião está voando?</p>
		<input type="radio" name="pergunta6" value="a"> a) Altímetro <br>
		<input type="radio" name="pergunta6" value="b"> b) Giroscópio <br>
		<input type="radio" name="pergunta6" value="c"> c) Bússola <br>

		<!-- Pergunta 7 -->
		<p>7. O que é o TORA em relação ao parâmetro de pista? </p>
		<input type="radio" name="pergunta7" value="a"> a) Take-Off Run Available <br>
		<input type="radio" name="pergunta7" value="b"> b) Total Runway Available <br>
		<input type="radio" name="pergunta7" value="c"> c) Total Obstacle Runway Avoidance <br>
				<!-- Pergunta 8 -->
		<p>8. Qual é o nome da parte da asa responsável por aumentar a sustentação em baixas velocidades?</p>
		<input type="radio" name="pergunta8" value="a"> a) Spoiler <br>
		<input type="radio" name="pergunta8" value="b"> b) Flap <br>
		<input type="radio" name="pergunta8" value="c"> c) Aileron <br>

		<!-- Pergunta 9 -->
		<p>9. Qual é a maneira correta de transmitir 1001 como um QNH?</p>
		<input type="radio" name="pergunta9" value="a"> a) QNH um duplo zero um <br>
		<input type="radio" name="pergunta9" value="b"> b) QNH um zero zero um <br>
		<input type="radio" name="pergunta9" value="c"> c) QNH mil e um <br>

		<!-- Pergunta 10 -->
		<p>10. Que configuração de pressão você deve usar para definir o altímetro abaixo da Altitude de Transição no espaço aéreo controlado?</p>
		<input type="radio" name="pergunta10" value="a"> a) QNH <br>
		<input type="radio" name="pergunta10" value="b"> b) QFE <br>
		<input type="radio" name="pergunta10" value="c"> c) QNE <br>

		<!-- Botão de envio -->
		<br><br>
<style>
  .container {
    position: relative;
    width: 50%;
    height: 10vh;
  }

  .botao-enviar {
    position: center;
    top: 20%;
    right: 0;
    left: 0;
    transform: relative;
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
</style>

<div class="container">
  <form method="post">
    <button type="submit" name="Enviar respostas" class="botao-enviar">Enviar respostas</button>
  </form>
</div>

Private Virtual - Todos direitos reservados.
</script>
</body>
</html>

