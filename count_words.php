<?php

// Se não foi passado, solicita ao usuário inserir o nome do diretório
fwrite(STDOUT, "Por favor, insira o nome do diretório: ");
$diretorio = trim(fgets(STDIN));

// Função para limpar uma palavra
$limparPalavra = function($palavra) {
    // Remove símbolos e números
    $palavra = preg_replace('/[^\p{L}\p{M}\']+/u', ' ', $palavra);
    // Converte para minúsculas
    $palavra = mb_strtolower($palavra);
    // Remove espaços em branco extras
    $palavra = trim($palavra);
    return $palavra;
};

// Função auxiliar para limpar e contar palavras
$contarPalavrasLegenda = function($legenda) use ($limparPalavra) {

    // Gera um novo array separando com as palavras separadas
    preg_match_all('/\b[\p{L}\']+\b/u', $legenda, $matches);
    $palavras = $matches[0];
    // Limpa Palavras e retorna como: Array ( ['i'] => 2 ['Cat'] => 1 ['Dog'] => 2 )
    return array_count_values(array_map($limparPalavra, $palavras));
};

// Função para contar as palavras em um arquivo .srt
$contarPalavras = function($arquivo) use ($contarPalavrasLegenda) {
    // Lê o conteúdo do arquivo e divide em linhas
    $linhas = explode("\n", file_get_contents($arquivo));

    // Filtra e processa as legendas
    $contagemPalavras = array_reduce($linhas, function($acc, $linha) use ($contarPalavrasLegenda) {
        static $legenda = "";
        // Verifica se é um número de sequência em legendas
        if (is_numeric(trim($linha))) {
            return $acc;
        }
        // Verifica se a linha é o final da sequencia e calcula palavras
        if (empty($linha) && !empty($legenda)) {
            $acc += $contarPalavrasLegenda($legenda);
            $legenda = "";
        } else {
            $legenda .= " " . $linha;
        }
        return $acc;
    }, []);

    return $contagemPalavras;
};

// Função para formatar o JSON
$formatarJSON = function($contagemPalavras) {
    $jsonArray = array();
    foreach ($contagemPalavras as $palavra => $frequencia) {
        $jsonArray[] = array(
            "palavra" => $palavra,
            "frequencia" => $frequencia
        );
    }
    return $jsonArray;
};

// Função para salvar os resultados em um arquivo JSON
$salvarJSON = function($arquivo, $dados) use($formatarJSON) {
     // Ordena os dados por frequência (decrescente)
     arsort($dados);
     // Formata a contagem de palavras como JSON
     $jsonArray = $formatarJSON($dados);
     // Converte para JSON
     $json = json_encode($jsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
     // Salva no arquivo
     file_put_contents($arquivo, $json);
};

// Cria o diretório de resultados, se não existir
if (!is_dir('resultados')) {
    mkdir('resultados', 0755, true);
}

// Verifica se o diretório específico para a temporada existe
if (!is_dir('resultados/' . $diretorio)) {
    // Se não existir, cria o diretório
    mkdir('resultados/' . $diretorio, 0755, true);
}

// Lista todos os arquivos .srt no diretório especificado
$arquivos = glob("$diretorio/*.srt");

// Inicializa o array de contagem de palavras para a temporada
$contagemTemporada = [];

// Percorre cada arquivo .srt
array_walk($arquivos, function($arquivo) use (&$contagemTemporada, $contarPalavras, $salvarJSON, $diretorio) {
    // Obtém o nome do episódio
    $nomeEpisodio = pathinfo($arquivo, PATHINFO_FILENAME);
    // Conta as palavras no arquivo
    $contagemPalavras = $contarPalavras($arquivo);
    // Adiciona a contagem de palavras do episódio à contagem da temporada
    foreach ($contagemPalavras as $palavra => $frequencia) {
        $contagemTemporada[$palavra] = ($contagemTemporada[$palavra] ?? 0) + $frequencia;
    }
    // Salva a contagem de palavras do episódio em um arquivo JSON
    $arquivoEpisodio = "resultados/". $diretorio ."/episodio-$nomeEpisodio.json";
    $salvarJSON($arquivoEpisodio, $contagemPalavras);
});

// Salva a contagem de palavras da temporada em um arquivo JSON
$arquivoTemporada = "resultados/". $diretorio ."/temporada-" . $diretorio . ".json";
$salvarJSON($arquivoTemporada, $contagemTemporada);

echo "Análise concluída. Resultados salvos na pasta 'resultados'.";

?>
