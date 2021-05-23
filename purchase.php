<?php
    require_once 'src/response.php';
    require_once 'src/function.php';

    date_default_timezone_set('UTC');

    /*
            <span class="expiration">
                    <input type="text" name="month" placeholder="MM" maxlength="2" size="2" />
                    <span>/</span>
                    <input type="text" name="year" placeholder="YY" maxlength="2" size="2" />
            </span>
        */
    /* controlla se esistono i parametri specificati nella POST e se hanno un valore
        se non sono validi restituisce il messaggio parametri errati */
    if (!isPostValid("cc", "month", "year", "cvv")) {
        echo new Response(400, "Wrong parameters");
    } else {
        // inizializza le variabili prendendo i valori dalla POST
        $cc = $_POST["cc"];
        $month = $_POST["month"];
        $year = $_POST["year"];
        $cvv = $_POST["cvv"];

        // prende il mese e l'anno corrente
        $currMonth = idate("m");
        $currYear = idate("y");


        // controlla il numero della carta se è veramente un numero e se ha 16 cifre, 
        // altrimenti restituisce errore
        if (strlen($cc) != 16 || !is_numeric($cc)) {
            echo new Response(400, "Wrong credit card number!"); // status code 400: Bad request.
        }
        // controlla se il mese e l'anno di scadenza della carta inserito sono numeri    
        else if (!is_numeric($month) || !is_numeric($year)) {
            echo new Response(400, "Wrong card expiration!");
        }
        // controlla se il cvv è un numero di tre cifre
        else if (strlen($cvv) != 3 || !is_numeric($cvv)) {
            echo new Response(400, "CVV invalid!");
        } 
        // se tutti i controlli sono andati a buon fine restituisce "pagamento completato"
        else {
            $month = intval($month);
            $year = intval($year);
            $wrong = false;

            // esegue il controllo della data se non è scaduta
            if ($currYear - $year > 0 || $currYear + 10 < $year || $month <= 0 || $month > 12)
                // se c'è stato un errore sui controlli la variabile $wrong conterrà true
                $wrong = true;
            else if ($currYear == $year)
                if ($currMonth >= $month)
                    $wrong = true;

            if ($wrong)
                echo new Response(400, "Wrong card expiration or expired!");
            else
                echo new Response(200, "Payment completed!"); // status code 200: OK.
        }
    }
?>