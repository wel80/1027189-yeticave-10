<div class="lot-item__state">
    <div class="lot-item__timer timer <?=isset($is_lot["date_expiry"]) && rest_time($is_lot["date_expiry"])[0] === '00' ? 'timer--finishing' : ''; ?>">
        <?=isset($is_lot["date_expiry"]) ? rest_time($is_lot["date_expiry"])[0] . ' : ' . rest_time($is_lot["date_expiry"])[1] : ''; ?>
    </div>
    <div class="lot-item__cost-state">
        <div class="lot-item__rate">
            <span class="lot-item__amount">Текущая цена</span>
            <span class="lot-item__cost"><?=isset($is_lot['current_price']) ? price_format($is_lot['current_price']) : ''; ?></span>
        </div>
        <div class="lot-item__min-cost">
            Мин. ставка <span><?=isset($is_lot['min_rate']) ? price_format_rate($is_lot['min_rate']) : ''; ?></span>
        </div>
    </div>
        <form class="lot-item__form" action="lot.php?id=<?=isset($id) ? htmlspecialchars($id) : ''; ?>" method="post" autocomplete="off">
            <p class="lot-item__form-item form__item <?=isset($error) ? 'form__item--invalid' : ''; ?>">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="text" name="cost" placeholder="Сделайте ставку" value="<?=getPostVal('cost'); ?>">
                <span class="form__error"><?=isset($error) ? $error : ''; ?></span>
            </p>
            <button type="submit" class="button">Сделать ставку</button>
        </form>
</div>
          