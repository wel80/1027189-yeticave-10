<div class="lot-item__state">
    <div class="lot-item__timer timer <?=isset($is_lot["date_expiry"]) && rest_time($is_lot["date_expiry"])[0] === '00' ? 'timer--finishing' : ''; ?>">
        <?=isset($is_lot['completion_period']) && $is_lot['completion_period'] < 0 && isset($is_lot['date_expiry']) ? 'Торги окончены' :
        htmlspecialchars(rest_time($is_lot['date_expiry'])[0] . ' : ' . rest_time($is_lot['date_expiry'])[1]); ?>
    </div>
    <div class="lot-item__cost-state">
        <div class="lot-item__rate">
            <span class="lot-item__amount">Текущая цена</span>
            <span class="lot-item__cost"><?=isset($is_lot['current_price']) ? price_format($is_lot['current_price']) :''; ?></span>
        </div>
    </div>
</div>
          