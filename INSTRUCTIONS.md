# Instruções de Instalação e Execução do Programa

## 1. Configuração do Ambiente
- Certifique-se de ter o PHP 8 instalado em seu sistema. Você pode baixar e instalar o PHP a partir do [site oficial do PHP](https://www.php.net/downloads).
- Tenha um ambiente de linha de comando configurado para executar scripts PHP.

## 2. Preparação do Diretório
- Coloque todos os arquivos `.srt` que deseja analisar em um diretório específico. Certifique-se de que o diretório contém apenas os arquivos `.srt` que você deseja analisar.

## 3. Execução do Programa
- Abra um terminal ou prompt de comando.
- Navegue até o diretório onde você salvou o arquivo PHP e os arquivos `.srt` usando o comando `cd`.
- Execute o script PHP digitando o seguinte comando e pressionando Enter: `php count_words.php`.
- Se você fornecer o nome do diretório como um argumento ao executar o script, ele usará esse diretório para procurar os arquivos `.srt`.
- Caso contrário, será solicitado que você insira o nome do diretório durante a execução do script.

## 5. Resultados
- Após a execução do programa, os resultados serão salvos na pasta `resultados` dentro do diretório fornecido.
- Serão criados diretórios adicionais dentro de `resultados` para cada temporada e para cada episódio.
- Os resultados serão armazenados em arquivos JSON, um para cada episódio e um para toda a temporada.
- O programa imprimirá "Análise concluída. Resultados salvos na pasta 'resultados'." para indicar que a análise foi concluída com sucesso.

Certifique-se de que os arquivos `.srt` estão corretamente nomeados e formatados para garantir uma análise precisa.