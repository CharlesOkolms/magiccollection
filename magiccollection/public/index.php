<?php

?>
<html>
<head>

    <title>CharlesOkolms' site</title>
    <meta charset="UTF-8"/>

    <noscript>Ce site a besoin de JavaScript pour fonctionner.</noscript>

    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/v/dt/dt-1.10.18/b-1.5.6/b-html5-1.5.6/b-print-1.5.6/fh-3.1.4/kt-2.5.0/r-2.2.2/sl-1.3.0/datatables.min.css"/>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript"
            src="https://cdn.datatables.net/v/dt/dt-1.10.18/b-1.5.6/b-html5-1.5.6/b-print-1.5.6/fh-3.1.4/kt-2.5.0/r-2.2.2/sl-1.3.0/datatables.min.js"></script>


    <link rel="stylesheet" type="text/css" href="style/style.css"/>

    <script type="text/javascript" src="script/func.js"></script>
    <script type="text/javascript" src="script/init.js"></script>

</head>
<body id="body">
<div id="left">
</div>
<div id="middle">
    <table style="width:100%;" id="cardslist" class="dt-responsive stripe order-column hover compact">
        <thead>
        <tr>
            <!--Note : class="not-visible-col" rend la colonne invisible dans le rendu DataTables (JS),
					class="not-exportable-col" > non-concernée par les export vers pdf ou autres (y compris impression)
                    class="not-searchable-col" > la recherche ne se fait pas dans cette colonne -->
            <th class="not-searchable-col">Date màj</th>
            <th>Multiverse id</th>
            <th>Nom (EN)</th>
            <th>Nom (FR)</th>
            <th>Edition</th>
            <th>Rareté</th>
            <th>Coût</th>
            <th>Prix</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
<div id="right">
</div>
</body>
</html>
