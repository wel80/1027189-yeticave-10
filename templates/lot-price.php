<div class="lot-item__state">
    <div class="lot-item__timer timer <?=isset($is_lot["date_expiry"]) && rest_time($is_lot["date_expiry"])[0] === '00' ? 'timer--finishing' : ''; ?>">
        <?=isset($is_lot['completion_period']) && isset($session_user_id) && isset($is_lot['date_expiry']) ? 
        rates_timer_content($is_lot['completion_period'],  $session_user_id, $is_lot['date_expiry']) : ''; ?>
    </div>
    <div class="lot-item__cost-state">
        <div class="lot-item__rate">
            <span class="lot-item__amount">Текущая цена</span>
            <span class="lot-item__cost"><?=isset($is_lot['current_price']) ? price_format($is_lot['current_price']) :''; ?></span>
        </div>
    </div>
</div>
          