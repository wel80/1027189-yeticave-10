<div class="lot-item__state">
    <div class="lot-item__timer timer <?=(rest_time($is_lot["date_expiry"])[0] == '00') ? 'timer--finishing' : ''; ?>">
        <?=rates_timer_content($is_lot['completion_period'],  $session_user_id, $is_lot['date_expiry']); ?>
    </div>
    <div class="lot-item__cost-state">
        <div class="lot-item__rate">
            <span class="lot-item__amount">Текущая цена</span>
            <span class="lot-item__cost"><?=price_format($is_lot['current_price']) ; ?></span>
        </div>
    </div>
</div>
          