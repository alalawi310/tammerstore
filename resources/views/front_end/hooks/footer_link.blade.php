<div class="external-btns">
    @stack('CommmetIconView')
    @if(isset($whatsapp_setting_enabled) && !empty($whatsapp_setting_enabled))
        <div class="floating-wpp"></div>
    @endif
</div>
<div class="external-left-btn">
    <div class="show-btn btn"><i class="fas fa-sun"></i></div>
    <div class="left-btn-inner">
        @stack('addCompareButton')
        @stack('addCatelogRequestButton')
        @stack('DonationFormButton')
        @stack('freeShippingPopupButton')
        @stack('boostSalesModelPopup')
        @stack('couponRequestbutton')
    </div>
</div>