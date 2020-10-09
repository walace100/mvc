<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>GeralException</title>
        <style>
            * {
                margin: 10px 0;
                padding: 0;
            }

            div.table {
                border-radius: 25px;
                box-shadow: lightgray 1px 2px 15px;
                padding: 10px 20px;
                width: 80%;
                text-align: center;
                margin: auto;
                overflow-x: auto;
            }

            h1 {
                margin-bottom: 15px;
            }

            th, td {
                padding: 10px;
                border: 1px solid lightgray;
            }

            table {
                border-bottom: 1px solid lightgray;
                border-top: 1px solid lightgray;
                border-collapse: collapse;
            }

            table tr:nth-child(odd) {
                background: #f2f2f2;
            }
            .strong {
                font-weight: bold;
            }
            .left {
                text-align: left;
            }
        </style>
    </head>
    <body>
        <div class="table">
        <h1>GeralException</h1>
            <table>
                <tr>
                    <th> Mensagem
                    <th> Código
                    <th> Arquivo
                    <th> Linha
                    <th> Anterior
                <tr>
                    <td> @ $getMessage; @
                    <td> @ $getCode; @
                    <td> @ $getFile; @
                    <td> @ $getLine; @
                    <td> @ $getPrevious; @
                <tr>
                    <th colspan="5"> Rota como String
                <tr>
                    <td colspan="5" class="strong"> <samp>@ $getTraceAsString; @</samp>
                <tr>
                    <th colspan="5"> Rota
                    @@ for ($i = 0; $i < count($getTrace); $i++): @@
                        @@ if ($i % 2 === 0): @@
                            <tr>
                                <td colspan="2" class="strong left"> <pre>@ var_dump($getTrace[$i]); @</pre>
                        @@ else: @@
                            <td colspan="3" class="strong left"> <pre>@ var_dump($getTrace[$i]); @</pre>
                        @@ endif @@
                    @@ endfor @@
            </table>
        </div>
    </body>
</html>