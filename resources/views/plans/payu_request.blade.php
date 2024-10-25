<html>
    <head>
    </head>

    <body onload="submitPayuForm()">
    <form action="{{ $pay->action }}" method="post" name="payuForm"><br />
        <input type="hidden" id="key" name="key" value="{{ $pay->key }}" /><br />
        <input type="hidden" id="hash" name="hash" value="{{ $pay->hash }}" /><br />
        <input type="hidden" id="txnid" name="txnid" value="{{ $pay->txnid }}" /><br />
        <input type="hidden" id="amount" name="amount" value="{{ $pay->amount }}" /><br />
        <input type="hidden" id="firstname" name="firstname" id="firstname" value="{{ $pay->firstname }}" /><br />
        <input type="hidden" id="email" name="email" id="email" value="{{ $pay->email }}" /><br />
        <input type="hidden" id="productinfo" name="productinfo" value="{{ $pay->productinfo }}"><br />
        <input type="hidden" id="surl" name="surl" value="{{ $pay->surl }}" /><br />
        <input type="hidden" id="furl" name="furl" value="{{ $pay->furl }}" /><br />
        <input type="hidden" id="service_provider" name="service_provider" value="payu_paisa" /><br />
        @if (!$pay->hash)
            <input type="submit" value="Submit" />
        @endif
    </form>
    <script>
        var payuForm = document.forms.payuForm;
        payuForm.submit();
    </script>
    </body>
</html>
