<div class="col-full">
    <a class="quotes" href="https://tammerofficial.com/product-category/theme-addon/?utm_source=ecom-demo&utm_medium=topbar&utm_campaign=topbar" target="_blank">
        <p class="text-center" style="color: #fff;"> <strong></strong>  Purchase now and receive  <b><span style="color:#F4B41A"> an additional 35 free add-on themes</span></b></p>
    </a>

    <a class="quotes" style="display: none;" href="https://tammerofficial.com/product/apps/ecommercego-saas-seller-apps/?utm_source=ecom-demo&utm_medium=topbar&utm_campaign=topbar" target="_blank">
        <p class="text-center" style="color: #fff;"> <strong></strong>  Boost your  <b><span style="color:#F4B41A"> e-commerce Business</span></b>  with <span style="color: #6FD943;">eCommerceGo Seller App</span> </p>
    </a>

</div>

<script src="{{ asset('public/js/jquery-3.6.0.min.js') }}"></script>
<script type="text/javascript">
    (function() {

    var quotes = $(".quotes");
    var quoteIndex = -1;

    function showNextQuote() {

    ++quoteIndex;
    quotes.eq(quoteIndex % quotes.length)
        .fadeIn(2000)
        .delay(2000)
        .fadeOut(2000, showNextQuote);

    }

    showNextQuote();

    })();
</script>
