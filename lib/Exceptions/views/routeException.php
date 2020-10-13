<div class="table">
<h1>Route Exception</h1>
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